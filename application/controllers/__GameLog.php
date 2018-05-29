<?php
/*!
 * ゲーム画面
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameLog extends CI_Controller
{
	var $_user_id;
	var $_game_id;

	public function __construct()
	{
		parent::__construct();
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
	}

	public function index()
	{
		try {
			$this->_validate();
		} catch (Exception $obj) {
			var_dump($obj);
		}

		$data["logs"] = $this->logs->load($this->_game_id);
		$data["user_id"] = $this->_user_id;
		$data["game_id"] = $this->_game_id;
		$this->smarty->view("GameLog.tpl", $data);
	}

}