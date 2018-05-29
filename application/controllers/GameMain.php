<?php
/*!
 * ゲーム画面
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameMain extends CI_Controller
{
	private $_user_id;
	private $_game_id;
	private $_error;

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
		$this->_error = trim($this->input->get('error'));
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
			$data["game"]     = $game;
			$data["wave"]     = $game->getWave();
			$data["members"]  = $game->members;
			$data["logs"]     = $game->logs;
			$data["end_of_game"] = $game->endOfGame();
		} else {
			$data["game"]     = NULL;
			$data["wave"]     = 0;
			$data["members"]  = array();
			$data["logs"]     = array();
			$data["end_of_game"] = false;
		}

		$game->getWave();

		$data["user_id"] = $this->_user_id;
		$data["game_id"] = $this->_game_id;
		$data["error"]   = $this->_error;
		$this->smarty->view("GameMain.tpl", $data);
	}

}
