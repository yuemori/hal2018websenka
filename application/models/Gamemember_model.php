<?php
/*!
 * Gamemember_model
 *
 */
	class Gamemember_model extends CI_Model
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
			$this->db->from('GameMember');
			$this->db->join('UserAccount', 'UserAccount.user_id=GameMember.user_id');
			$this->db->join('Keyword', 'word_id', 'left');
			$this->db->where('game_id', $game_id);
			$query = $this->db->get();
			return $query->result('Gamemember_model');
		}

		/*!
		 */
		public function setWord($word_id)
		{
			$this->db->set('word_id', $word_id);
			$this->db->where('game_id', $this->game_id);
			$this->db->where('user_id', $this->user_id);
			return $this->db->update('GameMember');
		}
	}

?>
