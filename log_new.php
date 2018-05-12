<?php
// パラメータをパース
/*
if($_SERVER["REQUEST_METHOD"] != "POST"){
    die('POSTでアクセスしてください');
}
*/
$user_id   = htmlspecialchars($_GET['user_id'],     ENT_QUOTES);
$score     = htmlspecialchars($_GET['score'],       ENT_QUOTES);
$play_time = htmlspecialchars($_GET['play_time'],  ENT_QUOTES);
if (!$user_id) {
    $user_id = "0";
}
if (!$score) {
    $score = "0";
}
if (!$play_time) {
    $play_time = 10;
}
$json = file_get_contents("./password.json");
$arr  = json_decode($json, true);


// DB接続、DB選択
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


// user 情報を取得
$sql = "SELECT * FROM user WHERE user_id = '$user_id'";
$result = mysql_query($sql);
$record = array();
while ($row = mysql_fetch_array($result)) {
    $record[] = $row;
}

$json = json_encode($record);
echo $json;

$highscore      = ($score > $record[0]['highscore']) ? $score : $record[0]['highscore'];
$play_count     = $record[0]['play_count'] + 1;
$play_time_sum  = $record[0]['play_time_sum'] + $play_time;

$sql = "UPDATE user SET   highscore='$highscore',
                          play_count='$play_count',
                          play_time_sum='$play_time_sum'
                    WHERE user_id = '$user_id'";
$result_flag = mysql_query($sql);
$user_name = $record[0]['user_name'];
if (!$result_flag) {
    file_put_contents("log/error_" . date('Ymd') . '.txt', date("Y-m-d H:i:s") . " $user_id $user_name $score $play_time $sql クエリーが失敗しました。" . mysql_error() . PHP_EOL, FILE_APPEND); 
    die(" $sql クエリーが失敗しました。".mysql_error());
}
file_put_contents("log/log_" . date('Ymd') . '.txt', date("Y-m-d H:i:s") . " $user_id $user_name $score $play_time" . PHP_EOL, FILE_APPEND); 

mysql_close($link);
?>
