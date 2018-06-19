<?php
/*!
 * 誰が投票完了してるのか？をJSONで返す
 *
 * user_id: ログイン中のユーザーID
 * game_id: 進行中のゲームID
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GameJudgementCheck extends CI_Controller
{
	private $_user_id;
	private $_game_id;

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

		$object = array();
		$game = $this->game->load($this->_game_id);
		if (NULL !== $game) {
			foreach ($game->members as $member) {
				$object[] = array(
								  'user_id' => $member->user_id,
								  'vote' => $game->existResult($member->user_id)
								  );
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($object));
	}
}

?>
