<?php
/*
 * ###    ###        ##     ## ##             ###### ##          ##   ##
 * ####  ####  ####  ##        ##  ####     ##       ##   ####   ##       ####  ## ##
 * ## #### ## ##  ## #####  ## ## ##  ##     ###    #####    ## ##### ## ##  ## ### ##
 * ##  ##  ## ##  ## ##  ## ## ## #####        ###   ##   #####  ##   ## ##  ## ##  ##
 * ##      ## ##  ## ##  ## ## ## ##              ## ##  ##  ##  ##   ## ##  ## ##  ##
 * ##      ##  ####  #####  ## ##  #####    ######    ### ### ##  ### ##  ####  ##  ##
 *
 * もばすて Copyright(c) 2010 株式会社シンク All Rights Reserved.
 * http://www.think-ahead.jp/
 * http://www.mobilestation.jp/
 *
 * サポート:  R.Eugenio [ross@think-ahead.jp]
 *          T.Springer [toddspringer@think-ahead.jp]
 *
 */

class YkController extends AppController {
    var $name = 'Yk';
    var $uses = array(
            'LogSessionKeitai',
            'Customer',
            'Company',
            'Store',
            'Staff',
            'ServiceList',
            'StoreService',
            'StoreSettings',
            'StaffShift',
            'Shift',
            'BreakTime',
            'StoreHoliday',
            'StoreTransaction',
            'StoreTransactionDetails',
            'StaffRowsHistory',
            'StaffAssignToStore',
            'FinishedShift',
            'StoreAccount',
            'WebyanAccount',
            'CustomerTotal',
            'Position',
            'DataShare',
            'YoyakuMessage',
            'YoyakuStaffServiceTime',
            'Storetype',
            'Stafftype',
            'Syscode', //Added by MarvinC 2015-07-03
            'MobasuteStoreInfo'
            );
    var $components = array(
    'KeitaiSession',
    'RequestHandler',
    'MiscFunction',
    'Email',
    'Cookie'
    );
    var $output_encoding = 'UTF-8';

     /**
     * redirect page "index"
     *
     */
    function index($sessionid = "") {

        if ($sessionid != "") {
            $this->redirect('/yk/mypage/0/0/'.$sessionid);
            exit();
        }
        else {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }
    }

    /**
     * redirect page "i"
     *
     */
    function i($sessionid = "") {
        if ($sessionid != "") {
            $this->redirect('/yk/mypage/0/0/'.$sessionid);
            exit();
        }
        else {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }
    }

    /**
     * Myページスクリプト、予約情報とポイントとメニューを表示する
     * My Page script, provides current yoyaku info, points, and customer menu
     *
     * @param int $companyid
     * @param int $storecode
     * @param string $sessionid
     */
    function mypage($companyid = 0, $storecode = 0, $sessionid = "", $action_option = "") {

        if ($sessionid == "") {
            $this->redirect('/yk/login/'.$companyid.'/'.$storecode);
            exit();
        }
        //haben temporary redirect
        if($companyid == 23 && $storecode != 29)
        {
            $habenurl = "http://bit.ly/web-yoyaku";
            $this->redirect($habenurl, 302, true);
            exit();
        }

        if($this->params['form']['p_new']) {
            $this->_redirect('/yk/new0/'.$sessionid);
            exit();
        }
        //予約追加
        if($this->params['form']['p_add']) {
            $this->_redirect('/yk/new0/'.$sessionid);
            exit();
        }
        if($this->params['form']['p_info']) {
            $this->_redirect('/yk/reg/'.$sessionid);
            exit();
        }

        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);

        if ($session_info == false) {
            $this->redirect('/yk/login/'.$companyid.'/'.$storecode.'/'.$sessionid.'/'.'401');
            exit();
        }

        if($session_info['ccode'] == 0) {
            $this->_redirect('/yk/reg/'.$sessionid);
            exit();
        }

        if($this->params['form']['p_logout'] || $action_option == "logout") {
            $this->KeitaiSession->Destroy($this, $session_info['session_no']);
            $this->Cookie->destroy();
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode']);
            exit();
        }

        // Check if the Session is expired (30 minutes)
        $sec_since_last_activity  = time() - strtotime($session_info['last_activity']);
        if($sec_since_last_activity > SESSION_EXPIRATION_MIN * 60) {
            //Session Expired
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $session_info['companyid'],
        $session_info['storecode'],
        $session_info['dbname']);
        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        // p_delete[date]の分解
        $delete_date = "";
        foreach ($this->params['form'] as $key => $value) {
            $pos = strpos($key,"p_delete");
            if($pos !== false) //use !==
            {
                $delete_date = str_replace("p_delete","",$key);
            }
        }

        if($delete_date != "" ){
            //if($this->params['form']['p_delete'] ||  ) {
            $delete_it = true;

            //test code...
            $trans = $this->KeitaiSession->checkDateTransaction($this, $session_info,$delete_date,0);
            //$transhistory = $this->KeitaiSession->GetPrevNextTransactions($this, $session_info);
            if(!isset($trans)){
                $delete_it == false;
            }
            if(date("YYYY-MM-dd") == $trans["date"] && $store_info['CancelLimit'] > 0) {
                $hours_until_yoyaku = (strtotime($trans['yoyakutime']) - time()) / (60 * 60);
                if($hours_until_yoyaku < $store_info['CancelLimit']) {
                    $delete_it = false;
                }
            }
            /*
            if($transhistory['nexttrans']['date'] == date("Y年m月d日") && $store_info['CancelLimit'] > 0) {
            $hours_until_yoyaku = (strtotime($transhistory['nexttrans']['time']) - time()) / (60 * 60);
            if($hours_until_yoyaku < $store_info['CancelLimit']) {
            $delete_it = false;
            }
            }
             */
            if($delete_it == true) {
                //$this->KeitaiSession->CancelYoyaku($this, $transhistory['nexttrans']['transcode'], $transhistory['nexttrans']['keyno'], $session_info['dbname']);
                $this->KeitaiSession->CancelYoyaku($this, $trans['transcode'], $trans['keyno'], $session_info['dbname']);

                $yoyaku_deleted = 1;
            }
            else {
                $yoyaku_deleted = 2;
            }
        }

