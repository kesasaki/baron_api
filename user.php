<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    die('GETでアクセスしてください');
}
$user_id = htmlspecialchars($_GET['user_id'], ENT_QUOTES);
$json = file_get_contents("./password.json");
$arr  = json_decode($json, true);
$link = mysql_connect('localhost', 'root', $arr[0]);
if (!$link) {
    die('接続失敗です。'.mysql_error());
}
$db_name = "baron_battle";
$db_selected = mysql_select_db($db_name, $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

$sql = "select * from user where user_id = '$user_id';";
$result = mysql_query($sql);

if (!$result) {
    die("$sql が失敗しました。".mysql_error());
}
mysql_close($link);

$record = array();
while ($row = mysql_fetch_array($result)) {
    $record[] = $row;
}

error_log(var_export($record, true));

// scoreでソート
$json = json_encode($record[0]);
echo $json;
?>
