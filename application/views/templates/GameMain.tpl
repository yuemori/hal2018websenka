{strip}
<div class="row mb-4">
  <div class="col-lg-3">
    <button class="btn btn-outline-primary" style="width: 100%;">
      TURN {$wave}
    </button>
    {include file="GameMemberlist.tpl"}
  </div>

  <div class="col-lg">
    <div class="row">
      <div class="col-lg-8">
      {if $end_of_game}
      {else}
        {include file="GameSay.tpl"}
      {/if}
      </div>

      <div class="col-lg">
        <div class="float-right">
          {if $end_of_game}
            <a class="btn btn-primary mr-2" href="{$SITE_URL}GameJudgement">投票画面へ</a>
          {else}
          {/if}
          <a class="btn btn-secondary" href="{$SITE_URL}GameMain?game_id={$game_id}&user_id={$user_id}#game-log">再読込</a>
        </div>
      </div>

    </div>
    <div id="log-view-area">
    {include file="GameLog.tpl"}
    </div>
  </div>
</div>

{/strip}
