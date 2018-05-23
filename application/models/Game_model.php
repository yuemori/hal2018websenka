<?php
/*!
 * Game_model
 *
 */
	class Game_model extends CI_Model
	{
		public $members;  // array of Gamemember_model class
		public $logs;  // array of Gamelog_model class

		/*!
		 */
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		 */
		public function endOfGame()
		{
			return ($this->wave > $this->max_wave);
		}

		/*!
		 */
		public function gotoNextWave()
		{
			$this->wave ++;
			$this->db->set('wave', $this->wave);
			$this->db->where('game_id', $this->game_id);
			$this->db->update('Game');
			return true;
		}

		/*!
		 * 今現在のターンに発言した履歴のあるユーザーID一覧を配列で取得
		 */
		private function getSendedUserInThisWave()
		{
			$result = array();
			foreach ($this->logs as $log) {
				if ($log->wave != $this->wave) continue;	// 現在進行中のターン以外の発言は無視
				$result[] = $log->user_id;
			}
			return $result;
		}

		/*!
		 * ターンを進めて良いか？判定する
		 */
		public function canFinishThisWave()
		{
			// 今現在のターンに発言した履歴のあるユーザーID一覧を配列で取得
			$this_wave_sended = $this->getSendedUserInThisWave();
			if (0 == count($this_wave_sended)) {
				return false;
			}
			// このゲームの参加者一覧を走査、
			// 全員が発言終了しているか？チェックする
			foreach ($this->members as $person) {
				if (in_array($person->user_id, $this_wave_sended)) continue;
				return false;
			}
			return true;
		}

		/*!
		 * 指定されたIDのゲームオブジェクトを生成して返す
		 */
		public function load($game_id)
		{
			// 読み込み
			$this->db->select('*');
			$this->db->from('Game');
			$this->db->where('game_id', $game_id);
			$query = $this->db->get();
			$ret = $query->first_row('Game_model');
			if (NULL === $ret) return NULL;

			$CI =& get_instance();
			$CI->load->model('Gamemember_model', 'members');
			$CI->load->model('Gamelog_model', 'logs');
			$ret->members = $CI->members->load($ret->game_id);
			$ret->logs = $CI->logs->load($ret->game_id);
			return $ret;
		}

		/*!
		 * ユーザーの発言を登録
		 */
		public function logWrite($user_id, $message)
		{
			$CI =& get_instance();
			$CI->load->model('Gamelog_model', 'logs');
			$ret = $CI->logs->write($game->game_id, $game->wave, $user_id, $message);
			if (!$ret) return false;

			$CI->load->model('Gamelog_model', 'logs');
			$this->logs = $CI->logs->load($this->game_id);
			return true;
		}
	}

?>
