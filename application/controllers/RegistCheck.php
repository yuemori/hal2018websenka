<?php
/*!
 * 新規ユーザー登録画面
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once dirname(__FILE__). '/Regist.php';


class RegistCheck extends CI_Controller
{
	private $_username;
	private $_password;
	private $_nickname;

	/*!
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Useraccount_model', 'users');
	}

	/*!
	 */
	private function _validate()
	{
		$errors = array();
   		$this->_username = $this->input->post('name');
		if (!preg_match("/^[0-9a-zA-Z]{4,}$/", $this->_username)) {
			$errors[] = ERR_USERNAME_INVALID;
		}

		$this->_password = $this->input->post('pass');
		if (!preg_match("/^[0-9a-zA-Z]{4,}$/", $this->_password)) {
			$errors[] = ERR_PASSWORD_INVALID;
		}

		$this->_nickname = $this->input->post('nickname');
		if (NULL == $this->_nickname) {
			$errors[] = ERR_NICKNAME_INVALID;
		}
		return $errors;
	}

	/*!
	 */
	public function index()
	{
		$errors = $this->_validate();
		if (count($errors) == 0) {
			if ($this->users->register($this->_username, $this->_password, $this->_nickname)) {
				echo "+++ INSERT OK!!\n";
			} else {
				if ($this->users->exist_by_username($this->_username)) {
					$errors[] = ERR_USERNAME_DUPLICATE;
				}
				if ($this->users->exist_by_nickname($this->_nickname)) {
					$errors[] = ERR_NICKNAME_DUPLICATE;
				}
			}
		}
		$object = array(
						'errors' => $errors
						);
		echo json_encode($object);
		return ;
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($object));
		

//		$this->smarty->view("Regist.tpl", $data);

//	<a href="ModeSelect');">登録完了</a><br />
	}
}
