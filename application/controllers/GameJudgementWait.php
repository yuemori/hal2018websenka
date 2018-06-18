<?php
/*!
 * 投票終了後の待機画面
 * 参加メンバー全員の投票が終わるのを待つ
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameJudgementWait extends CI_Controller
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
			if ($game->isFinishVote()) {
				// 全員の投票が完了したら、結果画面へリダイレクト
				redirect(
						 sprintf("GameResult?game_id=%d&user_id=%d"
								 , $this->_game_id
								 , $this->_user_id
								 )
						 );
			}
		} else {
			$data["game"] = NULL;
		}
		$data["user_id"] = $this->_user_id;
		$data["game_id"] = $this->_game_id;
		$this->smarty->view("GameJudgementWait.tpl", $data);
		return ;
	}
}

?>
