<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GameSelect extends CI_Controller
{
	private $_user_id;

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
	}

	public function index()
	{
		try {
			$this->_validate();
		} catch (Exception $obj) {
			var_dump($obj);
		}

		$data["user_id"] = $this->_user_id;
		$data["games"] = $this->game->load_list();

		$this->smarty->view('GameSelect.tpl', $data);
	}
}

?>
