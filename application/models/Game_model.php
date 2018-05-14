<?php
/*!
 * Game_model
 *
 */
	class Game_model extends CI_Model
	{
		var $_data;

		/*!
		  propaties
		 */
		public function id() : int
		{
			if (!isset($this->_data)) return 0;
			return (int)(isset($this->_data->game_id) ? $this->_data->game_id : 0);
		}
		public function wave() : int
		{
			if (!isset($this->_data)) return 0;
			return (int)(isset($this->_data->wave) ? $this->_data->wave : 0);
		}

		/*!
		 */
		public function __construct()
		{
			$this->_data = NULL;
			$this->load->database();
		}

		/*!
		 */
		public function load($game_id)
		{
			// キャッシュの初期化
			$this->_data = NULL;

			// 読み込み
			$this->db->select('*');
			$this->db->from('Game');
			$this->db->where('game_id', $game_id);
			$query = $this->db->get();
			$temp = $query->result();
			$this->_data = isset($temp[0]) ? $temp[0] : NULL;
			return true;
		}

	}

?>
