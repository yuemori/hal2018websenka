
--
-- ユーザーアカウント
--
-- user_id: ユーザーID
-- nickname: ゲーム中、他プレーヤーに公開される名前
-- name: ログインユーザー名
-- pass: ログインパスワード（MD5）
-- insert_at: 登録日時
-- update_at: 最終更新日時
--
DROP TABLE IF EXISTS user_account;

CREATE TABLE user_account (
  user_id SERIAL
, nickname VARCHAR(255) NOT NULL
, name VARCHAR(255) NOT NULL
, pass VARCHAR(32) NOT NULL
, insert_at TIMESTAMP
, update_at TIMESTAMP
);

CREATE UNIQUE INDEX unique_name_on_user_account ON user_account (name);

CREATE UNIQUE INDEX unique_nickname_on_user_account ON user_account (nickname);

--
-- 登録キーワードリスト
--
-- word_id: ID
-- group_id: キーワードのペアに付与されるグループID
-- word: ゲームに利用されるキーワード
-- insert_at: 登録日時
-- update_at: 最終更新日時
--
DROP TABLE IF EXISTS keyword;

CREATE TABLE keyword (
  word_id SERIAL
, group_id INT NOT NULL
, word VARCHAR(255) NOT NULL
, insert_at TIMESTAMP
, update_at TIMESTAMP
, PRIMARY KEY (word_id)
);

CREATE UNIQUE INDEX unique_group_id_and_word_on_keyword ON keyword (group_id, word);

--
-- テストデータ
--

INSERT INTO keyword
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

SELECT SETVAL('keyword_word_id_seq', 9);

--
-- 登録キーワードの重複チェックテーブル
-- ＊キーワードをASCII昇順でソートして連結した物のMD5
--
-- group_id: キーワードのペアに付与されるグループ識別子
-- register_user_id: 登録者のユーザーID
-- md5sum: 同一グループのキーワードを全て連結して算出したMD5
--
DROP TABLE IF EXISTS keyword_groups;

CREATE TABLE keyword_groups (
  group_id SERIAL
, register_user_id INT NOT NULL
, md5sum VARCHAR(32) NOT NULL
, PRIMARY KEY (group_id)
);

CREATE UNIQUE INDEX md5sum ON keyword_groups (md5sum);

--
-- テストデータ
--

INSERT INTO keyword_groups
  (group_id, register_user_id, md5sum)
VALUES
  (1, 10, MD5('ワカメ豆腐'))
, (2, 10, MD5('コーヒー紅茶'))
, (3,  1, MD5('オセロ囲碁'))
, (4, 10, MD5('プール露天風呂'))
;

SELECT SETVAL('keyword_groups_group_id_seq', 5);

--
-- ゲーム進行管理
--
-- game_id: ゲーム毎に振られる一意なID
-- status: 現在の状態
-- group_id: このゲームのお題として選ばれたワードのグループID
-- minority_user_id: このゲームにおける少数派ユーザーID
-- minimum: 最低参加人数制限
-- playtime: ゲームの制限時間（秒単位）
-- creator_user_id: ルーム作成者のユーザーID
-- start_at: ゲーム開始時間
-- end_at: ゲーム終了時間
--
DROP TABLE IF EXISTS game;

CREATE TABLE game (
  game_id SERIAL
, status INT NOT NULL
, group_id INT
, minority_user_id INT
, minimum INT NOT NULL
, playtime INT NOT NULL
, creator_user_id INT NOT NULL
, start_at TIMESTAMP
, end_at TIMESTAMP
, PRIMARY KEY (game_id)
);

CREATE INDEX status_on_game ON game (status);

--
-- 参加者情報
--
-- game_id: ゲームID
-- user_id: このゲームに参加しているユーザーのID
-- word_id: このユーザーに対して公開されたキーワードID
--
DROP TABLE IF EXISTS game_member;

CREATE TABLE game_member (
  game_id INT NOT NULL
, user_id INT NOT NULL
, word_id INT NOT NULL
);

CREATE UNIQUE INDEX unique_game_id_and_user_id_on_game_member ON game_member (game_id, user_id);

--
-- ゲームログ（ユーザーの発言内容）
--
-- log_id: ゲームログ一つに付与される一意なID
-- game_id: どのゲームに対して付けられた発言なのか？
-- wave: 何ターン目に対しての発言なのか？
-- user_id: 発言者のユーザーID
-- say: 発言内容
-- insert_at: 発言日時
-- update_at: レコード更新日時
--
DROP TABLE IF EXISTS game_log;

CREATE TABLE game_log (
  log_id SERIAL
, game_id INT NOT NULL
, wave INT NOT NULL
, user_id INT NOT NULL
, say VARCHAR(255)
, insert_at TIMESTAMP
, update_at TIMESTAMP
, PRIMARY KEY (log_id)
);

CREATE INDEX game_id_and_wave_and_insert_at_on_game_log ON game_log (game_id, wave, insert_at);

--
-- ゲームリザルト
--
-- result_id: 結果に対して一意に振られるID
-- game_id: どのゲームの結果なのか？
-- user_id: 誰の結果？
-- vote_user_id: 誰を少数派として指名したのか？
-- insert_at: 登録日時
-- update_at: 最終更新日時
--
DROP TABLE IF EXISTS game_result;

CREATE TABLE game_result (
  result_id SERIAL
, game_id INT NOT NULL
, user_id INT NOT NULL
, vote_user_id INT NOT NULL
, insert_at TIMESTAMP
, update_at TIMESTAMP
, PRIMARY KEY (result_id)
);

CREATE UNIQUE INDEX unique_game_id_and_user_id_on_game_result ON game_result (game_id, user_id);
