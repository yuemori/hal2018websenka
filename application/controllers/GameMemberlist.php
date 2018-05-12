<?php
/*!
 * ゲーム画面
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameMemberlist extends CI_Controller
{
	var $_user_id;
	var $_game_id;

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

		$this->load->model('Gamemember_model', 'members');

		$data["members"] = $this->members->load($this->_game_id);
		// var_dump($data["members"]);
		$data["user_id"] = $this->_user_id;
		$data["game_id"] = $this->_game_id;
		$this->smarty->view("GameMemberlist.tpl", $data);
	}

}