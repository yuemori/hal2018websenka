<?php
/*!
 * 新規ワードの登録フォーム
 *
 * EndPoint:
 *   /index: フォームの表示
 *   /write: フォームからの送信先（POST）
 */
defined('BASEPATH') OR exit('No direct script access allowed');


/*!
 */
class WordRegister extends CI_Controller
{
	// 登録処理時に返されるエラーコード
	private const SUCCESS               =  0; // 成功（正常終了）
	private const UNKNOWN               =  1; // 原因不明のエラー
	private const ERR_USER_ID_REQUIRED  = 10; // ユーザーID未入力
	private const ERR_WORD1_REQUIRED    = 20; // 単語１未入力
	private const ERR_WORD1_LENGTH_MIN  = 21; // 単語１が短すぎる
	private const ERR_WORD1_LENGTH_MAX  = 22; // 単語１が長すぎる
	private const ERR_WORD2_REQUIRED    = 30; // 単語２未入力
	private const ERR_WORD2_LENGTH_MIN  = 31; // 単語２が短すぎる
	private const ERR_WORD2_LENGTH_MAX  = 32; // 単語２が長すぎる
	private const ERR_WORD_EQUAL        = 40; // 単語１，２が等しい
	private const ERR_DB_INSERT_FAILURE = 41; // DB処理に失敗

	// 単語と認める文字数の最短と最長
	private const WORD_LENGTH_MIN = 3;
	private const WORD_LENGTH_MAX = 20;
	
	private $_user_id;
	private $_word1;
	private $_word2;
	
	/*!
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Game_model', 'game');
		$this->load->model('Keywordgroups_model', 'keywords');
	}

	/*!
	 * /index: フォームの表示、入力を促す
	 *
	 * get:
	 *   user_id: ログイン中のユーザーID
	 */
	public function index()
	{
		$data = array();
		try {
			$this->index_validate();
		} catch (Exception $obj) {
			redirect("Login");
			return false;
		}
		$data["user_id"] = $this->_user_id;
		$data["word_min"]= WordRegister::WORD_LENGTH_MIN;
		$data["word_max"]= WordRegister::WORD_LENGTH_MAX;
		$this->smarty->view("WordRegister.tpl", $data);
		return true;
	}

	/*!
	 * /index 呼び出し時の検証
	 */
	private function index_validate()
	{
		$this->_user_id = $this->input->get('user_id');
		if (NULL == $this->_user_id) {
			throw new Exception('invalid params user_id');
		}
		return true;
	}

	/*!
	 * /write: ワードをDBに書き込む
	 *
	 * get:
	 *   user_id: 登録するユーザーID（ログインユーザーID）
	 * post:
	 *   word1: 単語
	 *   word2: 単語
	 *
	 * 結果: json
	 *    {
	 *       "result": false,
	 *       "errors": [int,int,...]
	 *    }
	 */
	public function write()
	{
		// 入力されたパラメータの検証
		$errors = array();
		try {
			$this->write_validate();
		} catch (Exception $obj) {
			$errors[] = $obj->getCode();
		}

		if (0 == count($errors)) {
			$ret = $this->keywords->register(
				$this->_user_id,
				array($this->_word1, $this->_word2)
			);
		}

		$object = array(
						'result' => count($errors) == 0 ? TRUE : FALSE,
						'errors' => count($errors) == 0 ? array(WordRegister::SUCCESS) : $errors,
						);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($object));
		return true;
	}

	/*!
	 * /write 呼び出し時の検証
	 */
	private function write_validate()
	{
		$this->_user_id = $this->input->get('user_id');
		if (NULL == $this->_user_id) {
			throw new Exception("user_id is required", WordRegister::ERR_USER_ID_REQUIRED);
		}

		$this->_word1 = trim($this->input->post('word1'));
		if (NULL == $this->_word1) {
			throw new Exception("word1 is required", WordRegister::ERR_WORD1_REQUIRED);
		}
		$this->_word2 = trim($this->input->post('word2'));
		if (NULL == $this->_word2) {
			throw new Exception("word2 is required", WordRegister::ERR_WORD2_REQUIRED);
		}
		if ($this->_word1 == $this->_word2) {
			throw new Exception("word1 and word2 equal ", WordRegister::ERR_WORD_EQUAL);
		}

		$ret = $this->_word_length_validation($this->_word1);
		if ($ret < 0) {
			throw new Exception("word1 is short", WordRegister::ERR_WORD1_LENGTH_MIN);
		}
		if ($ret > 0) {
			throw new Exception("word1 is long", WordRegister::ERR_WORD1_LENGTH_MAX);
		}

		$ret = $this->_word_length_validation($this->_word2);
		if ($ret < 0) {
			throw new Exception("word2 is short", WordRegister::ERR_WORD2_LENGTH_MIN);
		}
		if ($ret > 0) {
			throw new Exception("word2 is long", WordRegister::ERR_WORD2_LENGTH_MAX);
		}

		return true;
	}

	/*!
	 * 入力されたワードが単語として許可できる長さか？判定する
	 *
	 * @retval (int)
	 *   0 未満：短すぎる
	 *     0 ==：許可する長さ
	 *   1 以上：長過ぎる
	 */
	private function _word_length_validation($input)
	{
		$len = mb_strlen($input, "UTF-8");
		if ($len < WordRegister::WORD_LENGTH_MIN) return -1;
		if ($len > WordRegister::WORD_LENGTH_MAX) return 1;
		return 0;
	}

}

?>
