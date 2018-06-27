{strip}
  <form method="get" action="{$SITE_URL}/GameCreateCheck">
    <input type="hidden" name="user_id" value="{$user_id}" />

    <div>
      最低参加人数：
      <select name="minimum">
        {section name=loop start=3 loop=8+1 step=1}
          <option value="{$smarty.section.loop.index}">{$smarty.section.loop.index}</option>
        {/section}
      </select>
      人
    </div>

    <div>
      ゲーム時間：
      <select name="playtime">
        {section name=loop start=300 loop=480+1 step=30}
          {assign var="label" value="{$smarty.section.loop.index / 60}"}
          <option value="{$smarty.section.loop.index}">{$label}</option>
        {/section}
      </select>
      分
    </div>

    <div>
      <input type="submit" name="submit" value="ゲーム開始" />
    </div>
  </form>


  <div>
    <a href="{$SITE_URL}ModeSelect?user_id={$user_id}">戻る</a>
  </div>
{/strip}
