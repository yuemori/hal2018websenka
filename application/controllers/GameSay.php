<?php
/*!
 * ゲーム画面
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameSay extends CI_Controller
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

		$this->_message = $this->input->post('message');
		if (strlen($this->_message) != 0) {
			// プレーヤーが何かしらのテキストを入力して「送信」をクリックした時のみココを通ります
			// TODO: 入力された文字列をDBに書く
			var_dump($this->_message);
		}

		//		$data["error"] = "入力エラーがあった場合は、ここに警告がでますよ。";
		$data["error"] = "";
		$data["user_id"] = $this->_user_id;
		$data["game_id"] = $this->_game_id;
		$this->smarty->view("GameSay.tpl", $data);
	}

}
