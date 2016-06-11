<?php
//エラーが出て分からなくなりググって調べたエラーを調べるおまじない
ini_set("display_errors", On);
error_reporting(E_ALL);

$name = $_POST['name'];
$title = $_POST['title'];
$body = $_POST['body'];
$pass = $_POST['pass'];

if ($name == '' || $body == ''){
  // Locationヘッダ
  // header('Location:戻り先のURL');
  header('Location: bbs.php');
  exit();
}
if (!preg_match("/^[0-9]{4}$/", $pass)){
  //header()を使うとき<php　以外のhtmlで改行をしてるとエラーが出るので注意
  header('Location: bbs.php');
  exit();
}

//データベース接続
$dsn = 'mysql:host=localhost; dbname=test; charset=utf8';
$user = 'testuser';
$password = 'pass';

try{
  //newでインスタンス作成、PDOはクラス
  $db = new PDO($dsn, $user, $password);
  //->でメソッドを使う記号
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $stmt = $db->prepare("
    INSERT INTO bbs (name, title, body, date, pass)
    VALUES(:name, :title, :body, now(),:pass)"
  );

  //パラメータの割り当て
  $stmt->bindParam(':name', $name, PDO::PARAM_STR);
  $stmt->bindParam(':title', $title, PDO::PARAM_STR);
  $stmt->bindParam(':body', $body, PDO::PARAM_STR);
  $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
  //クエリの実行
  $stmt->execute();

  //bbsに戻る
  header('Location: http://localhost:8888/tech-aca/bbs.php');
  exit();


}catch(PDOExeption $e){//データベースに接続できないエラーに対処
  die('エラー：'.$e->getMessage());
}
?>
