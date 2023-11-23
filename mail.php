<?php
$to = ''; // 宛先
$subject = 'テストメール'; // 件名
$message = 'test=123456789-<>これはテストである'; // 本文
$headers = '' . "\r\n" .
           'Reply-To: ' . "\r\n" .
           'X-Mailer: PHP/' . phpversion(); // メールヘッダー

// メールを送信する
if (mb_send_mail($to, $subject, $message, $headers)) {
    echo 'メールが送信されました。かもしれない';
} else {
    echo 'メールの送信に失敗しました。';
}