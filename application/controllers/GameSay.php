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

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Game_model', 'game');
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

		$this->_message = $this->input->post('message');
		if (strlen($this->_message) == 0) {
			// プレーヤーからのテキスト入力が無い状態
			// 入力フォームを表示して終了する
			return $this->_show_input_form("");
		}

		// プレーヤーが何かしらのテキストを入力して「送信」をクリックした時のみココを通ります
		$game = $this->game->load($this->_game_id);
		if (NULL === $game) {
			// 対象のゲームが見つからない？ game_idが不正な状態
			return $this->_show_input_form("不正なリクエストです");
		}
		if (!$this->logs->write($game, $this->_user_id, $this->_message)) {
			// ログの書き込みに失敗
			return $this->_show_input_form("不正な入力です");
		}

		// 正常に書き込みが完了
		// TODO: 自分以外のプレーヤーの入力が終わるのを待つ？
		return $this->_show_input_form("");
	}

	private function _show_input_form($error)
	{
		$data["error"] = $error;
		$data["user_id"] = $this->_user_id;
		$data["game_id"] = $this->_game_id;
		$this->smarty->view("GameSay.tpl", $data);
		return true;
	}

}
