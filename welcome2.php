<?php 
  $dsn = '（データベース名）';
	$user = '（ユーザー名）';
	$password = '（パスワード）';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	$sql = "CREATE TABLE IF NOT EXISTS submitinfo1"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name TEXT,"
  . "pw TEXT,"
  . "email TEXT"
	.");";
  $stmt = $pdo->query($sql);
  
  $i=0;
  $length=6;
  if(!empty($_POST["to"]) && !empty($_POST["address"])){

    $dsn = '（データベース名）';
    $user = '（ユーザー名）';
    $password = '（パスワード）';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $sql = 'SELECT * FROM submitinfo1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
      //$rowの中にはテーブルのカラム名が入る
      if($row['email']==$_POST["address"]){
        $i++;
      }
    }
    $mail = isset($_POST['address']) ? $_POST['address'] : NULL;

    if($i>0){
      echo 'すでに使用されているメールアドレスです。<br><a href="./login1.php">ログイン</a>しますか？';

      //メールアドレス構文チェック
    }else if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
        echo "メールアドレスの形式が正しくありません。";
    }else{
      $defaultUserName= substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, $length);
      $defaultPassword= substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
      
      require 'src/Exception.php';
      require 'src/PHPMailer.php';
      require 'src/SMTP.php';
      require 'setting.php';

      // PHPMailerのインスタンス生成
      $mail = new PHPMailer\PHPMailer\PHPMailer();
      
      $mail->isSMTP(); // SMTPを使うようにメーラーを設定する
      $mail->SMTPAuth = true;
      $mail->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
      $mail->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
      $mail->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
      $mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
      $mail->Port = SMTP_PORT; // 接続するTCPポート
      
      // メール内容設定
      $mail->CharSet = "UTF-8";
      $mail->Encoding = "base64";
      $mail->setFrom(MAIL_FROM,MAIL_FROM_NAME);
      $mail->addAddress($_POST["address"], $_POST["to"].'さま'); //受信者（送信先）を追加する
      //    $mail->addReplyTo('xxxxxxxxxx@xxxxxxxxxx','返信先');
      //    $mail->addCC('xxxxxxxxxx@xxxxxxxxxx'); // CCで追加
      //    $mail->addBcc('xxxxxxxxxx@xxxxxxxxxx'); // BCCで追加
      $mail->Subject = MAIL_SUBJECT; // メールタイトル
      $mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
      $body = 'ご登録ありがとうございます。<br>表示された画面で以下の仮ユーザ名と仮パスワードを入力してください。<br>仮ユーザ名:'.$defaultUserName.'<br>仮パスワード:'.$defaultPassword;
      
      $mail->Body  = $body; // メール本文
      // メール送信の実行
      if(!$mail->send()) {
        echo 'メッセージを送信できませんでした。<br>お名前とメールアドレスを確認して、もう一度お試しください。';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
        $sql = $pdo -> prepare("INSERT INTO submitinfo1 (name, pw, email) VALUES (:name, :pw, :email)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':pw', $pw, PDO::PARAM_STR);
        $sql -> bindParam(':email', $email, PDO::PARAM_STR);
        $name = $defaultUserName;
        $pw = $defaultPassword;
        $email=$_POST["address"];
        $sql -> execute();
        echo '送信完了！';
        
        http_response_code( 301 ) ;
        
        // リダイレクト
        header( "Location: ./emailcheck1.php" ) ;
        exit ;
      }
    }
  }else if(isset($_POST["send_email"])){
    echo "名前とEメールアドレスの両方を入力してください。";
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>新規登録</title>
</head>
<body>
<p>welcome!</p>
<p>名前とEメールアドレスを入力してください。</p>
  <form action="" method="POST">
  <p>名前</p><input type="text" name="to">
  <p>Eメールアドレス</p><input type="text" name="address">
  <input type="submit" name="send_email">
  </form>
<a href="./login1.php">ログインはこちら！</a>
</body>
</html>