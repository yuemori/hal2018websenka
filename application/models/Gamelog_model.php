<?php

require_once dirname(__FILE__). '/Game_model.php';

/*!
 * Gamelog_model
 *
 */
	class Gamelog_model extends CI_Model
	{
		/*!
		 */
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		 */
		public function load($game_id)
		{
			$this->db->select('*');
			$this->db->from('GameLog');
			$this->db->join('UserAccount', 'UserAccount.user_id=GameLog.user_id');
			$this->db->where('game_id', $game_id);
			$this->db->order_by('GameLog.wave DESC,GameLog.insert_at DESC');
			$query = $this->db->get();
			return $query->result('Gamelog_model');
		}

		/*!
		 */
		public function write($game_id, $wave, $user_id, $message)
		{
			$this->db->set('game_id', $game_id);
			$this->db->set('wave', $wave);
			$this->db->set('user_id', $user_id);
			$this->db->set('say', $message);
			$this->db->set('insert_at', 'NOW()', FALSE);
			$this->db->set('update_at', 'NOW()', FALSE);
			$ret = $this->db->insert('GameLog');
			return $ret;
		}
	}

?>
