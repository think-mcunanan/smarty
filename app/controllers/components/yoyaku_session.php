<?php
/*
 * ###    ###        ##     ## ##             ###### ##          ##   ##
 * ####  ####  ####  ##        ##  ####     ##       ##   ####   ##       ####  ## ##
 * ## #### ## ##  ## #####  ## ## ##  ##     ###    #####    ## ##### ## ##  ## ### ##
 * ##  ##  ## ##  ## ##  ## ## ## #####        ###   ##   #####  ##   ## ##  ## ##  ##
 * ##      ## ##  ## ##  ## ## ## ##              ## ##  ##  ##  ##   ## ##  ## ##  ##
 * ##      ##  ####  #####  ## ##  #####    ######    ### ### ##  ### ##  ####  ##  ##
 *
 * もばすて Copyright(c) 2009 株式会社シンク All Rights Reserved.
 * http://www.think-ahead.jp/
 * http://www.mobilestation.jp/
 *
 * サポート:  R.Eugenio [ross@think-ahead.jp]
 *          T.Springer [toddspringer@think-ahead.jp]
 *
 */

class YoyakuSessionComponent extends CakeObject
{

	/**
     * 新規セッションを作成する
     * Creates a new session
	 *
	 * @param controller &$controller
	 * @param int $companyid
	 * @param int $storecode
	 * @return string
	 */
	function Create(&$controller, $storecode, $companyid) {
		$length = SESSION_KEY_LENGTH;
	    $sessionid = "";
	    $possible_characters = "abcdefghijklmnopqrstuvwxyz".
                               "1234567890".
                               "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $computername  = $controller->_soap_server->methodparams['param']['computername'];

	    // ランダムセッションIDを発生する (Generate a Random Session Key)
	    while (strlen($sessionid)<$length) {
	        $sessionid .= substr($possible_characters, mt_rand()%strlen($possible_characters),1);
	    }

        //--------------------------------------------------------------------------------------    
        $session_no = 1;    
        //--------------------------------------------------------------------------------------    
        $Sql = "SHOW TABLE STATUS LIKE 'logsession_yoyaku'";
        //--------------------------------------------------------------------------------------
        $rs = $controller->LogSession->query($Sql);
        //--------------------------------------------------------------------------------------
        if (count($rs) > 0) {
            $session_no = $rs[0]['TABLES']['Auto_increment'];
        }//end if
        //--------------------------------------------------------------------------------------
        $sessionid .= $session_no;
        //--------------------------------------------------------------------------------------
        
	// セッション情報を書き込む(Write Session Entry)
        $controller->LogSession->set('datetime',     date('Y-m-d h:i:s'));
        $controller->LogSession->set('sessionid',   $sessionid);
        $controller->LogSession->set('login_logout', 1);
        $controller->LogSession->set('clientip',     $controller->RequestHandler->getClientIP());
        $controller->LogSession->set('storecode',    $storecode);
        $controller->LogSession->set('companyid',    $companyid);
        $controller->LogSession->set('computername', $computername);
        $controller->LogSession->save();

	    return $sessionid;
	}


    /**
     * セッションを確認する
     * Checks for the validity of session
     *
     * @param controller &$controller
     * @return $arrReturn
     */
    function Check(&$controller) {

        $v = $controller->LogSession->find('all', array(
                'conditions' => array('LogSession.sessionid' => $controller->_soap_server->methodparams['sessionid'],
                                      'LogSession.login_logout' => 1),
                'fields'     => array('Company.dbname',
                                      'LogSession.storecode',
                                      'LogSession.companyid',
                                      'LogSession.session_no')
                ));

        if (!empty($v)) {
        	$arrReturn = array_merge($v[0]['LogSession'], $v[0]['Company']);
            return $arrReturn;
        } else {
        	return false;
        }
    }


    /**
     * セッションを破壊します
     * Destroys session
     *
     * @param controller &$controller
     * @param int $session_no
     * @return boolean
     */
    function Destroy(&$controller, $session_no) {
        // セッション情報にlogin_logoutを設定する(Update Session Entry with login_logout flag)
        $controller->LogSession->set('session_no',   $session_no);
        $controller->LogSession->set('datetime',     date('Y-m-d h:i:s'));
        $controller->LogSession->set('login_logout', 0);
        $controller->LogSession->save();

        return true;
    }

}

?>