        $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "0","",$session_info);

        $customer_info = $this->KeitaiSession->GetCustomerInfo($this,
        $session_info['companyid'],
        $session_info['ccode'],
        $session_info['dbname']);
        if($customer_info == false) {
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode']);
            exit;
        }
        //----------------------
        //test code...
        //$transhistory = $this->KeitaiSession->GetPrevNextTransactions($this, $session_info);
        $transhistory2 = $this->KeitaiSession->GetNextTransactions($this, $session_info,$store_info['CancelLimit']);
        //---------------------ここまで
        //$kubun = $this->KeitaiSession->CheckStoreGyoshuKubun($this,$session_info['storecode'], $session_info['dbname']);

        $is_nextyoyaku = ($transhistory2['nexttrans']) ? 1 : 0;
        $addyoyaku_button = 1;//$transhistory2['service_count'] > 0 ? 1 : 0; //count($kubun) > $transhistory2['service_count'] ? 1 : 0;

        /*
        if($transhistory['nexttrans']['date'] == date("Y年m月d日") && $store_info['CancelLimit'] > 0) {
        $hours_until_yoyaku = (strtotime($transhistory['nexttrans']['time']) - time()) / (60 * 60);

        if($hours_until_yoyaku < $store_info['CancelLimit']) {
        $cancel_button = 3;
        }
        }
         */
        //---- ページの上のメッセージ ---------//
        if($yoyaku_deleted == 1) {
            $top_message = $store_info['CancelMsg'];
        }
        elseif($yoyaku_deleted == 2) {
            $top_message = "<font color='red'>申し訳ございません。予約をキャンセルできませんでした。</font>";
        }
        elseif($session_info['y_status'] == 5) { // Just logged in
            $top_message = "ようこそ、".$customer_info['CNAME']."様";
        }
        elseif($session_info['y_status'] == 6) { // YOYAKU Just Registered
            $top_message = $store_info['ThankyouMsg'];
        }
        elseif($session_info['y_status'] == 7) { // Updated User info
            $top_message = "会員情報を更新しました。";
        }
        elseif($session_info['y_status'] == 9) { // Updated User info
            $top_message = $store_info['NewMemberMsg'];
        }
        elseif($session_info['y_status'] == 21) { // Updated User info
            $top_message = "次回のご予約は既に完了しています。";
        }elseif($session_info['y_status'] == 61) { // send mail finish and YOYAKU Just Registered
            $top_message = "確認メールを送信しました。"."<br />".$store_info['ThankyouMsg'];
        }
        elseif($session_info['y_status'] == 101) { // send mail finish and YOYAKU Just Registered
            $top_message = "選択されたメニューは全て、既に予約済みです。ご来店をお待ちしております。"."<br />";
        }
        else {
            $top_message = ($is_nextyoyaku == 1) ? "ご来店をお待ちしております。" : "予約情報";
        }

        $points1 = ($store_info['PointKubun1'] > 0)?"累計".
        $store_info['PointName1']." ".
        intval($customer_info['points1']).
        $store_info['CardPoint1Tani'].
                                            "<br />":"";
        $points2 = ($store_info['PointKubun2'] > 0)?"累計".
        $store_info['PointName2']." ".
        intval($customer_info['points2']).
        $store_info['CardPoint2Tani'].
                                            "<br />":"";

        $this->set('top_message', $top_message);
        $this->pageTitle = $store_info['STORENAME'];

        //$this->set('prevdate_b',  $transhistory2['prevtrans']['date']);
        //$this->set('prevstaff_b', $transhistory2['prevtrans']['staff']);
        //$this->set('prevstname_b',$transhistory2['prevtrans']['storename']);
        /*
        $this->set('prevdate',  $transhistory['prevtrans']['date']);
        $this->set('prevstaff', $transhistory['prevtrans']['staff']);
        $this->set('prevstname',$transhistory['prevtrans']['storename']);
         */
        //nexttransactions
        $this->set('nexttrans', $transhistory2['nexttrans']);
        $this->set('addyoyakub',$addyoyaku_button);

        //$this->set('nextdate',  $transhistory['nexttrans']['date']);
        //$this->set('nexttime',  $transhistory['nexttrans']['time']);
        //$this->set('nextstaff', $transhistory['nexttrans']['staff']);
        //$this->set('nextstname',$transhistory['nexttrans']['storename']);

        $this->set('points1',   $points1);
        $this->set('points2',   $points2);

        $this->set('nextyoyaku',   $is_nextyoyaku);
        $this->set('companyid',$session_info['companyid']); //cid
        $this->set('storecode',$session_info['storecode']); //scd
        $this->set('setLogoutButton', true);
        $this->set('logoutpath',"mypage/".$companyid."/".$storecode."/".$sessionid."/logout");
        $this->set('sitepath',MOBASUTE_PATH.$store_info['storeid'].'/');
        $this->set('privacypath', "privacy/".$session_info['companyid']."/".$session_info['storecode']);
        $this->set('cancellim', $store_info['CancelLimit']);
        $this->set('form_action', MAIN_PATH."yk/mypage/".$companyid."/".$storecode."/".$sessionid);

        //add_access log
        $this->KeitaiSession->SetAccesslog($this, $session_info['dbname'], $sessionid, $session_info['ccode'], $session_info['storecode'], "mypage",1);
        $this->prepare_carrier_output($session_info['carrier'], $store_info['storeid']);
    }

    /**
     * 顧客情報ページ、新規顧客登録と顧客情報更新
     * Customer Information Page, New Customer registeration and updatesb to info
     *
     * @param string $sessionid
     */
    function reg($sessionid = "") {

        if ($sessionid == "") {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);

        if ($session_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        $this->redirectIfNecessary($session_info['companyid'], $session_info['storecode']);

        if($this->params['form']['p_cancel']) {
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        // Check if the Session is expired (30 minutes)
        $sec_since_last_activity  = time() - strtotime($session_info['last_activity']);
        if($sec_since_last_activity > SESSION_EXPIRATION_MIN * 60) {
            //Session Expired
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        $snsdata = $this->Cookie->read('snsdata');
        $isSnsUser = !empty($snsdata);
        $setPasswordFields = !$isSnsUser;
        $setEmailTextbox = $isSnsUser;

        if($session_info['carrier'] == "docomo_old") {
            $t_name  = mb_convert_encoding($this->params['form']['r_name'], "UTF-8", "Shift_JIS");
            $t_name_1 = mb_substr(trim($t_name),0,1,"Shift_JIS");
        }
        else {
            $t_name  = $this->params['form']['r_name'];
            $t_name_1 = mb_substr(trim($t_name),0,1,"UTF-8");
        }
        $t_phone = $this->KeitaiSession->Numeric($this->params['form']['r_phone']);
        $t_cnumber = $this->KeitaiSession->Numeric($this->params['form']['r_kaiin_no']); //大文字を小文字に。

        if($this->params['form']['p_reg'] && strlen($t_name) > 0 && strlen($t_phone) > 0) {

            $failed_submit = false;

            $t_sex         = $this->params['form']['r_sex'];
            $t_mailkubun   = (int) $this->params['form']['r_mailkubun'];

            $t_email = $session_info['y_staff'];
            if($setEmailTextbox){
                $t_email = $this->params['form']['r_email'];

                $indexofAtSymbol = strpos($t_email,"@");
                $lastIndexofAtSymbol = strrpos($t_email,"@");
                $emailLength = strlen($t_email);

                // Inbuilt code of php (filter_var) to validate email wasn't used
                // because In Japan, Japanese mobile carriers allowed users to change the email 
                // which do not comply with RFC 2822 compliant
                // and Filter_var checks if the email address is compliant with RFC
                if(!$indexofAtSymbol || $indexofAtSymbol == $emailLength - 1 || $indexofAtSymbol != $lastIndexofAtSymbol){
                    $emailError = true;
                    $failed_submit = true;
                }
            }

            $t_pwrd1 = $this->params['form']['r_password1'];
            $t_pwrd2 = $this->params['form']['r_password2'];

            $r_year  = intval($this->KeitaiSession->Numeric($this->params['form']['r_year']));
            $r_month = intval($this->KeitaiSession->Numeric($this->params['form']['r_month']));
            $r_day   = intval($this->KeitaiSession->Numeric($this->params['form']['r_day']));

            if($r_year > 1900 && $r_year <= date('Y') && $r_month > 0 && $r_day > 0 && $r_month < 13 && $r_day < 32) {
                $t_bday  = sprintf("%4d-%02d-%02d", $r_year, $r_month, $r_day);
            }
            else if($r_month > 0 && $r_day > 0 && $r_month < 13 && $r_day < 32) {
                $t_bday  = sprintf("1888-%02d-%02d", $r_month, $r_day);
            }
            else {
                $t_bday  = null;
            }
            
            if ($setPasswordFields){
                if(strlen($t_pwrd1) > 0 && $t_pwrd1 == $t_pwrd2) {
                    if(preg_match("/^[a-zA-Z0-9]+$/", $t_pwrd1)) {
                        $password_error1 = false;
                        $password_error2 = false;
                        $failed_submit = false;
                        $t_password = $t_pwrd1;
                    }
                    else {
                        $password_error1 = false;
                        $password_error2 = true;
                        $failed_submit = true;
                        $t_password = "";
                    }
                }
                else if($t_pwrd1 != $t_pwrd2) {
                    $password_error1 = true;
                    $password_error2 = false;
                    $failed_submit = true;
                    $t_password = "";
                }
                else {
                    $password_error1 = false;
                    $password_error2 = false;
                    $failed_submit = false;
                    $t_password = "";
                }
            }
        }
        else if($this->params['form']['p_reg']) {
            $failed_submit = true;
        }

        if($this->params['form']['p_reg'] && !$failed_submit) {
            if ($session_info["y_status"] == 8) {
                // 新規登録の場合
                // 電話番号を条件として、重複した顧客の顧客番号を取得する
                //もし顧客番号が登録されている場合は、そちらを優先する！

                $duplicateCustomerCCode = null;
                if(!empty($this->params['form']['r_kaiin_no'])){
                    $duplicateCustomerCCode = $this->KeitaiSession->GetDuplicateCustomerCNumber(
                    $this,
                    $session_info['storecode'],
                    $t_cnumber,
                    $t_name_1,
                    $session_info["dbname"]
                    );
                }
                //見つからなければ通常の電話番号検索
                if(isset($duplicateCustomerCCode) == false){
                    $duplicateCustomerCCode = $this->KeitaiSession->GetDuplicateCustomerCCode(
                    $this,
                    $session_info['storecode'],
                    $t_phone,
                    $session_info["dbname"]
                    );
                }
                if (isset($duplicateCustomerCCode)) {
                    // 重複した顧客が見つかった場合、重複した顧客にデータを上書きする・・・大丈夫かこれ
                    $session_info["ccode"] = $duplicateCustomerCCode;
                    $t_name = null;
                    $t_phone = null;
                    $t_sex = null;
                    $t_bday = null;
                    $t_password = null;
                    $t_mailkubun = null;
                }
            }

            $ccode = $this->KeitaiSession->UpdateCProfile(
            $this,
            $session_info,
            $t_name,
            $t_phone,
            $t_email,
            $t_sex,
            $t_bday,
            $t_password,
            $t_mailkubun
            );

            if($session_info['y_status'] == 8) {
                 //New customer

                $this->KeitaiSession->UpdateStatus($this,
                $session_info['session_no'],
                                               "9",
                $ccode,$session_info);
                $store_info = $this->KeitaiSession->GetStoreInfo($this,
                $session_info['companyid'],
                $session_info['storecode'],
                $session_info['dbname']);
                //-- 会員登録 --------------------//
                $body  = $store_info['NewMemberMailMsg']."\n\n";
                $body .= $store_info['MailFooter']."\n";

                mb_language("ja");
                mb_internal_encoding("utf-8");

                $to = $t_email;
                $content = $body;
                $title =  $store_info['STORENAME'].'会員登録完了のお知らせ';

                // メールヘッダを作成
                $header  = "From: ".mb_encode_mimeheader($store_info['STORENAME']).' <'.$store_info['storeid'].'@'.EMAIL_DOMAIN.'>'."\n";
                $header .= 'Bcc: yoyakumaillog@think-ahead.jp' ."\n";
                $header .= "Reply-To: ".'err_'.$store_info['storeid'].'@'.EMAIL_DOMAIN;

                //送信ポート及び送信先サーバーの設定
                ini_set( "SMTP", MAILSERVER_ADDRESS);
                ini_set( "smtp_port", MAILSERVER_PORT);

                //メール送信
                $send_mail = mb_send_mail($to, $title, $content, $header);

                if ($ccode != 0 && $isSnsUser){
                    //insert data to customer_sns table
                    $this->KeitaiSession->SaveCustomerSns($this, $session_info, $snsdata, $ccode);
                }
            }
            else {
                //Existing customer

                $this->KeitaiSession->UpdateStatus($this,
                $session_info['session_no'],
                                               "7","",$session_info);
            }



            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'],"","",$session_info);

        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $session_info['companyid'],
        $session_info['storecode'],
        $session_info['dbname']);
        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        if($session_info['y_status'] == 8) {
            // 新規顧客 //

            $showCancelButton = false;
            $showLogoutButton = false;

            $showcnumber = 1;
            $emailaddress  = $session_info['y_staff']; //3つめがメアド
            if($failed_submit) {
                if($password_error1) {
                    $top_message = "<font color='red'>パスワードが確認用のものと一致しません</font>";
                } elseif($password_error2) {
                    $top_message = "<font color='red'>パスワードに使用できる文字は半角英数字以外のみです</font>";
                } elseif($emailError) {
                    $top_message = "<font color='red'>メールアドレスが無効です</font>";
                } else{
                    if($setEmailTextbox){
                        $top_message = "<font color='red'>名前・メールアドレス・電話番号の入力は必須です</font>";
                    } else{
                        $top_message = "<font color='red'>名前と電話番号の入力は必須です</font>";
                    }
                }

                $name   = $this->params['form']['r_name'];
                $phone  = $this->params['form']['r_phone'];
                $sex    = $this->params['form']['r_sex'];
                $year   = $this->params['form']['r_year'];
                $month  = $this->params['form']['r_month'];
                $day    = $this->params['form']['r_day'];
                $mailkubun = $this->params['form']['r_mailkubun'];
                if ($setEmailTextbox){
                    $emailaddress = $this->params['form']['r_email'];
                }
            }
            else {
                $top_message = "はじめまして。会員情報のご登録をお願いします";
                $name          = "";
                $phone         = "";
                $sex           = 0;
                $year          = "";
                $month         = "";
                $day           = "";
                $mailkubun     = 1;

                //SNS Details
                if ($isSnsUser){
                    $name = $snsdata['snsname'];
                    $emailaddress = $snsdata['snsemail'];
                }
            }
        }
        else {
            // 顧客情報更新 //

            $showCancelButton = true;
            $showLogoutButton = true;

            $showcnumber = 0;
            // 顧客情報を取り込む
            $customer_info = $this->KeitaiSession->GetCustomerInfo($this,
            $session_info['companyid'],
            $session_info['ccode'],
            $session_info['dbname']);
            if($customer_info == false) {
                $this->redirect('/yk/login/'.$companyid.'/'.$storecode);
                exit;
            }

            $emailaddress   = $customer_info['MAILADDRESS2'];
            if($customer_info['MAILADDRESS1'] != $customer_info['MAILADDRESS2'] &&
            strlen($customer_info['MAILADDRESS1']) > 0) {
                if(strlen($emailaddress) > 0) {
                    $emailaddress .= "<br />";
                }
                $emailaddress .= $customer_info['MAILADDRESS1'];
            }

            if($failed_submit) {
                if($password_error1) {
                    $top_message = "<font color='red'>パスワードが確認用のものと一致しません</font>";
                } elseif($password_error2) {
                    $top_message = "<font color='red'>パスワードに使用できる文字は半角英数字以外のみです</font>";
                } elseif($emailError) {
                    $top_message = "<font color='red'>メールアドレスが無効です</font>";
                } else{
                    if($setEmailTextbox){
                        $top_message = "<font color='red'>名前・メールアドレス・電話番号の入力は必須です</font>";
                    } else{
                        $top_message = "<font color='red'>名前と電話番号の入力は必須です</font>";
                    }
                }

                $name      = $this->params['form']['r_name'];
                $phone     = $this->params['form']['r_phone'];
                $sex       = $this->params['form']['r_sex'];
                $year      = $this->params['form']['r_year'];
                $month     = $this->params['form']['r_month'];
                $day       = $this->params['form']['r_day'];
                $mailkubun = $this->params['form']['r_mailkubun'];
                if ($setEmailTextbox){
                    $emailaddress = $this->params['form']['r_email'];
                }
            }
            else {
                $top_message = "会員情報の更新を行います";
                $name      = $customer_info['CNAME'];
                $phone     = $customer_info['TEL2'];
                $sex       = $customer_info['SEX'];
                $year      = substr($customer_info['BIRTHDATE'],0,4);
                $month     = substr($customer_info['BIRTHDATE'],5,2);
                $day       = substr($customer_info['BIRTHDATE'],8,2);
                $mailkubun = $customer_info['MAILKUBUN'];
                if(intval($year) == 0) {
                    $year = "";
                }
                if(intval($month) == 0) {
                    $month = "";
                }
                if(intval($day) == 0) {
                    $day = "";
                }
            }

            // 解約ページ
            $this->set('unregpath', "unreg/{$sessionid}");
        }

        if($year == 1888) {
            $year = "";
        }
        $name = htmlspecialchars($name, ENT_QUOTES);
        if ($setEmailTextbox){
            $emailaddress = htmlspecialchars($emailaddress, ENT_QUOTES);
        }
        $this->pageTitle = $store_info['STORENAME'];
        $this->set('top_message', $top_message);
        $this->set('name',        $name);
        $this->set('phone',       $phone);
        $this->set('email',       $emailaddress);
        $this->set('sex_list',    array(0 => '女', 1 => '男'));
        $this->set('sex',         $sex);
        $this->set('year',        $year);
        $this->set('month',       $month);
        $this->set('day',         $day);
        $this->set('showcnumber', $showcnumber); //showflg
        $this->set('mailkubun',   $mailkubun);
        $this->set('companyid',   $session_info['companyid']); //cid
        $this->set('storecode',   $session_info['storecode']); //scd
        $this->set('setCancelButton',   $showCancelButton);
        $this->set('setEmailTextbox',   $setEmailTextbox);
        $this->set('setPasswordFields', $setPasswordFields);
        $this->set('setLogoutButton',   $showLogoutButton);
        $this->set('logoutpath',  "mypage/".$session_info['companyid']."/".$session_info['storecode']."/".$sessionid."/logout");
        $this->set('sitepath',MOBASUTE_PATH.$store_info['storeid'].'/');
        $this->set('privacypath', "privacy/".$session_info['companyid']."/".$session_info['storecode']);
        $this->set('form_action', MAIN_PATH."yk/reg/".$sessionid);

        //if newcustomer_flg
        if($session_info['y_status'] == 8) {
            $this->KeitaiSession->SetAccesslog($this, $store_info['dbname'], $sessionid, "", $store_info['STORECODE'], "reg",0,50);
        }

        $this->prepare_carrier_output($session_info['carrier'], $store_info['storeid']);
    }

    /**
     * 解約ページ
     *
     * @param string $sessionid
     */
    function unreg($sessionid = "") {
        if ($sessionid === "") {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        $session_info = $this->KeitaiSession->Check($this, $sessionid);

        if ($session_info === false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        $store_info = $this->KeitaiSession->GetStoreInfo(
        $this, $session_info["companyid"],
        $session_info["storecode"],
        $session_info["dbname"]
        );

        if ($store_info === false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        if (isset($this->params["form"]["p_cancel"])) {
            $this->redirect("mypage/{$session_info["companyid"]}/{$session_info["storecode"]}/{$sessionid}");
            exit();
        }

        if (isset($this->params["form"]["p_unreg"])) {
            // 解約が完了した場合
            $top_message = "解約が完了しました";
            $complete = true;

            $this->KeitaiSession->UnregisterCustomer($this, $session_info["dbname"], $session_info["ccode"]);
            $this->KeitaiSession->Destroy($this, $session_info["session_no"]);
        } else {
            // 解約が完了していない場合
            $top_message = "解約を行います";
            $complete = false;
            $this->set('setLogoutButton', true);
            $this->set("logoutpath", "mypage/{$session_info["companyid"]}/{$session_info["storecode"]}/{$sessionid}/logout");
            $this->set("privacypath", "privacy/{$session_info["companyid"]}/{$session_info["storecode"]}");
        }

        $this->pageTitle = $store_info["STORENAME"];
        $this->set("top_message", $top_message);
        $this->set("complete", $complete);
        $this->set("form_action", MAIN_PATH . "yk/unreg/{$sessionid}");
        $this->prepare_carrier_output($session_info["carrier"], $store_info["storeid"]);
    }

    /**
     * ログイン画面
     * Login Interface
     *
     * @param int $companyid
     * @param int $storecode
     * @param string $sessionid
     */
    function login($companyid = 0, $storecode = 0, $sessionid = "",$errcode =0) {

        $this->redirectIfNecessary($companyid, $storecode);

        $top_message = "";
        $store_info = $this->KeitaiSession->GetStoreInfo($this, $companyid, $storecode);

        if($sessionid == 401 || $errcode==401){
            $top_message = "この接続は有効期限が切れているか、無効です。お手数ですが再度ログインしてください。";
            $errcode = 0; //トップ画面表示する
        }
        elseif($errcode==500) {
            $top_message = "この接続は有効期限が切れているか、無効です。有効なURLを指定してください。";
        }elseif($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        //利用規約を表示
        if( $store_info['tosflg'] == 1 && empty($sessionid))
        {
            $this->redirect('/yk/tos/'.$companyid.'/'.$storecode.'/');
            exit;
        }

        if(intval($companyid) == 0) {
            $companyid = $this->params['form']['companyid'];
        }

        $antiCSRFtoken = md5(uniqid(rand(), true));
        $state = rawurlencode(json_encode(compact('companyid', 'storecode', 'antiCSRFtoken')));
        
        $this->Cookie->path     = COOKIE_PATH;
        $this->Cookie->domain   = COOKIE_DOMAIN;
        $this->Cookie->secure   = COOKIE_HTTPS_ONLY;
        $expires = date('Y-m-d H:i:s', time()+(60*30)); // 60sec * 30
        $this->Cookie->write("antiCSRFCookie", $antiCSRFtoken, true, $expires);

        if (isset($this->params['form']['login'])) {
            $username = $this->params["form"]["username"];

            if (!empty($this->params['form']['username']) && !empty($this->params['form']['password'])) {
                $sessionid = $this->KeitaiSession->Create(
                $this,
                $username,
                $this->params['form']['password'],
                $companyid,
                $storecode,
                $store_info['logintype']
                );

                if (!$sessionid) {
                    $top_message = "<font color=\"red\">ユーザー名、またはパスワードが誤っています</font>";
                }
            } else {
                $top_message = "<font color=\"red\">ユーザー名、およびパスワードを入力してください</font>";
            }
        } else {
            // クッキーから取得する
            $username = $this->Cookie->read("username");
            $password = $this->Cookie->read("password");
        }

        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);

        if (!empty($session_info)) {

            if($storecode == 0) {
                $storecode = $session_info['storecode'];
            }

            // Check if the Session is expired (30 minutes)
            $sec_since_last_activity  = time() - strtotime($session_info['last_activity']);
            if($sec_since_last_activity > SESSION_EXPIRATION_MIN * 60) {
                //Session Expired
                $top_message = "申し訳ございません、一定時間操作がなかったため、セッションが切断されました。";
                $this->KeitaiSession->Destroy($this, $session_info['session_no']);
            }
            else {
                // Session is Valid
                // クッキーに設定する
                $expires = COOKIE_EXPIRATION_DAY * 60 * 60 * 24;
                $this->Cookie->write("username", $username, true, $expires);
                $this->Cookie->write("password", $this->params["form"]["password"], true, $expires);
                $this->redirect('/yk/mypage/'.$companyid.'/'.$storecode.'/'.$sessionid);
                exit();
            }
            $keitai_carrier = $session_info['carrier'];
        }
        else {
            $keitai_carrier = $this->KeitaiSession->getMobileCarrier();
        }

        $this->pageTitle = $store_info['STORENAME'];
        $this->set('top_message', $top_message);
        $this->set('username', $username);
        $this->set('password', $password);
        $this->set('salonmail', $store_info['storeid']."@".EMAIL_DOMAIN);
        if ($store_info['logintype']==1) {
            $this->set('logincomment', '(顧客番号)');
        }else{
            $this->set('logincomment', '(メールアドレス or 電話番号)');
        }
        $this->set('sitepath',MOBASUTE_PATH.$store_info['storeid'].'/');
        $this->set('storename',$store_info['STORENAME']);
        $this->set('line_url', "https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=".LINE_OAUTH_CHANNEL_ID."&redirect_uri=".LINE_OAUTH_REDIRECT_URL."&state={$state}&scope=profile");
        $this->set('facebook_url', "https://www.facebook.com/".FACEBOOK_API_VERSION."/dialog/oauth?response_type=code&client_id=".FACEBOOK_OAUTH_CHANNEL_ID."&redirect_uri=".FACEBOOK_OAUTH_REDIRECT_URL."&state={$state}&scope=email");
        $this->set('google_url', "https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=".GOOGLE_OAUTH_CHANNEL_ID."&redirect_uri=".GOOGLE_OAUTH_REDIRECT_URL."&state={$state}&scope=profile email");
        $this->set('form_action', MAIN_PATH."yk/login/".$companyid."/".$storecode."/".$sessionid);
        $this->set('privacypath', "privacy/".$companyid."/".$storecode);
        $this->prepare_carrier_output($keitai_carrier, $store_info['storeid'],$errcode);

        //add_access log
        $this->KeitaiSession->SetAccesslog($this, $store_info['dbname'], "", "", $store_info['STORECODE'], "login",0);

    }

    private function redirectIfNecessary($companyid, $storecode) 
    { 
        if((int)$companyid !== 23 || (int)$storecode !== 29) {
            return;
        }

        $this->redirect("https://web.sipss.jp/web/?store_id=26", 302, true);
        exit;
    }

    /*業種区分選択　NEW0*/
    function new0($sessionid = "")
    {
        $top_message = "施術を選択してください";

        //session_check
        if ($sessionid == "") {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);

        //セッション情報が破棄されている
        if ($session_info == false) {
            if(isset($this->params['form']['cid']) && isset($this->params['form']['scd']))
            {
                $this->_redirect_timeout($this->params['form']['cid'],$this->params['form']['scd']);
            }else{
                $this->_redirect(FAIL_REDIRECT,false);
            }
            exit();
        }
        //次へボタンが押された場合は、情報をセッションに書き込んでスタッフ選択の画面へ
        if($this->params['form']['p_next'] && $this->params['form']['syscode']) {
            // Write Selected Info to Session
            $ykstatus = "1";
            $ykstatus .= "|".intval($this->params['form']['syscode'])."|";
            $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], $ykstatus,"",$session_info);
            $this->_redirect('/yk/new1/'.$sessionid);
            exit();
        }

        //キャンセルボタンが押されたときはトップ画面へ
        if($this->params['form']['p_cancel']) {
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }


        $gyoshukubun = $this->KeitaiSession->CheckStoreGyoshuKubun($this,
            $session_info['storecode'],
            $session_info['dbname']
        );

        if($gyoshukubun != false && count($gyoshukubun) == 1)
        {
            //店舗が業種が一種類展開の場合、この画面を表示しない
            // Write Selected Info to Session
            $ykstatus = "1";
            $ykstatus .= "|".intval($gyoshukubun[0])."|";
            $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], $ykstatus,"",$session_info);
            $this->_redirect('/yk/new1/'.$sessionid);
            exit();
        }

        //業種区分一覧を取得。すでに済みのものは表示しない
        /*
        $syscode_list = $this->KeitaiSession->GetStoreGyoshuKubun($this,
        $session_info['storecode'],
        $session_info['dbname'],
        $session_info['ccode']
        );
         */
        $syscode_list = $this->KeitaiSession->GetStoreGyoshuKubun2($this,
            $session_info['storecode'],
            $session_info['dbname']
        );

        if($syscode_list == false) {
            //スタッフが、いない場合
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit;
        }

        $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'],"","",$session_info);

        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $session_info['companyid'],
        $session_info['storecode'],
        $session_info['dbname']);
        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        reset($syscode_list);       //配列の内部ポインタを先頭の要素にセット.
        $name=key($syscode_list);

        $this->pageTitle = $store_info['STORENAME'];
        $this->set('top_message', $top_message);
        $this->set('gyoshukubun_list', $syscode_list);
        $this->set('companyid',$session_info['companyid']); //cid
        $this->set('storecode',$session_info['storecode']); //scd
        $this->set('gyoshukubun', $name);
        $this->set('setLogoutButton', true);
        $this->set('logoutpath',"mypage/".$session_info['companyid']."/".$session_info['storecode']."/".$sessionid."/logout");
        $this->set('privacypath', "privacy/".$session_info['companyid']."/".$session_info['storecode']);
        $this->set('form_action', MAIN_PATH."yk/new0/".$sessionid);
        //add_access log
        $this->KeitaiSession->SetAccesslog($this, $session_info['dbname'], $sessionid, $session_info['ccode'], $session_info['storecode'], "new0",10);
        $this->prepare_carrier_output($session_info['carrier'], $store_info['storeid']);
    }

    /**
     * 予約登録ページ１、担当スタッフ選択
     * Yoyaku registration Page 1, Staff Select
     *
     * @param string $sessionid
     */
    function new1($sessionid = "") {

        $top_message = "担当者を選択してください";

        if ($sessionid == "") {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }
        //echo "<pre style=\"border-style: dashed;\">";var_dump($this->params['form']);echo "</pre>"; exit;
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);
        if ($session_info === false || $session_info['syscode'] == 0) {

            if(isset($this->params['form']['cid']) && isset($this->params['form']['scd']))
            {
                $this->_redirect_timeout($this->params['form']['cid'],$this->params['form']['scd']);
            }else{
                $this->_redirect(FAIL_REDIRECT,false);
            }
            exit();
        }


        if($this->params['form']['p_next']) {
            // Write Selected Info to Session
            $ykstatus = "1";
            $ykstatus .= "|".intval($session_info['syscode'])."|";
            $ykstatus .= intval($this->params['form']['staff'])."|";
            //$session_info['data']['updateflg'] = $this->KeitaiSession->checkNextTransaction($this,$session_info);

            $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], $ykstatus,"",$session_info);
            $this->_redirect('/yk/new2/'.$sessionid);
            exit();
        }

        if($this->params['form']['p_cancel']) {
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        //戻るボタン押下時は業種区分選択画面へ。ただし
        if($this->params['form']['p_back']) {
            $gyoshukubun = $this->KeitaiSession->CheckStoreGyoshuKubun($this,
            $session_info['storecode'], $session_info['dbname'] );
            if(count($gyoshukubun)==1 ){
                $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            }else{
                $this->_redirect('/yk/new0/'.$sessionid);
            }
            exit();
        }

        //                if ($session_info == false) {
        //                    $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
        //                    exit();
        //                }

        // Check if the Session is expired (30 minutes)
        $sec_since_last_activity  = time() - strtotime($session_info['last_activity']);
        if($sec_since_last_activity > SESSION_EXPIRATION_MIN * 60) {
            //Session Expired
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }
        $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'],"","",$session_info);

        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $session_info['companyid'],
        $session_info['storecode'],
        $session_info['dbname']);
        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        $customer_info = $this->KeitaiSession->GetCustomerInfo($this,
        $session_info['companyid'],
        $session_info['ccode'],
        $session_info['dbname']);

        $staff_list = null;
        $staff_name_list = null;
        $this->KeitaiSession->GetStaffList(
            $this,
            $staff_list,
            $staff_name_list,
            $session_info['storecode'],
            $session_info['dbname'],
            $session_info['syscode']
        );

        $services_list = $this->KeitaiSession->GetServicesList($this,
        $session_info['storecode'],
        $session_info['dbname'],
        $customer_info['SEX'],
        -1,
        $session_info['syscode']
        );

        if($customer_info == false) {
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode']);
            exit;
        }

        if(empty($staff_list)) {
            $top_message = "申し訳ございません。店舗側で今月のスタッフシフトが設定されていないため、予約を行うことができません";
            $this->set('error', 1);
        }

        if(empty($services_list) || $services_list == false)
        {
            $top_message = "申し訳ございません。選択した施術のメニューがまだ設定されていません。";
            $this->set('error', 1);
        }

        $this->pageTitle = $store_info['STORENAME'];
        $this->set('top_message', $top_message);
        $this->set('staff_name_list', $staff_name_list);
        $this->set('staff', $session_info['y_staff']);
        $this->set('services_list', $services_list);
        $this->set('services', explode(",",$session_info['y_services']));
        $this->set('companyid',$session_info['companyid']); //cid
        $this->set('storecode',$session_info['storecode']); //scd
        $this->set('setLogoutButton', true);
        $this->set('logoutpath',"mypage/".$session_info['companyid']."/".$session_info['storecode']."/".$sessionid."/logout");
        $this->set('privacypath', "privacy/".$session_info['companyid']."/".$session_info['storecode']);
        $this->set('form_action', MAIN_PATH."yk/new1/".$sessionid);
        $staffHtmlTr = $this->createNew1StaffHtmlTr($sessionid, $staff_list);
        $this->set("staffhtmltr", $staffHtmlTr);
        //add_access log
        $this->KeitaiSession->SetAccesslog($this, $session_info['dbname'], $sessionid, $session_info['ccode'], $session_info['storecode'], "new1",20);
        $this->prepare_carrier_output($session_info['carrier'], $store_info['storeid']);
    }

    /**
     * 予約登録ページ1のスタッフHTMLテーブル行を取得します
     * @param $sessionid セッションID
     * @param $staffList スタッフのリスト
     * @return 予約登録ページ1のスタッフHTMLテーブル行
     */
    function createNew1StaffHtmlTr($sessionId, $staffList) {
        if (!isset($sessionId, $staffList)) { return null; }

        $result = "";
        $columnCount = 4;
        $currentStaffIndex = 0;
        $firstStaff = $staffList[0];

        while ($currentStaffIndex < count($staffList)) {
            $currentStaffList = array_slice($staffList, $currentStaffIndex, $columnCount);
            $tdRadio = "";
            $tdData = "";

            // Modified by jonathanparel, 20160908; RM#1724; --------------------------------------------------------------------------------------ii
            foreach ($currentStaffList as $currentStaff) {
                $checked = $currentStaff === $firstStaff ? " checked=\"checked\"" : "";
                $shashinUrl = sprintf("%s/yk/s_shashin/%s/%s", MAIN_PATH, $sessionId, $currentStaff["STAFFCODE"]);
                $blogUrl = $currentStaff["BLOG_URL"];

                $tdRadio = sprintf("<div id=\"staffButton\"><input type=\"radio\" name=\"staff\" value=\"%s\"%s></div>", $currentStaff["STAFFCODE"], $checked);

                $tdData .= "<div id=\"staffData\" height=\"360\" align=\"center\">" . $tdRadio;
                $tdData .= sprintf("<img src=\"%s\" width=\"200\" height=\"300\"><br />", $shashinUrl);
                $tdData .= htmlspecialchars($currentStaff["STAFFNAME"]) . "<br />";
                $tdData .= htmlspecialchars($currentStaff["POSITIONNAME"]) . "<br />";
                $tdData .= isset($blogUrl) ? sprintf("<a href=\"%s\" target=\"_blank\">ブログを見る</a>", htmlspecialchars($blogUrl)) : "&nbsp;";
                $tdData .= "</div>";
                //$tdData .= "</div><div id=\"filler\"><br \></div>";

            }

            //$result .= sprintf("<div>%s</div>", "<div>" . $tdData . "</div>");
            $result .= sprintf("<div>%s</div>", $tdData);
            $currentStaffIndex += $columnCount;
            // Modified by jonathanparel, 20160908; RM#1724; --------------------------------------------------------------------------------------ii
        }
        return $result;
    }

    /**
     * 予約登録ページ２、技術選択
     * Yoyaku registration Page 2, Services Select
     *
     * @param string $sessionid
     */
    function new2($sessionid = "") {

        $top_message = "メニューを選択してください";

        if ($sessionid == "") {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        if($this->params['form']['p_back']) {
            $this->_redirect('/yk/new1/'.$sessionid);
            exit();
        }

        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);
        if ($session_info === false || $session_info['syscode'] == 0) {

            if(isset($this->params['form']['cid']) && isset($this->params['form']['scd']))
            {
                $this->_redirect_timeout($this->params['form']['cid'],$this->params['form']['scd']);
            }else{
                $this->_redirect(FAIL_REDIRECT,false);
            }
            exit();
        }

        if($this->params['form']['p_next']) {
            // Write Selected Info to Session
            $ykstatus = "1";
            $ykstatus .= "|".intval($session_info['syscode'])."|";
            $ykstatus .= intval($session_info['y_staff'])."|";
            $i = 0;
            foreach($this->params['form']['services'] as $srv) {
                if($i!=0) {
                    $ykstatus .= ",";
                }
                $ykstatus .= $srv;
                $i++;
            }

            if($i != 0) {
                $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], $ykstatus,"",$session_info);
                $this->_redirect('/yk/new3/'.$sessionid);
                exit();
            }
            else {
                $top_message = "<font color='red'>メニューを選択してください</font>";
            }
        }

        if($this->params['form']['p_cancel'] || $session_info['y_status'] != 1) {
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        //                if ($session_info == false) {
        //                    $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
        //                    exit();
        //                }

        // Check if the Session is expired (30 minutes)
        $sec_since_last_activity  = time() - strtotime($session_info['last_activity']);
        if($sec_since_last_activity > SESSION_EXPIRATION_MIN * 60) {
            //Session Expired
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }
        $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'],"","",$session_info);

        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $session_info['companyid'],
        $session_info['storecode'],
        $session_info['dbname']);
        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        $customer_info = $this->KeitaiSession->GetCustomerInfo($this,
        $session_info['companyid'],
        $session_info['ccode'],
        $session_info['dbname']);

        $services_list = $this->KeitaiSession->GetServicesList($this,
        $session_info['storecode'],
        $session_info['dbname'],
        $customer_info['SEX'],
        $session_info['y_staff'],
        $session_info['syscode']
        );

        if($customer_info == false) {
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode']);
            exit;
        }

        if(empty($services_list) || $services_list == false)
        {
            $top_message = "申し訳ございません。選択した施術のメニューがまだ設定されていません。";
            $this->set('error', 1);
        }

        $this->pageTitle = $store_info['STORENAME'];
        $this->set('top_message', $top_message);
        //$this->set('staff_list', $staff_list);
        $this->set('staff', $session_info['y_staff']);
        $this->set('services_list', $services_list);
        $this->set('services', explode(",",$session_info['y_services']));
        $this->set('setLogoutButton', true);
        $this->set('logoutpath',"mypage/".$session_info['companyid']."/".$session_info['storecode']."/".$sessionid."/logout");
        $this->set('privacypath', "privacy/".$session_info['companyid']."/".$session_info['storecode']);
        $this->set('form_action', MAIN_PATH."yk/new2/".$sessionid);
        $this->set('menu_name_only', $store_info['MenuNameOnly']);
        $this->set('companyid',$session_info['companyid']); //cid
        $this->set('storecode',$session_info['storecode']); //scd
        //add_access log
        $this->KeitaiSession->SetAccesslog($this, $session_info['dbname'], $sessionid, $session_info['ccode'], $session_info['storecode'], "new2",30);
        $this->prepare_carrier_output($session_info['carrier'], $store_info['storeid']);
    }

    /**
     * 予約登録ページ３、日付選択
     * Yoyaku registration Page 3, Date Select
     *
     * @param string $sessionid
     * @param string $yearmonth
     * @param int $submit_error
     */
    function new3($sessionid = "", $yearmonth = "", $submit_error = 0) {

        $current_year  = date("Y");
        $current_month = date("n");

        if ($sessionid == "") {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        if($this->params['form']['p_back']) {
            $this->_redirect('/yk/new2/'.$sessionid);
            exit();
        }

        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);
        if ($session_info === false || $session_info['syscode'] == 0) {
            if(isset($this->params['form']['cid']) && isset($this->params['form']['scd']))
            {
                $this->_redirect_timeout($this->params['form']['cid'],$this->params['form']['scd']);
            }else{
                $this->_redirect(FAIL_REDIRECT,false);
            }
            exit();
        }

        if($this->params['form']['p_cancel'] || $session_info['y_status'] != 1) {
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        // Check if the Session is expired (30 minutes)
        $sec_since_last_activity  = time() - strtotime($session_info['last_activity']);
        if($sec_since_last_activity > SESSION_EXPIRATION_MIN * 60) {
            //Session Expired
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }
        $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'],"","",$session_info);

        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $session_info['companyid'],
        $session_info['storecode'],
        $session_info['dbname']
        );
        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        if(strlen($yearmonth) > 0) {
            $sel_year  = intval(substr($yearmonth, 0,4));
            $sel_month = intval(substr($yearmonth, 4,2));
        }
        else {
            $sel_month = $current_month;
            $sel_year = $current_year;
        }
        if($sel_month > 12 || $sel_month < 1) {
            $sel_month = $current_month;
        }
        if($sel_year > ($current_year + 10) || $sel_year < $current_year) {
            $sel_year = $current_year;
        }

        if($sel_year <= $current_year && $sel_month <= $current_month) {
            $prevlink = "";
        }
        else {
            $prevlink = ($sel_month == 1)?sprintf("%4d12", ($sel_year-1)):
            sprintf("%4d%02d", $sel_year, ($sel_month-1));
        }

        $months_offset = ($store_info['UpLimitOp'] == "months")?$store_info['UpLimit']:0;
        $day_offset    = ($store_info['UpLimitOp'] == "days")?$store_info['UpLimit']:0;
        $lastdate = mktime(date("H"),0,0,(date("m")+$months_offset),(date("d")+$day_offset),date("Y"));

        if($sel_year >= date("Y", $lastdate) && $sel_month >= date("m", $lastdate)) {
            $nextlink = "";
        }
        else {
            $nextlink = ($sel_month == 12)?sprintf("%4d01", ($sel_year+1)):
            sprintf("%4d%02d", $sel_year, ($sel_month+1));

            //if($session_info['y_staff'] != 0) {
            $next_finished_shift = $this->KeitaiSession->GetFinishedShift($this,
            $session_info['storecode'],
            intval(substr($nextlink,4,2)),
            intval(substr($nextlink,0,4)),
            $session_info['dbname']);

            if(intval($next_finished_shift) == 0) {
                $nextlink = "";
            }
            //}
        }

        $calendar = $this->KeitaiSession->BuildCalendarMonth($this,
        $session_info['y_staff'],
        $session_info['storecode'],
        $session_info['dbname'],
        $sel_month,
        $sel_year,
        $store_info['LowLimit'],
        $store_info['LowLimitOp'],
        $store_info['UpLimit'],
        $store_info['UpLimitOp']);

        if($submit_error == 1) {
            $top_message = "<font color='red'>申し訳ございません。選択されたご来店日は無効になりました。お手数ですが、新しいご来店日を選択してください</font>";
        }
        elseif($submit_error == 2) {
            $top_message = "<font color='red'>選択された日時とメニューは全て予約済みです。新しいご来店日を選択するか、再度メニューを選択して下さい。</font>";
        }
        else if($session_info['y_staff'] > 0) { // Staff Selected
            //$staffname = $this->KeitaiSession->GetStaff($this, $session_info['y_staff'], $session_info['dbname']);
            $top_message = "ご来店日を選択してください";
        }
        else { // Free Staff
            $top_message = "ご来店日を選択してください";
        }

        $this->pageTitle = $store_info['STORENAME'];
        $this->set('top_message', $top_message);
        $this->set('yoyaku_path', MAIN_PATH.'yk');
        $this->set('sessionid', $sessionid);
        $this->set('calendar_header', $sel_year.'年'.$sel_month.'月');
        $this->set('nextlink', $nextlink);
        $this->set('prevlink', $prevlink);
        $this->set('calendar', $calendar);
        $this->set('setLogoutButton', true);
        $this->set('logoutpath',"mypage/".$session_info['companyid']."/".$session_info['storecode']."/".$sessionid."/logout");
        $this->set('privacypath', "privacy/".$session_info['companyid']."/".$session_info['storecode']);
        $this->set('form_action', MAIN_PATH."yk/new3/".$sessionid);
        $this->set('companyid',$session_info['companyid']); //cid
        $this->set('storecode',$session_info['storecode']); //scd
        //add_access log
        $this->KeitaiSession->SetAccesslog($this, $session_info['dbname'], $sessionid, $session_info['ccode'], $session_info['storecode'], "new3",40);
        $this->prepare_carrier_output($session_info['carrier'], $store_info['storeid']);
    }

    /**
     * 予約登録ページ４、時間選択
     * Yoyaku registration Page 4, Time Select
     *
     * @param string $sessionid
     * @param string $p_date
     * @param int $page
     */
    function new4($sessionid = "", $p_date = "", $page = 1) {

        if ($sessionid == "") {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        if($p_date == "err") {
            $submit_error = 1;
            $p_date = "";
        }

        $cur_page = intval($page);
        if($cur_page < 1) {
            $cur_page = 1;
        }

        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);
        if ($session_info === false || $session_info['syscode'] == 0) {
            if(isset($this->params['form']['cid']) && isset($this->params['form']['scd']))
            {
                $this->_redirect_timeout($this->params['form']['cid'],$this->params['form']['scd']);
            }else{
                $this->_redirect(FAIL_REDIRECT,false);
            }
            exit();
        }

        if($session_info['y_status'] != 1 || $session_info['y_services'] =="")
        {
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        $customer_info = $this->KeitaiSession->GetCustomerInfo($this,
        $session_info['companyid'],
        $session_info['ccode'],
        $session_info['dbname']);

        if($this->params['form']['p_back']) {
            $this->_redirect('/yk/new3/'.$sessionid.'/'.substr($session_info['y_date'],0,6));
            exit();
        }

        if(strlen($p_date) == 0 || intval($p_date) == 0) {
            $p_date = $session_info['y_date'];
        }
        else {
            $session_info['y_date'] = $p_date;
        }

        if(strlen($p_date) == 0) {
            $this->_redirect('/yk/new3/'.$sessionid);
            exit();
        }

        if($this->params['form']['p_cancel']) {
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }


        // Check if the Session is expired (30 minutes)
        $sec_since_last_activity  = time() - strtotime($session_info['last_activity']);
        if($sec_since_last_activity > SESSION_EXPIRATION_MIN * 60) {
            //Session Expired
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }
        $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "1|".$session_info['syscode']."|". $session_info['y_staff']."|".$session_info['y_services']."|".$p_date,"",$session_info);

        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $session_info['companyid'],
        $session_info['storecode'],
        $session_info['dbname']);
        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }


        $full_services_arr = $this->KeitaiSession->GetServices($this,
            $session_info['storecode'],
            $session_info['dbname'],
            explode(",",$session_info['y_services']),
            $customer_info['SEX'],
            $session_info['y_staff']
        );





        $total_servicetime = 0;
        foreach($full_services_arr as $srvc) {
            $total_servicetime += $srvc['servicetime'];
        }

        $session_info['kanzashiflag'] = $store_info['KanzashiFlag'];
        $AvailableTimes = $this->KeitaiSession->GetAvailableTimes($this,
        $session_info,
        $total_servicetime,
        $store_info['LowLimit'],
        $store_info['LowLimitOp']);

        //-- 追加予約の確認 --//
        $ret = $this->KeitaiSession->checkDateTransaction($this, $session_info);

        //追加予約の場合、ここで判定
        if($ret != false)
        {
            //endtimeを15分単位に「切り上げ」る
            $endtime_hour = substr($ret['endtime'],0,2);
            $endtime_min =  substr($ret['endtime'],3,2);

            //tset
            $interval = "15";
            $ajust_endtime_min = floor(($endtime_min + $interval - 1) / $interval) * $interval;

            //時間調整
            if($ajust_endtime_min >= 60){$endtime_hour++;}
            $ajust_endtime = sprintf("%02d",$endtime_hour).sprintf("%02d",$ajust_endtime_min);


            if(isset($AvailableTimes[$ajust_endtime]))
            {
                $session_info['update'] = true;
                $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'],"","",$session_info);
                $this->_redirect('/yk/new5/'.$sessionid.'/'.$ajust_endtime);

            }else{
                $session_info['update'] = false;
                $AvailableTimes = null;
                //$this->_redirect('/yk/new3/'.$sessionid.'/');
            }
        }

        /////////////////////////////////////////testcode
        //--   間隔のフィールター    --//
        if($store_info['Interval'] == 30 || $store_info['Interval'] == 60) {
            foreach($AvailableTimes as $tkey => $tval) {
                $tmin = substr($tkey,2,2);
                if(   $tmin == '15' ||
                $tmin == '45' ||
                ($tmin == '30' && $store_info['Interval'] == 60)) {
                    unset($AvailableTimes[$tkey]);
                }
            }
        }

        $titledate = substr($p_date,0,4)."年".
        substr($p_date,4,2)."月".
        substr($p_date,6,2)."日";

        $show_start = ($cur_page-1) * 10;
        $show_end = $show_start + 10;

        $cnt = 0;
        $PagedAvailableTimes = array();
        foreach($AvailableTimes as $key => $itm) {

            if($cnt >= $show_start && $cnt < $show_end) {
                $PagedAvailableTimes[$key] = $itm;
            }
            $cnt++;
        }
        $nextpage = ($cnt > $cur_page * 10)?($cur_page + 1):0;
        $prevpage = ($cur_page > 1)?($cur_page - 1):0;

        if($session_info['update'] === false){
            $top_message = "申し訳ございません。選択したメニューを予約可能な時間がありませんでした。<br />「戻る」ボタンから、他の日を選択して下さい。";
        }
        else if($submit_error == 1) {
            $top_message = "<font color='red'>申し訳ございません。選択されたご来店時間は無効になりました。お手数ですが、新しいご来店時間を選択してください</font>";
        }
        else if(empty($AvailableTimes)) {
            $top_message = "申し訳ございません。選択されたメニューの合計時間で予約可能な時間がありませんでした";
        }
        elseif($session_info['y_staff'] > 0) { // Staff Selected
            $staffname = $this->KeitaiSession->GetStaff($this, $session_info['y_staff'], $session_info['dbname']);
            $top_message = "ご来店時間を選択してください";
        }
        else { // Free Staff
            $top_message = "ご来店時間を選択してください";
        }

        $this->pageTitle = $store_info['STORENAME'];
        $this->set('top_message', $top_message);
        $this->set('yoyaku_path', MAIN_PATH.'yk');
        $this->set('sessionid', $sessionid);
        $this->set('AvailableTimes', $PagedAvailableTimes);
        $this->set('nextpage', $nextpage);
        $this->set('prevpage', $prevpage);
        $this->set('setLogoutButton', true);
        $this->set('logoutpath',"mypage/".$session_info['companyid']."/".$session_info['storecode']."/".$sessionid."/logout");
        $this->set('privacypath', "privacy/".$session_info['companyid']."/".$session_info['storecode']);
        $this->set('form_action', MAIN_PATH."yk/new4/".$sessionid);
        $this->set('companyid',$session_info['companyid']); //cid
        $this->set('storecode',$session_info['storecode']); //scd
        //add_access log
        $this->KeitaiSession->SetAccesslog($this, $session_info['dbname'], $sessionid, $session_info['ccode'], $session_info['storecode'], "new4",50);
        $this->prepare_carrier_output($session_info['carrier'], $store_info['storeid']);
    }

    /**
     * 予約登録ページ５、予約登録画面
     * Yoyaku registration Page 5, Yoyaku Registration
     *
     * @param string $sessionid
     * @param string $p_time
     */
    function new5($sessionid = "", $p_time = "") {

        $dayofweek = array(1 => "月", 2 => "火", 3 => "水",  4 => "木",
        5 => "金", 6 => "土", 7 => "日");

        if ($sessionid == "") {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);
        if ($session_info === false || $session_info['y_services'] =="" || $session_info["y_date"] == "") {

            if(isset($this->params['form']['cid']) && isset($this->params['form']['scd']))
            {
                $this->_redirect_timeout($this->params['form']['cid'],$this->params['form']['scd']);
            }else{
                $this->_redirect(FAIL_REDIRECT,false);
            }
            exit();
        }

        if($session_info['data']['update'] == false){
            $top_message = "この内容でよろしいですか？<br />";
        }else{
            $top_message = "こちらの内容を追加しますか？";
        }

        if($this->params['form']['p_back']) {
            $ret = $this->KeitaiSession->checkDateTransaction($this, $session_info);
            if($ret == false){
                $this->_redirect('/yk/new4/'.$sessionid);
                exit();
            }else{
                $this->_redirect('/yk/new3/'.$sessionid);
                exit();
            }
        }

        if(strlen($p_time) == 0) {
            $this->_redirect('/yk/new4/'.$sessionid);
            exit();
        }

        $customer_info = $this->KeitaiSession->GetCustomerInfo($this,
        $session_info['companyid'],
        $session_info['ccode'],
        $session_info['dbname']);

        if($this->params['form']['p_confirm']) {

            //--  予約は既に存在してるかどうかチェックする   --//
            //                    $transhistory = $this->KeitaiSession->GetPrevNextTransactions($this, $session_info);
            //                    if($transhistory['nexttrans']) {
            //                        $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "21");
            //                        $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            //                        exit();
            //                    }
            /*
            //同日同種の予約がすでにされているかチェック
            if($this->KeitaiSession->checkNextTransaction($this, $session_info) == true)
            {
            $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "21","",$session_info);
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
            }
             */
            //--   日付はまだ大丈夫かどうか    --//
            $store_info = $this->KeitaiSession->GetStoreInfo($this,
            $session_info['companyid'],
            $session_info['storecode'],
            $session_info['dbname']);

            $calendar = $this->KeitaiSession->BuildCalendarMonth($this,
            $session_info['y_staff'],
            $session_info['storecode'],
            $session_info['dbname'],
            substr($session_info['y_date'],4,2),
            substr($session_info['y_date'],0,4),
            $store_info['LowLimit'],
            $store_info['LowLimitOp'],
            $store_info['UpLimit'],
            $store_info['UpLimitOp']);
            //$match = false;
            foreach($calendar as $week_itm) {
                foreach($week_itm as $day_itm) {
                    if($day_itm[1] == $session_info['y_date']) {
                        $match = true;
                    }
                }
            }
            if(!$match) {
                $this->_redirect('/yk/new3/'.$sessionid."/".$session_info['y_date']."/1");
                exit();
            }

            //GetYoyakuServices
            $services = $this->KeitaiSession->GetYoyakuServices($this,$session_info);
            if($services === false)
            {
                $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "101","",$session_info);
                $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
                exit();
            }else{
                $session_info['y_services'] = implode(",",$services);
            }

            //echo "<pre style=\"border-style: dashed;\">";var_dump($session_info['y_services']);echo "</pre>"; exit;
            //--   空き時間を再確認する     --//
            $full_services_arr = $this->KeitaiSession->GetServices($this,
            $session_info['storecode'],
            $session_info['dbname'],

            $services,
            $customer_info['SEX'],
            $session_info['y_staff']
             );

            $total_servicetime = 0;
            $service_list = "";
            foreach($full_services_arr as $srvc) {
                $total_servicetime += $srvc['servicetime'];
                if($service_list != "") {
                    $service_list .= "、";
                }
                $service_list .= $srvc['menuname'];
            }

            $session_info['kanzashiflag'] = $store_info['KanzashiFlag'];
            $AvailableTimes = $this->KeitaiSession->GetAvailableTimes($this,
            $session_info,
            $total_servicetime,
            $store_info['LowLimit'],
            $store_info['LowLimitOp']);
            $match = false;
            foreach($AvailableTimes as $tkey => $tval) {
                if($tkey == $session_info['y_time']) {
                    $match = true;
                }
            }
            if(!$match) {
                $this->_redirect('/yk/new4/'.$sessionid."/err");
                exit();
            }

            //-- 当日状況の確認 --//
            $update = $this->KeitaiSession->checkDateTransaction($this, $session_info,$session_info['y_date'],-1);
            if($update == false){
                $yoyaku = $this->KeitaiSession->WriteNewYoyaku($this, $session_info, $customer_info);
            }else{
                $yoyaku =$this->KeitaiSession->WriteAddYoyaku($this, $session_info, $customer_info);
            }

            if($yoyaku===false)
            {
                $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
                exit();
            }

            //$this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "6","",$session_info);

            //-- ありがとうメール -----------------//
            /*
            if(strlen($customer_info['MAILADDRESS1']) > 0 || strlen($customer_info['MAILADDRESS2']) > 0) {
            $email_address = (strlen($customer_info['MAILADDRESS2']) > 0)?$customer_info['MAILADDRESS2']:
            $customer_info['MAILADDRESS1'];

            $yk_date = substr($session_info['y_date'],0,4)."年".
            substr($session_info['y_date'],4,2)."月".
            substr($session_info['y_date'],6,2)."日";

            $yk_hour = substr($session_info['y_time'],0,2);
            $yk_min = substr($session_info['y_time'],2,2);
            //$yk_end_hour = $yk_hour + ($total_servicetime / 60);
            //$yk_end_min  = $yk_min + ($total_servicetime % 60);
            //if($yk_end_min >= 60) {
            //    $yk_end_hour++;
            //    $yk_end_min -= 60;
            //}
            //$yk_time = sprintf("%02d:%02d～%02d:%02d", $yk_hour, $yk_min, $yk_end_hour, $yk_end_min);
            $yk_time = sprintf("%02d:%02d", $yk_hour, $yk_min);

            $yk_staff = $this->KeitaiSession->GetStaff($this, $session_info['y_staff'], $session_info['dbname']);

            $body = $store_info['ThankUMailMsg']."\n\n予約詳細\n";
            $body .= "来店日：".$yk_date."\n";
            $body .= "時間：".$yk_time."\n";
            $body .= "担当者：".$yk_staff."\n";
            $body .= "技術：".$service_list."\n\n";
            $body .= $store_info['MailFooter']."\n";

            $this->Email->lineLength = 10000;
            $this->Email->from    = $store_info['STORENAME'].' <'.$store_info['storeid'].'@'.EMAIL_DOMAIN.'>';
            +                       $this->Email->replyTo = 'err_'.$store_info['storeid'].'@'.EMAIL_DOMAIN;

            $this->Email->to      = $email_address;
            $this->Email->subject = $store_info['STORENAME'].'予約登録';
            $this->Email->delivery = 'smtp';
            $this->Email->smtpOptions = array('port' => MAILSERVER_PORT,
            'host' => MAILSERVER_ADDRESS);
            $this->Email->send($body);
            }
            //------------------------------//
             */
            $this->redirect('/yk/yoyakumail/'.$sessionid.'/');
            exit();
        }

        if($this->params['form']['p_cancel']) {
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        if ($session_info == false) {
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        // Check if the Session is expired (30 minutes)
        $sec_since_last_activity  = time() - strtotime($session_info['last_activity']);
        if($sec_since_last_activity > SESSION_EXPIRATION_MIN * 60) {
            //Session Expired
            $this->redirect('/yk/login/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }
        $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "1|".$session_info['syscode']."|".$session_info['y_staff']."|".$session_info['y_services']."|".$session_info['y_date']."|".$p_time,"",$session_info);

        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $session_info['companyid'],
        $session_info['storecode'],
        $session_info['dbname']);
        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        $full_services_arr = $this->KeitaiSession->GetServices($this,
        $session_info['storecode'],
        $session_info['dbname'],
        explode(",",$session_info['y_services']),
        $customer_info['SEX'],
        $session_info['y_staff']
        );

        $services_name_list = "";
        //$total_servicetime = 0;
        foreach($full_services_arr as $srvc) {
            if($session_info['carrier'] != "pc") {
                $services_name_list .= "◇";
            }

            if ($store_info["MenuNameOnly"] != 1) {
                $services_name_list .= "{$srvc['menuname']} ({$srvc['servicetime']}分)<br />\n";
            } else {
                $services_name_list .= "{$srvc['menuname']}<br />\n";
            }
            //$total_servicetime += $srvc['servicetime'];
        }

        $this->pageTitle = $store_info['STORENAME'];
        $this->set('top_message', $top_message);
        $this->set('yoyaku_path', MAIN_PATH.'yk');
        $this->set('sessionid', $sessionid);

        if($session_info['y_staff'] > 0) {
            $staffname = $this->KeitaiSession->GetStaff($this, $session_info['y_staff'], $session_info['dbname']);
        }
        else {
            $staffname = "指名なし";
        }

        $this->set('trans_staff',    $staffname);
        $this->set('trans_services', $services_name_list);
        $sel_date = strtotime(substr($session_info['y_date'],0,4).'-'.
        substr($session_info['y_date'],4,2).'-'.
        substr($session_info['y_date'],6,2));
        $formatted_date  = date('Y年m月d日',$sel_date);
        $formatted_date .= " (".$dayofweek[date('N',$sel_date)].")";
        $this->set('trans_date',     $formatted_date);

        $time_from = strtotime(substr($p_time,0,2).":".substr($p_time,2,2));
        //$time_to = $time_from + ($total_servicetime * 60);
        $this->set('trans_time',  date("H:i",$time_from)); //" ～ ".date("H:i",$time_to) );
        $this->set('setLogoutButton', true);
        $this->set('logoutpath',  "mypage/".$session_info['companyid']."/".$session_info['storecode']."/".$sessionid."/logout");
        $this->set('privacypath', "privacy/".$session_info['companyid']."/".$session_info['storecode']);
        $this->set('companyid',$session_info['companyid']); //cid
        $this->set('storecode',$session_info['storecode']); //scd
        $this->set('form_action', MAIN_PATH."yk/new5/".$sessionid."/".$p_time);
        //add_access log
        $this->KeitaiSession->SetAccesslog($this, $session_info['dbname'], $sessionid, $session_info['ccode'], $session_info['storecode'], "new5",60);
        $this->prepare_carrier_output($session_info['carrier'], $store_info['storeid']);
    }

    /**
     * 予約登録ページ６、thanks画面、予約確認メール送信画面
     * Yoyaku registration Page 5, Yoyaku Registration
     *
     * @param string $sessionid
     */
    function yoyakumail($sessionid = "")
    {
        if ($sessionid == "") {
            $this->_redirect(FAIL_REDIRECT,false);
            exit();
        }

        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $session_info = $this->KeitaiSession->Check($this, $sessionid);
        if($session_info === false || !isset($session_info['companyid']))
        {
            if(isset($this->params['form']['cid']) && isset($this->params['form']['scd']))
            {
                $this->_redirect_timeout($this->params['form']['cid'],$this->params['form']['cid']);
            }else{
                $this->_redirect(FAIL_REDIRECT,false);
            }
            exit();
        }

        if($session_info['y_status'] != 1)
        {
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        //TOPに戻るボタン
        if($this->params['form']['p_cancel']) {
            $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "6","",$session_info);
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();
        }

        //店舗情報
        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $session_info['companyid'],
        $session_info['storecode'],
        $session_info['dbname']                );

        //送信ボタンクリック時
        if($this->params['form']['p_sendmail']) {
            //$this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "60","",$session_info);
            //顧客情報
            $customer_info = $this->KeitaiSession->GetCustomerInfo($this,
            $session_info['companyid'],
            $session_info['ccode'],
            $session_info['dbname']                );

            //技術情報
            $full_services_arr = $this->KeitaiSession->GetServices($this,
            $session_info['storecode'],
            $session_info['dbname'],
            explode(",",$session_info['y_services']),
            $customer_info['SEX'],
            $session_info['y_staff']
             );
            $total_servicetime = 0;
            $service_list = "";

            foreach($full_services_arr as $srvc) {
                $total_servicetime += $srvc['servicetime'];
                if($service_list != "") {
                    $service_list .= "、";
                }
                $service_list .= $srvc['menuname'];
            }


            $top_message = $store_info['ThankyouMsg'];
            $mail_send = 0;
            //-- ありがとうメール -----------------//
            if(strlen($customer_info['MAILADDRESS1']) > 5 || strlen($customer_info['MAILADDRESS2']) > 5) {

                $email_address = (strlen($customer_info['MAILADDRESS1']) > 5) ? $customer_info['MAILADDRESS1']: "";
                $email_address2 = (strlen($customer_info['MAILADDRESS2']) > 5) ? $customer_info['MAILADDRESS2']:"";
                //echo "<pre style=\"border-style: dashed;\">";var_dump($session_info);echo "</pre>"; exit;
                $yk_date = substr($session_info['y_date'],0,4)."年".
                substr($session_info['y_date'],4,2)."月".
                substr($session_info['y_date'],6,2)."日";

                $yk_hour = substr($session_info['y_time'],0,2);
                $yk_min = substr($session_info['y_time'],2,2);
                $yk_time = sprintf("%02d:%02d", $yk_hour, $yk_min);
                $yk_staff = $this->KeitaiSession->GetStaff($this, $session_info['y_staff'], $session_info['dbname']);

                $body = $store_info['ThankUMailMsg']."\n\n予約詳細\n";
                $body .= "来店日：".$yk_date."\n";
                $body .= "時間：".$yk_time."\n";
                $body .= "担当者：".$yk_staff."\n";
                $body .= "技術：".$service_list."\n\n";
                $body .= $store_info['MailFooter']."\n";
                //                        #redmine #1287
                //                        $this->Email->lineLength = 10000;
                //                        $this->Email->from    = $store_info['STORENAME'].' <'.$store_info['storeid'].'@'.EMAIL_DOMAIN.'>';
                //                        $this->Email->replyTo = 'err_'.$store_info['storeid'].'@'.EMAIL_DOMAIN;
                //
                //                        $this->Email->to      = $email_address;
                //                        $this->Email->subject = $store_info['STORENAME'].'予約登録';
                //                        $this->Email->delivery = 'smtp';
                //                        $this->Email->smtpOptions = array('port' => MAILSERVER_PORT,
                //                                                      'host' => MAILSERVER_ADDRESS);
                //                        $this->Email->send($body);

                //mailto address fixed 2016-05-24;
                $to = $email_address.",".$email_address2;

                $content = $body;
                $title =  $store_info['STORENAME'].'予約内容確認メール';

                mb_language("ja");
                mb_internal_encoding("utf-8");

                // メールヘッダを作成
                $header  = "From: ".mb_encode_mimeheader($store_info['STORENAME']).' <'.$store_info['storeid'].'@'.EMAIL_DOMAIN.'>'."\n";
                $header .= 'Bcc: yoyakumaillog@think-ahead.jp' ."\n";
                $header .= "Reply-To: ".'err_'.$store_info['storeid'].'@'.EMAIL_DOMAIN;

                //送信ポート及び送信先サーバーの設定
                ini_set( "SMTP", MAILSERVER_ADDRESS);
                ini_set( "smtp_port", MAILSERVER_PORT);

                //メール送信
                $send_mail = mb_send_mail($to, $title, $content, $header);

                $this->KeitaiSession->UpdateStatus($this, $session_info['session_no'], "61","",$session_info);
                //$this->set('smtperrors', $this->Email->smtpError); //DEBUG CODE,,, SMTP ERORR OUTPUT
                $mail_send = 1;
                $top_message = "";
                if($send_mail){
                    $top_message = "メールを送信しました。";
                }
            }
            //------------------------------//
            //$this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            //exit();
        }

        $this->pageTitle = $store_info['STORENAME'];
        $this->set('top_message', $top_message);
        $this->set('mail_send',$mail_send);
        $this->set('complete',false);
        $this->set('companyid',$session_info['companyid']); //cid
        $this->set('storecode',$session_info['storecode']); //scd
        $this->set('setLogoutButton', true);
        $this->set('logoutpath',  "mypage/".$session_info['companyid']."/".$session_info['storecode']."/".$sessionid."/logout");
        $this->set('privacypath', "privacy/".$session_info['companyid']."/".$session_info['storecode']);
        $this->set('form_action', MAIN_PATH."yk/yoyakumail/".$sessionid."/");
        //add_access log sendmail->110/ nosendmail->100
        $this->KeitaiSession->SetAccesslog($this, $session_info['dbname'], $sessionid, $session_info['ccode'], $session_info['storecode'], "yoyakumail",($mail_send*10) + 100);
        $this->prepare_carrier_output($session_info['carrier'], $store_info['storeid']);
    }

    /**
     * プラィバシーポリシー
     * Privacy Policy
     *
     * @param int $companyid
     * @param int $storecode
     */
    function privacy($companyid = 0, $storecode = 0) {

        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $companyid,
        $storecode,
        "");
        if($store_info == false) {
            $this->pageTitle = "";
            $this->prepare_carrier_output($this->KeitaiSession->getMobileCarrier());
        } else{
            $this->pageTitle = $store_info['STORENAME'];
            $this->set('storename', $store_info['STORENAME']);
            $this->prepare_carrier_output($this->KeitaiSession->getMobileCarrier(), $store_info['storeid']);
        }
    }

    /**
     * プラィバシーポリシー
     * Privacy Policy
     *
     * @param int $companyid
     * @param int $storecode
     */

    // Added by jonathanparel, 20160929; RM#1789 ----------------------------------------------------------ii
    function message($companyid = 0, $storecode = 0) {

        $store_info = $this->KeitaiSession->GetMobasuteStoreInfo($this, $companyid, $storecode, "");

        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        $this->pageTitle = $store_info['pagetitle'];
        $this->set('storename', $store_info['STORENAME']);

        if($store_info['message']==""){
            $this->set('message', "There is no message.");
        }else{
            $this->set('message', $store_info['message']);
        }
        $this->prepare_carrier_output($this->KeitaiSession->getMobileCarrier(), $store_info['storeid']);
    }
    // Added by jonathanparel, 20160929; RM#1789 ---------------------------------------------------------xx


    /**
     * 利用規約
     * Terms of service
     * @param int $companyid
     * @param int $storecode
     * @author t.shimizu
     * @since 2011/05/23
     *
     */
    function tos($companyid = 0, $storecode = 0) {
        $store_info = $this->KeitaiSession->GetStoreInfo($this,
        $companyid,
        $storecode,
                                                     "");
        if($store_info == false) {
            $this->_redirect(FAIL_REDIRECT,false);
            exit;
        }

        //[同意する]buttonクリックor表示しないケースなのに直接入力
        if($this->params['form']['p_agree'] || $store_info['tosflg'] != 1 ) {
            $this->redirect(array('action' => 'login', $companyid, $storecode,$sessionid ="agree"));
            exit;
        }

        $this->pageTitle = $store_info['STORENAME'];
        $this->set('loginpath',  "mypage/".$store_info['companyid']."/".$store_info['storecode']);
        $this->set('storename', $store_info['STORENAME']);
        $this->prepare_carrier_output($this->KeitaiSession->getMobileCarrier(),
        $store_info['storeid']);
    }


    /**
     * スタッフの写真を出力する
     *
     * @param $sessionId 現在のセッションID
     * @param $staffCode 検索対象となるスタッフコード
     */
    function s_shashin($sessionId, $staffCode) {
        if (!isset($sessionId, $staffCode)) { $this->_redirect(FAIL_REDIRECT,false); }

        $session_info = $this->KeitaiSession->Check($this, $sessionId);
        if (!$session_info) { $this->_redirect(FAIL_REDIRECT,false); };

        $dbName = $session_info["dbname"];
        $shashin = $this->KeitaiSession->GetStaffShashin($this, $dbName, $staffCode);

        // 写真が設定されていない場合、デフォルトの写真を出力する
        // (取得した写真が10バイト以下の場合、写真が設定されていないと判断する)
        if (strlen($shashin) <= 10) { $this->redirect("/img/no_image.gif"); }

        header("Content-Type: image/jpeg");
        exit($shashin);
    }

    /**
     * キャリアよってに出力を準備する
     * Prepares Layout and encoding depending on carrier
     *
     * @param string $carrier
     * @param int $storeid
     */
    function prepare_carrier_output($carrier, $storeid="", $errcode =0) {
        if($carrier == "docomo_old") {
            $this->output_encoding = "shift_jis";
            $this->action = "docomo_old/".$this->action;
            $this->layout = "docomo_old_layout";
            $logo_image = "ob_logo.gif";
            if ($errcode>0) $this->layout = 'empty_sjis';
        }
        else if($carrier == "docomo_new") {
            $this->action = "docomo_new/".$this->action;
            $this->layout = "keitai_layout";
            $logo_image = "ob_logo.gif";
            if ($errcode>0) $this->layout = 'empty_utf';
        }
        else if($carrier == "softbank") {
            $this->action = "softbank/".$this->action;
            $this->layout = "keitai_layout";
            $logo_image = "ob_logo.png";
            if ($errcode>0) $this->layout = 'empty_utf';
        }
        else if($carrier == "au") {
            $this->action = "au/".$this->action;
            $this->layout = "keitai_layout";
            $logo_image = "ob_logo.gif";
            if ($errcode>0) $this->layout = 'empty_utf';
        }
        else if($carrier == "iphone") { // Added by jonathanparel, 20160923; RM#1724;
            $this->layout = "iphone_layout";
            $this->action = "iphone/".$this->action;
            $logo_image = "ob_logo.png";
            if ($errcode>0) $this->layout = 'empty_utf';
        }
        else if($carrier == "smartphone") {  // Added by jonathanparel, 20160923; RM#1724;
            $this->action = "smartphone/".$this->action;
            $this->layout = "smartphone_layout";
            $logo_image = "ob_logo.png";
            if ($errcode>0) $this->layout = 'empty_utf';
        }
        else if($carrier == "pc") {
            $this->action = "pc/".$this->action;
            $this->layout = "pc_layout";
            $logo_image = "photo_top.jpg";
            if ($errcode>0) $this->layout = 'empty_utf';
        }
        else { // Default
            $this->layout = 'keitai_layout';
            if ($errcode>0) $this->layout = 'empty_utf';
        }
        if($storeid != ""){
            $logo_image = (file_exists(MOBASUTE_PATH_LOCAL."object/".$storeid."/".$logo_image))?
            MOBASUTE_PATH."object/".$storeid."/".$logo_image:"";
            $this->set('logo_image',     $logo_image );
        }
    }

    /**
     * キャリアよってに出力を準備する
     * Changes content encoding if needed
     *
     * @param string $carrier
     * @param int $storeid
     */
    function afterFilter() {
        if($this->output_encoding != "UTF-8") {
            $this->output = mb_convert_encoding( $this->output, $this->output_encoding);
        }
    }

    /**
     * アクションの実行前に実行する
     */
    function beforeFilter() {
        $this->set("ts", time());
    }

    /**
     * ログインページに戻す
     *
     * @param int $companyid
     * @param int $storecode
     */
    function _redirect_timeout($companyid,$storecode)
    {
        $this->redirect('/yk/login/'.$companyid.'/'.$storecode.'/401/', null, true);
    }

    /**
     * タイムスタンプを付加しリダイレクトする
     *
     * @param string $url リダイレクト先のURL
     * @param bool $timestamp　リダイレクト時timestampを付加するかどうか
     * @param int $status リダイレクト時のステータスコード
     * @param bool $url リダイレクトヘッダ送信後 exit() するかの真偽値
     *
     */
    function _redirect($url, $timestamp = true ,$status = null, $exit = true) {
        if($timestamp){
            $url .= "/ts:" . time();
        }
        $this->redirect($url, $status, $exit);
    }

    /** FACEBOOK
     * Link we referred to for creating login with facebook - https://developers.facebook.com/docs/facebook-login/manually-build-a-login-flow/
     * Facebook has a PHP SDK which could have been very convenient to use
     * but we didnot use it as it required higher version(v5.4 or greater) of php than we are using.
     * Initial plan was to implement few more sns login technologies,
     * which can use the same manually created implementation.
     * Therefore, we build the flow manually.
     */
    function facebook_oauth() {
        $this->OauthRedirects(new Facebook());
    }

    /** LINE
     * Link we referred to for creating login with LINE 
     * To get access_token - https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     * To get user details using access_token - https://developers.line.biz/en/docs/social-api/getting-user-profiles/
     */
    function line_oauth() {
        $this->OauthRedirects(new Line());
    }

    /** GOOGLE
     * Link we referred to for creating login with Google - https://developers.google.com/identity/protocols/OAuth2WebServer
     * In API URL of Google, v2/userinfo is used in the official document, but v3/userinfo worked well, so we decided to use v3.
     */
    function google_oauth() {
        $this->OauthRedirects(new Google());
    }

    function OauthRedirects($provider) {
        $params = $this->params['url'];
        $state = json_decode(rawurldecode($params['state']));
        $snsUserInfo = $this->GetUserInfoFromSns($provider, $params['code'], $state->companyid, $state->storecode, $state->antiCSRFtoken);
        $userInfo = $provider->UserInfo($snsUserInfo);
        $this->OauthSipssRedirects($userInfo, $provider->name, $state->companyid, $state->storecode);
    }

    function GetUserInfoFromSns($provider, $snsCode, $companyid, $storecode, $antiCSRF_URLtoken) {

        $antiCSRF_CookieToken  = $this->Cookie->read('antiCSRFCookie');
        if (!isset($snsCode, $antiCSRF_CookieToken, $antiCSRF_URLtoken) || $antiCSRF_CookieToken != $antiCSRF_URLtoken) {
            // If it is not appropriate access, redirect it.
            $this->redirect(MAIN_PATH.'yk/login/'.$companyid.'/'.$storecode);
            exit;
        }

        $data = http_build_query(array(
            'grant_type'    => 'authorization_code',
            'client_id'     => $provider->clientid,
            'redirect_uri'  => $provider->redirectUri,
            'client_secret' => $provider->clientSecret,
            'code'          => $snsCode
        ));

        $curl = curl_init();
        try {
            curl_setopt_array($curl, array(
                CURLOPT_HTTPHEADER      => array('Content-Type: application/x-www-form-urlencoded'),
                CURLOPT_URL             => $provider->accessTokenUrl,
                CURLOPT_CUSTOMREQUEST   => 'POST',
                CURLOPT_POSTFIELDS      => $data,
                CURLOPT_RETURNTRANSFER  => true
            ));
            $response = curl_exec($curl);
        }
        catch (Exception $e) {
            curl_close($curl);
            $this->redirect(MAIN_PATH.'yk/login/'.$companyid.'/'.$storecode.'/401');
            exit;
        }
        curl_close($curl);
        $json = json_decode($response);

        $curl = curl_init();
        // Get user data.
        try {
            curl_setopt_array($curl,array(
                CURLOPT_HTTPHEADER      => array("Authorization: Bearer {$json->access_token}"),
                CURLOPT_URL             => $provider->apiUrl,
                CURLOPT_CUSTOMREQUEST   => 'GET',
                CURLOPT_RETURNTRANSFER  => true
            ));
            $response = curl_exec($curl);
        }
        catch (Exception $e) {
            curl_close($curl);
            $this->redirect(MAIN_PATH.'yk/login/'.$companyid.'/'.$storecode.'/401');
            exit;
        }
        curl_close($curl);
        $json = json_decode($response);

        return $json;
    }
    function OauthSipssRedirects($userInfo, $providerName, $companyid, $storecode) {
        if ($userInfo["userId"] == ""){
            $this->redirect(MAIN_PATH.'yk/login/'.$companyid.'/'.$storecode.'/401');
            exit;
        }

        $sessionid = $this->KeitaiSession->CreateSnsSession($this, $userInfo["userId"], $providerName, $companyid, $storecode);
        if (!$sessionid) {
            $this->redirect('/yk/login/'.$companyid.'/'.$storecode.'/401');
            exit();
        }
        $session_info = $this->KeitaiSession->Check($this, $sessionid);
        if (!$session_info) {
            $this->redirect('/yk/login/'.$companyid.'/'.$storecode.'/401');
            exit();
        }

        $snsdata = array(
            'snsid'      => $userInfo["userId"],
            'snsemail'   => $userInfo["userEmail"],
            'snsname'    => $userInfo["userName"],
            'provider'   => $providerName
        );
        $this->Cookie->path     = COOKIE_PATH;
        $this->Cookie->domain   = COOKIE_DOMAIN;
        $this->Cookie->secure   = COOKIE_HTTPS_ONLY;
        $expires = COOKIE_EXPIRATION_DAY * 60 * 60 * 24;
        $this->Cookie->write("snsdata", $snsdata, true, $expires);

        if ($session_info['y_status'] == 5){
            // Existing sns Customer
            $this->redirect('/yk/mypage/'.$session_info['companyid'].'/'.$session_info['storecode'].'/'.$sessionid);
            exit();

        } elseif ($session_info['y_status'] == 8) {
            // New sns Customer
            $url = MAIN_PATH."yk/reg/".$sessionid;
            $this->_redirect($url, true, null, false);
            exit();
        }
    } 
    
}

