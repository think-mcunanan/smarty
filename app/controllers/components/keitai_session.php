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


class KeitaiSessionComponent extends CakeObject
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
    function Create(&$controller, $username, $password, $companyid, $storecode = 0,$logintype = 0) {

        if(intval($companyid) == 0 || strlen($username) == 0 || strlen($password) == 0) {
            return false;
        }

        $comp_rec = $controller->Company->find('all', array(
                            'conditions' => array('Company.companyid' => intval($companyid))));

        if (!empty($comp_rec)) {
            $controller->Customer->set_company_database($comp_rec[0]['Company']['dbname'], $controller->Customer);
            $condition=NULL;

            //顧客番号でログイン
            if ($logintype==1){
                $condition =array('Customer.CNUMBER'      => $username);
                $rec = $controller->Customer->find(
                    'all',
                    array(
                        'conditions' => array(
                            'or' => $condition,
                            'or' => array("date_format(Customer.BIRTHDATE,'%Y%m%d')" => $password),
                            'Customer.DELFLG' => null
                        )
                    )
                );

            }else{
                //e-mailでログイン
                $condition =array('Customer.MAILADDRESS1' => $username,
                                  'Customer.MAILADDRESS2' => $username,
                                  'Customer.TEL1'         => $username,
                                  'Customer.TEL2'         => $username);
                $rec = $controller->Customer->find(
                    'all',
                    array(
                        'conditions' => array(
                            'or' => $condition,
                            'Customer.password' => $password,
                            'Customer.DELFLG' => null
                        )
                    )
                );
            }


            if (!empty($rec)) {
                $ccode = $rec[0]['Customer']['CCODE'];

                if($storecode == 0) {
                    $storecode= $rec[0]['Customer']['CSTORECODE'];
                }

                //if ok write a session
                $sessionid = $this->GenerateSessionId();

                $controller->LogSessionKeitai->set('sessionid',       $sessionid);
                $controller->LogSessionKeitai->set('companyid',       $companyid);
                $controller->LogSessionKeitai->set('storecode',       $storecode);
                $controller->LogSessionKeitai->set('ccode',           $ccode);
                $controller->LogSessionKeitai->set('client_ip',       $controller->RequestHandler->getClientIP());
                $controller->LogSessionKeitai->set('ykstatus',        "5");
                $controller->LogSessionKeitai->set('createdatetime',  date("Y-m-d H:i:s"));
                $controller->LogSessionKeitai->set('last_activity',   date("Y-m-d H:i:s"));
                $controller->LogSessionKeitai->save();
            }
            else {
                $controller->set('top_message', "ユーザーIDまたはパスワードが正しくありません");
            }
        }

        return $sessionid;
    }

    /**
     * 新規メールセッションを作成する
     * Creates a new mail session
     *
     * @param controller &$controller
     * @param string $emailaddress
     * @param int $storeid
     * @return string
     */
    function CreateMailSession(&$controller, $emailaddress, $storeid) {

        if(strlen($emailaddress) == 0 || strlen($storeid) == 0) {
            return false;
        }

        $store_info = $controller->StoreAccount->find('all', array(
                            'conditions' => array('WebyanAccount.storeid' => $storeid)));

        if (!empty($store_info)) {
            $controller->Customer->set_company_database($store_info[0]['Company']['dbname'], $controller->Customer);

            $rec = $controller->Customer->find('all', array(
                            'conditions' => array('or' => array('Customer.MAILADDRESS1' => $emailaddress,
                                                                'Customer.MAILADDRESS2' => $emailaddress) ),
                            'fields' => array('CCODE')
            ));

            if (!empty($rec)) {
                // Existing User
                $ccode     = $rec[0]['Customer']['CCODE'];
                $ykstatus  = "5";
            }
            else {
                // New Customer
                $ccode     = 0;
                $ykstatus  = "8|0|".$emailaddress;
            }

            $storecode = $store_info[0]['StoreAccount']['storecode'];
            $companyid = $store_info[0]['Company']['companyid'];

            $sessionid = $this->GenerateSessionId();

            $controller->LogSessionKeitai->set('sessionid',       $sessionid);
            $controller->LogSessionKeitai->set('companyid',       $companyid);
            $controller->LogSessionKeitai->set('storecode',       $storecode);
            $controller->LogSessionKeitai->set('ccode',           $ccode);
            $controller->LogSessionKeitai->set('ykstatus',        $ykstatus);
            $controller->LogSessionKeitai->set('createdatetime',  date("Y-m-d H:i:s"));
            $controller->LogSessionKeitai->set('last_activity',   date("Y-m-d H:i:s"));
            $controller->LogSessionKeitai->save();

            return $sessionid;
        }
        else {
            return false;
        }
    }

    function CreateSnsSession(&$controller, $snsid, $provider, $companyid, $storecode){
        if(strlen($snsid) == 0 || intval($companyid) == 0) {
            return false;
        }

        $company_record = $controller->Company->find('all', array(
            'conditions' => array('Company.companyid' => intval($companyid))));

        if (empty($company_record)){
            return false;
        }
        $controller->Customer->set_company_database($company_record[0]['Company']['dbname'], $controller->Customer);

        $sql = "SELECT ccode
                FROM customer_sns
                WHERE oauth_uid = :oauth_uid
                AND storecode = :storecode
                AND oauth_provider = :oauth_provider";
        $params = array(
                        "oauth_uid"      => $snsid,
                        "storecode"      => $storecode,
                        "oauth_provider" => $provider
                        );
        $result = $controller->Customer->query($sql, $params, false);

        if (!empty($result)) {
            // Existing sns User
            $ccode     = $result[0]['customer_sns']['ccode'];
            $ykstatus  = "5";
        }
        else {
            // New sns Customer
            $ccode     = 0;
            $ykstatus  = "8|0|";
        }
        $sessionid = $this->GenerateSessionId();

        $controller->LogSessionKeitai->set('sessionid',       $sessionid);
        $controller->LogSessionKeitai->set('companyid',       $companyid);
        $controller->LogSessionKeitai->set('storecode',       $storecode);
        $controller->LogSessionKeitai->set('ccode',           $ccode);
        $controller->LogSessionKeitai->set('ykstatus',        $ykstatus);
        $controller->LogSessionKeitai->set('createdatetime',  date("Y-m-d H:i:s"));
        $controller->LogSessionKeitai->set('last_activity',   date("Y-m-d H:i:s"));
        $controller->LogSessionKeitai->save();

        return $sessionid;
    }

    function GenerateSessionId(){
        $sessionid = uniqid();
        $possible_characters =  "abcdefghijklmnopqrstuvwxyz".
                                 "1234567890".
                                 "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        while (strlen($sessionid) < SESSIONID_LENGTH) {
            $sessionid .= substr($possible_characters, mt_rand()%strlen($possible_characters),1);
        }
        return $sessionid;
    }

        /**
     * セッションを確認する
     * Checks for the validity of session
     *
     * @param controller &$controller
     * @return string
     */
    function Check(&$controller, $sessionid) {

        $v = $controller->LogSessionKeitai->find('all', array(
                 'conditions' => array('LogSessionKeitai.sessionid' => $sessionid),
                 'fields'     => array('Company.dbname',
                                       'LogSessionKeitai.storecode',
                                       'LogSessionKeitai.companyid',
                                       'LogSessionKeitai.session_no',
                                       'LogSessionKeitai.ccode',
                                       'LogSessionKeitai.ykstatus',
                                       'LogSessionKeitai.last_activity',
                                       'LogSessionKeitai.data',
                     )
        ));
        if (!empty($v)) {
            $arrReturn = array_merge($v[0]['LogSessionKeitai'], $v[0]['Company']);

            $tmparr = explode("|",$arrReturn['ykstatus']);

            $arrReturn['y_status']   = intval($tmparr[0]);
            $arrReturn['syscode']    = intval($tmparr[1]);  //sipss_beauty add;
            $arrReturn['y_staff']    = strpos($tmparr[2],"@") === false ? intval($tmparr[2]) : $tmparr[2];
            $arrReturn['y_services'] = $tmparr[3];
            $arrReturn['y_date']     = $tmparr[4];
            $arrReturn['y_time']     = $tmparr[5];
            $arrReturn['carrier']    = $this->getMobileCarrier();
            //その他データのフィールド
            $arrReturn['data']       = unserialize($v[0]['LogSessionKeitai']['data']);

            return $arrReturn;
        } else {
            return false;
        }
    }


    /**
     * セッションを確認する
     * Checks for the validity of session
     *
     * @param controller &$controller
     * @return string
     */
    function Check2(&$controller, $sessionid) {

        $v = $controller->LogSessionKeitai->find('all', array(
                 'conditions' => array('LogSessionKeitai.sessionid' => $sessionid),
                 'fields'     => array('Company.dbname',
                                       'LogSessionKeitai.storecode',
                                       'LogSessionKeitai.companyid',
                                       'LogSessionKeitai.session_no',
                                       'LogSessionKeitai.ccode',
                                       'LogSessionKeitai.ykstatus',
                                       'LogSessionKeitai.last_activity')
        ));

        if (!empty($v)) {
            $arrReturn = array_merge($v[0]['LogSessionKeitai'], $v[0]['Company']);

            //unserialize
            $ret = unserialize($arrReturn['ykstatus']);

            $arrReturn['y_status']   = $ret['y_status'];
            $arrReturn['syscode']    = $ret['syscode'];
            $arrReturn['y_staff']    = $ret['y_staff'];
            $arrReturn['y_services'] = $ret['y_services'];
            $arrReturn['y_date']     = $ret['y_date'];
            $arrReturn['y_time']     = $ret['y_time'];
            $arrReturn['carrier']    = $this->getMobileCarrier();

            return $arrReturn;
        } else {
            return false;
        }
    }

    /**
     * 携帯キャリアを読み込む
     * Reads the Carrier Type
     *
     * @return string
     */
    function getMobileCarrier () {

        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        // Added by jonathanparel, 20160914; RM#1744;
        $smartphones = '/(Motorola|DROIDX|DROID BIONIC|HTC|HTC.*(Sensation|Evo|Vision|Explorer|6800|8100|8900|A7272|S510e|C110e
                                  |Legend|Desire|T8282)|APX515CKT|Qtek9090|APA9292KT|HD_mini|Sensation.*Z710e|PG86100|Z715e
                                  |Desire.*(A8181|HD)|ADR6200|ADR6400L|ADR6425|001HT|Inspire 4G|Android.*\bEVO\b|T-Mobile G1
                                  |Z520m|Nexus One|Nexus S|Galaxy.*Nexus|Android.*Nexus.*Mobile|Nexus 4|Nexus 5|Nexus 6|mini 9.5
                                  |vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47
                                  |lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4
                                  |vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b
                                  |mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810
                                  |m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc
                                  |8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca
                                  |s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701
                                  |me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736
                                  |p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320
                                  |vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone
                                  |o2|pocket|kindle|mobile|psp|treo)/i';

        if (preg_match('/^DoCoMo/', $userAgent)) {
            if(intval(substr($userAgent, 7, 1)) < 2) {
                $carrier = 'docomo_old'; // No table support
            }
            else {
                $carrier = 'docomo_new';
            }
        } elseif (preg_match('/^(J\-PHONE|Vodafone|SoftBank|MOT\-)/', $userAgent)) {
            $carrier = 'softbank';
        } elseif (preg_match('/^(KDDI\-|UP\.Browser)/', $userAgent)) {
            $carrier = 'au';
        } elseif (preg_match('/^Mozilla.+(DDIPOCKET|WILLCOM)/', $userAgent)) {
            $carrier = 'willcom';
        } elseif (preg_match('/^emobile/', $userAgent)) {
            $carrier = 'emobile';
        } elseif (preg_match('/iPhone/', $userAgent)) {
            $carrier = 'iphone';
        } elseif (preg_match($smartphones, $userAgent)) { // Added by jonathanparel, 20160914; RM#1744;
            $carrier = 'smartphone';
        } else {
            $carrier = 'pc';
        }
        return $carrier;
    }

    /**
     * セッションステータスをアップデートする
     * Updates the sessions status
     * @param controller &$controller
     * @param int $session_no
     * @param string $status
     * @param int $ccode
     * @param session $session
     */
    function UpdateStatus(&$controller, $session_no, $status = "", $ccode = "",$session = "") {
        $controller->LogSessionKeitai->set('session_no', $session_no);
        if($status != "") {
            $controller->LogSessionKeitai->set('ykstatus', $status);

            if($ccode != "") {
                $controller->LogSessionKeitai->set('ccode', $ccode);
            }
        }
        //シリアリアイズ化したセッションを利用。
        if(isset($session)){
        //ykstatus=>dataのシリアライズ化
               $controller->LogSessionKeitai->set('data', serialize($session['data']));
        }
        $controller->LogSessionKeitai->set('last_activity', date("Y-m-d H:i:s"));
        return $controller->LogSessionKeitai->save();
    }

     /**
     * セッションステータスをアップデートする serialize対応版
     * Updates the sessions status
     * @param controller &$controller
     * @param int $session_no
     * @param string $status
     * @param int $ccode
     */
    function UpdateStatus2(&$controller, $session_no, $session = "", $ccode = "") {
        $controller->LogSessionKeitai->set('session_no', $session_no);
        if($session != "") {
            $controller->LogSessionKeitai->set('ykstatus', serialize($session));
            if($ccode != "") {
                $controller->LogSessionKeitai->set('ccode', $ccode);
            }
        }
        $controller->LogSessionKeitai->set('last_activity', date("Y-m-d H:i:s"));
        $controller->LogSessionKeitai->save();
    }



    /**
     * 顧客情報をアップデート
     * Updates Customer Profiles
     *
     * @param controller &$controller
     * @param string $name
     * @param string $phone
     * @param string $email
     * @param int $sex
     * @param string $bday
     * @param string $password
     * @return int
     */
    function UpdateCProfile(&$controller, $session_info, $name, $phone, $email, $sex, $bday, $password, $mailkubun=0) {
        $ccode = $session_info["ccode"];
        $storecode = $session_info["storecode"];
        $dbname = $session_info["dbname"];
        $carrier = $session_info["carrier"];

        $controller->Customer->set_company_database($dbname, $controller->Customer);

        if(strlen($ccode) < 3) {
            // New Customer
            //$sql_cid = "select ".
            //           "f_get_sequence_key('cid', ".$storecode.", '') as cid, ".
            //           "f_get_sequence_key('cnumber', ".$storecode.", '') as cnumber";
            $sql_cid = "select ".
                       "f_get_sequence_key('cid', ".$storecode.", '') as cid";
            $tmp_data = $controller->Customer->query($sql_cid);
            $cid = $tmp_data[0][0]['cid'];
            $ccode = sprintf("%03d%07d", $storecode,   $cid);
            //$cnumber = sprintf("%03d%07d", $storecode, $tmp_data[0][0]['cnumber']);
            $controller->Customer->set('CID',          $cid);
            //$controller->Customer->set('CNUMBER',      $cnumber);
            $controller->Customer->set('CSTORECODE',   $storecode);
            $controller->Customer->set("CREATEDATE", date("Y-m-d H:i:s"));
        }

        if (strlen($password) > 0) { $controller->Customer->set("PASSWORD", $password); }
        if (isset($ccode)) { $controller->Customer->set("CCODE", $ccode); }
        if (isset($name)) { $controller->Customer->set("CNAME", $name); }
        if (isset($phone)) { $controller->Customer->set("TEL2", $phone); }
        if (isset($sex)) { $controller->Customer->set("SEX", $sex); }
        if (isset($bday)) { $controller->Customer->set("BIRTHDATE", $bday); }
        if (isset($mailkubun)) { $controller->Customer->set("MAILKUBUN", $mailkubun); }
        //echo "<pre style=\"border-style: dashed;\">";var_dump(strpos($email,"@") ,strpos($email,"@") > 0,$carrier == "pc" ? "MAILADDRESS1" : "MAILADDRESS2", $email);echo "</pre>"; exit;
        if (isset($email) && $email !== 0 && strpos($email,"@") > 0 ) { $controller->Customer->set($carrier == "pc" ? "MAILADDRESS1" : "MAILADDRESS2", $email); }

        //====================================================================================
        // Created By: Homer Pasamba <homer.pasamba@think-ahead.jp>
        // Created Date: 2013/03/08
        // Comment: Check If New Customer
        //------------------------------------------------------------------------------------
        if ($session_info["y_status"] == 8) {
            //--------------------------------------------------------------------------------
            $CustCreatedFrom = 0;
            //--------------------------------------------------------------------------------
            if($carrier == "pc") {
                //----------------------------------------------------------------------------
                //For PC
                //----------------------------------------------------------------------------
                $CustCreatedFrom = 4;
                //----------------------------------------------------------------------------
            }else {
                //----------------------------------------------------------------------------
                //For Keitai
                //----------------------------------------------------------------------------
                $CustCreatedFrom = 5;
                //----------------------------------------------------------------------------
            }// End if($carrier == "pc")
            //--------------------------------------------------------------------------------
            if (isset($CustCreatedFrom)) {
                //----------------------------------------------------------------------------
                $controller->Customer->set("CREATEDFROMCODE", $CustCreatedFrom);
                //----------------------------------------------------------------------------
            }// End if (isset($CustCreatedFrom))
            //--------------------------------------------------------------------------------
        }// End if ($session_info["y_status"] == 8)
        //====================================================================================

        $controller->Customer->save();

        return $ccode;
    }

    /**
     * ストア情報を読み取り
     * Get Store Information
     *
     * @param controller &$controller
     * @param int $companyid
     * @param int $storecode
     * @param string $dbname
     * @return Array
     */
    function GetStoreInfo(&$controller, $companyid, $storecode, $dbname = "") {

        if(intval($companyid) == 0 || intval($storecode) == 0) {
            return false;
        }

        if($dbname == "") {

            $c = $controller->Company->find('all', array(
                 'conditions' => array('Company.companyid' => $companyid),
                 'fields'     => array('Company.dbname','Company.logintype')));

            if (empty($c)) {
                return false;
            }
            $dbname = $c[0]['Company']['dbname'];
            $logintype = $c[0]['Company']['logintype'];
        }

        $account_data = $controller->StoreAccount->find('all', array(
                 'conditions' => array('StoreAccount.companyid'    => $companyid,
                                       'StoreAccount.storecode'    => $storecode,
                                       'WebyanAccount.yoyaku_flag' => 1  ),
                 'fields'     => array('WebyanAccount.storeid','WebyanAccount.tos_flg')));

        if(empty($account_data)) {
            return false;
        }

        $controller->Store->set_company_database($dbname, $controller->Store);

        $v = $controller->Store->find(
            'all',
        array(
                'conditions' => array('Store.storecode' => $storecode),
                'recursive' => -1
        )
        );

        if (empty($v)) {
            return false;
        }


        $arrReturn = $v[0]['Store'];
        $arrReturn['dbname']  = $dbname;
        $arrReturn['logintype'] = $logintype;
        $arrReturn['storeid'] = $account_data[0]['WebyanAccount']['storeid'];
        $arrReturn['tosflg'] = $account_data[0]['WebyanAccount']['tos_flg'];
        $kanzashiSalons = $this->GetKanzashiSalons($controller, $companyid, $storecode);
        $arrReturn['KanzashiFlag'] = count($kanzashiSalons) > 0;

        $controller->StoreSettings->set_company_database($dbname, $controller->StoreSettings);
        $criteria   = array('STORECODE' => $storecode);
        $v = $controller->StoreSettings->find('all', array('conditions' => $criteria));

        foreach ($v as $itm) {
            switch ($itm['StoreSettings']['OPTIONNAME']) {
                case 'CardPoint1Tani':
                    $arrReturn['CardPoint1Tani'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'CardPoint2Tani':
                    $arrReturn['CardPoint2Tani'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'PointName1':
                    $arrReturn['PointName1'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'PointName2':
                    $arrReturn['PointName2'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'PointKubun1':
                    $arrReturn['PointKubun1'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'PointKubun2':
                    $arrReturn['PointKubun2'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuCancelLimit':
                    $arrReturn['CancelLimit'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuApptLimit':
                    $arrReturn['LowLimit'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuLimitOption':
                    $arrReturn['LowLimitOp'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuUpperLimit':
                    $arrReturn['UpLimit'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuUpperLimitOption':
                    $arrReturn['UpLimitOp'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuTime':
                    $arrReturn['Interval'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuMailNewYoyaku':
                    $arrReturn['ThankUMailMsg'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuNewYoyaku':
                    $arrReturn['ThankyouMsg'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuCancelMsg':
                    $arrReturn['CancelMsg'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuMailSignature':
                    $arrReturn['MailFooter'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuRegistration':
                    $arrReturn['NewMemberMsg'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuMailRegistration':
                    $arrReturn['NewMemberMailMsg'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuShowMenuNameOnly':
                    $arrReturn['MenuNameOnly'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
            }
        }

        return $arrReturn;
    }

    // Added by jonathanparel, 20160930; RM#1789 -----------------------------------------------------ii
    /**
     * スタッフリストを読み込む
     * Get Mobasute Store Information
     *
     * @param controller &$controller
     * @param companyid
     * @param int $storecode
     * @param string $dbname
     */
    function GetMobasuteStoreInfo(&$controller, $companyid, $storecode, $dbname = "") {

        if(intval($companyid) == 0 || intval($storecode) == 0) {
            return false;
        }

        if($dbname == "") {

            $c = $controller->Company->find('all', array(
                 'conditions' => array('Company.companyid' => $companyid),
                 'fields'     => array('Company.dbname','Company.logintype')));

            if (empty($c)) {
                return false;
            }
            $dbname = $c[0]['Company']['dbname'];
            $logintype = $c[0]['Company']['logintype'];
        }

        $account_data = $controller->StoreAccount->find('all', array(
            'conditions' => array('StoreAccount.companyid'    => $companyid,
                                  'StoreAccount.storecode'    => $storecode,
                                  'WebyanAccount.yoyaku_flag' => 1  ),
            'fields'     => array('WebyanAccount.storeid','WebyanAccount.tos_flg')));

        if(empty($account_data)) {
            return false;
        }

        $controller->MobasuteStoreInfo->set_company_database($dbname, $controller->MobasuteStoreInfo);

        $m = $controller->MobasuteStoreInfo->find('all',
            array(
                'conditions' =>
                    array('MobasuteStoreInfo.storecode' => $storecode),
                        'recursive' => -1
                )
            );

        if (empty($m)) {
            return false;
        }

        $arrReturn = $m[0]['MobasuteStoreInfo'];
        $arrReturn['dbname']  = $dbname;
        $arrReturn['logintype'] = $logintype;
        $arrReturn['storeid'] = $account_data[0]['WebyanAccount']['storeid'];
        $arrReturn['tosflg'] = $account_data[0]['WebyanAccount']['tos_flg'];

        $controller->StoreSettings->set_company_database($dbname, $controller->StoreSettings);
        $criteria   = array('STORECODE' => $storecode);
        $v = $controller->StoreSettings->find('all', array('conditions' => $criteria));

        return $arrReturn;
    }
    // Added by jonathanparel, 20160930; RM#1789 -----------------------------------------------------xx


    /**
     * スタッフリストを読み込む
     * Get a list of Staff
     *
     * @param controller &$controller
     * @param &$staffList スタッフのリスト
     * @param &$staffNameList スタッフ名のリスト
     * @param int $storecode
     * @param string $dbname
     */
    function GetStaffList(&$controller, &$staffList, &$staffNameList, $storecode, $dbname ,$syscode = 0) {
        if(intval($storecode) == 0 || strlen($dbname) == 0) { exit; }

        $staffList = array();
        $staffNameList = array();
        $finished_shift = $this->GetFinishedShift($controller, $storecode, date("n"), date("Y"), $dbname);

        // シフト設定が行われていない場合、、リターンする。
        if ($finished_shift != 1) { return; }

        /*
        if($syscode != 0){
            $where_syscode = "and stafftype.syscode = ". $syscode . " ";
        }
         *
         */
        $controller->Staff->set_company_database($dbname, $controller->Staff);
        $controller->StaffAssignToStore->set_company_database($dbname, $controller->StaffAssignToStore);
        $controller->Position->set_company_database($dbname, $controller->Position);
        $controller->Stafftype->set_company_database($dbname, $controller->Stafftype);
        //stafftype bind

        if($syscode != 0 ){
            $controller->Stafftype->set_company_database($dbname, $controller->Stafftype);
            $bindarray = array('hasOne' => array('Stafftype' => array(
                                        'type' => 'left',
                                        'foreignKey' => 'STAFFCODE',
                                        'conditions' => array(
                                            'Stafftype.STAFFCODE = Staff.STAFFCODE',
                                            'Stafftype.SYSCODE = '.$syscode
                                            ))
            ));
        $controller->Staff->bindModel($bindarray);
        }

        $where_str ="";
        if($syscode != 0 ){
            //$where_str =" StaffAssignToStore.SYSCODE = {$syscode} AND ";
        }

        $todaysdate = date("Y-m-d");
        $conditions =
            "(Stafftype.SYSCODE is not null or Staff.staffcode = 0) AND " .
            "StaffAssignToStore.STORECODE = {$storecode} AND " .
            "StaffAssignToStore.ASSIGN_YOYAKU = 1 AND " . $where_str .
            "StaffAssignToStore.WEBYAN_DISPLAY = 1 AND ( " .
            "  Staff.STORECODE = 0 AND " .
            "  Staff.STAFFCODE = 0 OR ( " .
            "    Staff.STORECODE = {$storecode} AND " .
            "    Staff.STAFFCODE > 0 AND ( " .
            "      Staff.HIREDATE IS NULL OR " .
            "      Staff.HIREDATE <= '{$todaysdate}' " .
            "    ) AND ( " .
            "      Staff.RETIREDATE IS NULL OR " .
            "      Staff.RETIREDATE >= '{$todaysdate}' " .
            "    ) AND " .
            "    Staff.DELFLG IS NULL " .
            "  ) " .
            ") ";

        $fields = "Staff.STAFFNAME, Staff.STAFFCODE, Staff.SUBLEVELCODE, Staff.POSITIONCODE, Staff.SEX, Staff.BLOG_URL";
        $staffRecords = $controller->Staff->findAll($conditions, $fields, null, null, null, 2);

        foreach ($staffRecords as $staffRecord) {
            $staff = $staffRecord["Staff"];
            $staffCode = $staff["STAFFCODE"];

            if ($staffCode === "0") {
                // フリーのスタッフの場合
                $staff["STAFFNAME"] = "指名なし";
            } else {
                // フリーのスタッフ以外の場合
                $sex = $staff["SEX"] === "0" ? " (女)" : " (男)";
                $staff["STAFFNAME"] .= $sex;
                $staff["POSITIONNAME"] = $staffRecord["Position"]["POSITIONNAME"];
            }

                $staffList[] = $staff;
                $staffNameList[$staffCode] = $staff["STAFFNAME"];
              //業種区分が一致した際に表示
//            if( empty($syscode) || $syscode == $staffRecord["StaffAssignToStore"]["Stafftype"]["SYSCODE"]){
//                $staffList[] = $staff;
//                $staffNameList[$staffCode] = $staff["STAFFNAME"];
//            }
        }
    }

    /**
     * スタッフを読み込む
     * Get Staff Information
     *
     * @param controller &$controller
     * @param int $staffcode
     * @param string $dbname
     * @return string
     */
    function GetStaff(&$controller, $staffcode, $dbname) {
        if(!isset($dbname)) { return false; }
        if($staffcode == 0) { return "指名なし"; }

        $controller->Staff->set_company_database($dbname, $controller->Staff);
        $v = $controller->Staff->find('all', array(
                 'conditions' => array('Staff.STAFFCODE' => $staffcode,
                                       'Staff.DELFLG IS NULL'),
                 'fields' => array('Staff.STAFFNAME', 'Staff.STAFFCODE')
        ));

        if (empty($v)) {
            return false;
        }

        return $v[0]['Staff']['STAFFNAME'];
    }

    /**
     * セッションを確認する
     * Checks for the validity of session
     *
     * @param controller &$controller
     * @param int $storecode
     * @param string $dbname
     * @param int $sex
     * @return Array
     */
    function GetServicesList(&$controller, $storecode, $dbname, $sex,$staffcode=-1,$syscode = 0) {

        if(intval($storecode) == 0 || strlen($dbname) == 0) {
            return false;
        }
        if(!isset($staffcode)){
        	$staffcode = -1;
        }

        $controller->ServiceList->set_company_database($dbname, $controller->ServiceList);
        $controller->StoreService->set_company_database($dbname, $controller->StoreService);

        //--------------------------------------------------------------------------------
        //担当者別メニュー時間を表示、利用時とそうでない場合の場合分け
        $controller->StoreSettings->set_company_database($dbname, $controller->StoreSettings);
        $options = $controller->StoreSettings->find('all',
            array(
                'conditions' => array(
                    'STORECODE'  => $storecode,
                    array('OPTIONNAME IN ("YOYAKU_MENU_TANTOU", "TAX", "TAXOPTION")')
                ))
        );

        $use_staff_service_time = false;
        $normal_tax = 0;
        $zei_option = 0;
        if(!empty($options)) {
            foreach ($options as $option) {
                switch (strtoupper($option['StoreSettings']['OPTIONNAME'])) {
                    case 'YOYAKU_MENU_TANTOU':
                        $use_staff_service_time = $option['StoreSettings']['OPTIONVALUEI'] > 0;
                        break;
                    case 'TAX': 
                        $normal_tax = +$option['StoreSettings']['OPTIONVALUEI'];
                        break;
                    case 'TAXOPTION': 
                        $zei_option = +$option['StoreSettings']['OPTIONVALUEI'];
                        break;
                }
            }
        }

       if($use_staff_service_time) {
            $service_time = "ifnull(yoyaku_staff_service_time.SERVICE_TIME,store_services.SERVICETIME) as SERVICE_TIME, ";
            $service_time_male = "ifnull(yoyaku_staff_service_time.SERVICE_TIME_MALE,store_services.SERVICETIME_MALE) as SERVICETIME_MALE, ";
       }else{
            $service_time = "store_services.SERVICETIME as SERVICE_TIME, ";
            $service_time_male = "store_services.SERVICETIME_MALE as SERVICETIME_MALE, ";
       }
        //--------------------------------------------------------------------------------
        $where_syscode = "";
        if($syscode != 0){
            $where_syscode = "and services.syscode = ". $syscode . " ";
        }

        $query = "
            SELECT 
                services.BUNRUINAME as BUNRUINAME, 
                store_services.GCODE as GCODE, 
                store_services.MENUNAME as MENUNAME, 
                {$service_time}
                {$service_time_male}
                store_services.PRICE as PRICE,
                store_services.ZTYPE as TAXTYPE,
                store_services.GDCODE as GDCODE 
            FROM services 
            LEFT JOIN store_services 
                ON services.GDCODE = store_services.GDCODE 
            LEFT JOIN yoyaku_staff_service_time 
                ON store_services.GCODE = yoyaku_staff_service_time.GCODE AND 
                store_services.STORECODE = yoyaku_staff_service_time.STORECODE AND 
                yoyaku_staff_service_time.STAFFCODE = ? 
            WHERE  
                store_services.STORECODE = ? AND 
                store_services.SHOWONCELLPHONE = 1 AND 
                store_services.GSCODE IS NOT NULL AND 
                store_services.DELFLG IS NULL AND 
                services.DELFLG IS NULL 
                {$where_syscode}
            ORDER BY 
                store_services.GDCODE,
                store_services.DISPLAY_ORDER,
                store_services.GCODE
        ";

        $v = $controller->ServiceList->query($query,array($staffcode,$storecode));
        if (empty($v)) {
            return false;
        }

        $arrReturn = array();
        foreach($v as $n) {
            $daibunrui   = $n['services']['BUNRUINAME'];
            $gcode       = $n['store_services']['GCODE'];
            $menuname    = $n['store_services']['MENUNAME'];
            if($use_staff_service_time){
                $servicetime = ($sex == 0)?$n[0]['SERVICE_TIME']:$n[0]['SERVICETIME_MALE'];
            }else{
                $servicetime = ($sex == 0)?$n['store_services']['SERVICE_TIME']:$n['store_services']['SERVICETIME_MALE'];
            }

            $price = (int)$n['store_services']['PRICE'];

            if((int)$n['store_services']['TAXTYPE'] === 0) {
                $current_tax = $price * ($normal_tax / 100);
                $price += $this->computeTaxBasedOnZeiOption($current_tax, $zei_option);
            } 
            
            if($servicetime < 15) { $servicetime = 15; }
            $arrReturn[$daibunrui][$gcode] = array($menuname, $servicetime, $price);
        }

        return $arrReturn;
    }

    private function computeTaxBasedOnZeiOption($current_tax_amount, $zei_option) {
        if($current_tax_amount === 0) {
            return $current_tax_amount;
        } 

        $tax_amount = $current_tax_amount < 0 ? $current_tax_amount * -1 : $current_tax_amount;
        $temp_remainder = $tax_amount - intval($tax_amount);
        $remainder = $temp_remainder < 1 ? $temp_remainder * 10 : $temp_remainder * 1;
        
        switch (+$zei_option) {
            case 0:
                $tax_amount = intval($tax_amount);
                break;
            case 1:
                if($remainder >= 1 && $remainder <= 4) {
                    $tax_amount = intval($tax_amount);
                } elseif($remainder >= 5 && $remainder <= 10) {
                    $tax_amount = intval($tax_amount) + 1;
                }
                break;
            case 2:
                if($remainder >= 1 && $remainder <= 10) {
                    $tax_amount = intval($tax_amount) + 1;
                }
                break;
        }

        return $current_tax_amount < 0 ? $tax_amount * -1 : $tax_amount;
    }

     /**
     * メニューを読み込む
     * Gets Services
     *
     * @param controller &$controller
     * @param int $storecode
     * @param string $dbname
     * @param int $sex
     * @return Array
     */
    function GetServices(&$controller, $storecode, $dbname, $sel_list, $sex,$staffcode = -1) {

        if(intval($storecode) == 0 || strlen($dbname) == 0) {
            return false;
        }

        if(!isset($staffcode)){
        	$staffcode = -1;
        }

        $controller->ServiceList->set_company_database($dbname, $controller->ServiceList);
        $controller->StoreService->set_company_database($dbname, $controller->StoreService);

        $selected_condition = "";
        foreach($sel_list as $itm) {
            if(strlen($selected_condition) != 0) {
                $selected_condition .= " OR ";
            }
            //$selected_condition .= "StoreService.GCODE = ".$itm;
            $selected_condition .= "store_services.GCODE = ".$itm;
        }
        if(strlen($selected_condition) != 0) {
            $selected_condition = "(".$selected_condition.")";
        }

/*
        $v = $controller->ServiceList->find('all', array(
                 'conditions' => array('StoreService.STORECODE' => $storecode,
        $selected_condition,
                                       'StoreService.DELFLG IS NULL',
                                       'ServiceList.DELFLG IS NULL'),
                 'fields' => array('StoreService.GCODE',
                                   'StoreService.MENUNAME',
                                   'StoreService.SERVICETIME',
                                   'StoreService.SERVICETIME_MALE',
                                   'StoreService.PRICE',
                                   'StoreService.MEMBERPRICE',
                                   'StoreService.ZTYPE')
        ));

        if (empty($v)) {
            return false;
        }

        $arrReturn = array();
        foreach($v as $n) {
            $gcode        = $n['StoreService']['GCODE'];
            $menuname     = $n['StoreService']['MENUNAME'];
            $servicetime  = ($sex==0)?$n['StoreService']['SERVICETIME']:
            $n['StoreService']['SERVICETIME_MALE'];
            if($servicetime < 15) { $servicetime = 15; }
            $price        = $n['StoreService']['PRICE'];
            $memberprice  = $n['StoreService']['MEMBERPRICE'];
            $ztype        = $n['StoreService']['ZTYPE'];
            $arrReturn[]  = array('gcode'        => $gcode,
                                  'menuname'     => $menuname,
                                  'servicetime'  => intval($servicetime),
                                  'price'        => intval($price),
                                  'memberprice'  => intval($memberprice),
                                  'ztype'        => intval($ztype)  );
        }
*/
                //--------------------------------------------------------------------------------
        //担当者別メニュー時間を表示、利用時とそうでない場合の場合分け
        $controller->StoreSettings->set_company_database($dbname, $controller->StoreSettings);
        $option = $controller->StoreSettings->find('all',array( 'conditions' => array('STORECODE'  => $storecode,
            'OPTIONNAME' => 'YOYAKU_MENU_TANTOU'),
            'order'      => 'STORECODE ASC',
            'limit'      => 1 ) );

        $use_staff_service_time = false;
        if(!empty($option)) {
            if($option[0]['StoreSettings']['OPTIONVALUEI'] > 0) {
                $use_staff_service_time = true;
                }
        }
       if($use_staff_service_time) {
            $service_time = "ifnull(yoyaku_staff_service_time.SERVICE_TIME,store_services.SERVICETIME) as SERVICE_TIME, ";
            $service_time_male = "ifnull(yoyaku_staff_service_time.SERVICE_TIME_MALE,store_services.SERVICETIME_MALE) as SERVICETIME_MALE, ";
       }else{
            $service_time = "store_services.SERVICETIME as SERVICE_TIME, ";
            $service_time_male = "store_services.SERVICETIME_MALE as SERVICETIME_MALE, ";
       }
        //--------------------------------------------------------------------------------

        $query = "select ".
		"services.BUNRUINAME as BUNRUINAME, ".
		"store_services.GCODE as GCODE, ".
		"store_services.MENUNAME as MENUNAME, ".
		$service_time.
		$service_time_male.
		"store_services.PRICE as PRICE, ".
		"store_services.GDCODE as GDCODE, ".
		"store_services.ZTYPE as ZTYPE, ".
		"store_services.MEMBERPRICE as MEMBERPRICE ".
		"from services ".
		"left join store_services on ".
		"services.GDCODE = store_services.GDCODE ".
		"left join ".
		"yoyaku_staff_service_time on ".
		"store_services.GCODE = yoyaku_staff_service_time.GCODE AND ".
		"store_services.STORECODE = yoyaku_staff_service_time.STORECODE  ".
		"AND yoyaku_staff_service_time.STAFFCODE = ? ".
		"where ".$selected_condition.
		" AND store_services.STORECODE = ? ".
		"AND store_services.SHOWONCELLPHONE = 1 AND ".
		"store_services.GSCODE IS NOT NULL AND ".
		"store_services.DELFLG IS NULL AND ".
		"services.DELFLG IS NULL";

        $v = $controller->ServiceList->query($query,array($staffcode, $storecode));
        if (empty($v)) {
            return false;
        }

        $arrReturn = array();
        foreach($v as $n) {
            $gcode        = $n['store_services']['GCODE'];
            $menuname     = $n['store_services']['MENUNAME'];
            //$servicetime = ($sex == 0)?$n[0]['SERVICE_TIME']:$n[0]['SERVICETIME_MALE'];
            if($use_staff_service_time){
                $servicetime = ($sex == 0)?$n[0]['SERVICE_TIME']:$n[0]['SERVICETIME_MALE'];
            }else{
                $servicetime = ($sex == 0)?$n['store_services']['SERVICE_TIME']:$n['store_services']['SERVICETIME_MALE'];
            }
            if($servicetime < 15) { $servicetime = 15; }
            $price        = $n['store_services']['PRICE'];
            $memberprice  = $n['store_services']['MEMBERPRICE'];
            $ztype        = $n['store_services']['ZTYPE'];
            $arrReturn[]  = array('gcode'        => $gcode,
                                  'menuname'     => $menuname,
                                  'servicetime'  => intval($servicetime),
                                  'price'        => intval($price),
                                  'memberprice'  => intval($memberprice),
                                  'ztype'        => intval($ztype)  );
        }
        return $arrReturn;
    }

    /**
     *
     */


    function CheckStoreGyoshuKubun(&$controller, $storecode, $dbname = "")
    {
        if(intval($storecode) == 0 || strlen($dbname) == 0) {
            return false;
        }
        $arrReturn = array();
        $controller->Storetype->set_company_database($dbname, $controller->Storetype);

        $query = "select storetype.syscode , servicessys.DESCRIPTION from storetype
                    left join servicessys on servicessys.SYSCODE = storetype.syscode
                    where storecode = ? and servicessys.delflg is null and storetype.DELFLG is null";

        $v = $controller->Storetype->query($query,array($storecode));

        if (empty($v)) {
            return false;
        }
        foreach($v as $n) {
            $arrReturn[]  = $n['storetype']['syscode'];
            //$arrReturn[$n['storetype']['syscode']]  = $n['servicessys']['DESCRIPTION'];
        }
        return $arrReturn;
    }

    /**
     *　ccodeを指定した場合、店舗の業種区分を読み込む。
     * 指定なしの場合、先頭のsyscodeを返す
     */
    function GetStoreGyoshuKubun2(&$controller, $storecode, $dbname = "" , $ccode = ""){
        if(intval($storecode) == 0 || strlen($dbname) == 0) {
            return false;
        }
        $arrReturn = array();
        $controller->Storetype->set_company_database($dbname, $controller->Storetype);

        //Store syscode
        $query = "select storetype.syscode , servicessys.DESCRIPTION from storetype
                    left join servicessys on servicessys.SYSCODE = storetype.syscode
                    where storecode = ? and servicessys.delflg is null and storetype.DELFLG is null";

            $v = $controller->Storetype->query($query,array($storecode,$ccode),false);
        if (empty($v)) {
            return false;
        }
        foreach($v as $n) {
            $arrReturn[$n['storetype']['syscode']]  = $n['servicessys']['DESCRIPTION'];
        }
        return $arrReturn;
    }


    /**
     *　ccodeを指定した場合、店舗の業種区分を読み込む。
     * 指定なしの場合、先頭のsyscodeを返す
     */
    function GetStoreGyoshuKubun(&$controller, $storecode, $dbname = "" , $ccode = ""){
        if(intval($storecode) == 0 || strlen($dbname) == 0) {
            return false;
        }
        $arrReturn = array();
        $controller->Storetype->set_company_database($dbname, $controller->Storetype);

        /*already yoyaku finished syscodes*/
        //        $tran_query ="select services.syscode
        //                      from store_transaction
        //                      left join store_transaction_details on
        //                      store_transaction_details.TRANSCODE = store_transaction.TRANSCODE and
        //                      store_transaction_details.KEYNO = store_transaction.KEYNO
        //                      left join store_services on store_services.GCODE = store_transaction_details.GCODE
        //                      left join services on store_services.GDCODE = services.GDCODE
        //                      where
        //                      ccode = ? and
        //                      store_transaction.TRANSDATE >= CURRENT_DATE() and
        //                      store_transaction.YOYAKU >= 1 and
        //                      store_transaction.TEMPSTATUS >= 4 and
        //                      store_transaction.delflg is null and
        //                      store_transaction_details.delflg is null
        //                      group by services.syscode";

        /*Store syscode*/
        //        $controller->Storetype->set_company_database($dbname, $controller->Storetype);
        //        $query = "select storetype.syscode , servicessys.DESCRIPTION from storetype
        //                    left join servicessys on servicessys.SYSCODE = storetype.syscode
        //                    where storecode = ? and servicessys.delflg is null and storetype.DELFLG is null";

            $query = "select storetype.syscode , servicessys.DESCRIPTION from storetype
                        left join servicessys on servicessys.SYSCODE = storetype.syscode
                        where storecode = ? and servicessys.delflg is null and storetype.DELFLG is null and
                        storetype.syscode not in (select services.syscode
                        from store_transaction
                        left join store_transaction_details on
                        store_transaction_details.TRANSCODE = store_transaction.TRANSCODE and
                        store_transaction_details.KEYNO = store_transaction.KEYNO
                        left join store_services on store_services.GCODE = store_transaction_details.GCODE
                        left join services on store_services.GDCODE = services.GDCODE
                        where
                        ccode = ? and
                        store_transaction.TRANSDATE >= CURRENT_DATE() and
                        store_transaction.YOYAKU >= 1 and
                        store_transaction.TEMPSTATUS >= 4 and
                        store_transaction.delflg is null and
                        store_transaction_details.delflg is null
                        group by services.syscode)";

            $v = $controller->Storetype->query($query,array($storecode,$ccode),false);
        if (empty($v)) {
            return false;
        }
        foreach($v as $n) {
            $arrReturn[$n['storetype']['syscode']]  = $n['servicessys']['DESCRIPTION'];
        }
        return $arrReturn;
    }


    /**
     * 顧客情報を読み込む
     * Gets Customer information
     *
     * @param controller &$controller
     * @param int $companyid
     * @param int $ccode
     * @param string $dbname
     * @return string
     */
    function GetCustomerInfo(&$controller, $companyid, $ccode, $dbname = "") {

        if(intval($companyid) == 0 || strlen($ccode) == 0) {
            return false;
        }

        if($dbname == "") {

            $c = $controller->Company->find('all', array(
                 'conditions' => array('Company.companyid' => $companyid),
                 'fields'     => array('Company.dbname')));

            if (empty($c)) {
                return false;
            }
            $dbname = $c[0]['Company']['dbname'];
        }

        $controller->Customer->set_company_database($dbname, $controller->Customer);
        $controller->CustomerTotal->set_company_database($dbname, $controller->CustomerTotal);
        $v = $controller->Customer->find('all', array(
                 'conditions' => array('Customer.ccode' => $ccode)  ));

        if (empty($v)) {
            return false;
        }

        $arrReturn = $v[0]['Customer'];
        $arrReturn['points1'] = $v[0]['CustomerTotal']['POINTTOTAL1'];
        $arrReturn['points2'] = $v[0]['CustomerTotal']['POINTTOTAL2'];
        $arrReturn['dbname']  = $dbname;
        return $arrReturn;
    }

    /**
     * 新規登録情報と重複した顧客の顧客番号を取得する
     *
     * @param $controller コントローラ
     * @param $storeCode 店舗番号
     * @param $tel 電話番号
     * @param $dbname データベース名
     */
    function GetDuplicateCustomerCCode(&$controller, $storeCode, $tel, $dbname) {
        // 電話番号が10桁未満の場合、nullをリターンする(不正な電話番号とする)
        if($storeCode < 0 || strlen($tel) < 10 || strlen($dbname) == 0) { return null; }

        $controller->Customer->set_company_database($dbname, $controller->Customer);
        $query =
            "SELECT DISTINCT customer.CCODE " .
            "FROM customer " .
            "LEFT JOIN datashare " .
            "ON datashare.STORECODE = {$storeCode} " .
            "WHERE " .
            "  customer.DELFLG IS NULL AND ( " .
            "    REPLACE(customer.TEL1, '-', '') = '{$tel}' OR " .
            "    REPLACE(customer.TEL2, '-', '') = '{$tel}' " .
            "  ) AND ( " .
            "    customer.CSTORECODE = {$storeCode} OR ( " .
            "      datashare.DELFLG IS NULL AND " .
            "      customer.CSTORECODE = datashare.SHARESTORECODE " .
            "    ) " .
            "  ) " .
            "ORDER BY customer.UPDATEDATE DESC ";
        $customerRecords = $controller->Customer->query($query);

        // 取得結果が0件の場合、nullをリターンする
        if (count($customerRecords) == 0) { return null; }

        // 取得結果の1件目をリターンする(最新の顧客情報)
        return $customerRecords[0]["customer"]["CCODE"];
    }

    /**
     * 新規予約を書き込む
     * Writes New Yoyaku
     * @param controller &$controller
     * @param array $customer_info
     * @param array $session_info
     */
    function WriteNewYoyaku(&$controller, $session_info, $customer_info) {
        if(intval($session_info['companyid']) == 0 ||
        intval($session_info['storecode']) == 0 ||
        strlen($session_info['ccode']) == 0) {
            return false;
        }

        $dbname = $session_info['dbname'];

        $services_arr = explode(",", $session_info['y_services']);

        //-- 会社データベースを設定する (Set the Company Database)
        $controller->StoreTransaction->set_company_database($session_info['dbname'], $controller->StoreTransaction);
        $controller->StoreTransactionDetails->set_company_database($session_info['dbname'], $controller->StoreTransactionDetails);
        $controller->StoreSettings->set_company_database($session_info['dbname'], $controller->StoreSettings);
        $controller->YoyakuMessage->set_company_database($session_info['dbname'], $controller->YoyakuMessage);
        $controller->StoreSettings->set_company_database($session_info['dbname'], $controller->StoreSettings);

        $controller->Customer->set_company_database($session_info['dbname'], $controller->Customer);

        $sel_date = substr($session_info['y_date'],0,4).'-'.
        substr($session_info['y_date'],4,2).'-'.
        substr($session_info['y_date'],6,2);

        $full_services_arr = $this->GetServices($controller,
        $session_info['storecode'],
        $session_info['dbname'],
        $services_arr,
        $customer_info['SEX'],
        $session_info['y_staff']
        );

        $total_servicetime = 0;
        foreach($full_services_arr as $srvc) {
            $total_servicetime += $srvc['servicetime'];
        }

        $time_from = strtotime(substr($session_info['y_time'],0,2).":".substr($session_info['y_time'],2,2));
        $time_to = $time_from + ($total_servicetime * 60);

        $sel_time = date("H:i",$time_from);
        $end_time = date("H:i",$time_to);

        $sql_idno = "select f_get_sequence_key('idno',
                                            ".$session_info['storecode']." ,
                                            '". $sel_date ."') as IDNO";

        $tmp_data = $controller->StoreTransaction->query($sql_idno);
        $next_idno = $tmp_data[0][0]['IDNO'];

        $transcode  = $controller->MiscFunction->GenerateTranscode( array(
                                           'storecode' => $session_info['storecode'],
                                           'date'      => $session_info['y_date'],
                                           'idno'      => $next_idno)  );

        if(!$controller->MiscFunction->IsRegularCustomer($controller->Customer, $session_info['ccode'])){

            $yoyakudatetime = date('Y-m-d H:i:s', strtotime($sel_date . ' ' . $sel_time));

            $kyakukubun = $controller->MiscFunction->GetKyakukubunByDateTime($controller->StoreTransaction, $session_info['ccode'], $yoyakudatetime);

            $regularcustomer = ($kyakukubun == 0 ? 1 : 0);
        }
        else
        {
            $kyakukubun = 0;
	        $regularcustomer = 1;
        }

        // Store Settings
        $criteria   = array('STORECODE' => $session_info['storecode']);
        $criteria[] = "(OPTIONNAME = 'Tax' OR OPTIONNAME = 'TaxOption' OR OPTIONNAME = 'TotalOption')";
        $v = $controller->StoreSettings->find('all', array('conditions' => $criteria));

        $ratetax = "0.05";
        $zeioption = 0;
        $sogokeioption = 0;

        foreach ($v as $itm) {
            switch ($itm['StoreSettings']['OPTIONNAME']) {
                case 'Tax':
                    $ratetax = $itm['StoreSettings']['OPTIONVALUEI'] / 100;
                    break;
                case 'TaxOption':
                    $zeioption = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'TotalOption':
                    $sogokeioption = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
            }
        }

        if($session_info['carrier'] != "pc") {
            // 携帯
            $tempstatus  = 5;
            $yoyaku      = 3;
            $origination = 1;
        }
        else {
            // PC
            $tempstatus  = 6;
            $yoyaku      = 2;
            $origination = 2;
        }
        $ret = "";
        if(count($full_services_arr) > 0){
        $controller->StoreTransaction->set('TRANSCODE',       $transcode);
        $controller->StoreTransaction->set('KEYNO',           1);
        $controller->StoreTransaction->set('STORECODE',       $session_info['storecode']);
        $controller->StoreTransaction->set('IDNO',            $next_idno);
        $controller->StoreTransaction->set('TRANSDATE',       $sel_date);
        $controller->StoreTransaction->set('YOYAKUTIME',      $sel_time);
        $controller->StoreTransaction->set('ENDTIME',         $end_time);
        $controller->StoreTransaction->set('CCODE',           $session_info['ccode']);
        $controller->StoreTransaction->set('REGULARCUSTOMER', $regularcustomer);
        $controller->StoreTransaction->set('KYAKUKUBUN',      $kyakukubun);
        $controller->StoreTransaction->set('RATETAX',         $ratetax );
        $controller->StoreTransaction->set('ZEIOPTION',       $zeioption);
        $controller->StoreTransaction->set('SOGOKEIOPTION',   $sogokeioption);
        $controller->StoreTransaction->set('UPDATEDATE',      date("Y-m-d H:i:s"));
        $controller->StoreTransaction->set('TEMPSTATUS',      $tempstatus);
        $controller->StoreTransaction->set('CNAME',           $customer_info['CNAME']);
        $controller->StoreTransaction->set('PRIORITY',        1);
        $controller->StoreTransaction->set('PRIORITYTYPE',    1);
        $controller->StoreTransaction->set('STAFFCODE',       $session_info['y_staff']);
        $controller->StoreTransaction->set('YOYAKU',          $yoyaku);
        $controller->StoreTransaction->set('SHIMEI',          ($session_info['y_staff'] == 0) ? 0 : 1);
        $controller->StoreTransaction->set('ORIGINATION',     $origination);
        $controller->StoreTransaction->set('HASSERVICES',     1);
        $controller->StoreTransaction->set('HASPRODUCTS',     1);
        $ret = $controller->StoreTransaction->save();

        }else{
            return false;
        }
        if($ret === true || $ret === false){
            return false;
        }
        $rowno = 1;

        //再定義
        $time_from = strtotime(substr($session_info['y_time'],0,2).":".substr($session_info['y_time'],2,2));
        foreach($full_services_arr as $srvc) {
            $serviceprice = ($customer_info['MEMBERSCATEGORY'] == 1) ? $srvc['memberprice'] : $srvc['price'];

            $time_to = $time_from + ($srvc['servicetime'] * 60);
            $starttime = date("H:i",$time_from);
            $endtime = date("H:i",$time_to);
            $time_from = $time_to;

            $controller->StoreTransactionDetails->create(); // model::create(),read Cakephp manual //no delete
            $controller->StoreTransactionDetails->set('TRANSCODE',       $transcode);
            $controller->StoreTransactionDetails->set('STARTTIME',       $starttime);
            $controller->StoreTransactionDetails->set('ENDTIME',         $endtime);
            $controller->StoreTransactionDetails->set('KEYNO',           1);
            $controller->StoreTransactionDetails->set('ROWNO',           $rowno);
            $controller->StoreTransactionDetails->set('STORECODE',       $session_info['storecode']);
            $controller->StoreTransactionDetails->set('TRANSDATE',       $sel_date);
            $controller->StoreTransactionDetails->set('GCODE',           $srvc['gcode']);
            $controller->StoreTransactionDetails->set('TRANTYPE',        1);
            $controller->StoreTransactionDetails->set('QUANTITY',        1);
            $controller->StoreTransactionDetails->set('UNITPRICE',       $serviceprice);
            $controller->StoreTransactionDetails->set('PRICE',           $serviceprice);
            $controller->StoreTransactionDetails->set('ZEIKUBUN',        $srvc['ztype']);
            $controller->StoreTransactionDetails->set('DISCOUNT',        0);
            $controller->StoreTransactionDetails->set('STAFFCODE',       $session_info['y_staff']);
            $controller->StoreTransactionDetails->set('STAFFCODESIMEI',  $session_info['y_staff']);
            $controller->StoreTransactionDetails->set('UPDATEDATE',      date("Y-m-d H:i:s"));
            $controller->StoreTransactionDetails->set('TEMPSTATUS',      $tempstatus);
            $ret = $controller->StoreTransactionDetails->save();
            if($ret == false){break;}
            $rowno++;
        }

        if($ret === true || $ret === false)
        {
            //予約の削除
            $controller->StoreTransaction->query("UPDATE store_transaction SET DELFLG = NOW() WHERE TRANSCODE = '{$transcode}' AND KEYNO = 1");
            $controller->StoreTransaction->query("UPDATE store_transaction_details SET DELFLG = NOW() WHERE TRANSCODE = '{$transcode}' AND KEYNO = 1");
        }


        // 予約詳細テーブルに受付日と受付スタッフを挿入
        $controller->StoreTransaction->query("INSERT INTO yoyaku_details(TRANSCODE, UKETSUKEDATE, UKETSUKESTAFF) VALUES('$transcode', CURRENT_DATE, 0) ON DUPLICATE KEY UPDATE UKETSUKEDATE = CURRENT_DATE, UKETSUKESTAFF = 0");

        //POPUPメッセージの追加
        $controller->StoreSettings->set_company_database($dbname, $controller->StoreSettings);
        $option = $controller->StoreSettings->find('all',array( 'conditions' => array('STORECODE'  => $session_info['storecode'],
                                                                'OPTIONNAME' => 'YOYAKU_MSG'),
                                                                'order'      => 'STORECODE ASC',
                                                                'limit'      => 1 ) );
        if(!empty($option)) {
            if($option[0]['StoreSettings']['OPTIONVALUEI']>0) {
                $staffname = $this->GetStaff($controller,$session_info['y_staff'],$session_info['dbname']);
                $controller->YoyakuMessage->set('STORECODE',      $session_info['storecode']);
                $controller->YoyakuMessage->set('CNAME',          $customer_info['CNAME']);
                $controller->YoyakuMessage->set('YOYAKUDATETIME', $sel_date.' '.$sel_time);
                $controller->YoyakuMessage->set('STAFFNAME',      $staffname);
                $controller->YoyakuMessage->set('MSG',            '新規予約を受け付けました。');
                $controller->YoyakuMessage->save();
                //
                $controller->StoreTransactionDetails->query("delete from customer_mail_reservation where storecode = ".$session_info['storecode']." and ccode = '".$session_info['ccode']."' and transdate<date('".$sel_date."')");
            }
        }
    }

     /**
     * 既存の予約に追加する
     * Writes New Yoyaku　
     */
    function WriteAddYoyaku(&$controller, $session_info, $customer_info) {
        //データチェック
        if(intval($session_info['companyid']) == 0 ||
        intval($session_info['storecode']) == 0 ||
        strlen($session_info['ccode']) == 0)
        {
            return false;
        }

        //-- 会社データベースを設定 (Set the Company Database)
        $controller->StoreTransaction->set_company_database($session_info['dbname'], $controller->StoreTransaction);
        $controller->StoreTransactionDetails->set_company_database($session_info['dbname'], $controller->StoreTransactionDetails);
        $controller->StoreSettings->set_company_database($session_info['dbname'], $controller->StoreSettings);
        $controller->YoyakuMessage->set_company_database($session_info['dbname'], $controller->YoyakuMessage);
        $controller->StoreSettings->set_company_database($session_info['dbname'], $controller->StoreSettings);
        $controller->Customer->set_company_database($session_info['dbname'], $controller->Customer);

        ////指定日の伝票番号、終了時間を取得
        $trans = $controller->StoreTransaction->find('all',
            array( 'conditions' => array(
                                        'TRANSDATE' => $session_info['y_date'],
                                        'CCODE' => $session_info['ccode'],
                                        'YOYAKU >= 1',
                                        'TEMPSTATUS >= 4',
                                        'DELFLG IS NULL'),
                                        'order' => 'TRANSDATE DESC',
                                        'limit' => 1 )
            );

        if(!empty($trans)) {
            //伝票から作業時間取得
            $transcode = $trans[0]['StoreTransaction']['TRANSCODE'];
            $sel_date = $trans[0]['StoreTransaction']['TRANSDATE'];
            $keyno = $trans[0]['StoreTransaction']['KEYNO'];
            //$yoyakutime = $trans[0]['StoreTransaction']['YOYAKUTIME'];
            $last_endtime = $trans[0]['StoreTransaction']['ENDTIME'];
        }

        //同日予約済みのメニュー一覧を取得



        //追加するサービス時間を取得。
        $services_arr = explode(",", $session_info['y_services']);
        $full_services_arr = $this->GetServices (
            $controller,
            $session_info['storecode'],
            $session_info['dbname'],
            $services_arr,
            $customer_info['SEX'],
            $session_info['y_staff']
         );

        //追加の総合計時間を再計算
        $total_add_servicetime = 0;
        foreach($full_services_arr as $srvc) {
            $total_add_servicetime += $srvc['servicetime'];
        }

        //$time_from = strtotime(substr($session_info['y_time'],0,2).":".substr($session_info['y_time'],2,2));
        //$time_to = $time_from + ($total_servicetime * 60);

        //既存サービスに追加。結構危険なので要確認
        $time_from = strtotime($last_endtime);
        $time_to = $time_from + ($total_add_servicetime * 60);

        //新しい終了時間の書き込み
        $endtime = date("H:i",$time_to);

        ////transactionの終了時間の更新
        $controller->StoreTransaction->query("UPDATE store_transaction SET ENDTIME = '{$endtime}' WHERE TRANSCODE = '{$transcode}' AND KEYNO = {$keyno}");

        //最終行の取得
        $detail = $controller->StoreTransactionDetails->query("select MAX(ROWNO) as ROWNO from store_transaction_details WHERE TRANSCODE = '{$transcode}' AND KEYNO = {$keyno} group by transcode");
        $rowno = $detail[0][0]['ROWNO'];

        if($session_info['carrier'] != "pc") {
            // 携帯
            $tempstatus  = 5;
            $yoyaku      = 3;
            $origination = 1;
        }
        else {
            // PC
            $tempstatus  = 6;
            $yoyaku      = 2;
            $origination = 2;
        }

        //追加するdetailsの生成
        foreach($full_services_arr as $srvc) {
            $rowno++;
            $serviceprice = ($customer_info['MEMBERSCATEGORY'] == 1) ? $srvc['memberprice'] : $srvc['price'];
            $time_to = $time_from + ($srvc['servicetime'] * 60);
            $starttime = date("H:i",$time_from);
            $endtime = date("H:i",$time_to);
            $time_from = $time_to;

            $controller->StoreTransactionDetails->create(); // model::create(),read Cakephp manual //no delete
            $controller->StoreTransactionDetails->set('TRANSCODE',       $transcode);
            $controller->StoreTransactionDetails->set('STARTTIME',       $starttime);
            $controller->StoreTransactionDetails->set('ENDTIME',         $endtime);
            $controller->StoreTransactionDetails->set('KEYNO',           $keyno);
            $controller->StoreTransactionDetails->set('ROWNO',           $rowno);
            $controller->StoreTransactionDetails->set('STORECODE',       $session_info['storecode']);
            $controller->StoreTransactionDetails->set('TRANSDATE',       $sel_date);
            $controller->StoreTransactionDetails->set('GCODE',           $srvc['gcode']);
            $controller->StoreTransactionDetails->set('TRANTYPE',        1);
            $controller->StoreTransactionDetails->set('QUANTITY',        1);
            $controller->StoreTransactionDetails->set('UNITPRICE',       $serviceprice);
            $controller->StoreTransactionDetails->set('PRICE',           $serviceprice);
            $controller->StoreTransactionDetails->set('ZEIKUBUN',        $srvc['ztype']);
            $controller->StoreTransactionDetails->set('DISCOUNT',        0);
            $controller->StoreTransactionDetails->set('STAFFCODE',       $session_info['y_staff']);
            $controller->StoreTransactionDetails->set('STAFFCODESIMEI',  $session_info['y_staff']);
            $controller->StoreTransactionDetails->set('UPDATEDATE',      date("Y-m-d H:i:s"));
            $controller->StoreTransactionDetails->set('TEMPSTATUS',      $tempstatus);
            $ret = $controller->StoreTransactionDetails->save();
            if($ret === true || $ret === false){ return false;}
        }
}
    /**
     * 当日の、重複しているサービス一覧を取得
     * @param type $controller
     * @param type $session_info
     * @param type $date
     */
    function GetYoyakuServices(&$controller, $session_info)
    {
        $controller->StoreTransactionDetails->set_company_database($session_info['dbname'], $controller->StoreTransactionDetails);

        $query = "select store_transaction.TRANSCODE,GROUP_CONCAT(CAST(`GCODE` AS CHAR)) as GCODE_ARR
                        from store_transaction
                        left join store_transaction_details on
                        store_transaction_details.TRANSCODE = store_transaction.TRANSCODE and
                        store_transaction_details.KEYNO = store_transaction.KEYNO
                        where
                        ccode = ? and
                        store_transaction.TRANSDATE = ? and
                        store_transaction.YOYAKU >= 1 and
                        store_transaction.TEMPSTATUS >= 4 and
                        store_transaction.delflg is null and
                        store_transaction_details.delflg is null and
			GCODE in ( {$session_info['y_services']} )
			group by store_transaction.TRANSCODE;";
        $sel_date = substr($session_info['y_date'],0,4).'-'.
        substr($session_info['y_date'],4,2).'-'.
        substr($session_info['y_date'],6,2);

        $v = $controller->StoreTransactionDetails->query($query,array($session_info['ccode'],$sel_date));


        $dep_arr = array();
        if(count($v)> 0){
            $dep_arr = explode(",",$v[0][0]['GCODE_ARR']);
        }
        $service_arr = explode(",",$session_info['y_services']);
            //
            //return implode(",",array_diff($service_arr,$dep_arr));
            $ret = array_diff($service_arr,$dep_arr);
            return count($ret) > 0 ? $ret : false;

    }

    /**
     * 予約可能の時間リストを作成する
     * gets a list of availiable yoyaku times
     *
     * @param controller &$controller
     * @param array $customer_info
     * @param int $servicetime
     * @param int $LowLimit
     * @param string $LowLimitOp
     * @param boolean $checkTime if use checktime
     * @return Array
     */
    function GetAvailableTimes(&$controller, $session_info, $servicetime, $LowLimit, $LowLimitOp) {
        $time_arr = array();
        $AvailableTimes = array();

        if ($servicetime == 0) {
            $servicetime = 1;
        }

        $controller->StaffRowsHistory->set_company_database($session_info['dbname'], $controller->StaffRowsHistory);
        $controller->StaffShift->set_company_database($session_info['dbname'], $controller->StaffShift);
        $controller->Shift->set_company_database($session_info['dbname'], $controller->Shift);
        $controller->BreakTime->set_company_database($session_info['dbname'], $controller->BreakTime);
        $controller->StaffAssignToStore->set_company_database($session_info['dbname'], $controller->StaffAssignToStore);
        $controller->StoreTransactionDetails->set_company_database($session_info['dbname'], $controller->StoreTransactionDetails);

        $sel_date = sprintf("%04s-%02s-%02s", substr($session_info['y_date'], 0, 4), substr($session_info['y_date'], 4, 2), substr($session_info['y_date'], 6, 2));

        $storecode = $session_info['storecode'];
        $kanzashiFlag = $session_info['kanzashiflag'];

       $availableStaffs = $this->GetAvailableStaffDetails($controller, $storecode, $sel_date, $kanzashiFlag);

        $total_staff_capacity = 0;
        for ($i = 0; $i < count($availableStaffs); $i++) {
            if($availableStaffs[$i]['StaffAssignToStore']['STAFFCODE'] > 0){
                //スタッフ全体のキャパ
                $total_staff_capacity += $availableStaffs[$i][0]['ROWS'];
            }
            //選択したスタッフの予約列のキャパシティ
            if($availableStaffs[$i]['StaffAssignToStore']['STAFFCODE'] == $session_info['y_staff'] )
            {
                $staff_capacity = intval($availableStaffs[$i][0]['ROWS']);
                if ($staff_capacity == 0) {
                    $staff_capacity = 1; // Default?
                }
            }
        }

        // 予約可能顧客数。デフォルトは99とする
        $yoyakuCustomersLimit = 99;

        // Use Store Yoyaku Time Settings
        $controller->StoreSettings->set_company_database($session_info['dbname'], $controller->StoreSettings);

        $criteria = array('STORECODE' => $session_info['storecode']);
        $criteria[] = "(OPTIONNAME = 'YoyakuStart' OR OPTIONNAME = 'YoyakuEnd' OR OPTIONNAME = 'YoyakuCustomersLimitAuto' OR
                        OPTIONNAME = 'YoyakuStart_satsun' OR OPTIONNAME = 'YoyakuEnd_satsun' OR
                        OPTIONNAME = 'OpenTime' OR OPTIONNAME = 'CloseTime' OR
                        OPTIONNAME = 'YoyakuCustomersLimit' OR OPTIONNAME = 'YoyakuShowMenuNameOnly')";
        $storeSettings = $controller->StoreSettings->find('all', array('conditions' => $criteria));

        $yoyakustart_satsun = "";
        $yoyakuend_satsun = "";
        $yoyakuShowMenuNameOnly = 0;
        $yoyakuCustomersLimitAuto = 0;

        foreach ($storeSettings as $itm) {
            switch ($itm['StoreSettings']['OPTIONNAME']) {
                case 'YoyakuStart':
                    $yoyakustart = sprintf("%04d", $itm['StoreSettings']['OPTIONVALUEI']);
                    break;
                case 'YoyakuEnd':
                    $yoyakuend = sprintf("%04d", $itm['StoreSettings']['OPTIONVALUEI']);
                    break;
                //----------------------------------------------------------------------------------
                //get yoyaku time for weekends saturday and sunday
                //----------------------------------------------------------------------------------
                case 'YoyakuStart_satsun':
                    $yoyakustart_satsun = sprintf("%04d", $itm['StoreSettings']['OPTIONVALUEI']);
                    break;
                case 'YoyakuEnd_satsun':
                    $yoyakuend_satsun = sprintf("%04d", $itm['StoreSettings']['OPTIONVALUEI']);
                    break;
                //----------------------------------------------------------------------------------
                case 'OpenTime':
                    $openstart = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'CloseTime':
                    $openend = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuCustomersLimit':
                    $yoyakuCustomersLimit = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuShowMenuNameOnly':
                    $yoyakuShowMenuNameOnly = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                //----------------------------------------------------------------------------------
                //スタッフの予約列で制限する
                //----------------------------------------------------------------------------------
                case 'YoyakuCustomersLimitAuto' :
                    $yoyakuCustomersLimitAuto = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
            }
        }

        //------------------------------------------------------------------
        //check if yoyaku time for saturday and sunday is null
        //------------------------------------------------------------------
        if ($yoyakustart_satsun == "") {
            $yoyakustart_satsun = $yoyakustart;
        }
        if ($yoyakuend_satsun == "") {
            $yoyakuend_satsun = $yoyakuend;
        }
        //------------------------------------------------------------------
        // Use Store Settings
        //-----------------------------------------------------------------------------------
        $dayOfWeek = date('l', strtotime($sel_date));
        //-----------------------------------------------------------------------------------
        if (strtolower($dayOfWeek) == "saturday" || strtolower($dayOfWeek) == "sunday") {
            //-------------------------------------------------------------------------------
            $tmp_start = $yoyakustart_satsun;
            //-------------------------------------------------------------------------------
            $tmp_end = $yoyakuend_satsun;
            //-------------------------------------------------------------------------------
        } else {
            //-------------------------------------------------------------------------------
            $tmp_start = $yoyakustart;
            //-------------------------------------------------------------------------------
            $tmp_end = $yoyakuend;
            //-------------------------------------------------------------------------------
        }
        //-----------------------------------------------------------------------------------
        if ($kanzashiFlag){
            //Note that since Web yoyaku does not have support for Multiple Kanzashi Account, it will still get the data from the old table.
            //this should be change when Web Yoyaku can support multiple Kanzashi Account.
            $kanzashiDetails = $controller->MiscFunction->GetDailyKanzashiCustomersLimitFromOldTable($controller, $session_info['dbname'], $storecode, $sel_date);
            if ($kanzashiDetails) {

                $StoreOpenTime = new DateTime($kanzashiDetails[0]['begin_time']);
                $tmp_start = $StoreOpenTime->format('Hi');

                $StoreCloseTime = new DateTime($kanzashiDetails[count($kanzashiDetails)-1]['end_time']);
                $tmp_end = $StoreCloseTime->format('Hi');

                $start_time = intval(substr($tmp_start, 0, 2)) * 4;
                $start_min = intval(substr($tmp_start, 2, 2));
                $end_time = intval(substr($tmp_end, 0, 2)) * 4;
                $end_min = intval(substr($tmp_end, 2, 2));

                $start_time += ceil($start_min / 15);
                $end_time += floor($end_min / 15);


                $k = 0;
                for($i=$start_time;$i<$end_time;$i++){
                    $j = $i+1;
                    while($k<$j){
                        //Yoyaku Customer Limit per time Slot
                        $yoyakuCustomersLimitarr[$i] = intval($kanzashiDetails[$k]['limit_count']);
                        if($i == $j) {
                            $k++;
                            break;
                        }
                        $i++;
                    }
                }
            }
        }
        $start_time = intval(substr($tmp_start, 0, 2)) * 4;
        $start_min = intval(substr($tmp_start, 2, 2));
        $end_time = intval(substr($tmp_end, 0, 2)) * 4;
        $end_min = intval(substr($tmp_end, 2, 2));

        $start_time += ($start_min > 45) ? 4 :
                        (($start_min > 30) ? 3 :
                                (($start_min > 15) ? 2 :
                                        (($start_min > 0) ? 1 : 0)));

        $end_time += ($end_min >= 45) ? 3 :
                        (($end_min >= 30) ? 2 :
                                (($end_min >= 15) ? 1 : 0));

        $start_time_staff = $start_time;
        $end_time_staff = $end_time;


         //--------- START Starttime/Endtime (Shift) スタッフにシフト設定がある場合の開始・終了時間取得-------------------------------//
        $v = $controller->StaffShift->find('all', array(
            'conditions' => array('StaffShift.STORECODE'   => $session_info['storecode'],
                                'StaffShift.YMD'         => $sel_date,
                                'StaffShift.HOLIDAYTYPE' => 4,
                                'StaffShift.DELFLG IS NULL'),
            'fields' => array('Shift.STARTTIME','Shift.ENDTIME','StaffShift.STAFFCODE')  ));

        // 時間ごとの予約件数
        $time_arr_reserves_staff = array();

        for ($i = 0; $i < count($availableStaffs); $i++) {
            $itm = NULL;
            if (!empty($v)) {
                foreach ($v as $item) {
                    if($availableStaffs[$i]['StaffAssignToStore']['STAFFCODE'] == $item['StaffShift']['STAFFCODE']) {
                        $itm = $item;
                        break;
                    }
                }
            }

            //初期値設定
            $start_time = intval(substr($tmp_start, 0, 2)) * 4;
            $start_min = intval(substr($tmp_start, 2, 2));
            $end_time = intval(substr($tmp_end, 0, 2)) * 4;
            $end_min = intval(substr($tmp_end, 2, 2));

            if(!empty($itm)){
                // Use Shift Settings
                $tmp_start_shift = $itm['Shift']['STARTTIME'];
                $tmp_end_shift = $itm['Shift']['ENDTIME'];

                if (strtotime($tmp_start_shift) > strtotime($tmp_start)) {
                    $start_time = intval(substr($tmp_start_shift, 0, 2)) * 4;
                    $start_min = intval(substr($tmp_start_shift, 3, 2));
                }

                if (strtotime($tmp_end_shift) < strtotime($tmp_end)) {
                    $end_time = intval(substr($tmp_end_shift, 0, 2)) * 4;
                    $end_min = intval(substr($tmp_end_shift, 3, 2));
                }
            }
            $start_time += ($start_min > 45) ? 4 :
                    (($start_min > 30) ? 3 :
                            (($start_min > 15) ? 2 :
                                    (($start_min > 0) ? 1 : 0)));

            $end_time += ($end_min >= 45) ? 3 :
                    (($end_min >= 30) ? 2 :
                            (($end_min >= 15) ? 1 : 0));


            // Adjustment made if today is the day selected //
            if ($sel_date == date("Y-m-d")) {
                // If its today limit hours before current time //
                $offset = ($LowLimitOp == "hours") ? $LowLimit : 0;

                $earlistblock = (date("H") + $offset) * 4;
                $earlistblock += (date("i") > 45) ? 4 :
                        ((date("i") > 30) ? 3 :
                                ((date("i") > 15) ? 2 :
                                        ((date("i") > 0) ? 1 : 0)));
                if ($earlistblock > $start_time) {
                    $start_time = $earlistblock;
                }
            }

            if ($availableStaffs[$i]['StaffAssignToStore']['STAFFCODE'] == $session_info['y_staff']) {
                //選択したスタッフの開始、終了時間を保持
                $start_time_staff = $start_time;
                $end_time_staff = $end_time;
            }

            //シフト時間外は、スタッフが「稼働中」とみなす。
            $staffcode = $availableStaffs[$i]['StaffAssignToStore']['STAFFCODE'];

            //開始時間までを埋める
            for ($b = 0; $b < $start_time; $b++) {
                    $time_arr_reserves_staff[$staffcode][$b] = intval($availableStaffs[$i][0]['ROWS']);
            }
            //終了時間までを埋める
            for ($b = $end_time; $b <= (23*4) + 3 ; $b++) {
                    $time_arr_reserves_staff[$staffcode][$b] = intval($availableStaffs[$i][0]['ROWS']);
            }
        }

        // 出勤終わった時間をブロックする
        $time_arr[$end_time_staff] = 90;
        //--------- END Starttime/Endtime (Shift) ---------------------------------//

        //--------- START Break Times ---------------------------------------------//
        $v = $controller->BreakTime->find('all', array('conditions' => array(
                'STORECODE' => $session_info['storecode'],
                'DATE' => $sel_date
              )));

        $time_arr_reserves_staff_yoyaku = array();

        foreach ($v as $itm) {
            $break_start_b = intval(substr($itm['BreakTime']['STARTTIME'], 0, 2)) * 4;
            $start_min = intval(substr($itm['BreakTime']['STARTTIME'], 3, 2));
            $break_end_b = intval(substr($itm['BreakTime']['ENDTIME'], 0, 2)) * 4;
            $end_min = intval(substr($itm['BreakTime']['ENDTIME'], 3, 2));

            $break_start_b += ($start_min > 45) ? 4 :
                    (($start_min > 30) ? 3 :
                            (($start_min > 15) ? 2 :
                                    (($start_min > 0) ? 1 : 0)));

            $break_end_b += ($end_min == 0) ? -1 :
                    (($end_min <= 15) ? 0 :
                            (($end_min <= 30) ? 1 :
                                    (($end_min <= 45) ? 2 :
                                            (($end_min > 45) ? 3 : 0))));

            //対象者を同時間帯予約可能人数から外す、使用枠の計算
            for ($i = 0; $i < count($availableStaffs); $i++) {
                if($availableStaffs[$i]['StaffAssignToStore']['STAFFCODE'] == $itm['BreakTime']['STAFFCODE']){
                    $staffcode = $itm['BreakTime']['STAFFCODE'];
                    for ($b = $break_start_b; $b <= $break_end_b; $b++) {
                        if(intval($time_arr_reserves_staff[$staffcode][$b]) < intval($availableStaffs[$i][0]['ROWS'])){
                            $time_arr_reserves_staff[$staffcode][$b] +=1;//$v2[$i][0]['ROWS'];
                            $time_arr_reserves_staff_yoyaku[$staffcode][$b]+=1;
                        }
                    }
                }
                if ($itm['BreakTime']['STAFFCODE'] == $session_info['y_staff']) {
                        $time_arr[$b]+=1;
                }
            }
        }
        //--------- END Break Times -----------------------------------------------//


        // START --- Count existing transaction ----------------------------------//
        $query = "select transaction.TRANSCODE ,transaction.YOYAKUTIME,transaction.PRIORITYTYPE,details.STAFFCODE, details.STARTTIME,details.ENDTIME from store_transaction as transaction
                  LEFT JOIN store_transaction_details as details ON
                        transaction.TRANSCODE = details.TRANSCODE AND
                        transaction.KEYNO = details.KEYNO
                        where
                        transaction.storecode = ? and
                        transaction.transdate = ? and
                        details.TRANTYPE = 1 and
                        details.DELFLG is null and
                        transaction.DELFLG is null
                        and transaction.PRIORITYTYPE = 1 and
                        details.TRANTYPE = 1";

        $v = $controller->StoreTransactionDetails->query($query,array($session_info['storecode'],$sel_date));

        // 時間ごとの予約件数
        $time_arr_free = array();

        foreach ($v as $itm) {
            $trans_start = $itm['details']['STARTTIME'];
            if (strlen($trans_start) == 0) {
                $trans_start = $itm['transaction']['YOYAKUTIME'];
            }
            $trans_start_b = intval(substr($trans_start, 0, 2)) * 4;
            $start_min = intval(substr($trans_start, 3, 2));
            $trans_end_b = intval(substr($itm['details']['ENDTIME'], 0, 2)) * 4;
            $end_min = intval(substr($itm['details']['ENDTIME'], 3, 2));

            //切り捨てに変更
            $trans_start_b += ($start_min >= 45) ? 3 :
                    (($start_min >= 30) ? 2 :
                            (($start_min >= 15) ? 1 : 0
                            ));


            $trans_end_b += ($end_min == 0) ? -1 :
                    (($end_min <= 15) ? 0 :
                            (($end_min <= 30) ? 1 :
                                    (($end_min <= 45) ? 2 :
                                            (($end_min > 45) ? 3 : 0))));

            //予約者数をカウントする
            for ($i = 0; $i < count($availableStaffs); $i++) {
                if ($availableStaffs[$i]['StaffAssignToStore']['STAFFCODE'] == $itm['details']['STAFFCODE']) {
                    $staffcode = $itm['details']['STAFFCODE'];
                    for ($b = $trans_start_b; $b <= $trans_end_b; $b++) {
                        if($time_arr_reserves_staff[$staffcode][$b] < $availableStaffs[$i][0]['ROWS']){
                            $time_arr_reserves_staff[$staffcode][$b]+=1;
                            $time_arr_reserves_staff_yoyaku[$staffcode][$b]+=1; //予約者数のカウント！
                        }
                    }
                    if ($itm['details']['STAFFCODE'] == $session_info['y_staff']) {
                        $time_arr[$b]+=1;
                    }
                    if($itm['details']['STAFFCODE'] == 0)
                    {
                        $time_arr_free[$b] +=1;
                    }
                    break;
                }
            }
        }
        // END --- Count existing transaction ----------------------------------//

        $time_arr_reserves = array();
        $time_arr_reserves_yoyaku = array();
        $time_arr = array(); //・・・reset????

        //スタッフの各列の合計を算出 //スタッフの予約列数で制御する場合
        foreach($time_arr_reserves_staff as $key => $staff){
            for($i=0; $i <= (23*4)+3; $i++){
                if($key > 0){
                    $time_arr_reserves[$i] += intval($staff[$i]);
                }elseif($key == 0){
                    //枠数計算条件変更：20160301
                    $time_arr_reserves[$i] += intval($staff[$i]);
                }
                if($key == $session_info['y_staff']){
                    $time_arr[$i] += $staff[$i];
                }
            }
        }

        //予約者数の算出 //同時間帯最大予約人数
        foreach($time_arr_reserves_staff_yoyaku as $key => $staff){
            for($i=0; $i <= (23*4)+3; $i++){
                $time_arr_reserves_yoyaku[$i] += intval($staff[$i]);
            }
        }

        for ($i = $start_time_staff; $i < $end_time_staff; $i++) {
            $ok = true;
            $end_block = $i + (intval($servicetime / 15));
            if ($servicetime % 15 > 0) {
                $end_block++;
            }

            for ($j = $i; $j < $end_block; $j++) {
                if ($kanzashiFlag) {$yoyakuCustomersLimit = $yoyakuCustomersLimitarr[$j];}
                if(intval($time_arr[$j]) >= $staff_capacity) {
                    $ok = false;
                }
                elseif (intval($time_arr_reserves_yoyaku[$j]) >= $yoyakuCustomersLimit) {
                    //同時間帯最大予約人数
                      $ok = false;
                }
                elseif ($yoyakuCustomersLimitAuto == 1 && intval($time_arr_reserves[$j]) >= $total_staff_capacity) {
                     //スタッフの予約列数で制御する場合
                    $ok = false;
                }
            }

            if ($ok) {
                $hour = intval($i / 4);
                $min = ($i % 4) * 15;
                $end_hour = $hour + ($servicetime / 60);
                $end_min = $min + ($servicetime % 60);
                if ($end_min >= 60) {
                    $end_hour++;
                    $end_min -= 60;
                }

                $timeKey = sprintf("%02d%02d", $hour, $min);

                if ($yoyakuShowMenuNameOnly != 1) {
                    $AvailableTimes[$timeKey] = sprintf("%02d:%02d (%02d:%02dまで)", $hour, $min, $end_hour, $end_min);
                } else {
                    $AvailableTimes[$timeKey] = sprintf("%02d:%02d", $hour, $min);
                }
            }
        }

        return $AvailableTimes;
    }

    /**
     * Summary of GetAvailableStaffDetails
     * @param mixed $controller
     * @param mixed $storecode
     * @param mixed $date
     * @return mixed
     */
    private function GetAvailableStaffDetails(&$controller, $storecode, $date, $kanzashiFlag){

        $staffRowsHistoryCond = $kanzashiFlag ? "" : "AND StaffRowsHistory.DATECHANGE <= '{$date}'";

        $sql = "
            SELECT
                Staff.STORECODE,
                StaffAssignToStore.STAFFCODE,
                Staff.STAFFNAME,
                Store.STORENAME,
                IF(Staff.STORECODE = {$storecode},
                    StaffAssignToStore.WEBYAN_DISPLAY,
                    IF(Staff.STAFFCODE = 0,
                        StaffAssignToStore.WEBYAN_DISPLAY,
                        1)
                ) as WEBYAN_DISPLAY,
                IF(StaffRowsHistory.ROWS > 0,StaffRowsHistory.ROWS , ".DEFAULT_ROWS.") as ROWS
            FROM staff_assign_to_store as StaffAssignToStore
            LEFT JOIN staff as Staff
                ON StaffAssignToStore.STAFFCODE = Staff.STAFFCODE
            LEFT JOIN store as Store
                ON Staff.STORECODE = Store.STORECODE
            LEFT JOIN (
                SELECT *
                FROM (
                    SELECT *
                    FROM staffrowshistory as StaffRowsHistory
                    WHERE StaffRowsHistory.STORECODE = {$storecode}
                        {$staffRowsHistoryCond}
                    ORDER BY StaffRowsHistory.DATECHANGE DESC
                ) as TMPTBL
                GROUP BY staffcode
            ) as StaffRowsHistory
                ON StaffRowsHistory.STAFFCODE = Staff.STAFFCODE
            LEFT JOIN (
                SELECT
                    STAFFCODE,
                    YMD,
                    MIN(HOLIDAYTYPE) as HOLIDAYTYPE,
                    BIKOU,
                    UPDATEDATE,
                    DELFLG,
                    UPDATE_INDEX,
                    STORECODE,
                    SHIFT
                FROM staff_holiday
                WHERE YMD = '{$date}'
                    AND storecode = {$storecode}
                    AND staff_holiday.delflg is null
                GROUP BY storecode, YMD, staffcode
            ) as Holiday
                ON Holiday.STAFFCODE = Staff.STAFFCODE
                AND Holiday.YMD = '{$date}'
            WHERE StaffAssignToStore.STORECODE = {$storecode}
            AND (Staff.STORECODE = {$storecode} OR (Staff.STORECODE = 0 AND Staff.Staffcode = 0))
            AND StaffAssignToStore.ASSIGN_YOYAKU = 1
            AND Staff.DELFLG IS NULL
            AND (Staff.HIREDATE IS NULL OR Staff.HIREDATE <= '{$date}')
            AND (Staff.RETIREDATE IS NULL OR Staff.RETIREDATE >= '{$date}')
            AND NOT (
                COALESCE(Holiday.HOLIDAYTYPE, 4) < 4
                AND Holiday.DELFLG IS NULL
            )
            GROUP BY StaffAssignToStore.STAFFCODE
            HAVING WEBYAN_DISPLAY > 0 AND ROWS > 0";

        return $controller->StaffAssignToStore->query($sql);
    }

    /**
     * カレンダーの情報を準備する
     * Creates an Array with Selected Calendar values
     *
     * @param controller &$controller
     * @param int $staffcode
     * @param int $storecode
     * @param string $dbname
     * @param int $month
     * @param int $year
     * @param int $LowLimit
     * @param string $LowLimitOp
     * @param int $UpLimit
     * @param string $UpLimitOp
     * @return Array
     */
    function BuildCalendarMonth(&$controller, $staffcode, $storecode, $dbname, $month, $year, $LowLimit, $LowLimitOp, $UpLimit, $UpLimitOp) {

        $holiday_list = array();
        $jp_days_of_the_week = array('日','月','火','水','木','金','土');
        $yearmonthstr = sprintf("%4d%02d",$year,$month);
        $first_day_of_week = date("w", mktime(0, 0, 0, $month, 1, $year));
        $last_day_of_month = date("t", mktime(0, 0, 0, $month, 1, $year));

        if($month == date("n") && $year == date("Y")) {
            $today = date("j");
        }
        else {
            $today = 0;
        }

        $hour_offset = ($LowLimitOp == "hours") ? $LowLimit : 0;
        $day_offset  = ($LowLimitOp == "days") ? $LowLimit : 0;
        $firstdate_tmp = mktime((date("H")+$hour_offset),0,0,date("m"),(date("d")+$day_offset),date("Y"));
        $firstdate = mktime(0,0,0,date("m", $firstdate_tmp),date("d",$firstdate_tmp),date("Y", $firstdate_tmp));

        $months_offset = ($UpLimitOp == "months")?$UpLimit:0;
        $day_offsetup  = ($UpLimitOp == "days") ? $UpLimit : 0;
        $lastdate = mktime(date("H"),0,0,(date("m")+$months_offset),(date("d")+$day_offsetup),date("Y"));

        $controller->StaffShift->set_company_database($dbname, $controller->StaffShift);
        $controller->Shift->set_company_database($dbname, $controller->Shift);
        $controller->StoreHoliday->set_company_database($dbname, $controller->StoreHoliday);

        $YMD_str = sprintf("(YMD >= '%d-%02d-01' AND YMD <= '%d-%02d-%02d')",$year,$month,$year,$month,$last_day_of_month);


        $h = $controller->StoreHoliday->find('all', array(
                 'conditions' => array('STORECODE'   => $storecode,
        $YMD_str,
                                       'DELFLG IS NULL')   ));
        foreach($h as $itm) {
            $holiday_list[$itm['StoreHoliday']['YMD']] = 1;
        }

        if($staffcode > 0) { // Exclude Free Staff

            $finished_shift = $this->GetFinishedShift($controller, $storecode, $month, $year, $dbname);

            if($finished_shift == 1) {
                $v = $controller->StaffShift->find('all', array(
                        'conditions' => array('StaffShift.STORECODE'   => $storecode,
                                            'StaffShift.STAFFCODE'   => $staffcode,
                $YMD_str,
                                            'StaffShift.DELFLG IS NULL')   ));

                foreach($v as $itm) {
                    $ymd   = $itm['StaffShift']['YMD'];

                    $htype = $itm['StaffShift']['HOLIDAYTYPE'];
                    if($htype == 4) {
                        //$holiday_list[$ymd] = 0; // Working
                        if( isset($holiday_list[$ymd]) == false) {
	                    	$holiday_list[$ymd] = 0; // Working
                        }else{
                            $holiday_list[$ymd] = 1; // Holiday
                        }
                    }
                    else {
                        $holiday_list[$ymd] = 1; // Holiday
                    }
                }
            }
        }
        else {
            $finished_shift = 1;
        }

        $month_data = array();
        for($w = 0; $w <= 6 ; $w++) {
            $week_data = array();
            if((($w * 7) - ($first_day_of_week-1)) <= $last_day_of_month &&
            ($w * 7) + 7 > $today) {

                for($d = 0 ; $d < 7 ; $d++) {
                    $n = $d + ($w * 7) - ($first_day_of_week-1);

                    $cur_date = mktime(0,0,0,$month,$n,$year);

                    if($n < 0 || $n > $last_day_of_month) {
                        $week_data[] = array(0, ""); // Blank Calendar Day
                    }
                    else if($finished_shift != 1   ||
                    $cur_date < $firstdate ||
                    $cur_date > $lastdate  ||
                    $holiday_list[sprintf("%4d-%02d-%02d", $year, $month, $n)] == 1) {
                        $week_data[] = array($n, ""); // Gray Calendar Day
                    }
                    else {
                        $day = sprintf("%02d",$n);
                        $week_data[] = array($n, $yearmonthstr.$day, $n."日（".$jp_days_of_the_week[$d]."）");
                    }
                }
                $month_data[] = $week_data;

            }
        }
        return $month_data;

    }

    /**
     * 前回と次回のtransactionデータを読み込む beauty no use...
     * Gets data for next and previous transactions
     *
     * @param controller &$controller
     * @param array $session_info
     * @return Array
     */
    function GetPrevNextTransactions(&$controller, $session_info) {

        $today = date("Y-m-d");
        $arrReturn = array();
        $storename_prev = "";
        $storename_next = "";
        $dbname = $session_info['$dbname'];
        $controller->StoreTransaction->set_company_database($session_info['$dbname'], $controller->StoreTransaction);
        $controller->Store->set_company_database($dbname, $controller->Store);

        //-- 前回  Previuos Transaction --//
        $v = $controller->StoreTransaction->find('all',
        array( 'conditions' => array(
                                    'CCODE' => $session_info['ccode'],
                                    "TRANSDATE <= '".$today."'",
                                    'TEMPSTATUS < 4',
                                    'DELFLG IS NULL'),
                                'order' => 'TRANSDATE DESC',
                                'limit' => 1 ) );
        if(!empty($v)) {
            $staffname = $this->GetStaff($controller,
            $v[0]['StoreTransaction']['STAFFCODE'],
            $session_info['dbname']);
            if(strlen($staffname) == 0) { $staffname = "指名なし"; }
            $date = substr($v[0]['StoreTransaction']['TRANSDATE'],0,4)."年".
            substr($v[0]['StoreTransaction']['TRANSDATE'],5,2)."月".
            substr($v[0]['StoreTransaction']['TRANSDATE'],8,2)."日";

            $storecode = $v[0]['StoreTransaction']['STORECODE'];

            if(intval($storecode) != intval($session_info['storecode'])) {
                $st = $controller->Store->find('all', array(
                   'conditions' => array('Store.storecode' => $storecode)));
                $storename_prev = $st[0]['Store']['STORENAME'];
            }

            $arrReturn['prevtrans'] = array("date" => $date,
                                            "staff" => $staffname,
                                            "storename" => $storename_prev);
        }

        //-- 次回  Next Transaction --//
        $v = $controller->StoreTransaction->find('all',
        array( 'conditions' => array(
                                    'CCODE' => $session_info['ccode'],
                                    "TRANSDATE >= '".$today."'",
                                    'YOYAKU >= 1',
                                    'TEMPSTATUS >= 4',
                                    'DELFLG IS NULL'),
                                'order' => 'TRANSDATE ASC',
                               'limit' => 1 ) );

        if(!empty($v)) {
            $staffname = $this->GetStaff($controller,
            $v[0]['StoreTransaction']['STAFFCODE'],
            $session_info['dbname']);
            if(strlen($staffname) == 0) { $staffname = "指名なし"; }
            $date = substr($v[0]['StoreTransaction']['TRANSDATE'],0,4)."年".
            substr($v[0]['StoreTransaction']['TRANSDATE'],5,2)."月".
            substr($v[0]['StoreTransaction']['TRANSDATE'],8,2)."日";
            if(strlen($v[0]['StoreTransaction']['STARTTIME']) > 0) {
                $time = substr($v[0]['StoreTransaction']['STARTTIME'],0,5);
            }
            else {
                $time = substr($v[0]['StoreTransaction']['YOYAKUTIME'],0,5);
            }
            $endtime = substr($v[0]['StoreTransaction']['ENDTIME'],0,5);
            $transcode = $v[0]['StoreTransaction']['TRANSCODE'];
            $keyno     = $v[0]['StoreTransaction']['KEYNO'];
            $storecode = $v[0]['StoreTransaction']['STORECODE'];

            if($storecode != $session_info['storecode']) {
                $st = $controller->Store->find('all', array(
                   'conditions' => array('Store.storecode' => $storecode)));
                $storename_next = $st[0]['Store']['STORENAME'];
            }

            $arrReturn['nexttrans'] = array("date"      => $date,
                                            "staff"     => $staffname,
                                            "time"      => $time,
                                            "endtime"   => $endtime,
                                            "transcode" => $transcode,
                                            "keyno"     => $keyno,
                                            "storename" => $storename_next);
        }
        return $arrReturn;
    }

     /**
     * 次回以降のすべての予約データを読み込む。GetPrevNextTransactionsの代替
     * Gets data for next and previous transactions
     *
     * @param controller &$controller
     * @param array $session_info
     * @return Array
     */
    function GetNextTransactions(&$controller, $session_info, $cancel_limit=0)
    {
            $arrReturn = array();
            $controller->StoreTransaction->set_company_database($session_info['dbname'], $controller->StoreTransaction);
            $controller->Store->set_company_database($session_info['dbname'], $controller->Store);
            $sql = "select store_transaction.TRANSCODE,store_transaction.STORECODE,store_transaction.STAFFCODE, store_transaction.KEYNO,store_transaction.TRANSDATE, store_transaction.ENDTIME,
                store_transaction.YOYAKUTIME,GROUP_CONCAT(DISTINCT servicessys.DESCRIPTION separator '、') as servicessys,count(DISTINCT servicessys.DESCRIPTION) as scount
                      from store_transaction
                      left join store_transaction_details on
                      store_transaction_details.TRANSCODE = store_transaction.TRANSCODE and
                      store_transaction_details.KEYNO = store_transaction.KEYNO
                      left join store_services on store_services.GCODE = store_transaction_details.GCODE
                      left join services on store_services.GDCODE = services.GDCODE
		     left join  servicessys on services.SYSCODE= servicessys.SYSCODE
                      where
                      ccode = ? and
                      store_transaction.TRANSDATE >= CURRENT_DATE() and
                      store_transaction.YOYAKU >= 1 and
                      store_transaction.TEMPSTATUS >= 4 and
                      store_transaction.delflg is null and
                      store_transaction_details.delflg is null
                      group by store_transaction.TRANSCODE
                      order by store_transaction.TRANSDATE
";

            //SQLを実行
            $v = $controller->StoreTransaction->query($sql,array($session_info['ccode']),false);

            $ret = 0;
            if(!empty($v)) {
                foreach($v as $itm) {

                $staffname = $this->GetStaff($controller,
                    $itm['store_transaction']['STAFFCODE'],
                    $session_info['dbname']
                );

                if(strlen($staffname) == 0) { $staffname = "指名なし"; }
                $date = substr($itm['store_transaction']['TRANSDATE'],0,4)."年".
                substr($itm['store_transaction']['TRANSDATE'],5,2)."月".
                substr($itm['store_transaction']['TRANSDATE'],8,2)."日";
                if(strlen($itm['store_transaction']['STARTTIME']) > 0) {
                    $time = substr($itm['store_transaction']['STARTTIME'],0,5);
                }
                else {
                    $time = substr($itm['store_transaction']['YOYAKUTIME'],0,5);
                }
                $endtime = substr($itm['store_transaction']['ENDTIME'],0,5);
                $transcode = $itm['store_transaction']['TRANSCODE'];
                $keyno     = $itm['store_transaction']['KEYNO'];
                $servicessys = $itm[0]['servicessys'];
                $transdate = $itm['store_transaction']['TRANSDATE'];

                $storecode = $itm['store_transaction']['STORECODE'];
                if($storecode != $session_info['storecode']) {
                    $st = $controller->Store->find('all', array(
                    'conditions' => array('Store.storecode' => $storecode)));
                    $storename_next = $st[0]['Store']['STORENAME'];
                }

                $is_cancelb = true;
                if($cancel_limit > 0)
                    {

                    if($transdate == date("Y-m-d") && $cancel_limit > 0) {
                        $hours_until_yoyaku = (strtotime($time) - time()) / (60 * 60);
                        if($hours_until_yoyaku < $cancel_limit) {
                            $is_cancelb = false;
                            }
                    }
               }

                $arrReturn['nexttrans'][$ret] = array("date"      => $date,
                                        "transdate" => $transdate,
                                        "staff"     => $staffname,
                                        "time"      => $time,
                                        "endtime"   => $endtime,
                                        "transcode" => $transcode,
                                        "keyno"     => $keyno,
                                        "servicessys"=> $servicessys,
                                        'servicessys_count' => $servicessys_count,
                                        "storename" => $storename_next,
                                        "canselb" => $is_cancelb);

                $arrReturn['service_count'] += $itm[0]['scount'];

                $ret++;

            }
        }
        return $arrReturn;

    }


     /**
     * 今日以降（今日含む）のある業種の予約の存在をチェックする。
     * Gets data for next and previous transactions
     *
     * @param controller &$controller
     * @param array $session_info
     * @param int $syscode
     * @return boolean true.. exists
     */
    function checkNextTransaction(&$controller, $session_info) {
        //$today = date("Y-m-d");
        //$arrReturn = array();
        $controller->StoreTransaction->set_company_database($session_info['dbname'], $controller->StoreTransaction);

        $sql = "select store_transaction.transcode
                      from store_transaction
                      left join store_transaction_details on
                      store_transaction_details.TRANSCODE = store_transaction.TRANSCODE and
                      store_transaction_details.KEYNO = store_transaction.KEYNO
                      left join store_services on store_services.GCODE = store_transaction_details.GCODE
                      left join services on store_services.GDCODE = services.GDCODE
                      where
                      ccode = ? and
                      store_transaction.TRANSDATE >= CURRENT_DATE() and
                      store_transaction.YOYAKU >= 1 and
                      store_transaction.TEMPSTATUS >= 4 and
                      store_transaction.delflg is null and
                      store_transaction_details.delflg is null and
                      store_transaction_details.trantype = 1 and
                      services.syscode = ?
                      group by services.syscode";



        //SQLを実行
        $retRecords = $controller->StoreTransaction->query($sql,array($session_info['ccode'],$session_info['syscode']),false);

        //取得可否をreturn
        if (count($retRecords) == 0) {
            return false;
        }else{
            return true;
        }
    }

     /**
     * 指定した日の、予約の存在をチェックする。
     * Gets data for next and previous transactions
     *
     * @param controller &$controller
     * @param string $date
     * @return array transaction
     */
    function checkDateTransaction(&$controller, $session_info, $date="" , $syscode = "") {

        //2012-12-15 add ..no use syscode
        $syscode = -1;

        //$today = date("Y-m-d");
        //$arrReturn = array();
        $controller->StoreTransaction->set_company_database($session_info['dbname'], $controller->StoreTransaction);

        $sql = "select store_transaction.TRANSDATE, store_transaction.YOYAKUTIME, store_transaction.ENDTIME, store_transaction.TRANSCODE, store_transaction.KEYNO
                      from store_transaction
                      left join store_transaction_details on
                      store_transaction_details.TRANSCODE = store_transaction.TRANSCODE and
                      store_transaction_details.KEYNO = store_transaction.KEYNO
                      left join store_services on store_services.GCODE = store_transaction_details.GCODE
                      left join services on store_services.GDCODE = services.GDCODE
                      where
                      ccode = ? and
                      store_transaction.TRANSDATE = ? and
                      store_transaction.YOYAKU >= 1 and
                      store_transaction.TEMPSTATUS >= 4 and
                      store_transaction.delflg is null and
                      store_transaction_details.delflg is null and
                      store_transaction_details.trantype = 1 and
                      services.syscode <> ?
                      group by services.syscode";

        if($date == ""){
            $date = $session_info['y_date'];
        }
        if($syscode == "")
        {
            $syscode = $session_info['syscode'];
        }

        $v = $controller->StoreTransaction->query($sql,array($session_info['ccode'],$date,$syscode),false);

        $date = $v[0]['store_transaction']['TRANSDATE'];
        $endtime = substr($v[0]['store_transaction']['ENDTIME'],0,5);
        $transcode = $v[0]['store_transaction']['TRANSCODE'];
        $keyno     = $v[0]['store_transaction']['KEYNO'];
        $yoyakutime = substr($v[0]['store_transaction']['YOYAKUTIME'],0,5);
        //$storecode = $v[0]['StoreTransaction']['STORECODE'];
        $arrReturn = array("date"      => $date,
                                        "yoyakutime" => $yoyakutime,
                                        "endtime"   => $endtime,
                                        "transcode" => $transcode,
                                        "keyno"     => $keyno);

        //取得可否をreturn
        if (count($v) == 0) {
            return false;
        }else{
            return $arrReturn;
        }
    }

     /**
     * 指定した日の、予約の存在をチェックする。
     * Gets data for next and previous transactions
     *
     * @param controller &$controller
     * @param string $date
     * @return array transaction
     */
    function getTransactionDetail(&$controller, $session_info, $date="") {
        //check $session_info
        if(!isset($session_info)){return false;}

        $controller->StoreTransactionDetails->set_company_database($session_info['dbname'], $controller->StoreTransaction);
        if($date == ""){
            $date = $session_info['y_date'];
        }

        $query = "select store_transaction.TRANSDATE, store_transaction.YOYAKUTIME, store_transaction.ENDTIME, store_transaction.TRANSCODE, store_transaction.KEYNO ,
            store_transaction_details.
                      from store_transaction
                      left join store_transaction_details on
                      store_transaction_details.TRANSCODE = store_transaction.TRANSCODE and
                      store_transaction_details.KEYNO = store_transaction.KEYNO
                      left join store_services on store_services.GCODE = store_transaction_details.GCODE
                      left join services on store_services.GDCODE = services.GDCODE
                      where
                      ccode = ? and
                      store_transaction.TRANSDATE = ? and
                      store_transaction.YOYAKU >= 1 and
                      store_transaction.TEMPSTATUS >= 4 and
                      store_transaction.delflg is null and
                      store_transaction_details.delflg is null and
                      store_transaction_details.trantype = 1
                      group by services.syscode";


    }

    /**
     * transactionをキャンセールする
     * Cancels a transaction
     *
     * @param controller &$controller
     * @param string $transcode
     * @param int $keyno
     * @param string $dbname
     * @return Boolean
     */
    function CancelYoyaku(&$controller, $transcode, $keyno, $dbname) {
        //POPUPメッセージの追加
        $controller->StoreTransaction->set_company_database($dbname, $controller->StoreTransaction);
        $controller->YoyakuMessage->set_company_database($dbname, $controller->YoyakuMessage);
        $trans = $controller->StoreTransaction->find('all',
        array( 'conditions' => array(
                                    'TRANSCODE' => $transcode,
                                    'KEYNO' => $keyno,
                                    'YOYAKU >= 1',
                                    'TEMPSTATUS >= 4',
                                    'DELFLG IS NULL'),
                                'order' => 'TRANSDATE ASC',
                               'limit' => 1 ) );
        if(!empty($trans)) {

            $controller->StoreSettings->set_company_database($dbname, $controller->StoreSettings);
            $option = $controller->StoreSettings->find('all',array( 'conditions' => array('STORECODE'  => $trans[0]['StoreTransaction']['STORECODE'],
                                                                                        'OPTIONNAME' => 'YOYAKU_MSG'),
                                                                    'order'      => 'STORECODE ASC',
                                                                        'limit'      => 1 ) );
            if(!empty($option)) {
                if($option[0]['StoreSettings']['OPTIONVALUEI']>0) {
                    $staffname = $this->GetStaff($controller,$trans[0]['StoreTransaction']['STAFFCODE'],$dbname);
                    $controller->YoyakuMessage->set('STORECODE',      $trans[0]['StoreTransaction']['STORECODE']);
                    $controller->YoyakuMessage->set('CNAME',          $trans[0]['StoreTransaction']['CNAME']);
                    $controller->YoyakuMessage->set('YOYAKUDATETIME', $trans[0]['StoreTransaction']['TRANSDATE'].' '.$trans[0]['StoreTransaction']['YOYAKUTIME']);
                    $controller->YoyakuMessage->set('STAFFNAME',      $staffname);
                    $controller->YoyakuMessage->set('MSG',            '予約がキャンセルされました。');
                    $controller->YoyakuMessage->save();
                }
            }

        }
        //予約の削除
        $controller->StoreTransaction->query("UPDATE store_transaction SET DELFLG = NOW() WHERE TRANSCODE = '{$transcode}' AND KEYNO = {$keyno}");
        $controller->StoreTransaction->query("UPDATE store_transaction_details SET DELFLG = NOW() WHERE TRANSCODE = '{$transcode}' AND KEYNO = {$keyno}");

        // 予約詳細テーブルにキャンセルフラグを挿入
        $controller->StoreTransaction->query("INSERT INTO yoyaku_details(TRANSCODE, CANCEL) VALUES('{$transcode}', 1) ON DUPLICATE KEY UPDATE CANCEL = 1");

        // 次回予約テーブルに反映
        $controller->StoreTransaction->query("UPDATE yoyaku_next SET CHANGEFLG = 2,YOYAKU_STATUS = 0 WHERE NEXTCODE = '{$transcode}'");

        // Update by: MarvinC - 2016-01-04 11:34
        $controller->StoreTransaction->query("UPDATE yoyaku_next_details SET CHANGEFLG = 2,YOYAKU_STATUS = 0 WHERE NEXTCODE = '{$transcode}'");

        return true;
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
        $controller->LogSessionKeitai->delete($session_no);
        return true;
    }

    /**
     * mb_string to int string
     *
     * @param string $str
     * @return string
     */
    function Numeric($str) {
        $retstr = "";

        $str_count = mb_strlen($str);
        for($i=0;$i<$str_count;$i++) {
            switch (mb_substr($str,$i,1)) {
                case '0': case '０':
                    $retstr .= '0';
                    break;
                case '1': case '１':
                    $retstr .= '1';
                    break;
                case '2': case '２':
                    $retstr .= '2';
                    break;
                case '3': case '３':
                    $retstr .= '3';
                    break;
                case '4': case '４':
                    $retstr .= '4';
                    break;
                case '5': case '５':
                    $retstr .= '5';
                    break;
                case '6': case '６':
                    $retstr .= '6';
                    break;
                case '7': case '７':
                    $retstr .= '7';
                    break;
                case '8': case '８':
                    $retstr .= '8';
                    break;
                case '9': case '９':
                    $retstr .= '9';
                    break;
            }
        }
        return $retstr;
    }

    /**
     * シフト情報が登録したかどうか確認する
     * verify if shift information has be registered
     *
     * @param controller &$controller
     * @param int $storecode
     * @param int $month
     * @param int $year
     * @param string $dbname
     * @return int
     */
    function GetFinishedShift(&$controller,$storecode, $month, $year, $dbname) {
        $controller->FinishedShift->set_company_database($dbname, $controller->FinishedShift);
        $fs = $controller->FinishedShift->find('all', array(
                        'conditions' => array('storecode'  => $storecode,
                                              'year'       => $year,
                                              'month'      => $month),
                        'fields' => array('complete')  ));

        return intval($fs[0]['FinishedShift']['complete']);

    }

    /**
     * スタッフの写真データを取得する
     *
     * @param $controller 使用するコントローラ
     * @param $dbName 対象となるDBの名前
     * @param $staffCode 検索対象となるスタッフコード
     * @return 取得したスタッフの写真データ
     */
    function GetStaffShashin(&$controller, $dbName, $staffCode) {
        if (is_null($controller) || is_null($dbName) || is_null($staffCode)) { return null; }

        $controller->Staff->set_company_database($dbName, $controller->Staff);
        $conditions = "STAFFCODE = " . $staffCode;
        $result = $controller->Staff->find($conditions, "SHASHIN", null, -1);
        return $result["Staff"]["SHASHIN"];
    }

    /**
     * 解約する
     *
     * @param $controller 使用するコントローラ
     * @param $dbName 対象となるDBの名前
     * @param $ccode 対象となる顧客コード
     */
    function UnregisterCustomer(&$controller, $dbName, $ccode) {
        if (is_null($controller) || is_null($dbName) || is_null($ccode)) { return null; }

        $controller->Customer->set_company_database($dbName, $controller->Customer);
        //$controller->Customer->query("UPDATE customer SET DELFLG = NOW() WHERE CCODE = '{$ccode}'");
        $controller->Customer->query("UPDATE customer SET mailkubun = 0 WHERE CCODE = '{$ccode}'");
    }

    /**
     *
     * アクセスログを記録する
     *
     * @param $controller 使用するコントローラ
     * @param $dbName 対象となるDBの名前
     * @param $ccode 対象となる顧客コード
     */
    function SetAccesslog(&$controller, $dbName,$sessionid,$ccode,$storecode,$windowname,$status ,$reg_status = 0,$user_fromcode = 0) {
        if (is_null($controller) || is_null($dbName) || is_null($ccode)) { return null; }
        //試験的にDIVAのみに導入
        if($dbName == "sipssb_diva" || $dbName == "sipssb_think" || $dbName == "sipssb_remi"){

            if($sessionid == ""){
                $sessionid = "new_".$dbName.md5(mt_rand());
            }
            $x_remote_addr = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : "0.0.0.0";
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $referer = $_SERVER['HTTP_REFERER'];

            $query = "insert into log_mobile(ymd,sessionid ,storecode,ccode, client_ip, ua,referer,lastpagename,status,reg_status,user_fromcode)
                values(
                     CURRENT_DATE(),
                     '{$sessionid}',
                     '{$storecode}',
                     '{$ccode}',
                     '{$x_remote_addr}',
                     '{$user_agent}',
                     '{$referer}',
                     '{$windowname}',
                     {$status},
                     {$reg_status},
                     {$user_fromcode}
                     ) ON DUPLICATE KEY UPDATE ccode = '{$ccode}',
                     client_ip = '{$x_remote_addr}',
                     ua = '{$user_agent}',
                     referer = '{$referer}'
                     , lastpagename = '{$windowname}',
                     status = IF(status > VALUES(status), status, VALUES(status)),
                     reg_status = IF(reg_status > VALUES(reg_status), reg_status,VALUES(reg_status)),
                     user_fromcode = IF(user_fromcode is null, VALUES(user_fromcode),user_fromcode)
                     ";

            $controller->Customer->set_company_database($dbName, $controller->Customer);
            $controller->Customer->query($query);
        }
    }

    /**
     * Summary of GetKanzashiSalons
     * @param mixed $controller
     * @param mixed $company
     * @param mixed $storecode
     * @return array
     */
    function GetKanzashiSalons(&$controller, $company, $storecode)
    {
        $sql = "
            SELECT 
                kanzashi_type,
                kanzashi_id
            FROM sipssbeauty_kanzashi.salon
            WHERE
                status IN (5, 6, 7, 8, 9, 10, 11, 101, 102) AND
                companyid = ? AND
                storecode = ?
        ";
        $param = array($company, $storecode);
        return $controller->Store->query($sql, $param, false);
    }

    function SaveCustomerSns(&$controller, $session_info, $snsdata, $ccode){
        $controller->Customer->set_company_database($session_info['dbname'], $controller->Customer);

        $sql = "INSERT INTO customer_sns (storecode, oauth_provider, oauth_uid, ccode, date_created)
                VALUES (:storecode, :oauth_provider, :oauth_uid, :ccode, :date_created)";
        $params = array(
                        "storecode"         => $session_info['storecode'],
                        "oauth_uid"         => $snsdata['snsid'],
                        "oauth_provider"    => $snsdata['provider'],
                        "ccode"             => $ccode,
                        "date_created"      => date("Y-m-d H:i:s")
                        );

        $controller->Customer->query($sql, $params, false);

    }

}
