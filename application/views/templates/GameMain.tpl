{strip}
<div class="row mb-4">
  <div class="col-lg-3">
    <button class="btn btn-outline-primary" style="width: 100%;">
      TURN {$wave}/{$max_wave}
    </button>
    {include file="GameMemberlist.tpl"}
  </div>

  <div class="col-lg">
    <div class="row">
      <div class="col-lg-8">
      {include file="GameSay.tpl"}
      </div>
      <div class="col-lg">
        <div class="float-right">
          <button class="btn btn-primary mr-2" href="{$SITE_URL}GameJudgement">投票画面へ</button>
          <button class="btn btn-secondary" href="{$SITE_URL}GameMain?game_id={$game_id}&user_id={$user_id}">再読込</button>
        </div>
      </div>
    </div>
    <div>
    {include file="GameLog.tpl"}
    </div>
  </div>
</div>

{/strip}
