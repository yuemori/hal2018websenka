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

		$game = $this->game->load($this->_game_id);
		if ($game->wave != $this->_wave) {
			// 既に他の誰かによってターンが進んでいる
			// ここでターンを進める処理実行してしまうと２ターン分進んでしまったりする
		} else {
			// ここまで来た場合は責任持ってゲームのターンを進める
			if (!$game->canFinishThisWave()) {
				$data["user_id"] = $this->_user_id;
				$data["game_id"] = $this->_game_id;
				$data["wave"]    = $this->_wave;
				$this->smarty->view("GameSayWait.tpl", $data);
				return ;
			}
			$game->gotoNextWave();
		}

		// ここまで来たという事は全てのユーザーが発言終了している
		redirect(
				 sprintf("GameMain?game_id=%d&user_id=%d"
						 , $this->_game_id
						 , $this->_user_id
						 )
				 );
	}

}
