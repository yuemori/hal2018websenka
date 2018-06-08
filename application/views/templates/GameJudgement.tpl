{* -------------------------------------------- *}
{* ゲームの参加者一覧を表示する領域 *}
{* 操作者自身が必ず一番上に表示される *}
{* -------------------------------------------- *}
{strip}
<form method="get" action="{$SITE_URL}GameJudgementInput">
  <input type="hidden" name="game_id" value="{$game_id}" />
  <input type="hidden" name="user_id" value="{$user_id}" />

  <div>
    {foreach from=$game->members item=person}
      {if $user_id == $person->user_id}
        <div>あなたのお題: {$person->word}</div>
      {/if}
    {/foreach}
    <div>
    参加者一覧

    <ul>
    {assign var="is_first" value=true}
    {foreach name=loop from=$game->members item=person}
      {if $user_id != $person->user_id}
        <li>
          <input
             type="radio"
             name="accuser"
             value="{$person->user_id}"
             {if $is_first} checked
               {assign var="is_first" value=false}
             {/if}
          />&nbsp;
          {$person->nickname}
        </li>
      {/if}
    {/foreach}
    </ul>
    </div>
  </div>

  <input type="submit" name="submit" />
</form>

<div>
  {include file="GameLog.tpl"}
</div>

{/strip}
