<?php

// メール情報
// メールホスト名・gmailでは smtp.gmail.com
define('MAIL_HOST','smtp.gmail.com');

// メールユーザー名・アカウント名・メールアドレスを@込でフル記述
define('MAIL_USERNAME','×××@×××（非掲載）');

// メールパスワード・上で記述したメールアドレスに即したパスワード
define('MAIL_PASSWORD','非掲載');

// SMTPプロトコル(sslまたはtls)
define('MAIL_ENCRPT','ssl');

// 送信ポート(ssl:465, tls:587)
define('SMTP_PORT', 465);

// メールアドレス・ここではメールユーザー名と同じでOK
define('MAIL_FROM','非掲載');

// 表示名
define('MAIL_FROM_NAME','XiaozeBibu');

// メールタイトル
define('MAIL_SUBJECT','登録のご案内');