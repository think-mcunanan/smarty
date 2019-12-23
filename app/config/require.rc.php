<?php

//-- パース設定 --//
define('FAIL_REDIRECT', 'http://rc.bmy.jp/');
define('MAIN_PATH',     'https://rcwsb2.sipss.jp/mobile_station_beauty/serverside/');

//Line Credentials
define('LINE_OAUTH_CHANNEL_ID', '1653327469');
define('LINE_OAUTH_CHANNEL_SECRET', '3df3842b333e4933dcec1d8ca6da0030');
define('LINE_OAUTH_REDIRECT_URL', MAIN_PATH.'yk/line_oauth');

define('LINE_ACCESS_TOKEN_URL','https://api.line.me/oauth2/v2.1/token');
define('LINE_API_URL','https://api.line.me/v2/profile');

//facebook Credentials
define('FACEBOOK_API_VERSION','v5.0');
define('FACEBOOK_OAUTH_CHANNEL_ID', '704063493412130');
define('FACEBOOK_OAUTH_CHANNEL_SECRET', 'abf70ef3b284ae42628947e68b2d1f02');
define('FACEBOOK_OAUTH_REDIRECT_URL', MAIN_PATH.'yk/facebook_oauth');

define('FACEBOOK_ACCESS_TOKEN_URL','https://graph.facebook.com/'.FACEBOOK_API_VERSION.'/oauth/access_token');
define('FACEBOOK_API_URL','https://graph.facebook.com/me?fields=id,name,email');

//Google Credentials
define('GOOGLE_OAUTH_CHANNEL_ID', '907669261098-1kb82tpv1dfsnrjhkdjo85iioiud8p46.apps.googleusercontent.com');
define('GOOGLE_OAUTH_CHANNEL_SECRET', 'C_zTnCyrzPf6wB4aTwKtFoZF');
define('GOOGLE_OAUTH_REDIRECT_URL', MAIN_PATH.'yk/google_oauth');

define('GOOGLE_ACCESS_TOKEN_URL','https://oauth2.googleapis.com/token');
define('GOOGLE_API_URL','https://www.googleapis.com/oauth2/v3/userinfo');

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
