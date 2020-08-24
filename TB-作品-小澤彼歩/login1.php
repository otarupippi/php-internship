<?php
  $dsn = '（データベース名）';
	$user = '（ユーザ名）';
	$password = '（パスワード）';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  
  if(!empty($_POST["enteredUn"]) && !empty($_POST["enteredPw"])){
    $sql = 'SELECT * FROM welcometo';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
      if(($row['name']==$_POST["enteredUn"]) && ($row['pw']==$_POST["enteredPw"])){
        http_response_code( 301 ) ;
          
          header( "Location: ./tb-sakuhin.php" ) ;
          exit ;
      }
    }
    echo 'ユーザ名かパスワードが間違っています。<br><a href="">新規登録</a>しますか？';
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>login</title>
</head>
<body>
  <p>ログイン</p>
  <p>ユーザ名とパスワードを入力してください。</p>
  <form action="" method="POST">
    <input type="text" name="enteredUn">
    <input type="text" name="enteredPw">
    <input type="submit" name="login_check">
  </form>
  <a href="">新規登録はこちら！</a>
  <!-- セキュリティ上の理由でリンクは非掲載 -->
</body>
</html>