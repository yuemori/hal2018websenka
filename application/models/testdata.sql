
DELETE FROM UserAccount;
INSERT INTO UserAccount
  (user_id, nickname, name, pass, insert_at, update_at)
VALUES
  (1, 'テスト1', 'test1', MD5('test'), NOW(), NOW())
, (2, 'テスト2', 'test2', MD5('test'), NOW(), NOW())
, (3, 'テスト3', 'test3', MD5('test'), NOW(), NOW())
, (4, 'テスト4', 'test4', MD5('test'), NOW(), NOW())
, (5, '<font color="red">テスト5</font>', 'test5', MD5('test'), NOW(), NOW())
, (6, 'テスト6', 'test6', MD5('test'), NOW(), NOW())
;


DELETE FROM Keyword;
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


DELETE FROM KeywordGroups;
INSERT INTO KeywordGroups
  (group_id, md5sum)
VALUES
  (1, MD5('ワカメ豆腐'))
, (2, MD5('コーヒー紅茶'))
, (3, MD5('オセロ囲碁'))
, (4, MD5('プール露天風呂'))
;


DELETE FROM Game;
INSERT INTO Game
  (game_id, wave, status, group_id, minority_user_id, minimum)
VALUES
  (1, 0, 0, 1, 1, 4)
;


DELETE FROM GameMember;
INSERT INTO GameMember
  (game_id, user_id, word_id)
VALUES
  (1, 1, 1)
, (1, 2, 2)
, (1, 3, 2)
, (1, 4, 2)
, (1, 5, 2)
;


DELETE FROM GameLog;
INSERT INTO GameLog
  (log_id, game_id, wave, user_id, say, insert_at, update_at)
VALUES
  (1,  1, 1, 1, '結構良く食卓でみかける？', NOW(), NOW())
, (2,  1, 1, 2, 'そうだね。よく見かけるかな？', NOW()+1, NOW())
, (3,  1, 1, 3, 'そうだね', NOW()+2, NOW())
, (4,  1, 1, 4, 'うんうん', NOW()+3, NOW())
, (5,  1, 1, 5, '一週間に一回は見るかな？', NOW()+4, NOW())

, (6,  1, 2, 1, 'ほげ', NOW()+10, NOW())
, (7,  1, 2, 2, 'ふが', NOW()+11, NOW())
, (8,  1, 2, 3, '何気にコレって気持ち悪くない？', NOW()+12, NOW())
, (9,  1, 2, 4, 'そうっすねー', NOW()+13, NOW())
, (10, 1, 2, 5, '<font color="red">( ﾟдﾟ)ﾎﾟｶｰﾝ</font>', NOW()+14, NOW())

, (11, 1, 3, 1, 'てすとー', NOW()+20, NOW())
, (12, 1, 3, 2, 'こめんとー', NOW()+21, NOW())
, (13, 1, 3, 3, 'テストデータ作るの疲れた', NOW()+22, NOW())
, (14, 1, 3, 4, 'そうだね、疲れるよねこれ', NOW()+23, NOW())
, (15, 1, 3, 5, 'もうええんちゃうかな？', NOW()+24, NOW())
;
