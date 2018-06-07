<!doctype html>
<html lang="ja">
  <head>
    <meta charaset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    <title>{$title|escape|default:'no title'}</title>
  </head>
  <body>
  <header class="navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><i class="fab fa-wolf-pack-battalion"></i> Word Wolf</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <!-- <a class="nav&#45;item nav&#45;link active" href="#">Home <span class="sr&#45;only">(current)</span></a> -->
      </div>
    </div>
  </header>
	<!-- ここまでは固定ヘッダー -->

  <div class="container-fluid mt-4">

    <!-- ここからアプリの描画 file:{$INCLUDE_PAGE}  -->
    {include file="$INCLUDE_PAGE"}
  </div>

	<!-- ここから固定フッター -->

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>

  <script type="text/javascript">
    var timer = null;         // タイマーオブジェクト
    var refresh_late = 1000;  // ログの更新間隔
    var is_checking = false;  // 更新処理の多重起動を防止する制御に使う
    var last_check_time = 0;  // 最後に更新確認した時間

    // ログの更新をチェックして、更新されていたら再描画する
    // ログ領域の再描画処理
    function refresh_log_area()
    {
      var requestURL = '{$SITE_URL}GameLog' +
                       '?game_id=' + {$game_id} +
                       '&user_id=' + {$user_id} 
      ;
      var request = new XMLHttpRequest();

      request.open('GET', requestURL);
      request.responseType = 'text';
	  request.onload = function () {
        elm = window.document.getElementById('log-view-area');
        elm.innerHTML = this.responseText;
        is_checking = false;  // 連投防止解除
      };
      request.send();
    }

    // この関数はタイマーオブジェクトによって定期的に呼び出される
    function check_and_refresh_log()
    {
      // 連投防止制御
      if (is_checking) return null;
      is_checking = true;

      var requestURL = '{$SITE_URL}WhatsNew' + 
                       '?game_id=' + {$game_id} +
                       '&user_id=' + {$user_id} +
                       '&ti=' + last_check_time
      ;
      var request = new XMLHttpRequest();
      request.open('GET', requestURL);
      request.responseType = 'json';
	  request.onload = function () {
        last_check_time = this.response.time;
        if (this.response.new) {
          refresh_log_area();   // ログの再描画
        } else {
          is_checking = false;  // 連投防止解除
        }
      };
      request.send();
    }

    // ログの更新確認を定期的に呼び出す様に設定する
    function initialize_timer()
    {
      if (null != timer) return false;
      timer = setInterval(check_and_refresh_log, refresh_late);
      return true;
    }

    // このページが読み込み終わった時に実行
    initialize_timer();
  </script>
</html>
