<?php
/*!
 * 新規ユーザー登録画面
 */
defined('BASEPATH') OR exit('No direct script access allowed');

define('ERR_NO_ERROR',            0);
define('ERR_USERNAME_INVALID',   10);
define('ERR_USERNAME_DUPLICATE', 11);
define('ERR_PASSWORD_INVALID',   20);
define('ERR_NICKNAME_INVALID',   30);
define('ERR_NICKNAME_DUPLICATE', 31);
define('ERR_UNKNOWN',            99);

/*!
 */
class Regist extends CI_Controller
{
	/*!
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*!
	 */
	public function index()
	{
		// クリックジャッキング対策
		header('X-FRAME-OPTIONS: SAMEORIGIN');

		$this->smarty->view("Regist.tpl");
	}
}
