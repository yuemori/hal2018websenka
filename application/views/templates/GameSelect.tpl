{strip}
  <div>
  {foreach from=$games item=game}
    {assign var=remain value=$game->minimum - $game->countOfMembers()}
    <div>
      <a href="{$SITE_URL}GameJoin?game_id={$game->game_id}&user_id={$user_id}">募集中</a>
      あと {$remain} 人
    </div>
  {/foreach}
  </div>

  <div>
	<a href="{$SITE_URL}GameSelect?user_id={$user_id}">再読込</a>
  </div>
  <div>
	<a href="{$SITE_URL}ModeSelect?user_id={$user_id}">戻る</a>
  </div>
{/strip}
