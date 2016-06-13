<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    die('GETでアクセスしてください');
}
$name = htmlspecialchars($_GET['name'], ENT_QUOTES);
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
?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>baron ranking</title>
    <link href='https://fonts.googleapis.com/css?family=Sorts+Mill+Goudy' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
  </head>
  <body>
    <dl>
      <dt>
        <ul>
          <li>ユーザ名</li>
          <li>スコア</li>
          <li>プレイ時間</li>
        </ul>
      </dt>
      <?php foreach($record as $key => $val) : ?>
        <dd>
          <ul>
            <li><?php echo $val['user_name'] ?></li>
            <li><?php echo $val['score'] ?></li>
            <li><?php echo $val['play_time'] ?></li>
          </ul>
        </dd>
      <?php endforeach; ?>
    </dl>
  </body>
</html>
