<?php

//-- パース設定 --//
define('FAIL_REDIRECT', 'http://www.bmy.jp/');
define('MAIN_PATH',     'http://wsb.sipss.jp/mobile_station_beauty/serverside/');

//-- メールサーバー --//
define('MAILSERVER_PORT', '25');
//define('MAILSERVER_ADDRESS', '192.168.88.105');     // orig settings
//define('MAILSERVER_ADDRESS', 'mail.bmy.jp');       // test04.mobilestation.jp
define('MAILSERVER_ADDRESS', '221.186.135.77');       // test04.mobilestation.jp
//define('MAILSERVER_ADDRESS', '218.216.75.20');      // stmp only

define('MOBASUTE_PATH_LOCAL', '/var/www/html/');
define('MOBASUTE_PATH', 'http://www.bmy.jp/');

define('EMAIL_DOMAIN', 'rctest.bmy.jp');

//-- かんざし設定 --//
define('KANZASHI_SIGNIN_URL', 'https://kanzashi-stg.pp-dev.org/signin');
define('KANZASHI_SIGNIN_HASH_KEY', '9rjaDn3TtIeuQxeWwOtlcIweoUrGGVuG0ZI8rjDsYdvs7lLFmBLKYeucEvEoU4BXibpxCLed7h9lKdu6yZM0HueiZz');
define('KANZASHI_SIGNIN_MEDIA', 'THK');
define('KANZASHI_SIGNIN_VERSION', 'v1');
define('KANZASHI_PATH', 'https://rckanzashiapi.sipss.jp');
