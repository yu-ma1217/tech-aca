<?php
//エラーが出て分からなくなりググって調べたエラーを調べるおまじない
ini_set("display_errors", On);
error_reporting(E_ALL);
?>
<?php
  $dsn = 'mysql:host=localhost; dbname=keijiban; charset=utf8';
  $user = 'user';
  $password = 'tech-aca';

  try{
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $stmt = $db->prepare(
      "SELECT * FROM post"
      //ORDER BY date;の記述があったが、dbにdateを入れていなかった
    );
    var_dump($stmt);
    //なぜfalseか　→　上のせいでおかしなクエリに
    $stmt->execute();
    //execute はdbか

  }catch(PDOExeption $e){
    echo "エラー" . $e->getMessage();
  }

?>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=UTF-8">
  <title>掲示板</title>
</head>
<body>
  <h1>掲示板</h1>
  <form action="write_shin.php" method="post">
    <p>名前:<input type="text" name="name"></p>
    <textarea name="contents"></textarea>
    <p><input type="submit" value="書き込む"></p>
  </form>
  <hr />
<?php
  while($row = $stmt->fetch()):
?>
    <p>ID:<?php echo $row['id'] ?></p>
    <p>名前:<?php echo $row['name'] ?></p>
    <!--specialで<>などをエスケープし、nl2brでphpの改行をhtmlに直す -->
    <p>本文:<?php echo nl2br(htmlspecialchars($row['contents'],ENT_QUOTES,'UTF-8'),false);?>>
    </p>
<?php
  endwhile;
  try{
    $stmt = $db->prepare("SELECT COUNT(*) FROM post");
    $stmt->execute();
  }catch(PDOExeption $e){
    echo "エラー". $e->getMessage();
  }
  ?>

  </body>
  </html>
