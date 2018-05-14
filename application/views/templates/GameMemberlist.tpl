{* -------------------------------------------- *}
{* ゲームの参加者一覧を表示する領域 *}
{* 操作者自身が必ず一番上に表示される *}
{* -------------------------------------------- *}
{strip}
  {foreach from=$members item=person}
    {if $user_id == $person->user_id}
	  お題：{$person->word}
      <div style="background-color:#FFCCCC">
      参加者一覧
      <div>
        <span>
          ＊{$person->nickname}

          &nbsp;({$person->word})

        </span>
      </div>
    {/if}
  {/foreach}

  {foreach from=$members item=person}
    {if $user_id != $person->user_id}
      <div>
        <span>
          ・{$person->nickname}

          &nbsp;({$person->word})

        </span>
      </div>
    {/if}
  {/foreach}
{/strip}
</div>
