<?php
/*!
 * 新規ユーザー登録画面
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*!
 */
class Register extends CI_Controller
{
	/*!
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Useraccount_model', 'users');
	}

	/*!
	 */
	public function index()
	{
		// クリックジャッキング対策
		header('X-FRAME-OPTIONS: SAMEORIGIN');
		$data = array();
		$this->smarty->view("Register.tpl", $data);
	}

	/*!
	 */
	public function execute()
	{
		$this->load->library('form_validation');
		$this->lang->load('items');
		$this->form_validation->set_error_delimiters('<span>', '</span>');
		$this->form_validation->set_rules(
			'name',               // [0]: Formから渡される変数名
			'lang:name',          // [1]: パラメータの和名（フォームの表示に使われる）
			array(                // [2]: ここからは検証ルールを書いてます、これらは | で連結されている
			  'trim',             //   文字列を切り詰めます
			  'required',         //   このパラメータは省略を許しません
			  'min_length[5]',    //   最小５文字
			  'max_length[12]',   //   最大１２文字
			  'is_unique[UserAccount.name]'
			)
		);
		$this->form_validation->set_rules(
			'pass',
			'lang:pass',
			'required'
		);
		$this->form_validation->set_rules(
			'nickname',
			'lang:nickname',
			array(
			  'required',
			  'is_unique[UserAccount.nickname]'
			)
		);
		$data = array();
		$data["name"] = $this->input->post('name');
		$data["pass"] = $this->input->post('pass');
		$data["nickname"] = $this->input->post('nickname');
		if (!$this->form_validation->run()) {
			$data["name_error"] = $this->form_validation->error("name");
			$data["pass_error"] = $this->form_validation->error("pass");
			$data["nickname_error"] = $this->form_validation->error("nickname");
			$this->smarty->view("Register.tpl", $data);
			return false;
		}

		$registered_id = $this->users->register(
			$this->input->post("name"),
			$this->input->post("pass"),
			$this->input->post("nickname")
		);
		if (NULL === $registered_id) {
			$data["db_error"] = true;
			$this->smarty->view("Register.tpl", $data);
			return false;
		}
		redirect(
				 sprintf("ModeSelect?user_id=%d"
						 , $registered_id
						 )
				 );
	}
}

?>
