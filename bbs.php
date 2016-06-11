<?php
//エラーが出て分からなくなりググって調べたエラーを調べるおまじない
ini_set("display_errors", On);
error_reporting(E_ALL);
?>


<?php
  //1pに表示されるコメント数
  $num = 10;

  $dsn = 'mysql:host=localhost; dbname=test; charset=utf8';
  $user = 'testuser';
  $password = 'pass';

  $page = 0;
  //isset() で変数が存在しているかどうか
  //$_GET['page']で　url の最後に ?page=  で代入したものが入る
  if(isset($_GET['page']) && $_GET['page'] > 0){
    $page = intval($_GET['page']) - 1;//intval()で　整数値を返す
  }

  try{
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //プリペアドステートメント
    //実行するクエリのテンプレートを作っておく
    $stmt = $db->prepare(
      "SELECT * FROM bbs ORDER BY date DESC LIMIT
      :page, :num"
    );
    $page = $page * $num;
    $stmt->bindParam(':page',$page,PDO::PARAM_INT);
    $stmt->bindParam(':num', $num, PDO::PARAM_INT);
    //クエリの実行　execute()
    $stmt->execute();
  }catch(PDOExeption $e){
    echo "エラー:" . $e->getMessage();

  }

 ?>


<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=UTF-8">
  <title>掲示板</title>
</head>
<body>
  <h1>掲示板</h1>
    <!--h1で一番大きい見出し-->
    <p><a href="index1.php">トップページへ戻る</a></p>
    <!--pタグで段落
        a href=""でリンクを貼る-->
    <form action="write.php" method="post">
      <p>名前:<input type="text" name="name"></p>
      <p>タイトル:<input type="text" name="title"></p>
      <textarea name="body"></textarea>
      <p>削除パスワード（数字４桁）:<input type="text" name="pass"></p>
      <p><input type="submit" value="書き込む"></p>
    </form>
    <hr />
<?php
  //fetch()　でレコード一件をカラム名をキーにして連想配列として返す
  while ($row = $stmt->fetch()):
    //三項演算子　条件式 ? 式１ :式２
    //条件式がTRUEなら式１を返す
    //タイトルに何も入っていなければ無題を返す
    $title = $row['title'] ? $row['title'] : '無題';

  ?>

    <p>名前:<?php echo $row['name'] ?></p>
    <p>タイトル:<?php echo $title ?></p>

    <p><?php echo nl2br(htmlspecialchars($row['body'],ENT_QUOTES,'UTF-8'), false); //nl2brでphpの改行をhtmlの改行に直す?></p>
    <p><?php echo $row['date'] ?></p>
<?php
  endwhile;

  try{
    //COUNT()でカラムのnullでないものの数を返す
    //行数を知りたいので(*)でどのカラムか指定しない
    $stmt = $db->prepare("SELECT COUNT(*) FROM bbs");
    $stmt->execute();
  }catch(PDOExeption $e){
    echo "エラー:" . $e->getMessage();
  }
  $comments = $stmt->fetchColumn();
  $max_page = ceil($comments / $num);


  ?>

</body>
</html>
