{* -------------------------------------------- *}
{* ゲームの参加者一覧を表示する領域 *}
{* 操作者自身が必ず一番上に表示される *}
{* -------------------------------------------- *}
{strip}
<div class="card mt-4" style="width: 100%;">
    <h5 class="card-header">参加者一覧</h5>
    <div class="card-body">
  {foreach from=$members item=person}
    {if $user_id == $person->user_id}
      <div class="card-title">あなたのお題: {$person->word}</div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          {$person->nickname}
          &nbsp;({$person->word})
        </li>
    {/if}
  {/foreach}

  {foreach from=$members item=person}
    {if $user_id != $person->user_id}
        <li class="list-group-item">
          {$person->nickname}

          &nbsp;({$person->word})
        </li>
    {/if}
  {/foreach}
      </ul>
  </div>
</div>
{/strip}
