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

$sql = "select * from log;";
$result = mysql_query($sql);

if (!$result) {
    die("$sql が失敗しました。".mysql_error());
}
mysql_close($link);

$record = array();
while ($row = mysql_fetch_array($result)) {
    $record[] = $row;
}

// scoreでソート
$key_arr = array();
foreach ($record as $key => $value){
  $key_arr[$key] = $value['score'];
}
array_multisort($key_arr , SORT_DESC , $record);

var_dump($record);
?>
