<?php

//-- パース設定 --//
define('FAIL_REDIRECT', 'http://rc.bmy.jp/');
define('MAIN_PATH',     'http://rcwsb.sipss.jp/mobile_station_beauty/serverside/');

//facebook Credentials
define('FACEBOOK_OAUTH_CHANNEL_ID', '2587100941510795');
define('FACEBOOK_OAUTH_CHANNEL_SECRET', '1c621c926ceeab9dacbd9506e3a4fc86');
define('FACEBOOK_OAUTH_REDIRECT_URL', MAIN_PATH.'yk/facebook_oauth');

define('FACEBOOK_ACCESS_TOKEN_URL','https://graph.facebook.com/v4.0/oauth/access_token');
define('FACEBOOK_API_URL','https://graph.facebook.com/me?fields=id,name,email');

//-- メールサーバー --//
define('MAILSERVER_PORT', '25');
define('MAILSERVER_ADDRESS', 'rcmail.bmy.jp');

define('MOBASUTE_PATH_LOCAL', '/var/www2/htmlbeauty-rc/');
define('MOBASUTE_PATH', 'http://rc.bmy.jp/');

define('EMAIL_DOMAIN', 'rcmail.bmy.jp');

//-- かんざし設定 --//
define('KANZASHI_SIGNIN_URL', 'https://kanzashi-stg.pp-dev.org/signin');
define('KANZASHI_SIGNIN_HASH_KEY', '9rjaDn3TtIeuQxeWwOtlcIweoUrGGVuG0ZI8rjDsYdvs7lLFmBLKYeucEvEoU4BXibpxCLed7h9lKdu6yZM0HueiZz');
define('KANZASHI_SIGNIN_MEDIA', 'THK');
define('KANZASHI_SIGNIN_VERSION', 'v1');
define('KANZASHI_PATH', 'https://rckanzashiapi.sipss.jp');

//-- Webプロキシ --//
putenv("http_proxy=http://192.168.88.13:10080");
putenv("https_proxy=http://192.168.88.13:10080");

?>
