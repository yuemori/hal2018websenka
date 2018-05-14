{strip}
<div>
<form method="post" action="{$SITE_URL}GameSay?game_id={$game_id}&user_id={$user_id}">
  <span>
    <input size="40" type="text" name="message" />
  </span>
  <br />
  <span>
    <input type="submit" name="submit" />
  </span>
  {if $error}
    <span>&nbsp;{$error}</span>
  {/if}
</form>
</div>
<hr />
{/strip}
