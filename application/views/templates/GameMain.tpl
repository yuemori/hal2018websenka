{strip}
<div class="row mb-4">
  <div class="col-lg-3">
    <button class="btn btn-outline-primary" style="width: 100%;">
      TURN {$wave}
    </button>
    {include file="GameMemberlist.tpl"}
  </div>

  <div class="col-lg">
    <div class="row">
      <div class="col-lg-8">
      {if $end_of_game || !$game->existUser($user_id)}
      {else}
        {include file="GameSay.tpl"}
      {/if}
      </div>

      <div class="col-lg">
        <div class="float-right">
          {if $end_of_game}
            <a class="btn btn-primary mr-2" href="{$SITE_URL}GameJudgement?game_id={$game_id}&user_id={$user_id}">投票画面へ</a>
          {else}
          {/if}
          <a class="btn btn-secondary" href="{$SITE_URL}GameMain?game_id={$game_id}&user_id={$user_id}#game-log">再読込</a>
        </div>
      </div>

    </div>
    <div id="log-view-area">
    {include file="GameLog.tpl"}
    </div>
  </div>
</div>
{/strip}



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

