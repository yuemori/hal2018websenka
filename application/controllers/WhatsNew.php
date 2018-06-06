<?php
/*!
 * ゲームの状況が変わっているか？確認する為のAPI
 *
 * game_id: 状況を確認するゲームのID
 * ti: 最後に状況を確認した時間のタイムスタンプ
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class WhatsNew extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Game_model', 'game');
		$this->load->model('Gamelog_model', 'logs');
	}

	private function _validate()
	{
		$this->_game_id = $this->input->get('game_id');
		if (NULL == $this->_game_id) {
			throw new Exception('invalid params game_id');
		}
		$this->_last_check_time = $this->input->get('ti');
		if (NULL == $this->_last_check_time) {
			throw new Exception('invalid params ti');
		}
	}

	public function index()
	{
		try {
			$this->_validate();
		} catch (Exception $obj) {
			var_dump($obj);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('new' => TRUE, 'time' => time(NULL))));
	}
}

?>
