{strip}
  <div>
    <div>
    投票状態

    <ul>
    {foreach from=$game->members item=person}
      <li>
        {$person->nickname}&nbsp;
        <span id="vote_{$person->user_id}"></span>
{*
        {foreach from=$game->results item=result}
          {if $result->user_id == $user_id}
            <span>&nbsp;済</span>
          {/if}
        {/foreach}
*}

      </li>
    {/foreach}
    </ul>
    </div>
  </div>

{/strip}


  <script type="text/javascript">
    var timer = null;         // タイマーオブジェクト
    var refresh_late = 1000;  // ログの更新間隔
    var is_checking = false;  // 更新処理の多重起動を防止する制御に使う
	var findedMessage = "done.";

    // この関数はタイマーオブジェクトによって定期的に呼び出される
    function check_vote_status()
    {
      // 連投防止制御
      if (is_checking) return null;
      is_checking = true;

      var requestURL = '{$SITE_URL}GameJudgementCheck' + 
                       '?game_id=' + {$game_id} +
                       '&user_id=' + {$user_id}
      ;
      var request = new XMLHttpRequest();
      request.open('GET', requestURL);
      request.responseType = 'json';
	  request.onload = function () {
	    var allOK = true;
	    for (let i=0; i < this.response.length; i++) {
		  var item = this.response[i];
		  if (item.vote) {
		    id = "vote_" + item.user_id;
			document.getElementById(id).innerHTML = findedMessage;
          } else {
	        allOK = false;
		  }
		}

		// 全員入力完了
		if (allOK) {
		  var redirectURL = '{$SITE_URL}GameResult' + 
                            '?game_id=' + {$game_id} +
                            '&user_id=' + {$user_id}
          ;
		  location.href = redirectURL;
		  return ;
		}

        is_checking = false;  // 連投防止解除
      };
      request.send();
    }

    function initialize_timer()
    {
      if (null != timer) return false;
      timer = setInterval(check_vote_status, refresh_late);
      return true;
    }

    // このページが読み込み終わった時に実行
    initialize_timer();
  </script>
