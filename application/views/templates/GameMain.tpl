<span>TURN: {$wave}</span><br />
<table border="2" cellpadding="0" width="800" height="800">
  <tr>
    <!-- ログイン中のプレーヤーからの発言を受け付ける入力フォーム -->
    <td height="150" align="left" valign="top" colspan="2">
	  <iframe width="100%" height="100%" src="{$SITE_URL}GameSay?game_id={$game_id}&user_id={$user_id}"></iframe>
    </td>
  </tr>
  <tr>
    <!-- ゲームに参加中のユーザー一覧 -->
    <td align="left" valign="top" width="300">
	  <iframe width="300" height="100%" src="{$SITE_URL}GameMemberlist?game_id={$game_id}&user_id={$user_id}"></iframe>
    </td>

    <!-- ゲーム中の参加ユーザーからの発言一覧 -->
    <td align="left" valign="top" width="100%">
	  <iframe width="100%" height="100%" src="{$SITE_URL}GameLog?game_id={$game_id}&user_id={$user_id}"></iframe>
    </td>
  </tr>
</table>

<a href="{$SITE_URL}GameLog">ここまでの皆の発言内容</a><br />
<a href="{$SITE_URL}GameJudgement">投票画面へ</a><br />
