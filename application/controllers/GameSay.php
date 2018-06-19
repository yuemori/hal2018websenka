<?php
/*!
 * ゲーム画面
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 */
defined('BASEPATH') OR exit('No direct script access allowed');


define('SAY_SUCCESS',             0);
define('SAY_ERR_NO_INPUT',        1);
define('SAY_ERR_INVALID_MESSAGE', 2);
define('SAY_ERR_GAME_NOT_FOUND',  3);
define('SAY_ERR_WAVE_MISSMATCH',  4);
define('SAY_ERR_NOT_PERMIT',      5);
define('SAY_ERR_GAME_FINISHED',   10);


class GameSay extends CI_Controller
{
	private $_user_id;
	private $_game_id;
	private $_wave;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Game_model', 'game');
		$this->load->model('Gamelog_model', 'logs');
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
	}

	public function index()
	{
		try {
			$this->_validate();
		} catch (Exception $obj) {
			var_dump($obj);
		}

		$game = $this->game->load($this->_game_id);
		if (NULL === $game) {
			// 対象のゲームが見つからない？ game_idが不正な状態
			return $this->_return_to_main(SAY_ERR_GAME_NOT_FOUND);
		}

		if (!$game->existUser($this->_user_id)) {
			return $this->_return_to_main(SAY_ERR_NOT_PERMIT);
		}

		if ($game->endOfGame()) {
			// ターンが進んだ事によって終了条件を満たす時
			// 他の誰かがターンを進めた事で終了条件が満たされた時も通る
			// TODO: goto game end
			$this->_return_to_main(SAY_ERR_GAME_FINISHED);
			return ;
		}

		$this->_message = $this->input->post('message');
		if (strlen($this->_message) == 0) {
			// プレーヤーからのテキスト入力が無い状態
			// 入力フォームを表示して終了する
			return $this->_return_to_main(SAY_ERR_NO_INPUT);
 		} else {
			// プレーヤーが何かしらのテキストを入力して「送信」をクリックした時のみココを通ります
		}

		if (!$game->logWrite($this->_user_id, $this->_message)) {
			// ログの書き込みに失敗
			return $this->_return_to_main(SAY_ERR_INVALID_MESSAGE);
		}

		// 正常に書き込みが完了
		$this->_return_to_main(SAY_SUCCESS);
		return ;
	}

	private function _return_to_main($error)
	{
		redirect(
				 sprintf("GameMain?game_id=%d&user_id=%d&error=%d"
						 , $this->_game_id
						 , $this->_user_id
						 , $error
						 )
				 );
		return true;
	}

}

?>
