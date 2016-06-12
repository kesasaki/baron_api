<?php
if($_SERVER["REQUEST_METHOD"] != "POST"){
    die('POSTでアクセスしてください');
}
$user_id   = htmlspecialchars($_POST['user_id'],     ENT_QUOTES);
$user_name = htmlspecialchars($_POST['user_name'],   ENT_QUOTES);
$score     = htmlspecialchars($_POST['score'],       ENT_QUOTES);
$is_clear  = htmlspecialchars($_POST['is_clear'],    ENT_QUOTES);
$play_time = htmlspecialchars($_POST['play_time'],  ENT_QUOTES);
$dead_x    = htmlspecialchars($_POST['dead_x'],      ENT_QUOTES);
$dead_y    = htmlspecialchars($_POST['dead_y'],      ENT_QUOTES);
$stage_id  = htmlspecialchars($_POST['stage_id'],    ENT_QUOTES);
if (!$user_id) {
    $user_id = "0";
}
if (!$user_name) {
    $user_name = "no_name";
}
if (!$score) {
    $score = "0";
}
if (!$is_clear) {
    $is_clear = false;
}
if (!$play_time) {
    $play_time = 1000000;
}
if (!$dead_x) {
    $dead_x = "0";
}
if (!$dead_y) {
    $dead_y = "0";
}
if (!$stage_id) {
    $stage_id = "1";
}

$json = file_get_contents("./password.json");
$arr  = json_decode($json, true);
$link = mysql_connect('localhost', 'root', $arr[0]);
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

$sql = "INSERT INTO  log(
    user_id,     user_name,    score,     is_clear,    play_time,    dead_x,    dead_y,    stage_id) VALUES (
   '$user_id', '$user_name', '$score',  '$is_clear', '$play_time', '$dead_x', '$dead_y', '$stage_id' )";
$result_flag = mysql_query($sql);

if (!$result_flag) {
    die(" $sql クエリーが失敗しました。".mysql_error());
}

mysql_close($link);
?>
