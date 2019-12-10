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
 *
 */

class AppController extends Controller {

    var $view = 'Smarty';
    var $helpers = array('SmartyHtml', 'SmartySession','SmartyText','SmartyAjax', 'SmartyCache',
                         'SmartyForm', 'SmartyJavascript', 'SmartyNumber', 'SmartyTime',
	 					 'ajax', 'cache', 'form', 'html', 'javascript', 'number',
	 					 'paginator', 'rss', 'session', 'text', 'time', 'xml');

    // 以下、コード補完用

    /**
     * BreakTime モデル
     *
     * @var BreakTime
     */
    var $BreakTime;

    /**
     * Company モデル
     *
     * @var Company
     */
    var $Company;

    /**
     * Customer モデル
     *
     * @var Customer
     */
    var $Customer;

    /**
     * CustomerSns モデル
     *
     * @var CustomerSns
     */
    var $CustomerSns;

    /**
     * CustomerTotal モデル
     *
     * @var CustomerTotal
     */
    var $CustomerTotal;

    /**
     * DataShare モデル
     *
     * @var DataShare
     */
    var $DataShare;

    /**
     * FinishedShift モデル
     *
     * @var FinishedShift
     */
    var $FinishedShift;

    /**
     * Login モデル
     *
     * @var Login
     */
    var $Login;

    /**
     * LogSession モデル
     *
     * @var LogSession
     */
    var $LogSession;

    /**
     * LogSessionKeitai モデル
     *
     * @var LogSessionKeitai
     */
    var $LogSessionKeitai;

    /**
     * Position モデル
     *
     * @var Position
     */
    var $Position;

    /**
     * Service モデル
     *
     * @var Service
     */
    var $Service;

    /**
     * ServiceList モデル
     *
     * @var ServiceList
     */
    var $ServiceList;

    /**
     * Shift モデル
     *
     * @var Shift
     */
    var $Shift;

    /**
     * Staff モデル
     *
     * @var Staff
     */
    var $Staff;

    /**
     * StaffAssignToStore モデル
     *
     * @var StaffAssignToStore
     */
    var $StaffAssignToStore;

    /**
     * StaffRowsHistory モデル
     *
     * @var StaffRowsHistory
     */
    var $StaffRowsHistory;

    /**
     * StaffShift モデル
     *
     * @var StaffShift
     */
    var $StaffShift;

    /**
     * StoreTypeモデル
     *
     * @var StaffType
     */
    var $StaffType;
    
    /**
     * Store モデル
     *
     * @var Store
     */
    var $Store;

    /**
     * StoreAccount モデル
     *
     * @var StoreAccount
     */
    var $StoreAccount;

    /**
     * StoreHoliday モデル
     *
     * @var StoreHoliday
     */
    var $StoreHoliday;

    /**
     * StoreService モデル
     *
     * @var StoreService
     */
    var $StoreService;

    /**
     * Storetype モデル
     *
     * @var Storetype
     */
    var $Storetype;    
    
    
    /**
     * StoreSettings モデル
     *
     * @var StoreSettings
     */
    var $StoreSettings;

    /**
     * StoreTransaction モデル
     *
     * @var StoreTransaction
     */
    var $StoreTransaction;

     /**
     * StoreTransaction モデル
     *
     * @var StoreTransactionDetails
     */
    var $StoreTransactionDetails;
    
    /**
     * StoreTransactionColors モデル
     *
     * @var StoreTransactionColors
     */
    var $StoreTransactionColors;

    /**
     * Sublevel モデル
     *
     * @var Sublevel
     */
    var $Sublevel;

    /**
     * SubService モデル
     *
     * @var SubService
     */
    var $SubService;

    /**
     * WebyanAccount モデル
     *
     * @var WebyanAccount
     */
    var $WebyanAccount;

    /**
     * YoyakuMessage モデル
     *
     * @var YoyakuMessage
     */
    var $YoyakuMessage;
    
    /**
     * YoyakuStaffServiceTime モデル
     *
     * @var YoyakuStaffServiceTime
     */
    var $YoyakuStaffServiceTime;

    /**
     * Zipcode モデル
     *
     * @var Zipcode
     */
    var $Zipcode;

    /**
     * Email コンポーネント
     *
     * @var CookieComponent
     */
    var $Cookie;

    /**
     * Email コンポーネント
     *
     * @var EmailComponent
     */
    var $Email;

    /**
     * KeitaiSession コンポーネント
     *
     * @var KeitaiSessionComponent
     */
    var $KeitaiSession;

    /**
     * MiscFunction コンポーネント
     *
     * @var MiscFunctionComponent
     */
    var $MiscFunction;

    /**
     * RequestHandler コンポーネント
     *
     * @var RequestHandlerComponent
     */
    var $RequestHandler;

    /**
     * YoyakuSession コンポーネント
     *
     * @var YoyakuSessionComponent
     */
    var $YoyakuSession;

    #------------------------------------------------------------------------------------------------------------------------
    # ADDED BY MARVINC - 2015-06-22
    # For Updating Next Reservation
    #------------------------------------------------------------------------------------------------------------------------
     /**
     * Syscode 
     *
     * @var Syscode
     */
    var $Syscode;
    #------------------------------------------------------------------------------------------------------------------------
    # Added by jonathanparel, 20160930; RM#1789
    # For mobasute_storeinfo.message
    #------------------------------------------------------------------------------------------------------------------------
     /**
     * Syscode 
     *
     * @var Syscode
     */
    var $MobasuteStoreInfo;
    #------------------------------------------------------------------------------------------------------------------------
}

?>