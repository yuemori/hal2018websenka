{strip}
<nav id="game-log" class="navbar navbar-light bg-light">
  <ul class="nav nav-pills">
{assign var=turn value=0}
{foreach name=loop from=$logs item=log}
  {if $turn != $log->wave}
    <li class="nav-item"><a class="nav-link" href="#{$log->wave}">Turn{$log->wave}</a></li>
  {/if}
  {assign var=turn value=$log->wave}
{/foreach}
  </ul>
</nav>

<div data-spy="scroll" data-target="#game-log" data-offset="0">
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
  <div class="row">
    <div class="col-lg-2"><small>{$log->insert_at}</small></div>
    <div class="col-lg-2">{$log->nickname}</div>
    <div class="col-lg">{$log->say}</div>
  </div>
  {assign var=turn value=$log->wave}
  {if $smarty.foreach.loop.last}</p>{/if}
{foreachelse}
  <p>
    <span>TURN:1</span>
  </p>
{/foreach}
</div>
{/strip}
