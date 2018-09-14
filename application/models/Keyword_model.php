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
			$this->db->from('keyword');
			$this->db->where('word_id', $word_id);
			$query = $this->db->get();
			$ret = $query->first_row('Keyword_model');
		}

		/*!
		 */
		public function load_list()
		{
			$this->db->select('*');
			$this->db->from('keyword');
			$this->db->order_by('group_id, word_id');
			$query = $this->db->get();
			return $query->result('Keyword_model');
		}

		/*!
		 * 単語の新規登録
		 *
		 * $group_id: グループのID
		 * $word: 単語
		 * @retval (int) 0: 失敗, それ以外: 新しく追加された単語のID
		 */
		public function register($new_group_id, $word)
		{
			return 0;
		}
	}

?>
