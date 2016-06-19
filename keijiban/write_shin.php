<?php
ini_set("display_errors", "On");
error_reporting(E_ALL);

$name = $_POST['name'];
$contents = $_POST['contents'];


$dsn = 'mysql:host=localhost; dbname=keijiban; charset=utf8';
$user = 'user';
$password = 'tech-aca';

try{
  $db = new PDO($dsn, $user, $password);
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $stmt = $db->prepare("
    INSERT INTO post (name, contents)
    VALUES (:name, :contents)"
  );
  $stmt->bindParam(':name', $name, PDO::PARAM_STR);
  $stmt->bindParam(':contents', $contents, PDO::PARAM_STR);
  $stmt->execute();
  // var_dump($contents);

  header('Location: bbs_shin.php');
  exit();

}catch(PDOExeption $e){
  die('エラー:'.$e->getMessage());
}
?>
