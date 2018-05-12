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

$sql = "select * from user order by highscore desc;";
$result = mysql_query($sql);

if (!$result) {
    die("$sql が失敗しました。".mysql_error());
}
mysql_close($link);

$record = array();
while ($row = mysql_fetch_array($result)) {
    $record[] = $row;
}

?>
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta name="format-detection" content="telephone=no">
    <title>baron ranking</title>
    <link href='https://fonts.googleapis.com/css?family=Sorts+Mill+Goudy' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
  </head>
  <body>
    <dl>
      <dt>
        <ul>
          <li>ユーザID</li>
          <li>ユーザ名</li>
          <li>ハイスコア</li>
          <li>総プレイ時間</li>
        </ul>
      </dt>
      <?php foreach($record as $key => $val) : ?>
        <dd>
          <ul>
            <li><?php echo $val['user_id'] ?></li>
            <li><?php echo $val['user_name'] ?></li>
            <li><?php echo $val['highscore'] ?></li>
            <li><?php echo $val['play_time_sum'] ?></li>
          </ul>
        </dd>
      <?php endforeach; ?>
    </dl>
  </body>
</html>
