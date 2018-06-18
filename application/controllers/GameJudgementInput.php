<?php
/*!
 * 投票処理
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 * accuser: ワードウルフとして指名したユーザーのID
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameJudgementInput extends CI_Controller
{
	private $_user_id;
	private $_game_id;
	private $_accuser;

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
		$this->_accuser = $this->input->get('accuser');
		if (NULL == $this->_accuser) {
			throw new Exception('invalid params accuser');
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
			$game->vote($this->_user_id, $this->_accuser);
		}
		redirect(
				 sprintf("GameJudgementWait?game_id=%d&user_id=%d"
						 , $this->_game_id
						 , $this->_user_id
						 )
				 );
	}
}

?>
