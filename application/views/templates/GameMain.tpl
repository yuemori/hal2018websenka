{strip}
<div>
  <span>TURN: {$wave}/{$max_wave}</span>
  <span>
    <a href="{$SITE_URL}GameJudgement">投票画面へ</a>
  </span>
  <span>
    <a href="{$SITE_URL}GameMain?game_id={$game_id}&user_id={$user_id}">再読込</a>
  </span>
</div>

<!-- 送信フォーム -->
{include file="GameSay.tpl"}
{include file="GameMemberlist.tpl"}
{include file="GameLog.tpl"}

{/strip}
