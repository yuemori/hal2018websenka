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
		 * filter_user_id_array にユーザーIDの一覧を渡すと、
		 * 可能な限りそれらのユーザーが登録したワードを除外して返そうとする
		 */
		public function enum_ids($filter_user_id_array)
		{
			$this->db->select('group_id, register_user_id');
			$this->db->from('KeywordGroups');
			$query = $this->db->get();
			$filter_result = array();
			$full_result = array();
			foreach ($query->result() as $row) {
				if (!in_array($row->register_user_id, $filter_user_id_array)) {
					$filter_result[] = $row->group_id;
				}
				$full_result[] = $row->group_id;
			}
			return count($filter_result) == 0 ? $full_result : $filter_result;
		}
	}

?>
