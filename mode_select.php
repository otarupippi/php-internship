<?php
  if (empty($_SERVER["HTTP_REFERER"])) {
    //リダイレクト
    header('Location: login1.php');
  } 

  if(isset($_POST["easy"])){
    header( "Location: ./easy_page.php" ) ;
  }
  if(isset($_POST["normal"])){
    header( "Location: ./normal_page.php" ) ;
  }
  if(isset($_POST["hard"])){
    header( "Location: ./hard_page.php" ) ;
  }
  if(isset($_POST["logout"])){
    header('Location: login1.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mode-Select</title>
</head>
<body>
  <p>モードを選んでください</p>
  <form action="" method="POST">
    <input type="submit" value="かんたん" name="easy">
    <input type="submit" value="ふつう" name="normal">
    <input type="submit" value="むずかしい" name="hard">
  </form>
  <form action="" method="POST">
    <input type="submit" name="logout" value="ログアウト">
  </form>
</body>
</html>