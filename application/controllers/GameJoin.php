<?php
/*!
 * ゲームに参加する
 *
 * user_id: ログイン中のユーザーID
 * game_id: 参加するゲームID
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameJoin extends CI_Controller
{
	private $_user_id;
	private $_game_id;

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
	}

	public function index()
	{
		try {
			$this->_validate();
		} catch (Exception $obj) {
			var_dump($obj);
		}

		$game = $this->game->load($this->_game_id);
		if (NULL !== $game) {
			$data["game"] = $game;
		} else {
			redirect(
					 sprintf("GameSelect?user_id=%d"
							 , $this->_user_id
							 )
					 );
			return false;
		}

		if ($game->existUser($this->_user_id)) {
			// 既に参加している
		} else {
			// ゲームに参加可能か？
			// 既に開始されてしまっているゲームへの参加は不可能
			// 参加に失敗した時は、一つ前の選択画面へ戻る
			//
			// 参加してゲーム開始待機画面へ飛ぶ
			// このユーザーが参加した事により、ゲーム開始の最低人数を満たした場合は
			// そこから一定時間後にゲームが開始される様に設定する
			// ＊実際の開始までに若干の猶予時間を持たせる
			if (!$game->join($this->_user_id)) {
				redirect(
						 sprintf("GameSelect?user_id=%d"
								 , $this->_user_id
								 )
						 );
				return false;
			}
			if ($game->countOfMembers() >= $game->minimum) {
				$this->load->model('GameInitializer', 'initializer');
				$this->initializer->execute($game);
			}

		}
		redirect(
				 sprintf("GameStartWait?game_id=%d&user_id=%d"
						 , $this->_game_id
						 , $this->_user_id
						 )
				 );
		return true;
	}
}

?>
