{strip}
<div>
  <form method="post" action="{$SITE_URL}LoginCheck">
    <div>
      ユーザー名：
      <input name="name" type="text" value="" />
    </div>
    <div>
      パスワード：
      <input name="pass" type="password" value="" />
    </div>
    <input type="submit" name="submit" value="Login" />
  </form>
</div>

{if $error == 0}
{else if $error == 1}
  <div>ユーザー名、パスワードを御確認下さい。</div>
{else}
  <div>不明なエラーです。</div>
{/if}

<div>
  <a href="{$SITE_URL}Register">新規登録はコチラ</a><br />
</div>
{/strip}
