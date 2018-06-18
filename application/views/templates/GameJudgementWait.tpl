{strip}
  <div>
    <div>
    投票状態

    <ul>
    {assign var="is_first" value=true}
    {foreach name=loop from=$game->members item=person}
      {assign var="user_id" value=$person->user_id}
      <li>
        {$person->nickname}
        {foreach from=$game->results item=result}
          {if $result->user_id == $user_id}
            <span>&nbsp;済</span>
          {/if}
        {/foreach}
      </li>
    {/foreach}
    </ul>
    </div>
  </div>
{/strip}
