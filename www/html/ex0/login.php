<?php

require_once('config.php');

session_start();
//メールアドレスのバリデーション
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  echo '入力された値が不正です。';
  return false;
}
//DB内でPOSTされたメールアドレスを検索
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $stmt = $pdo->prepare('select * from userDeta where email = ?');
  $stmt->execute([$_POST['email']]);
  $all = $pdo->prepare('select * from userDeta order by id desc');
  $all->execute();
  $loopcount = $all->rowCount();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;
}
//emailがDB内に存在しているか確認
if (!isset($row['email'])) {
  echo 'メールアドレス又はパスワードが間違っています。';
  return false;
}
//パスワード確認後sessionにメールアドレスを渡す
if (password_verify($_POST['password'], $row['password'])) {
  session_regenerate_id(true); //session_idを新しく生成し、置き換える
  $_SESSION['EMAIL'] = $row['email'];

    // WELCOMEページを作る
    echo $_SESSION['EMAIL'];
    ?>

    <!DOCTYPE html>
    <html lang="ja">
    <head>
    <meta charset="UTF-8">
    <title>php</title>
    </head>
    <body>
    <a href="logout.php">logout</a>
    
  
    <h2>Users(<?php echo $loopcount;?>)</h2>
    <ui>
    <?php foreach($all as $loop) {?>
        <li>
          <?php echo "${loop['email']}"?>
        </li>
      <?php } ?>
    </body>
    </html>
    

<?php
} else {
  echo 'メールアドレス又はパスワードが間違っています。';
  return false;
}
