<?php
/*!
 * Keyword_model
 *
 */
	class Keyword_model extends CI_Model
	{
		/*!
		 */
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		 */
		public function load($word_id)
		{
			$this->db->select('*');
			$this->db->from('Keyword');
			$this->db->where('word_id', $word_id);
			$query = $this->db->get();
			$ret = $query->first_row('Keyword_model');
		}

		/*!
		 */
		public function load_list()
		{
			$this->db->select('*');
			$this->db->from('Keyword');
			$this->db->order_by('group_id, word_id');
			$query = $this->db->get();
			return $query->result('Keyword_model');
		}
	}

?>
