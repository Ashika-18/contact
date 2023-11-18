<?php
$to = 'ashibasama@gmail.com'; // 宛先
$subject = 'テストメール'; // 件名
$message = 'test=123456789-<>これはテストである'; // 本文
$headers = 'From: ashibasama@gmail.com' . "\r\n" .
           'Reply-To: ashibasama@gmail.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion(); // メールヘッダー

// メールを送信する
if (mb_send_mail($to, $subject, $message, $headers)) {
    echo 'メールが送信されました。かもしれない';
} else {
    echo 'メールの送信に失敗しました。';
}