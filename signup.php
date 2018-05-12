<?php

// パラメータをパース
/*
if($_SERVER["REQUEST_METHOD"] != "POST"){
    die('POSTでアクセスしてください');
}
*/
$user_name = htmlspecialchars($_POST['user_name'],   ENT_QUOTES);
//$user_name = htmlspecialchars($_GET['user_name'],   ENT_QUOTES);
if (!$user_name) {
    $user_name = "no_name";
}


// DB接続、DB選択
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


// 登録、ID発行 
$sql = "INSERT INTO  user(
    user_name,    highscore,     play_count,    play_time_sum) VALUES (
  '$user_name',         '0',            '0',              '0')";
$result_flag = mysql_query($sql);
if (!$result_flag) {
    file_put_contents("log/error_" . date('Ymd') . '.txt', date("Y-m-d H:i:s") . " $sql クエリーが失敗しました。" . mysql_error() . PHP_EOL, FILE_APPEND); 
    $json = json_encode(array());
    echo $json;
    die(" $sql クエリーが失敗しました。".mysql_error());
}
file_put_contents(    "log/log_" . date('Ymd') . '.txt',   date("Y-m-d H:i:s") . " $sql" . PHP_EOL, FILE_APPEND); 

// ID取得
$sql = "select * from user order by user_id desc limit 1;";
$result = mysql_query($sql);
$record = array();
while ($row = mysql_fetch_array($result)) {
    $record[] = $row;
}
mysql_close($link);

// 返り値
$json = json_encode($record[0]);
echo $json;
?>
