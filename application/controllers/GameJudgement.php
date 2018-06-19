<?php
/*!
 * 議論終了後の投票画面
 * 参加メンバーをリスト表示して投票を促す
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameJudgement extends CI_Controller
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
			$data["game"] = NULL;
		}

		// 既に投票が完了している場合は、投票完了後の待機ページへ飛ばす
		if ($game->existResult($this->_user_id)) {
			redirect(
					 sprintf("GameJudgementWait?game_id=%d&user_id=%d"
							 , $this->_game_id
							 , $this->_user_id
							 )
					 );
		}

		// ゲームに参加してなかったユーザーからのアクセスの時は投票完了待機画面へ
		if (!$game->existUser($this->_user_id)) {
			redirect(
					 sprintf("GameJudgementWait?game_id=%d&user_id=%d"
							 , $this->_game_id
							 , $this->_user_id
							 )
					 );
		}

		$data["user_id"] = $this->_user_id;
		$data["game_id"] = $this->_game_id;
		$this->smarty->view("GameJudgement.tpl", $data);
	}
}

?>
