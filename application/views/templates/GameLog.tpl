{strip}
<a name="top" />
{assign var=turn value=0}
{foreach name=loop from=$logs item=log}
  {if $turn != $log->wave}
    <a href="#{$log->wave}">{$log->wave}</a>/
  {/if}
  {assign var=turn value=$log->wave}
{/foreach}

{assign var=turn value=0}
{foreach name=loop from=$logs item=log}
  {if $log->wave != $turn}
    {if !$smarty.foreach.loop.first}
      </p>
    {/if}
    <p>
    <a name="{$log->wave}" />
    <span>TURN:{$log->wave}</span>&nbsp;<a href="#top">▲</a>
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
