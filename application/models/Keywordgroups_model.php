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

		/*!
		 * 新規ワードの登録処理
		 *
		 * $register_user_id: 登録者のユーザーID
		 * $word_array: 単語を配列としてまとめた物
		 * @retval: bool
		 */
		public function register($register_user_id, $word_array)
		{
			// 登録されているワードグループの唯一制を保つ為の処理
			// 受け取ったワードを並べ替えてチェックサムをさん算出する事で、
			// いちいちワード毎の比較を行わなくても良い様にして取り扱っています。
			//
			// 例）
			// ・コーヒー、紅茶
			// ・紅茶、コーヒー
			// 異なる順番で渡されても、内部的には必ず
			// ・コーヒー、紅茶 として処理を行い、
			// 最終的に同一のチェックサム（1a990917ff226b54a286819c47714cb4）こんな文字列を作り出します。
			sort($word_array);
			$check_sum = md5(implode($word_array));

			// 一つのワードグループのDBの構造はこんな感じ
			// KeywordGroups 1 record
			//   |-- Keyword
			//   |-- Keyword
			// ＊KeywordGroups 1レコードに対して、Keyword 2レコード
			//
			// 上記で求めたチェックサムは
			// KeywordGroups テーブル内でユニークになる様に制約定義されているので
			// 既に登録されている単語のペアは重複して登録できない
			//
			// KeywordGroups テーブルにレコードをINSERT成功したら、
			// 責任持ってKeywordテーブルに２つのレコードを登録すること！

			// DBへ書き込み KeywordGroups table.
			// ここで新しく振られたIDは、下記単語の登録時に利用します。
			$this->db->set('register_user_id', $register_user_id);
			$this->db->set('md5sum', $check_sum);
			if (!$this->db->insert('KeywordGroups')) {
				return false;
			}
			$new_group_id = $this->db->insert_id();

			// DBへ書き込み Keyword table.
			$CI =& get_instance();
			$CI->load->model('Keyword_model', 'keyword');
			foreach ($word_array as $word) {
				$CI->keyword->register($new_group_id, $word);
			}
			return true;
		}
	}

?>
