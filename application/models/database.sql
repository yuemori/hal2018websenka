
#
# ユーザーアカウント
#
# user_id: ユーザーID
# nickname: ゲーム中、他プレーヤーに公開される名前
# name: ログインユーザー名
# pass: ログインパスワード（MD5）
# insert_at: 登録日時
# update_at: 最終更新日時
#
DROP TABLE IF EXISTS UserAccount;
CREATE TABLE UserAccount (
  user_id INT NOT NULL AUTO_INCREMENT
, nickname VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
, name VARCHAR(255) NOT NULL
, pass VARCHAR(32) NOT NULL
, insert_at DATETIME
, update_at DATETIME
, PRIMARY KEY (user_id)
, UNIQUE INDEX(name)
, UNIQUE INDEX(nickname)
) ENGINE=InnoDB
;


#
# 登録キーワードリスト
#
# word_id: ID
# group_id: キーワードのペアに付与されるグループID
# word: ゲームに利用されるキーワード
# insert_at: 登録日時
# update_at: 最終更新日時
#
DROP TABLE IF EXISTS Keyword;
CREATE TABLE Keyword (
  word_id INT NOT NULL AUTO_INCREMENT
, group_id INT NOT NULL
, word VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
, insert_at DATETIME
, update_at DATETIME
, PRIMARY KEY (word_id)
, UNIQUE INDEX(group_id, word)
) ENGINE=InnoDB
;



#
# 登録キーワードの重複チェックテーブル
# ＊キーワードをASCII昇順でソートして連結した物のMD5
#
# group_id: キーワードのペアに付与されるグループ識別子
# register_user_id: 登録者のユーザーID
# md5sum: 同一グループのキーワードを全て連結して算出したMD5
#
DROP TABLE IF EXISTS KeywordGroups;
CREATE TABLE KeywordGroups (
  group_id INT NOT NULL
, register_user_id INT NOT NULL
, md5sum VARCHAR(32) CHARACTER SET ascii NOT NULL
, PRIMARY KEY (group_id)
, UNIQUE INDEX(md5sum)
) ENGINE=InnoDB
;



#
# ゲーム進行管理
#
# game_id: ゲーム毎に振られる一意なID
# status: 現在の状態
# group_id: このゲームのお題として選ばれたワードのグループID
# minority_user_id: このゲームにおける少数派ユーザーID
# minimum: 最低参加人数制限
# playtime: ゲームの制限時間（秒単位）
# creator_user_id: ルーム作成者のユーザーID
# start_at: ゲーム開始時間
# end_at: ゲーム終了時間
#
DROP TABLE IF EXISTS Game;
CREATE TABLE Game (
  game_id INT NOT NULL AUTO_INCREMENT
, status INT NOT NULL
, group_id INT
, minority_user_id INT
, minimum INT NOT NULL
, playtime INT NOT NULL
, creator_user_id INT NOT NULL
, start_at DATETIME
, end_at DATETIME
, PRIMARY KEY (game_id)
, INDEX (status)
) ENGINE=InnoDB
;


#
# 参加者情報
#
# game_id: ゲームID
# user_id: このゲームに参加しているユーザーのID
# word_id: このユーザーに対して公開されたキーワードID
#
DROP TABLE IF EXISTS GameMember;
CREATE TABLE GameMember (
  game_id INT NOT NULL
, user_id INT NOT NULL
, word_id INT NOT NULL
, UNIQUE INDEX(game_id, user_id)
) ENGINE=InnoDB
;


#
# ゲームログ（ユーザーの発言内容）
#
# log_id: ゲームログ一つに付与される一意なID
# game_id: どのゲームに対して付けられた発言なのか？
# wave: 何ターン目に対しての発言なのか？
# user_id: 発言者のユーザーID
# say: 発言内容
# insert_at: 発言日時
# update_at: レコード更新日時
#
DROP TABLE IF EXISTS GameLog;
CREATE TABLE GameLog (
  log_id INT NOT NULL AUTO_INCREMENT
, game_id INT NOT NULL
, wave INT NOT NULL
, user_id INT NOT NULL
, say VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin
, insert_at DATETIME
, update_at DATETIME
, PRIMARY KEY (log_id)
, INDEX(game_id, wave, insert_at)
) ENGINE=InnoDB
;



#
# ゲームリザルト
#
# result_id: 結果に対して一意に振られるID
# game_id: どのゲームの結果なのか？
# user_id: 誰の結果？
# vote_user_id: 誰を少数派として指名したのか？
# insert_at: 登録日時
# update_at: 最終更新日時
#
DROP TABLE IF EXISTS GameResult;
CREATE TABLE GameResult (
  result_id INT NOT NULL AUTO_INCREMENT
, game_id INT NOT NULL
, user_id INT NOT NULL
, vote_user_id INT NOT NULL
, insert_at DATETIME
, update_at DATETIME
, PRIMARY KEY (result_id)
, UNIQUE INDEX(game_id, user_id)
) ENGINE=InnoDB
;
