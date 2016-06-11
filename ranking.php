<?php
$name = htmlspecialchars($_GET['name'], ENT_QUOTES);
$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('接続失敗です。'.mysql_error());
}
echo "接続成功です<br>";
$db_name = "baron_battle";
$db_selected = mysql_select_db($db_name, $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}
print("<p> $db_name データベースを選択しました。</p>");
mysql_close($link);
echo "入力したお名前は".$name."ですね";
?>
