<?php
/*!
 * 新規ゲームルーム作成
 *
 * user_id: ログインしているユーザーID（作成者）
 * minimum: 最小人数
 * playtime: ゲームプレー時間
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameCreateCheck extends CI_Controller
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
   		$this->_minimum = $this->input->get('minimum');
		if (NULL == $this->_minimum) {
			throw new Exception('invalid params minimum');
		}
   		$this->_playtime = $this->input->get('playtime');
		if (NULL == $this->_playtime) {
			throw new Exception('invalid params playtime');
		}
	}

	public function index()
	{
		try {
			$this->_validate();
		} catch (Exception $obj) {
			var_dump($obj);
		}
		
		$created_id = $this->game->regist($this->_user_id, $this->_minimum, $this->_playtime);

		// 作成したルームに参加
		redirect(
				 sprintf("GameJoin?game_id=%d&user_id=%d"
						 , $created_id
						 , $this->_user_id
						 )
				 );
	}
}

?>
