{assign var=turn value=0}
{foreach from=$logs item=log}
  {if $log->wave != $turn}
    {if $turn != 0}<br />{/if}
    <span>TURN:{$log->wave}</span><br />
  {/if}

  <div>
    <span>{$log->nickname}</span>
    <span>{$log->say}</span>
  </div>
  {assign var=turn value=$log->wave}
{/foreach}

<a href="{$SITE_URL}GameLog?game_id={$game_id}&user_id={$user_id}">再読込</a>
