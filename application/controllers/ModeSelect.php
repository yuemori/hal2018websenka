<?php
/*!
 * ゲームモードの選択画面（ログイン必須）
 *
 * @user_id：ログインしているユーザーID
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*!
 */
class ModeSelect extends CI_Controller
{
	private $_user_id;

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
   		$this->_user_id = $this->input->get('user_id');
		if (NULL == $this->_user_id) {
			throw new Exception('invalid params user_id');
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

		$user = $this->users->load($this->_user_id);
		if (NULL === $user) {
			redirect("Login");
			return ;
		}

		$data["user"] = $user;
		$data["user_id"] = $this->_user_id;
		$this->smarty->view('ModeSelect.tpl', $data);
	}
}
