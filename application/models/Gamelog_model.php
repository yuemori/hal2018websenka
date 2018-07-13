<?php
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
			return $query->result();
		}
	}

?>
