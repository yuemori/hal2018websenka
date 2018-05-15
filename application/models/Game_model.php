<?php
/*!
 * Game_model
 *
 */
	class Game_model extends CI_Model
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
			// 読み込み
			$this->db->select('*');
			$this->db->from('Game');
			$this->db->where('game_id', $game_id);
			$query = $this->db->get();
			$temp = $query->result();
			return isset($temp[0]) ? $temp[0] : NULL;
		}

		/*!
		 */
		public function setWave($game_id, $wave)
		{
			$this->db->set('wave', $wave);
			$this->db->where('game_id', $game_id);
			$this->db->update('Game');
			return true;
		}
	}

?>
