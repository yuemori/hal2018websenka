{strip}
{assign var=turn value=0}
{foreach name=loop from=$logs item=log}
  {if $log->wave != $turn}
    {if !$smarty.foreach.loop.first}
      </p>
    {/if}
    <p>
    <span>TURN:{$log->wave}</span>
  {/if}
  <div>
    <span>{$log->insert_at}</span>
    <span>{$log->nickname}</span><br />
    <span>{$log->say}</span>
  </div>
  {assign var=turn value=$log->wave}
  {if $smarty.foreach.loop.last}</p>{/if}
{foreachelse}
  <p>
    <span>TURN:1</span>
  </p>
{/foreach}
{/strip}
