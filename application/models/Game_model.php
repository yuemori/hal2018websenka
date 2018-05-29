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
	}

	class Game_model extends CI_Model
	{
		// ゲーム進行の区切りを何分にするか？
		private const WAVE_INTERVAL_MINUTE = 5;

		public $wave;
		public $max_wave;
		public $members;  // array of Gamemember_model class
		public $logs;  // array of Gamelog_model class
		public $waves;  // array of GameWave

		/*!
		 */
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		 */
		public function getWave()
		{
			$start= new DateTime($this->start_at, new DateTimeZone('Asia/Tokyo'));
			$now  = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
			$interval = $now->getTimestamp() - $start->getTimestamp();
			$ret = (int)($interval / 60 / Game_model::WAVE_INTERVAL_MINUTE) + 1;
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
			return $ret;
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

			echo "<pre>";
			$ret->waves = Game_model::waveFactory($ret->game_id);
			echo "</pre>";

			return $ret;
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

			$CI->load->model('Gamelog_model', 'logs');
			$this->logs = $CI->logs->load($this->game_id);
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
