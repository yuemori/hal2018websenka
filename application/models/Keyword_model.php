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
			$this->db->set('group_id', $new_group_id);
			$this->db->set('word', $word);
			$this->db->set('insert_at', 'NOW()', FALSE);
			$this->db->set('update_at', 'NOW()', FALSE);
			if (!$this->db->insert('keyword')) {
				return 0;
			}
			return $this->db->insert_id('keyword_word_id_seq');
		}
	}

?>
