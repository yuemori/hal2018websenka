{strip}
<div>
{$user->nickname}さん、ようこそ！
</div>
<div>
  <a href="{$SITE_URL}GameSelect?user_id={$user_id}">既存のゲームに参加</a>
</div>
<div>
  <a href="{$SITE_URL}GameCreate?user_id={$user_id}">ゲームを新しく始める</a>
</div>
<div>
  <a href="{$SITE_URL}GameHistory?user_id={$user_id}">過去の結果</a>
</div>

<div>
  <a href="{$SITE_URL}WordRegister?user_id={$user_id}">新しいワードの登録</a>
</div>
<div>
  <a href="{$SITE_URL}Login">ログアウト</a>
</div>
{/strip}
