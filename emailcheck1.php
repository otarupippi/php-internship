<?php
  $dsn = '（データベース名）';
	$user = '（ユーザー名）';
	$password = '（パスワード）';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  $i=0;
  $warning="";
  $used_dUn="";
  $used_dPw="";
  $used_newUn="";
  $used_newPw="";

  if(isset($_POST["email_check"])){
    $used_dUn=$_POST["dUn_check"];
    $used_dPw=$_POST["dPw_check"];
    $used_newUn=$_POST["newUn"];
    $used_newPw=$_POST["newPw"];
  }

  if(!empty($_POST["dUn_check"]) && !empty($_POST["dPw_check"])){
    
    
    $sql = 'SELECT * FROM submitinfo1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
      //$rowの中にはテーブルのカラム名が入る
      if(($row['name']==$_POST["dUn_check"]) && ($row['pw']==$_POST["dPw_check"])){
        if(!empty($used_newUn) && !empty($used_newPw)){
          $id = $row['id']; //変更する投稿番号
          $name = $used_newUn;
          $pw = $used_newPw; //変更したい名前、変更したいコメントは自分で決めること
          $sql = 'UPDATE submitinfo1 SET name=:name,pw=:pw WHERE id=:id';
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':name', $name, PDO::PARAM_STR);
          $stmt->bindParam(':pw', $pw, PDO::PARAM_STR);
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

          http_response_code( 301 ) ;
          
          header( "Location: ./login1.php" ) ;
          exit ;
        }
      }
      
      $i++;

      // 	echo $row['id'].',';
      // 	echo $row['name'].',';
      // 	echo $row['pw'].'<br>';
      // echo "<hr>";
    }
    $warning="<br>仮ユーザ名または仮パスワードが間違っているか、新しいユーザ名、パスワードが入力されていません。";
    // echo $warning;
  }
  else if(isset($_POST["email_check"])){
    $warning="<br>仮ユーザ名または仮パスワードが入力されていません。";
    // echo $warning;
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>emailcheck1.php</title>
</head>
<body>
<p>入力されたメールアドレスに記載された仮ユーザ名と仮パスワードを入力してください。<?php echo $warning;?></p>
  <form action="" method="POST">
    <p>仮ユーザ名</p><input type="text" name="dUn_check" value="<?php echo $used_dUn; ?>">
    <p>仮パスワード</p><input type="text" name="dPw_check" value="<?php echo $used_dPw; ?>">
    <br>
    <p>新しいユーザ名</p><input type="text" name="newUn" value="<?php echo $used_newUn; ?>">
    <p>新しいパスワード</p><input type="text" name="newPw" value="<?php echo $used_newPw; ?>">
    <input type="submit" name="email_check">
  </form>
</body>
</html>