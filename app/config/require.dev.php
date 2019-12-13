<?php

//-- パース設定 --//
define('FAIL_REDIRECT', 'http://www.bmy.jp/');
define('MAIN_PATH',     'https://wsdev.bmy.jp/mobile_station_beauty/serverside/');

//Line Credentials
define('LINE_OAUTH_CHANNEL_ID', '1653327469');
define('LINE_OAUTH_CHANNEL_SECRET', '3df3842b333e4933dcec1d8ca6da0030');
define('LINE_OAUTH_REDIRECT_URL', MAIN_PATH.'yk/line_oauth');

define('LINE_ACCESS_TOKEN_URL','https://api.line.me/oauth2/v2.1/token');
define('LINE_API_URL','https://api.line.me/v2/profile');

//facebook Credentials
define('FACEBOOK_API_VERSION','v5.0');
define('FACEBOOK_OAUTH_CHANNEL_ID', '2587100941510795');
define('FACEBOOK_OAUTH_CHANNEL_SECRET', '1c621c926ceeab9dacbd9506e3a4fc86');
define('FACEBOOK_OAUTH_REDIRECT_URL', MAIN_PATH.'yk/facebook_oauth');

define('FACEBOOK_ACCESS_TOKEN_URL','https://graph.facebook.com/'.FACEBOOK_API_VERSION.'/oauth/access_token');
define('FACEBOOK_API_URL','https://graph.facebook.com/me?fields=id,name,email');

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
