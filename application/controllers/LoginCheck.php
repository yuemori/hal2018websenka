<?php
/*!
 * ログイン処理
 *
 * @name: ログインユーザー名
 * @pass: ログインパスワード
 */
defined('BASEPATH') OR exit('No direct script access allowed');

define('LOGIN_SUCCESS', 0);
define('LOGIN_ERR_USER_NOT_FOUND', 1);
define('LOGIN_ERR_PARAMETER', 2);


/*!
 */
class LoginCheck extends CI_Controller
{
	private $_username;
	private $_password;

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
   		$this->_username = $this->input->post('name');
		if (NULL == $this->_username) {
			throw new Exception('invalid params name');
		}
		$this->_password = $this->input->post('pass');
		if (NULL == $this->_password) {
			throw new Exception('invalid params pass');
		}
	}

	/*!
	 */
	public function index()
	{
		try {
			$this->_validate();
		} catch (Exception $obj) {
			$url = sprintf("Login?error=%d", LOGIN_ERR_PARAMETER);
			redirect($url);
		}

		$user = $this->users->login($this->_username, $this->_password);
		if (NULL !== $user) {
			$url = sprintf("ModeSelect?user_id=%d", $user->user_id);
			redirect($url);
			return ;
		}
		$url = sprintf("Login?error=%d", LOGIN_ERR_USER_NOT_FOUND);
		redirect($url);
	}
}
