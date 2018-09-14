<?php
/*!
 * Game_model
 *
 */

// Game_model::status values
define('GAME_STATUS_READY',   0); // 初期化前のゲーム
define('GAME_STATUS_STARTED', 1); // ゲーム中


	/*!
	 * ゲームを初期化して始めれる様にする手続きを担う
	 */
	class GameInitializer extends CI_Model
    {
		private const GAME_START_INTERVAL = 3; // ゲーム開始までの待機時間

		/*!
		 */
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		 * @game: Game_model class
		 */
		public function execute($game)
		{
			/*
			mt_srand($game->game_id);
			*/
			$this->db->query('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;');
			$this->db->trans_begin();
			// このゲームで利用するワードの選定
			$words = $this->lottery_keyword($game->members);
			// このゲームのワードウルフを決定
			$wordwolf = $this->lottery_wordwolf($game);
			// 参加者にワードを配る
			$this->deal($game->members, $words, $wordwolf);
			// 開始時間、終了時間の決定、ステータスの更新
			$this->finalize($game, $words, $wordwolf);

			if (!$this->db->trans_status()) {
				$this->db->trans_rollback();
				return false;
			}
			$this->db->trans_commit();
			return true;
		}

		/*!
		 */
		private function deal($members, $words, $wordwolf)
		{
			$offset = mt_rand(0, 1);
			$minority_word = &$words[$offset];
			$normal_word = &$words[($offset + 1) % count($words)];
			foreach ($members as $member) {
				if ($member->user_id == $wordwolf->user_id) {
					$member->setWord($minority_word->word_id);
				} else {
					$member->setWord($normal_word->word_id);
				}
			}
			return true;
		}

		/*!
		 */
		private function lottery_wordwolf($game)
		{
			$offset = mt_rand(0, count($game->members) - 1);
			return $game->members[$offset];
		}

		/*!
		 */
		private function lottery_keyword($members)
		{
			$CI =& get_instance();
			$CI->load->model('Keywordgroups_model', 'keywordgroups');

			// ゲームの公正さを保つために
			// このゲームへの参加者が登録したワードはなるべく選ばれない様に処理する
			// ゲーム参加者が登録したワードを除外した結果、
			// 全てのワードが使えない時は諦めて適当に選ぶ
			$members_user_id = array();
			foreach ($members as $member) {
				$members_user_id[] = $member->user_id;
			}
			$gids = $CI->keywordgroups->enum_ids($members_user_id);
			$offset = mt_rand(0, count($gids) - 1);
			$group_id = $gids[$offset]; // 今回利用するキーワードグループのID
			$words = $CI->keywordgroups->load($group_id);
			return $words;
		}

		/*!
		 */
		private function finalize($game, $words, $wordwolf)
		{
			$this->db->set('group_id', $words[0]->group_id);
			$this->db->set('minority_user_id', $wordwolf->user_id);
			$this->db->set('status', GAME_STATUS_STARTED); 
			$this->db->set('start_at'
						   , sprintf("(NOW() + INTERVAL '%d SECOND')"
									 , GameInitializer::GAME_START_INTERVAL)
						   , FALSE);
			$this->db->set('end_at'
						   , sprintf("(NOW() + INTERVAL '%d SECOND')"
									 , GameInitializer::GAME_START_INTERVAL + $game->playtime)
						   , FALSE);
			$this->db->where('game_id', $game->game_id);
			$ret = $this->db->update('game');
			return $ret;
		}
	}

	/*!
	 * 一つのゲームを時間で分割して管理する一つの区切り
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

	/*!
	 * 一つのゲームを表現する
	 * 開始前、ゲーム中、終了後
	 */
	class Game_model extends CI_Model
	{
		// ゲーム進行の区切りを何分にするか？
		private const WAVE_INTERVAL_MINUTE = 5;

		public $status;
		public $members;  // array of Gamemember_model class
		public $waves;    // array of GameWave
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
		}

		/*!
		 */
		public function isStarted()
		{
			if (NULL == $this->start_at) return false;
			$start= new DateTime($this->start_at, new DateTimeZone('Asia/Tokyo'));
			$now  = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
			return ($now > $start);
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
			$this->db->update('game');
			return true;
		}

		/*!
		 * 開始前状態のゲームの取得
		 */
		public function load_list()
		{
			// 読み込み
			$this->db->select('game_id');
			$this->db->from('game');
			$this->db->where('status', GAME_STATUS_READY);
			$query = $this->db->get();

			$result = array();
			foreach ($query->result('Game_model') as $row) {
				$result[] = $this->load($row->game_id);
			}
			return $result;
		}

		/*!
		 * 指定されたIDのゲームオブジェクトを生成して返す
		 */
		public function load($game_id)
		{
			// 読み込み
			$this->db->select('*');
			$this->db->from('game');
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
		 * ゲームへの参加
		 */
		public function join($user_id)
		{
			$this->db->set('game_id', $this->game_id);
			$this->db->set('user_id', $user_id);
			$this->db->set('word_id', 0);
			$ret = $this->db->insert('game_member');

			$CI =& get_instance();
			$CI->load->model('Gamemember_model', 'members');
			$this->members = $CI->members->load($this->game_id);
			return $ret;
		}

		/*!
		 * for view template method
		 */
		public function countOfMembers()
		{
			return count($this->members);
		}

		/*!
		 */
		public function existUser($user_id)
		{
			foreach ($this->members as $mem) {
				if ($mem->user_id != $user_id) continue;
				return true;
			}
			return false;
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
		 * 勝敗判定、ワードウルフ側が勝利と判定したらtrue
		 */
		public function isWordwolfWin()
		{
			// どのユーザーに何票入ったのか？を集計
			// 得票数降順でソートし、最大得票数を取得する
			//
			// ワードウルフに指名されたユーザーが、
			// この得票数を集めていたらワードウルフの負け
			// ＊同率１位の場合はワードウルフが負ける様にする（決選投票は無し）
			$votes = array();  // key => user_id, value = number of votes
			foreach ($this->results as $ret) {
				if (isset($votes[$ret->vote_user_id])) {
					$votes[$ret->vote_user_id]++;
				} else {
					$votes[$ret->vote_user_id] = 1;
				}
			}
			arsort($votes, SORT_NUMERIC);
			$temp = $votes; // array_shift() の呼び出しによる破壊を回避
			$top_value = array_shift($temp);

			foreach ($votes as $user_id => $value) {
				if ($value != $top_value) break;
				if ($this->minority_user_id != $user_id) continue;
				return false;
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

		/*!
		 * 新規登録
		 */
		public function regist($creator_user_id, $minimum, $playtime)
		{
			$this->db->set('creator_user_id', $creator_user_id);
			$this->db->set('status', GAME_STATUS_READY);
			$this->db->set('minimum', $minimum);
			$this->db->set('playtime', $playtime);
			$this->db->insert('game');
			return $this->db->insert_id('game_game_id_seq');
		}
	}

?>
