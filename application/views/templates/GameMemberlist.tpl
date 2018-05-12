<div>参加者一覧</div>

{foreach from=$members item=person}
<div>
  <span>・{$person->nickname}</span>
</div>
{/foreach}

<a href="{$SITE_URL}GameMemberlist?game_id={$game_id}&user_id={$user_id}">再読込</a>