abstract class SNS {
    public $name;
    public $clientid; 
    public $redirectUri;
    public $clientSecret;
    public $accessTokenUrl;
    public $apiUrl;

    abstract function UserInfo($customerInfo);
}

class Facebook extends SNS {
    function __construct() {
        $this->name           = "Facebook";
        $this->clientid       = FACEBOOK_OAUTH_CHANNEL_ID;
        $this->redirectUri    = FACEBOOK_OAUTH_REDIRECT_URL;
        $this->clientSecret   = FACEBOOK_OAUTH_CHANNEL_SECRET;
        $this->accessTokenUrl = FACEBOOK_ACCESS_TOKEN_URL;
        $this->apiUrl         = FACEBOOK_API_URL;
    }

    public function UserInfo($customerInfo){
        return array("userId"    => $customerInfo->id,
                     "userName"  => $customerInfo->name,
                     "userEmail" => $customerInfo->email);
    }
}
class Line extends SNS {
    function __construct() {
        $this->name           = "Line";
        $this->clientid       = LINE_OAUTH_CHANNEL_ID;
        $this->redirectUri    = LINE_OAUTH_REDIRECT_URL;
        $this->clientSecret   = LINE_OAUTH_CHANNEL_SECRET;
        $this->accessTokenUrl = LINE_ACCESS_TOKEN_URL;
        $this->apiUrl         = LINE_API_URL;
    }

    public function UserInfo($customerInfo){
        return array("userId"    => $customerInfo->userId,
                     "userName"  => $customerInfo->displayName,
                     "userEmail" => ""); // requires to apply for permission from line console app to use email
    }
}
class Google extends SNS {
    function __construct() {
        $this->name           = "Google";
        $this->clientid       = GOOGLE_OAUTH_CHANNEL_ID;
        $this->redirectUri    = GOOGLE_OAUTH_REDIRECT_URL;
        $this->clientSecret   = GOOGLE_OAUTH_CHANNEL_SECRET;
        $this->accessTokenUrl = GOOGLE_ACCESS_TOKEN_URL;
        $this->apiUrl         = GOOGLE_API_URL;
    }

    public function UserInfo($customerInfo){
        return array("userId"    => $customerInfo->sub,
                     "userName"  => $customerInfo->name,
                     "userEmail" => $customerInfo->email);
    }
}

?>
