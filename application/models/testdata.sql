INSERT INTO UserAccount
  (user_id, nickname, name, pass, insert_at, update_at)
VALUES
  (1, 'テスト1', 'test1', MD5('test'), NOW(), NOW())
, (2, 'テスト2', 'test2', MD5('test'), NOW(), NOW())
, (3, 'テスト3', 'test3', MD5('test'), NOW(), NOW())
, (4, 'テスト4', 'test4', MD5('test'), NOW(), NOW())
, (5, 'テスト5', 'test5', MD5('test'), NOW(), NOW())
, (6, 'テスト6', 'test6', MD5('test'), NOW(), NOW())
;

INSERT INTO Keyword
  (word_id, group_id, word, insert_at, update_at)
VALUES
  (1, 1, '豆腐', NOW(), NOW())
, (2, 1, 'ワカメ', NOW(), NOW())
, (3, 2, 'コーヒー', NOW(), NOW())
, (4, 2, '紅茶', NOW(), NOW())
, (5, 3, 'オセロ', NOW(), NOW())
, (6, 3, '囲碁', NOW(), NOW())
, (7, 4, 'プール', NOW(), NOW())
, (8, 4, '露天風呂', NOW(), NOW())
;

INSERT INTO KeywordGroups
  (group_id, md5sum)
VALUES
  (1, MD5('ワカメ豆腐'))
, (2, MD5('コーヒー紅茶'))
, (3, MD5('オセロ囲碁'))
, (4, MD5('プール露天風呂'))
;

