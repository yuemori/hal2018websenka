<?php
/*!
 * Game_model
 *
 */
	class GameWave
	{
		public $wave;
		public $logs; // array of Gamelog_model class

		public function __construct($wave)
		{
			$this->wave = $wave;
			$this->logs = array();
		}

		public function append_log($in)
		{
			$this->logs[] = $in;
			return usort($this->logs, function ($left, $right) {
					return ($left->insert_at <=> $right->insert_at);
				}
				);
		}

		public function find_log_by_time(&$latest, $after)
		{
			$result = array();
			foreach ($this->logs as $log) {
				$ti = new DateTime($log->insert_at, new DateTimeZone('Asia/Tokyo'));
				$time = $ti->getTimestamp();
				if ($time <= $after) continue;
				$latest = $latest > $time ? $latest : $time;
				$result[] = $log;
			}
			return $result;
		}

	}

	class Game_model extends CI_Model
	{
		// ゲーム進行の区切りを何分にするか？
		private const WAVE_INTERVAL_MINUTE = 5;

		public $wave;
		public $max_wave;
		public $members;  // array of Gamemember_model class
		public $waves;  // array of GameWave
		public $results;  // array of Gameresult_model class

		/*!
		 */
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		 * 指定時間以降のログを返す
		 *
		 * @latest 取得したログの中で一番直近の物の時間を受け取る
		 * @after  unix_timestamp
		 */
		public function findLogByTime(&$latest, $after)
		{
			$result = array();
			$latest = 0;
			foreach ($this->waves as $wave) {
				$result = array_merge($result, $wave->find_log_by_time($latest, $after));
			}
			return $result;
		}

		/*!
		 */
		public function getWave()
		{
			$start= new DateTime($this->start_at, new DateTimeZone('Asia/Tokyo'));
			$now  = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
			$interval = $now->getTimestamp() - $start->getTimestamp();
			$ret = (int)($interval / 60 / Game_model::WAVE_INTERVAL_MINUTE) + 1;
			return $ret;
			/*
			echo "<pre>";
			var_dump($start->format('Y-m-d H:i:s'));
			var_dump($start->getTimestamp());
			var_dump($now->format('Y-m-d H:i:s'));
			var_dump($now->getTimestamp());
			var_dump($interval);
			var_dump($ret);
			echo "</pre>";
			*/
		}

		/*!
		 */
		public function endOfGame()
		{
			$end = new DateTime($this->end_at, new DateTimeZone('Asia/Tokyo'));
			$now = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
			return ($now > $end);
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
			$CI->load->model('Gameresult_model', 'results');

			$ret->members = $CI->members->load($ret->game_id);
			$ret->waves = Game_model::waveFactory($ret->game_id);
			$ret->results = $CI->results->load($ret->game_id);

			return $ret;
		}

		/*!
		 * 投票する
		 */
		public function vote($user_id, $accuser_id)
		{
			$CI =& get_instance();
			$CI->load->model('Gameresult_model', 'results');
			$ret = $CI->results->vote($this->game_id, $user_id, $accuser_id);
			if (!$ret) return false;
			return true;
		}

		/*!
		 */
		public function existResult($user_id)
		{
			foreach ($this->results as $ret) {
				if ($user_id == $ret->user_id) {
					return true;
				}
			}
			return false;
		}

		/*!
		 * 参加者全員の投票が完了していたらtrue
		 */
		public function isFinishVote()
		{
			foreach ($this->members as $member) {
				if (!$this->existResult($member->user_id)) {
					return false;
				}
			}
			return true;
		}

		/*!
		 * ユーザーの発言を登録
		 */
		public function logWrite($user_id, $message)
		{
			$CI =& get_instance();
			$CI->load->model('Gamelog_model', 'logs');
			$ret = $CI->logs->write($this->game_id, $this->getWave(), $user_id, $message);
			if (!$ret) return false;
			return true;
		}

		/*!
		 * ターン毎のデータを構築する
		 */
		static private function waveFactory($game_id)
		{
			$CI =& get_instance();
			$CI->load->model('Gamelog_model', 'logs');
			$result = array();
			$temp = $CI->logs->load($game_id);
			foreach ($temp as $log) {
				if (!isset($result[$log->wave])) {
					$result[$log->wave] = array();
					$result[$log->wave] = new GameWave($log->wave);
				}
				$result[$log->wave]->append_log($log);
			}
			ksort($result);
			return $result;
		}

	}

?>
