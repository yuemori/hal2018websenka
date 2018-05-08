<?php


function usage()
{
	$error_msg = <<<EOF
> generate model template script
>   > gen.php [sql_filename] [[output_filename]]
>   [sql_filename] 入力ファイル
>   [[output_filename]] 省略時は標準出力

EOF;
	echo $error_msg;
}


if (!isset($argv[1])) {
	echo "argument error!\n";
	usage();
	exit (0);
}
if (!file_exists($argv[1])) {
	echo "file not found. [". $argv[1]. "]\n";
	usage();
	exit (0);
}

$sql = file_get_contents($argv[1]);


preg_match_all(
    "/CREATE TABLE ([^\(]+) .*$/im"
	, $sql
	, $matches
	, PREG_SET_ORDER
);

foreach ($matches as $i => $match) {
	$table_name = ucfirst(strtolower($match[1]));
	
	
	$template = file_get_contents("template.php");
	$result = str_replace("TABLENAME", $table_name, $template);

	$filename = $table_name. "_model.php";
	if (!file_exists($filename)) {
		$fp = fopen($filename, "w");
		fwrite($fp, $result);
		fclose($fp);
		echo "INFO. file write. ". $filename. " ...done\n";
	} else {
		echo "WARN. file exists. ". $filename. "\n";
	}
}



?>
