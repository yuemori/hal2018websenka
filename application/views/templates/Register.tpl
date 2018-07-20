{strip}
<div>
新規ユーザーの登録
</div>
<div>
  <form id="inputform" method="post" action="{$SITE_URL}Register/execute">
    <div>
      ユーザー名：
      <input name="name" type="text" value="{$name|default:''|escape}" />
	  {$name_error|default:''}
    </div>
    <div>
      パスワード：
      <input name="pass" type="password" value="{$pass|default:''|escape}" />
	  {$pass_error|default:''}
    </div>
    <div>
      ニックネーム：
      <input name="nickname" type="text" value="{$nickname|default:''|escape}" />
	  {$nickname_error|default:''}
    </div>
    <input type="submit" name="submit" value="登録" />
  </form>
</div>

{if isset($db_error)}
<div id="error-area">
  DB書き込み処理に失敗しました。
</div>
{/if}

<div>
  <a href="{$SITE_URL}Login">戻る</a>
</div>
{/strip}
