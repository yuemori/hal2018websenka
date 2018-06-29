{strip}
<div>
  <form method="post" action="{$SITE_URL}RegistCheck">
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
    <input type="submit" name="submit" value="登録" />
  </form>
</div>

{if $error == 0}
{else if $error == 10}
  <div>ユーザー名を御確認下さい、ユーザー名は半角英数4文字以上です。</div>
{else if $error == 11}
  <div>既に利用されているユーザー名です。</div>
{else if $error == 20}
  <div>パスワードを御確認下さい、パスワードは半角英数4文字以上です。。</div>
{else if $error == 30}
  <div>ニックネームを御確認下さい。</div>
{else if $error == 31}
  <div>既に利用されているニックネームです。</div>
{else}
  <div>不明なエラーです。</div>
{/if}

<div>
  <a href="{$SITE_URL}Login">戻る</a>
</div>
{/strip}
