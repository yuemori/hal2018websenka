<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function index()
	{
		// クリックジャッキング対策
		header('X-FRAME-OPTIONS: SAMEORIGIN');

		$data["error"] = $this->input->get('error');
		$this->smarty->view("Login.tpl", $data);
	}
}

?>
