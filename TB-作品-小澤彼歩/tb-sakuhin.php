<?php
  //直リンク禁止
  if (empty($_SERVER["HTTP_REFERER"])) {
    //リダイレクト
    header('Location: login1.php');
  }    
  // $deleteNumber=$_POST["deleteNumber"];
  $dsn = '（データベース名）';
  $user = '（ユーザ名）';
  $password = '（パスワード）';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  
  $sql = "CREATE TABLE IF NOT EXISTS stationdb1"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"
  . "name char(32),"
  . "comment TEXT"
  .");";
  $stmt = $pdo->query($sql);

  $filename="station_game.txt";
  $lines=file("station_game.txt",FILE_IGNORE_NEW_LINES);
  $i=0;
  $j=0;
  $date=date("Y/m/d H:i:s");
  if(isset($_POST["reset"])){
    $flg = copy('stations.txt', 'station_game.txt');
    if ($flg) {
      echo "コピー成功です";
    } else {
      echo "コピー失敗です";
    }
  }

  if(!empty($_POST["deleteStation"])){
    if(file_exists("station_game.txt")){
        // $fp_d=fopen($filename,"w");
        // fwrite($fp_d,"");
                $fp_d=fopen("station_game.txt","w");
        foreach ($lines as $line){
            $parts=explode("<>",$line);
            if($_POST["deleteStation"]!=$parts[1]){
                $i++;
                // echo "<br>";
                // echo $i.$parts[1]."駅を残しました<br>";
                // $record_delete=$i."<>".$parts[1]."<>".$parts[2]."<>".$parts[3]."<>" .PHP_EOL ;
                $record_delete=$i."<>".$parts[1]."<>" .PHP_EOL ;
                // $fp_a=fopen($filename,"a");
                fwrite($fp_d,$record_delete);
                
                // fwrite($fp_a,$record_delete);
                // fclose($fp_a);
            };
        }
        // echo $i;
        // echo count($lines);

        if($i==0){
          echo "おめでとう！";
          fclose($fp_d);
          $flg = copy('stations.txt', 'station_game.txt');
          if ($flg) {
            echo "リセットしました";
          } else {
            echo "リセットに失敗しました";
          }
        }
        else if($i==count($lines)){
          echo "残念！";
          fclose($fp_d);
          $flg = copy('stations.txt', 'station_game.txt');
          if ($flg) {
            echo "リセットしました";
          } else {
            echo "リセットに失敗しました";
          }

          $sql = $pdo -> prepare("INSERT INTO stationdb1 (name, comment) VALUES (:name, :comment)");
          $sql -> bindParam(':name', $name, PDO::PARAM_STR);
          $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
          $name = 'reset';
          $comment = 'You lost'; //好きな名前、好きな言葉は自分で決めること
          $sql -> execute();
        }
        else{
          echo "<br>";
          fclose($fp_d);
          
          $sql = $pdo -> prepare("INSERT INTO stationdb1 (name, comment) VALUES (:name, :comment)");
          $sql -> bindParam(':name', $name, PDO::PARAM_STR);
          $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
          $name = 'you';
          $comment = $_POST["deleteStation"]; //好きな名前、好きな言葉は自分で決めること
          $sql -> execute();

        //   echo $i;
          $rand=rand(1,$i);
        //   echo $rand."らんど<br>";
          $lines2=file("station_game.txt",FILE_IGNORE_NEW_LINES);
          $fp_dd=fopen("station_game.txt","w");
          foreach ($lines2 as $row){
            // echo $parts[1]."とは";
            $parts=explode("<>",$row);
            if($rand!=$parts[0]){
                $j++;
                // echo "<br>";
                // echo $j.$parts[1]."駅を残しました<br>";
                // $record_delete=$i."<>".$parts[1]."<>".$parts[2]."<>".$parts[3]."<>" .PHP_EOL ;
                $record_delete=$j."<>".$parts[1]."<>" .PHP_EOL ;
                // $fp_a=fopen($filename,"a");
                fwrite($fp_dd,$record_delete);
                
                // fwrite($fp_a,$record_delete);
                // fclose($fp_a);
            }
            else{
              $sql = $pdo -> prepare("INSERT INTO stationdb1 (name, comment) VALUES (:name, :comment)");
          $sql -> bindParam(':name', $name, PDO::PARAM_STR);
          $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
          $name = 'com';
          $comment = $parts[1]; //好きな名前、好きな言葉は自分で決めること
          $sql -> execute();

          $sql = 'SELECT * FROM stationdb1';
          $stmt = $pdo->query($sql);
          $results = $stmt->fetchAll();
          foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].'<br>';
            echo "<hr>";
          }
            }
          }
        //   echo $j;
        //   echo count($lines2);
          fclose($fp_dd);

          if($j==0){
            echo "おめでとう！";

            $sql = $pdo -> prepare("INSERT INTO stationdb1 (name, comment) VALUES (:name, :comment)");
          $sql -> bindParam(':name', $name, PDO::PARAM_STR);
          $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
          $name = 'reset';
          $comment = 'Completed! Congratulations'; //好きな名前、好きな言葉は自分で決めること
          $sql -> execute();
            $flg = copy('stations.txt', 'station_game.txt');
            if ($flg) {
              echo "リセットしました";
            } else {
              echo "リセットに失敗しました";
            }
          }
        }
        
    }
  }
  
  
  ?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>課題</title>
</head>
<body>
<form action="" method="post">
      <p>山手線ゲーム！</p>
      <p>コンピューターと交代で山手線の駅名を言い合います。間違えたり、同じ駅名を2回言ったら負けです。</p>
      <input type="text" name="deleteStation">
      <input type="submit" name="deleteSubmit">
</form>
<hr>
<form action="" method="POST">
  <p>リセットボタン</p>
  <input type="submit" name="reset">
</form>

</body>
</html>