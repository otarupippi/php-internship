
<!doctype html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>山手線ゲーム</title>
  </head>
  <body>
    <?php
      $usernameOutput="";
      $passwordOutput="";
      $p=0;
      $hitName="";
      $hitPw="";
      $origin="";
      $saveStation="";
      $exited="";

      $def="";
      $from="";


      if (empty($_SERVER["HTTP_REFERER"])) {
        //リダイレクト
        header('Location: login1.php');
      }    

      $dsn = '（データベース名）';
      $user = '（ユーザー名）';
      $password = '（パスワード）';
      $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
      session_start();
      if(!empty($_POST["exitCheck"])){
        $exited=$_POST["exitCheck"];
      }

      if(!empty($_POST["enteredUn"])){
        $dsn = '（データベース名）';
        $user = '（ユーザー名）';
        $password = '（パスワード）';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        $sql = 'SELECT * FROM submitinfo1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();

        $usernameOutput= $_POST["enteredUn"];

        if(empty($_POST["enteredPw"]) && empty($_POST["hiddenPw"])){
          echo "パスワードが入力されていません";
          if(!empty($_POST["deleteStation"])){
            $saveStation=$_POST["deleteStation"];
          }
        }else{
          if(!empty($_POST["enteredPw"])){
          foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            // echo $row['id'].',';
            // echo $row['name'].',';
            // echo $row['pw'].',';
            // echo $row['email'].'<br>';
            // echo "<hr>";
            if(($row['name']==$_POST["enteredUn"]) && ($row['pw']==$_POST["enteredPw"])){
              $hitName=$_POST["enteredUn"];
              $hitPw=$_POST["enteredPw"];
            }
          }
    
          }else{
          // データベースとhPwを照合、一致したら回答を認め、しなかったらリセット（？）
          foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            // echo $row['id'].',';
            // echo $row['name'].',';
            // echo $row['pw'].',';
            // echo $row['email'].'<br>';
            // echo "<hr>";
            if(($row['name']==$_POST["enteredUn"]) && ($row['pw']==$_POST["hiddenPw"])){
              $hitName=$_POST["enteredUn"];
              $hitPw=$_POST["hiddenPw"];
            }else{
              $p++;
            }
          }
          }
        if(!empty($hitName) && !empty($hitPw)){
          // echo $hitName."と".$hitPw."ですね。正しいです。<br>";
          $passwordOutput=$hitPw;
          $origin=$hitName."2";
          $dsn = '（データベース名）';
          $user = '（ユーザー名）';
          $password = '（パスワード）';
          $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
          
            if($_POST["exitCheck"]=="exited"){
              $fp_d=fopen($origin.".txt","w+");
              fclose($fp_d);
              $flg = copy('stations.txt', $origin.'.txt');
              if ($flg) {
                echo "リセットしました。";
              } else {
                echo "リセットに失敗しました。";
              }
              echo "<br><hr>";

              // ここから4-3
              $sql ='SHOW TABLES';
              $result = $pdo -> query($sql);
              foreach ($result as $row){
                // echo $row[0];
                // echo '<br>';
                if($row[0]==$origin){
                  $hitTable=$origin;
                }
              }
              // echo "<hr>";
              if(!empty($hitTable)){
                $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $name = 'reset';
                $comment = 'user exited'; 
                $date = strtotime("now");
                $sql -> execute();
                $hitTable="";
              }else{
                $sql = "CREATE TABLE IF NOT EXISTS $origin"
                ." ("
                . "id INT AUTO_INCREMENT PRIMARY KEY,"
                . "name char(32),"
                . "comment TEXT,"
                . "date TEXT"
                .");";
                $stmt = $pdo->query($sql);
              }
            }
    
          $filename=$origin.".txt";
          if(file_exists($origin.".txt")){
            $lines=file($origin.".txt",FILE_IGNORE_NEW_LINES);
          }
          $i=0;
          $j=0;
          $date=date("Y/m/d H:i:s");
          $resetId="";
    
          
          $exited="entered";
          
          if(isset($_POST["reset"])){
    
            $flg = copy('stations.txt', $origin.'.txt');
            if ($flg) {
              echo "リセットしました。";
            } else {
              echo "リセットに失敗しました。";
            }
            echo "<br><hr>";
    
            $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $name = 'reset';
            $comment = 'reset button pressed'; 
            $date = strtotime("now");
            $sql -> execute();
          }else if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

            // ここからhard
            $date= strtotime("now");
            $to=$date;
            // echo $to."が提出時刻です<br>";
            $sql = 'SELECT * FROM '. $origin;
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            $abc=count($results);
            foreach($results as $row){
                $def=$row['name'];
                $from=$row['date'];
            }

            if( isset( $_SESSION["key"], $_POST["key"] ) && $_SESSION["key"] == $_POST["key"] ) {
                unset( $_SESSION["key"] );
                if($def!='resetOld' && ($to - $from)>10){
                  echo "残念！時間切れです！<br>";
                  $flg = copy('stations.txt', $origin.'.txt');
                  if ($flg) {
                    echo "リセットしました";
                  } else {
                    echo "リセットに失敗しました";
                  }
                  echo "<br><br><hr>";
          
                  $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
                  $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                  $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                  $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                  $name = 'reset';
                  $comment = 'Time up!'; 
                  $date= strtotime("now");
                  $sql -> execute();

                  // ユーザが駅名を入力した場合
                } else if(!empty($_POST["deleteStation"])){
                  // ↓(ユーザ名1).txtが存在する場合
                  if(file_exists($origin.".txt")){
                      $fp_d=fopen($origin.".txt","w+");
                      foreach ($lines as $line){
                        $parts=explode("<>",$line);
                        // var_dump($parts);
                        // 特定の行に対して、その行に書かれた駅名が入力された文字列と異なる場合
                        if($_POST["deleteStation"]!=$parts[1]){
                          $i++;
                          // echo "<br>";
                          // echo $i.$parts[1]."駅を残しました<br>";
                          $record_delete=$i."<>".$parts[1]."<>" .PHP_EOL ;
                          fwrite($fp_d,$record_delete);
                        };
                      }
                
                      $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
                      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                      $name = 'you';
                      $comment = $_POST["deleteStation"];
                      $date = strtotime("now");
                      $sql -> execute();
                
                      // $iが0になる⇔$i++が働いていない⇔file内に入力された文字列と違う駅名がfile内にない⇔fileが空になった
                      if($i==0){
                        echo "コンプリート！おめでとう！<br>";
                        fclose($fp_d);
                        $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                        $name = 'reset';
                        $comment = 'Completed! Congratulations!'; 
                        $date = strtotime("now");
                        $sql -> execute();
                        $flg = copy('stations.txt', $origin.'.txt');
                        if ($flg) {
                          echo "リセットしました";
                        } else {
                          echo "リセットに失敗しました";
                        }
                        echo "<br><br><hr>";
                
                      // ファイルに残っている駅名を言えなかった場合
                      }else if($i==count($lines)){
                        echo "残念！<br>";
                        echo $_POST["deleteStation"]."は間違っています<br>";
                        fclose($fp_d);
                        $flg = copy('stations.txt', $origin.'.txt');
                        if ($flg) {
                          echo "リセットしました";
                        } else {
                          echo "リセットに失敗しました";
                        }
                        echo "<br><br><hr>";
                
                        $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                        $name = 'reset';
                        $comment = 'You lost'; 
                        $date = strtotime("now");
                        $sql -> execute();
                      // 正解の場合
                      }else{
                        echo "<br>";
                        fclose($fp_d);
                        
                        
                
                        $rand=rand(1,$i);
                        $lines2=file($origin.".txt",FILE_IGNORE_NEW_LINES);
                        $fp_dd=fopen($origin.".txt","w+");
                        foreach ($lines2 as $row){
                          $parts=explode("<>",$row);
                          // 特定の行（番号）に対して、その行（番号）がcomの指定した行（番号）と異なる場合
                          if($rand!=$parts[0]){
                              $j++;
                              // echo "<br>";
                              // echo $j.$parts[1]."駅を残しました<br>";
                              $record_delete=$j."<>".$parts[1]."<>" .PHP_EOL ;
                              fwrite($fp_dd,$record_delete);
                              
                          }
                          // 特定の行（番号）に対して、その行（番号）がcomの指定した行（番号）である場合
                          else{
                            $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
                            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                            $name = 'com';
                            $comment = $parts[1]; 
                            $date = strtotime("now");
                            $sql -> execute();
                
                            
                          }
                        }
                        fclose($fp_dd);
                
                        if($j==0){
                          echo "コンプリート！おめでとう！<br>";
                
                          $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
                          $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                          $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                          $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                          $name = 'reset';
                          $comment = 'Completed! Congratulations!'; 
                          $date = strtotime("now");
                          $sql -> execute();
                
                          $flg = copy('stations.txt', $origin.'.txt');
                          if ($flg) {
                            echo "リセットしました";
                          } else {
                            echo "リセットに失敗しました";
                          }
                        echo "<br><br><hr>";
                        }
                      }
                
                  }
                }
                
                
              } else if(empty($_POST["reset"])){
                $flg = copy('stations.txt', $origin.'.txt');
                if ($flg) {
                  echo "リセットしました。";
                } else {
                  echo "リセットに失敗しました。";
                }
                echo "<br><hr>";
    
                $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $name = 'reset';
                $comment = 'page refreshed'; 
                $date = strtotime("now");
                $sql -> execute();
            }
          }else{
            $flg = copy('stations.txt', $origin.'.txt');
                if ($flg) {
                  echo "リセットしました。";
                } else {
                  echo "リセットに失敗しました。";
                }
                echo "<br><hr>";
    
                $sql = $pdo -> prepare("INSERT INTO $origin (name, comment, date) VALUES (:name, :comment, :date)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $name = 'reset';
                $comment = 'user exited'; 
                $date = strtotime("now");
                $sql -> execute();
          }
    
          $sql = 'SELECT * FROM '. $origin;
          $stmt = $pdo->query($sql);
          $results = $stmt->fetchAll();
          foreach($results as $row){
            if($row['name']=='resetOld'){
              $resetId=$row['id'];
            }
            if($row['name']=='reset'){
              $id = $row['id']; //変更する投稿番号
              $name = "resetOld";
              // $comment = "（変更したいコメント）";
              // $sql = 'UPDATE $origin SET name=:name,comment=:comment WHERE id=:id';
              $sql = 'UPDATE '.$origin.' SET name=:name WHERE id=:id';
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(':name', $name, PDO::PARAM_STR);
              // $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
              $stmt->bindParam(':id', $id, PDO::PARAM_INT);
              $stmt->execute();
            } 
          }
          // echo $resetId.'←resetId<br>';
          foreach($results as $row){
            $lastname=$row['name'];
          }
          if ( $_SERVER["REQUEST_METHOD"] == "POST" ){
      
            if($lastname=='reset'){
              foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                if($row['id']>$resetId){
                  
                  echo $row['name'].',';
                  echo $row['comment'].'<br>';
                  echo $row['date'].'<br>';
                  echo "<hr>";
                }
              }
            }else{
              $from="";
              foreach($results as $row){
                if($row['id']>count($results)-2){
                  echo $row['name'].',';
                  echo $row['comment'].'<br>';
                  echo $row['date'].'<br>';
                  echo "<hr>";
                }if($row['id']==count($results)-2){
                  $from=$row['date'];
                }
              }

            }
          }
            
        }else{
          echo "Usernameか".$hitName.$hitPw."パスワードが間違っています。両方を入力しなおしてください。";
          $usernameOutput="";
          $passwordOutput="";
        }
        }
      }else if( isset( $_SESSION["key"], $_POST["key"] ) && $_SESSION["key"] == $_POST["key"] ){
        echo "Username";
        if(empty($_POST["enteredPw"]) && empty($_POST["hiddenPw"])){
          echo "とパスワード";
        }else{
          if(!empty($_POST["enteredPw"])){
            $passwordOutput=$_POST["enteredPw"];
          }else{
            $passwordOutput=$_POST["hiddenPw"];
          }
        }
        echo "が入力されていません";
        if(!empty($_POST["deleteStation"])){
          $saveStation=$_POST["deleteStation"];
        }
      }else{
        echo "Usernameとパスワードが記入されていることを確かめてから、最初の駅名を入力してスタートです。";
      }


      if ( $_SERVER["REQUEST_METHOD"] != "POST" ) {
        if(!empty($origin)){
          // echo $origin;
          $exited="impossible";
        }
        else{
          $exited="exited";
        }
      }

      if(isset($_POST["modeChange"])){
        header('Location: mode_select.php');
      }
      if(isset($_POST["logout"])){
        header('Location: login1.php');
      }

      $_SESSION["key"] = md5(uniqid().mt_rand());

    ?>
    <?php ob_start(); ?>
    <p>山手線ゲーム！(かんたん）</p>
    <p>コンピューターと交代で山手線の駅名を言い合います。間違えたり、同じ駅名を2回言ったら負けです。</p>
    <p>※正しく入力された場合、システムがユーザ名とパスワードを記憶します。ただし、パスワードは入力欄に表示しません。</p>
    <form action="" method="post">
        <input type="hidden" name="key" value="<?php echo htmlspecialchars( $_SESSION["key"], ENT_QUOTES );?>">
        <input type="text" name="deleteStation" value="<?php echo $saveStation; ?>">
        <input type="submit" name="deleteSubmit" id="deleteSubmit" onClick="phpShow()">
        <input type="text" name="enteredUn" value="<?php echo $usernameOutput; ?>">
        <input type="text" name="enteredPw">
        <input type="hidden" name="hiddenPw" value="<?php echo $passwordOutput; ?>">
        <input type="submit" name="reset" value="リセット">
        <input type="hidden" name="exitCheck" value="<?php echo $exited; ?>">
    </form>
    <hr>
    <form action="" method="POST">
      <input type="submit" name="modeChange" value="モード変更">
    </form>
    <form action="" method="POST">
      <input type="submit" name="logout" value="ログアウト">
    </form>
  </body>
</html>
