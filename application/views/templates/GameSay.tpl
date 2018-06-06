{strip}
<form class="form-inline" method="post" action="{$SITE_URL}GameSay?game_id={$game_id}&user_id={$user_id}&wave={$wave}">
  <label class="sr-only" for="message">Message</label>
  <input class="form-control mb-4 mr-sm-2" style="width: 80%;" placeholder="Message to room" type="text" name="message" />
  <input class="btn btn-primary mb-4" type="submit" name="submit" />
</form>

<div><span>
{if $error == 0}
{else if $error == 1}
  テキストを入力して下さい。
{else if $error == 2}
  不正なテキストです。
{else if $error == 3}
  不正なリクエストです。
{else if $error == 4}
  ターンが進んでしまっています、再読込してください。
{else if $error == 10}
　ゲームが終了しました。投票画面から投票を行ってください。
{else}
  不明なエラーコードを検出しました。
{/if}
</span></div>
{/strip}
