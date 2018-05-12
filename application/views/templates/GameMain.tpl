<div>
  <span>TURN: {$wave}</span>
  <span>
    <a href="{$SITE_URL}GameJudgement">投票画面へ</a>
  </span>
  <span>
    <a href="{$SITE_URL}GameMain?game_id={$game_id}&user_id={$user_id}">再読込</a>
  </span>
</div>
<table border="0" cellpadding="0" width="800" height="800">
  <tr>
    <!-- ログイン中のプレーヤーからの発言を受け付ける入力フォーム -->
    <td height="75" align="left" valign="top" colspan="2">
	  <iframe frameborder="0" width="100%" height="100%" src="{$SITE_URL}GameSay?game_id={$game_id}&user_id={$user_id}"></iframe>
    </td>
  </tr>
  <tr>
    <!-- ゲームに参加中のユーザー一覧 -->
    <td align="left" valign="top" width="250">
	  <iframe frameborder="0" width="250" height="100%" src="{$SITE_URL}GameMemberlist?game_id={$game_id}&user_id={$user_id}"></iframe>
    </td>

    <!-- ゲーム中の参加ユーザーからの発言一覧 -->
    <td align="left" valign="top" width="100%">
	  <iframe frameborder="0" width="100%" height="100%" src="{$SITE_URL}GameLog?game_id={$game_id}&user_id={$user_id}"></iframe>
    </td>
  </tr>
</table>
