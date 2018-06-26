<?php
/*!
 * ゲームが開始されるまで待機する画面
 *
 * user_id: ログイン中のユーザーID
 * game_id: 待機中のゲームID
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameStartWait extends CI_Controller
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

		$data["user_id"] = $this->_user_id;
		$data["game_id"] = $this->_game_id;
		$this->smarty->view("GameStartWait.tpl", $data);
	}

}
?>
