<?php
/*!
 * Gameresult_model
 *
 */
	class Gameresult_model extends CI_Model
	{
		/*!
		 */
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		 */
		public function vote($game_id, $user_id, $vote_user_id)
		{
			$this->db->set('game_id', $game_id);
			$this->db->set('user_id', $user_id);
			$this->db->set('vote_user_id', $vote_user_id);
			$this->db->set('insert_at', 'NOW()', FALSE);
			$this->db->set('update_at', 'NOW()', FALSE);
			$ret = $this->db->insert('game_result');
			return $ret;
		}

		/*!
		 */
		public function load($game_id)
		{
			$this->db->select('*');
			$this->db->from('game_result');
			$this->db->where('game_id', $game_id);
			$query = $this->db->get();
			return $query->result('Gameresult_model');
		}
	}

?>
