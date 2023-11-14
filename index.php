<?php
  // セッションを開始
session_start();

  // セッションが正常に開始されたかどうかを確認
  if (session_status() == PHP_SESSION_ACTIVE) {
      echo "セッションが正常に開始されました";
  } else {
      echo "セッションの開始に問題がありました";
  }

  function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
  }
  
  $mode = "input";
  $errmessage = [];

  if( isset($_POST['back']) && $_POST['back']) {
    // 何もしない
  } else if( isset($_POST['confirm']) && $_POST['confirm']) {

    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['message'] = $_POST['message'];

    $mode = "confirm";
  } else if ( isset($_POST['send']) && $_POST['send']) {
    $message = "お問い合わせを受け付けました。\r\n"
            . "名前" . $_SESSION["name"] . "\r\n"
            . "email" . $_SESSION["email"] . "\r\n"
            . "お問い合わせ内容:\r\n"
            . preg_replace("/\r\n|\r|\n/", "\r\n", $_SESSION['message'] );
    mail($_SESSION['email'], "お問い合わせありがとうございます。", $message);
    mail("ashibasama@yahoo.co.jp", "お問い合わせありがとうございます。", $message);
    $_SESSION = [];
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
    <p><?php echo "-今のmodeは($mode)やでぇ-"; ?></p>
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
    <p><?php echo "-今のmodeは($mode)やでぇ-"; ?></p>
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
      <input class="btn btn-primary" type="submit" name="send" value="送信">
      <input class="btn btn-danger" type="submit" name="back"  value="戻る">
    </form>
  <?php } else { ?>
    <p><?php echo "-今のmodeは($mode)やでぇ-"; ?></p>
    <!-- 完了画面 -->
    <h1 style="color: red">送信完了しました</h1>
    <button class="btn-primary" type="button" onclick="location.href='index.php'">HOMEへ</button>
  <?php } ?>
</body>
</html>
