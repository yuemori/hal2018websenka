{strip}
<div>
  ゲーム開始までしばらくお待ち下さい。
</div>
{/strip}

  <script type="text/javascript">
    var timer = null;         // タイマーオブジェクト
    var refresh_late = 1000;  // ログの更新間隔

    function initialize_timer()
    {
      if (null != timer) return false;
      timer = setInterval(function () {
        var redirectURL = '{$SITE_URL}GameJoin' + 
                          '?game_id=' + {$game_id} +
                          '&user_id=' + {$user_id}
        ;
		location.href = redirectURL;
      }, refresh_late);
      return true;
    }

    // このページが読み込み終わった時に実行
    initialize_timer();
  </script>

