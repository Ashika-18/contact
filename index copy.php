<?php

  // セッションを開始
session_start();

  function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
  }
  
  $mode = "input";
  $errmessage = [];

  if( isset($_POST['back']) && $_POST['back']) {
  //何もしない
  } else if( isset($_POST['confirm']) && $_POST['confirm']) {
    
    if ( !$_POST['name']) {
      $errmessage[] = "名前を入力して下さい";
    } else if (mb_strlen($_POST['name']) > 40) {
      $errmessage[] = "名前は40文字以内にして下さい";
    }
    $_SESSION['name'] = h($_POST['name']);

    if (!$_POST['email']) {
      $errmessage[] = "メールアドレスを入力して下さい";
    } else if (mb_strlen($_POST['email']) > 30) {
      $errmessage[] = "メールアドレスは30文字以内にして下さい";
    } else if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $errmessage[] = "メールアドレスが不正です。";
    }
    $_SESSION['email'] = h($_POST['email']);

    if (!$_POST['message']) {
      $errmessage[] = "お問い合わせ内容を入力して下さい。";
    } else if (mb_strlen($_POST['message']) > 200) {
      $errmessage[] = "お問い合わせ内容は200文字以内で入力して下さい。";
    }
    $_SESSION['message'] = h($_POST['message']);

    if( $errmessage ){	
      $mode = 'input';	
    } else {	
      //トークン生成 
    $token = bin2hex(random_bytes(32));

    $_SESSION["token"] = $token;
    $mode = 'confirm';	
    }
  } else if ( isset($_POST['send']) && $_POST['send']) {
      if ( !$_POST['token'] || !$_SESSION['token'] || !$_SESSION['email']) {
        $errmessage[] = "不正な処理が行われました";
        $_SESSION = [];
      
    } else if ( $_POST['token'] != $_SESSION['token']) {
      $errmessage[] = "不正な処理が行われました";
      $_SESSION = [];
      $mode = "input";

    } else  {
    $message = "お問い合わせを受け付けました。\r\n"
            . "名前" . $_SESSION["name"] . "\r\n"
            . "email" . $_SESSION["email"] . "\r\n"
            . "お問い合わせ内容:\r\n"
            . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message'] );
    mail($_SESSION['email'], 'お問い合わせありがとうございます。', $message);
    mail('ashibasama@yahoo.co.jp', 'お問い合わせがありました。', $message);
    $_SESSION = [];
    $mode = "send";
      }
    } else {
    $_SESSION = [];
  }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/earlyaccess/kokoro.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.css" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="./css/reset.css"> -->
    <link rel="stylesheet" href="./stylesheets/style.css">
    <title>問い合わせフォーム</title>
</head>
<body>
  <?php if( $mode == "input") { ?>
    <h1 style="color: blue">入力画面</h1>
    <!-- 入力画面 -->
    <?php
      if($errmessage) {
        echo '<div style="color: red;">';
        echo implode('<br>', $errmessage);
        echo '</div>';
      }
    ?>
    <form action="index.php" method="POST">
      <input type="hidden" name="token" value="<?php echo $token; ?>" />
      <div class="mb-3">
        <label for="name" class="form-label">名前</label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : '' ?>">
      </div>
      <div class="mb-3">
        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : '' ?>">
      </div>
      <div class="mb-3">
        <label for="message">本文</label>
        <textarea name="message" id="message" cols="30" rows="10" class="form-control"><?php echo isset($_SESSION['message']) ? $_SESSION['message'] : '' ?></textarea>
      </div>
      <input class="btn btn-primary" type="submit" name="confirm" value="確認">
    </form>  
  <?php } else if( $mode == "confirm") { ?>
    <h1 style="color: green">確認画面</h1>
    <!-- 確認画面 -->
   
   <form action="index.php" method="POST">
   <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
      <div class="mb-3">
        <div class="form-control">
          <?php echo $_SESSION['name']; ?>
        </div>
      </div>
      <div class="mb-3">
        <div class="form-control">
          <?php echo $_SESSION['email']; ?>
        </div>
      </div>
      <div class="mb-3">
        <div class="form-control">
          <?php echo $_SESSION['message']; ?>
        </div>
      </div>
      <input class="btn btn-primary" type="submit" name="send" value="送信">
      <input class="btn btn-danger" type="submit" name="back"  value="戻る">
    </form>
  <?php } else { ?>
    <!-- 完了画面 -->
    <h1 style="color: red">送信完了しました</h1>
    <button class="btn-primary" type="button" onclick="location.href='index.php'">HOMEへ</button>
  <?php } ?>
</body>
</html>
