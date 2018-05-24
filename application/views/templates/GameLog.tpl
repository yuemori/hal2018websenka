{strip}
<nav id="game-log" class="navbar navbar-light bg-light">
  <ul class="nav nav-pills">
{assign var=turn value=0}
{foreach name=loop from=$game->waves item=w}
  {if $turn != $w->wave}
    <li class="nav-item"><a class="nav-link" href="#{$w->wave}">Turn{$w->wave}</a></li>
  {/if}
  {assign var=turn value=$w->wave}
{/foreach}
  </ul>
</nav>

<div data-spy="scroll" data-target="#game-log" data-offset="0">
{foreach name=loop from=$game->waves|@array_reverse:true item=w}
  {if !$smarty.foreach.loop.first}
    </p>
  {/if}
  <p>
  <a name="{$w->wave}" />
  <span>TURN:{$w->wave}</span>&nbsp;<a href="#top">▲</a>
  {foreach from=$w->logs item=log}
    <div class="row">
      <div class="col-lg-2"><small>{$log->insert_at}</small></div>
      <div class="col-lg-2">{$log->nickname}</div>
      <div class="col-lg">{$log->say}</div>
    </div>
  {/foreach}
  {if $smarty.foreach.loop.last}</p>{/if}
{foreachelse}
  <p>
    <span>TURN:1</span>
  </p>
{/foreach}
</div>
{/strip}
