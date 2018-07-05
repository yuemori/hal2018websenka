{strip}
<div>
新規ユーザーの登録
</div>
<div>
  <form id="inputform" method="post" action="{$SITE_URL}RegistCheck">
    <div>
      ユーザー名：
      <input name="name" type="text" value="" />
    </div>
    <div>
      パスワード：
      <input name="pass" type="password" value="" />
    </div>
    <div>
      ニックネーム：
      <input name="nickname" type="text" value="" />
    </div>
    <input type="button" name="button" onclick="handler();" value="登録" />
  </form>
</div>

<div id="error-area">
</div>

<div>
  <a href="{$SITE_URL}Login">戻る</a>
</div>
{/strip}

  <script type="text/javascript">
    /*
     * フォームの内容をPOSTできるように変換する
     */
    function formToHTTP(form)
    {
      var params = [];
      for (var i=0; i<form.elements.length; i++) {
        var name  = form.elements[i].name;
        var value = form.elements[i].value;
        var param = encodeURIComponent(name) +
                    '=' +
                    encodeURIComponent(value)
        ;
        params.push(param);
      }
      return params.join('&').replace(/%20/g, '+');
    }

    /*
     * 「登録」ボタンが押された時のアクション
     */
    function handler()
    {
      var form = document.getElementById("inputform");
      var body = formToHTTP(form);
      var requestURL = '{$SITE_URL}RegistCheck';
      var request = new XMLHttpRequest();
      request.open('POST', requestURL);
      request.responseType = 'json';
	  request.onload = function () {
        if (this.response.result) {
          var redirectURL = '{$SITE_URL}ModeSelect' + 
                            '?user_id=' + this.response.user_id
          ;
          location.href = redirectURL;
        } else {
          var text = "";
          for (var i=0; i<this.response.errors.length; i++) {
            text = text +
                   '<p class="alert">' + 
                   getError(this.response.errors[i]) +
                   '</p>'
            ;
          }
          document.getElementById("error-area").innerHTML=text;
        }
      };
      request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      document.getElementById("error-area").innerHTML="";
      request.send(body);
      return false;
    }

    /*
     * エラー番号に対応した文字列を返す
     * エラー定義は controller/Regist.php を参照
     */
    function getError(err)
    {
      if (err == 0) {
        return ""; //  0: ERR_NO_ERROR
      } else if (err == 10) {
        return "ユーザー名を御確認下さい、ユーザー名は半角英数4文字以上です。"; // 10: ERR_USERNAME_INVALID
      } else if (err == 11) {
        return "そのユーザー名は既に使われてます。"; // 11: ERR_USERNAME_DUPLICATE
      } else if (err == 20) {      
        return "パスワードを御確認下さい、パスワードは半角英数4文字以上です。"; // 20: ERR_PASSWORD_INVALID
      } else if (err == 30) {
        return "ニックネームの入力が不正です。"; // 30: ERR_NICKNAME_INVALID
      } else if (err == 31) {
        return "そのニックネームは既に使われてます。"; // 31: ERR_NICKNAME_DUPLICATE
      }
      return "予期せぬエラーが発生しました。"; // 99: ERR_UNKNOWN and other
    }

  </script>
