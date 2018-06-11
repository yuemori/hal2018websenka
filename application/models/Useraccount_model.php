<?php

require_once dirname(__FILE__). '/Game_model.php';

/*!
 * Useraccount_model
 *
 */
	class Useraccount_model extends CI_Model
	{
		public $user_id;
		public $nickname;


		/*!
		 */
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		 * ログイン用途
		 */
		public function login($name, $pass)
		{
			$this->db->select('user_id, nickname');
			$this->db->from('UserAccount');
			$this->db->where('name', $name);
			$this->db->where('pass', md5($pass));
			$query = $this->db->get();
			return $query->first_row('Useraccount_model');
		}

		/*!
		 * ログイン完了後のユーザーデータ参照用途
		 */
		public function load($user_id)
		{
			$this->db->select('user_id, nickname');
			$this->db->from('UserAccount');
			$this->db->where('user_id', $user_id);
			$query = $this->db->get();
			return $query->first_row('Useraccount_model');
		}

		/*!
		 * 新規登録
		 */
		public function register($user_id, $nickname, $name, $pass)
		{
			$this->db->set('user_id', $user_id);
			$this->db->set('nickname', $nickname);
			$this->db->set('name', $name);
			$this->db->set('pass', md5($pass));
			$this->db->set('insert_at', 'NOW()', FALSE);
			$this->db->set('update_at', 'NOW()', FALSE);
			$ret = $this->db->insert('UserAccount');
			return $ret;
		}

		/*!
		 * load()してから使う（データ更新用途）
		 */
		public function update($nickname, $name="", $pass="")
		{
			if (!isset($this->user_id)) return false;
			if ($this->user_id == 0) return false;

			$this->db->set('nickname', $nickname);
			if ($name != "") $this->db->set('name', $name);
			if ($pass != "") $this->db->set('pass', md5($pass));
			$this->db->set('update_at', 'NOW()', FALSE);
			$this->db->where('user_id', $this->user_id);
			$ret = $this->db->update('UserAccount');
			return $ret;
		}

	}

?>
