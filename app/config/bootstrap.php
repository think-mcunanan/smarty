<?php
/* SVN FILE: $Id$ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */


//-- 追加の定数 (Additional Constants) ------------------------------------------------
require_once('require.php');

//-- SOAPサーバー設定 --//
define('DEFAULT_LIMIT',     10);
define('DEFAULT_STARTPAGE', 1);
define('DEFAULT_ROWS',      2);     //予約　Column
define('DEFAULT_PHONEROWS', 1);     //来店　Column
define('MIN_SERVICE_TIME',  15);
define('OVER_MAXTIME',      '03:00');
define('MAXTIME',           '23:59');

//-- セッション設定  --//
define('SESSION_KEY_LENGTH',     25);
define('INVALID_SESSION',        'SessionID is invalid');
define('SESSION_EXPIRATION_MIN', 15);
define('SESSIONID_LENGTH',       15); // (Minimum 13)

/**
 * クッキーの生存期間(日)
 * 
 * @var int
 */
define('COOKIE_EXPIRATION_DAY', 14);

//-----------------------------------------------------------------------------------

//EOF
?>