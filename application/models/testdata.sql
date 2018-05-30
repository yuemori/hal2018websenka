
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
  (game_id, status, group_id, minority_user_id, minimum, start_at, end_at)
VALUES
  (1, 0, 1, 1, 4, (NOW() -  INTERVAL 1400 SECOND), (NOW() +  INTERVAL 30 SECOND))
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
  (1,  1, 1, 1, '結構良く食卓でみかける？', (NOW() +  INTERVAL 0 SECOND), NOW())
, (2,  1, 1, 2, 'そうだね。よく見かけるかな？', (NOW() + INTERVAL 1 SECOND), NOW())
, (3,  1, 1, 3, 'そうだね', (NOW() + INTERVAL 2 SECOND), NOW())
, (4,  1, 1, 4, 'うんうん', (NOW() + INTERVAL 3 SECOND), NOW())
, (5,  1, 1, 5, '一週間に一回は見るかな？', (NOW() + INTERVAL 4 SECOND), NOW())

, (6,  1, 2, 1, 'ほげ', (NOW() + INTERVAL 10 SECOND), NOW())
, (7,  1, 2, 2, 'ふが', (NOW() + INTERVAL 11 SECOND), NOW())
, (8,  1, 2, 3, '何気にコレって気持ち悪くない？', (NOW() + INTERVAL 12 SECOND), NOW())
, (9,  1, 2, 4, 'そうっすねー', (NOW() + INTERVAL 13 SECOND), NOW())
, (10, 1, 2, 5, '<font color="red">( ﾟдﾟ)ﾎﾟｶｰﾝ</font>', (NOW() + INTERVAL 14 SECOND), NOW())

, (11, 1, 3, 1, 'てすとー', (NOW() + INTERVAL 20 SECOND), NOW())
, (12, 1, 3, 2, 'こめんとー', (NOW() + INTERVAL 21 SECOND), NOW())
, (13, 1, 3, 3, 'テストデータ作るの疲れた', (NOW() + INTERVAL 22 SECOND), NOW())
, (14, 1, 3, 4, 'そうだね、疲れるよねこれ', (NOW() + INTERVAL 23 SECOND), NOW())
, (15, 1, 3, 5, 'もうええんちゃうかな？', (NOW() + INTERVAL 24 SECOND), NOW())

, (22, 1, 4, 2, 'テスト１が発言したら', (NOW() + INTERVAL 31 SECOND), NOW())
, (23, 1, 4, 3, 'このターンも終わりで', (NOW() + INTERVAL 32 SECOND), NOW())
, (24, 1, 4, 4, 'ゲームも終わりますね', (NOW() + INTERVAL 33 SECOND), NOW())
, (25, 1, 4, 5, 'そうですね！', (NOW() + INTERVAL 34 SECOND), NOW())
;
