<?php
  // セッションを開始
session_start();

  // セッション変数がセットされていない場合にデフォルトの値を設定
  if (!isset($_SESSION['name'])) {
    $_SESSION['name'] = "";
  }
  if (!isset($_SESSION['email'])) {
    $_SESSION['email'] = "";
  }
  if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = "";
  }

// セッションが正常に開始されたかどうかを確認
if (session_status() == PHP_SESSION_ACTIVE) {
    echo "セッションが正常に開始されました";
} else {
    echo "セッションの開始に問題がありました";
}

  $mode = "input";

  if( isset($_POST['back']) && $_POST['back']) {
    // 何もしない
  } else if( isset($_POST['confirm']) && $_POST['confirm']) {

    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['message'] = $_POST['message'];

    $mode = "confirm";
  } else if ( isset($_POST['send']) && $_POST['send']) {
    $mode = "send";
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
    <form action="index.php" method="POST">
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
      <div class="mb-3">
        <label for="name" class="form-label">名前</label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo $_SESSION['name'] ?>">
      </div>
      <div class="mb-3">
        <label for="email">メールアドレス</label>
        <input type="email" id="email" name="email" class="form-control" value="<?php echo $_SESSION['email'] ?>">
      </div>
      <div class="mb-3">
        <label for="message">本文</label>
        <textarea name="message" id="message" cols="30" rows="10" class="form-control"><?php echo $_SESSION['message']?></textarea>
      </div>
      <button type="submit" class="btn btn-primary" name="send">送信</button>
      <button type="submit" class="btn btn-danger" name="back">戻る</button>
    </form>
  <?php } else { ?>
    <!-- 完了画面 -->
  <?php } ?>
</body>
</html>
