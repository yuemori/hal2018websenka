<html>
  <head>
    <link href="{$BASE_URL}css/default.css" type="text/css" rel="stylesheet" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{$title|escape|default:'no title'}</title>
  </head>
  <body>
  <!-- ここまでは固定ヘッダー -->
  <!-- ここからアプリの描画 file:{$INCLUDE_PAGE}  -->
{include file="$INCLUDE_PAGE"}

  <!-- ここから固定フッダー -->
  </body>
</html>
