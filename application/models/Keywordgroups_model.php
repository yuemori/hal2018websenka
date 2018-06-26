<?php
/*!
 * Keywordgroups_model
 *
 */
	class Keywordgroups_model extends CI_Model
	{
		/*!
		 */
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		 */
		public function load($group_id)
		{
			$CI =& get_instance();
			$CI->load->model('Keyword_model', 'keyword');

			$this->db->select('*');
			$this->db->from('KeywordGroups');
			$this->db->join('Keyword', 'KeywordGroups.group_id=Keyword.group_id');
			$this->db->where('KeywordGroups.group_id', $group_id);
			$query = $this->db->get();
			return $query->result('Keyword_model');
		}

		/*!
		 */
		public function enum_ids()
		{
			$this->db->select('group_id');
			$this->db->from('KeywordGroups');
			$query = $this->db->get();
			$result = array();
			foreach ($query->result() as $row) {
				$result[] = $row->group_id;
			}
			return $result;
		}
	}

?>
