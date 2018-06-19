{strip}
<div>

  <div>
    {foreach from=$game->members item=person}
      {if $person->user_id == $game->minority_user_id}
        <div>
          今回のワードウルフは&nbsp;{$person->nickname}&nbsp;さん
        </div>
      {/if}
    {/foreach}

    <div>
      {if $is_wordwolf_win}
        ワードウルフの勝利！！
      {else}
        ワードウルフの負け
      {/if}
    </div>
  </div>

  <div>
    投票結果
  </div>

  {foreach from=$game->members item=person}
    <div>
      <span>
        {$person->nickname}
      </span>

      {foreach from=$game->results item=result}
        {if $result->user_id == $person->user_id}
          {foreach from=$game->members item=p}
            {if $result->vote_user_id == $p->user_id}
              &nbsp;-&gt;&nbsp;
              <span>
                {$p->nickname}
              </span>
            {/if}
          {/foreach}
        {/if}
      {/foreach}
    </div>
  {/foreach}

  <a href="{$SITE_URL}ModeSelect?user_id={$user_id}">戻る</a>
</div>
{/strip}
