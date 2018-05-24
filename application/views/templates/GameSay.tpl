{strip}
<form class="form-inline" method="post" action="{$SITE_URL}GameSay?game_id={$game_id}&user_id={$user_id}">
  <label class="sr-only" for="message">Message</label>
  <input class="form-control mb-4 mr-sm-2" style="width: 80%;" placeholder="Message to room" type="text" name="message" />

  <input class="btn btn-primary mb-4" type="submit" name="submit" />
{*
  {if $error}
    <span>&nbsp;{$error}</span>
  {/if}
*}

</form>
{/strip}
