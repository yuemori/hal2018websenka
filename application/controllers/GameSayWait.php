<?php
/*!
 * ゲームに対しての発言後、
 * 参加メンバー全員が発言するのを待つページ
 * 全員が発言完了していれば次の入力ページへリダイレクトする
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 * wave:    待つ事を決めたターン
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameSayWait extends CI_Controller
{
	var $_user_id;
	var $_game_id;
	var $_wave;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Game_model', 'game');
		$this->load->model('Gamemember_model', 'members');
		$this->load->model('Gamelog_model', 'logs');
	}

	private function _validate()
	{
   		$this->_user_id = $this->input->get('user_id');
		if (NULL == $this->_user_id) {
			throw new Exception('invalid params user_id');
		}
		$this->_game_id = $this->input->get('game_id');
		if (NULL == $this->_game_id) {
			throw new Exception('invalid params game_id');
		}
		$this->_wave = $this->input->get('wave');
		if (NULL == $this->_wave) {
			throw new Exception('invalid params wave');
		}
	}

	public function index()
	{
		try {
			$this->_validate();
		} catch (Exception $obj) {
			var_dump($obj);
		}

		$game    = $this->game->load($this->_game_id);
		$members = $this->members->load($this->_game_id);
		$logs    = $this->logs->load($this->_game_id);
		if ($game->wave != $this->_wave) {
			// 既に他の誰かによってターンが進んでいる
			// ここでターンを進める処理実行してしまうと２ターン分進んでしまったりする
		} else {
			// 今現在のターンに発言した履歴のあるユーザーID一覧を配列で取得
			$this_wave_sended = array();
			foreach ($logs as $log) {
				if ($log->wave != $game->wave) continue;	// 現在進行中のターン以外の発言は無視
				$this_wave_sended[] = $log->user_id;
			}
			if (0 == count($this_wave_sended)) {
				// 少なくとも自分１人は発言しているはずなので、
				// ここを通る事はありえないが、一応エラー判定しておく
			} else {
				// このゲームの参加者一覧を走査、
				// 全員が発言終了しているか？チェックする
				foreach ($members as $person) {
					if (in_array($person->user_id, $this_wave_sended)) continue;
					// このユーザーの発言が見つからなかった
					$data["user_id"] = $this->_user_id;
					$data["game_id"] = $this->_game_id;
					$data["wave"]    = $this->_wave;
					$this->smarty->view("GameSayWait.tpl", $data);
					return ;
				}

				// ここまで来た場合は責任持ってゲームのターンを進める
				$this->game->setWave($game->game_id, $game->wave + 1);
			}
		}
		// ここまで来たという事は全てのユーザーが発言終了している
		redirect(
				 sprintf("GameSay?game_id=%d&user_id=%d"
						 , $this->_game_id
						 , $this->_user_id
						 )
				 );
	}

}
