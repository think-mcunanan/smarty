<?php

//-- パース設定 --//
define('FAIL_REDIRECT', 'http://www.bmy.jp/');
define('MAIN_PATH',     'http://wsb.sipss.jp/mobile_station_beauty/serverside/');

//-- メールサーバー --//
define('MAILSERVER_PORT', '25');
//define('MAILSERVER_ADDRESS', '192.168.88.105');     // orig settings
define('MAILSERVER_ADDRESS', 'mail.bmy.jp');       // test04.mobilestation.jp
//define('MAILSERVER_ADDRESS', '218.216.75.20');      // stmp only

define('MOBASUTE_PATH_LOCAL', '/var/www2/html/');
define('MOBASUTE_PATH', 'http://www.bmy.jp/');

define('EMAIL_DOMAIN', 'bmy.jp');

//-- かんざし設定 --//
define('KANZASHI_SIGNIN_URL', 'https://kanzashi.com/signin');
define('KANZASHI_PATH', 'https://kanzashiapi.sipss.jp');

//-- Webプロキシ --//
putenv("http_proxy=http://192.168.88.13:10080");
putenv("https_proxy=http://192.168.88.13:10080");
