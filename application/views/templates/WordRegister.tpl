{strip}
<div>
  <form id="inputform-id" method="post" action="{$SITE_URL}WordRegister/write?user_id={$user_id}">
    <div>
      単語１：<input name="word1" type="text" />
    </div>
    <div>
      単語２：<input name="word2" type="text" />
    </div>
    <div>
      <input type="button" name="button" onclick="handler('inputform-id');" value="登録" />

      {* APIのデバッグを行う時は、このボタン使うと楽 *}
      {* <input type="submit" name="submit" value="Debug" /> *}
    </div>
  </form>
</div>
<div id="error-area">
</div>
<div>
  <a href="{$SITE_URL}ModeSelect?user_id={$user_id}">戻る</a>
</div>
{/strip}
{**************************************************}
{* strip の中にJavaScript書いちゃうと *}
{* 改行コードが潰されて正常動作しないので注意 *}
{**************************************************}
  <script type="text/javascript">
    /*
     * 「登録」ボタンが押された時のアクション
     */
    function handler(form_id)
    {
      // 引数から送信対象のフォームオブジェクトを取得
      // JavaScriptからフォームが参照可能になる
      var form = document.getElementById(form_id);

      // フォームの各input内容を送信する形式にエンコードする
      var body = formToHTTP(form);

      // JavaScriptから送信するのに使うオブジェクトを用意
      // HTMLからフォームに設定されたリクエストURLとかを設定して送信
      var request = new XMLHttpRequest();
      request.open(form.method, form.action);
      request.setRequestHeader('Content-Type', form.enctype);

      // サーバから返される形式がJSONなので、ここでresponseTypeを設定しておいてあげると
      // リクエスト後に呼ばれる下記の関数内で、JSONオブジェクトとして結果を参照できる様になります。
      request.responseType = 'json';

      //------------------------------------------
      // リクエスト後に呼ばれる関数を設定、
      // この処理は現時点では実行されないので読む時は注意！
	  request.onload = function () {
        // サーバ側から返された登録結果を表示
        // エラーコードに対応するエラー内容の文字列を取得して表示しています、
        // 複数の検証内容が返ってきても対応できる様にループで処理している。
        // ＊結果の成功 or 失敗は this.response.result で参照可能
        var text = "";
        for (var i=0; i<this.response.errors.length; i++) {
          text = text +
                 '<p class="alert">' + 
                 getError(this.response.errors[i]) +
                 '</p>'
          ;
        }
        document.getElementById("error-area").innerHTML=text;
      };
      // ここまでがリクエスト後に呼ばれる処理
      //------------------------------------------

      // エラー領域を綺麗に掃除してから送信
      document.getElementById("error-area").innerHTML="";
      request.send(body);
      return true;
    }

    /*
     * エラー番号に対応した文字列を返す
     * エラー定義は controller/WordRegister.php を参照
     */
    function getError(err)
    {
      if (err == 0) {
        return "登録できました。"; //  0: SUCCESS
      } else if (err == 20 || err == 30) {
        return "単語は２つ入力して下さい。"; // 20,30: ERR_WORD__REQUIRED
      } else if (err == 21 || err == 22) { // 21, 22: ERR_WORD1_LENGTH
        return "単語１の長さが不正です。{$word_min}文字以上 {$word_max}文字以下で入力して下さい。";
      } else if (err == 31 || err == 32) { // 31, 32: ERR_WORD2_LENGTH
        return "単語２の長さが不正です。{$word_min}文字以上 {$word_max}文字以下で入力して下さい。";
      } else if (err == 40) {
        return "異なる単語を入力して下さい。"; // 40: ERR_WORD_EQUAL
      }
      return "予期せぬエラーが発生しました。"; // 99: ERR_UNKNOWN and other
    }

    /*
     * フォームの内容をPOSTできるように変換する
     * POSTで送信するためにフォーム内のパラメータを連結させた文字列として返す
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

  </script>
