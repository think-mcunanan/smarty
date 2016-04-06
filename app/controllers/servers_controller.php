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

App::import('Vendor', 'WebServicesController');

class ServersController extends WebServicesController
{
    var $name           = 'Servers';
    
    var $uses           = array('Login', 'LogSession', 'Customer', 'Store', 'Staff',
                                'StoreAccount', 'WebyanAccount', 'Service', 'StoreService',
                                'Position', 'Sublevel', 'StaffRowsHistory', 'Zipcode',
                                'StaffShift', 'Shift', 'SubService','StoreTransaction',
                                'StaffAssignToStore', 'StoreSettings', 'StoreTransactionColors',
                                'BreakTime', 'FinishedShift', 'StoreHoliday', 'YoyakuMessage',
                                'YoyakuStaffServiceTime','YoyakuNext','Stafftype', 'Syscode', //Update by MarvinC 2015-06-24
        
        );

    
 

    var $components     = array('YoyakuSession','RequestHandler','MiscFunction');
    var $autoRender     = false;

    var $arrDays        = array('day1', 'day2', 'day3', 'day4', 'day5',
                                'day6', 'day7', 'day8', 'day9', 'day10',
                                'day11','day12','day13','day14','day15',
                                'day16','day17','day18','day19','day20',
                                'day21','day22','day23','day24','day25',
                                'day26','day27','day28','day29','day30', 'day31');


    //--.NETクライアント側の使用してるSOAP機能 (SOAP Functions used by .NET client)
    var $api = array(// LOGIN / LOGOUT FUNCTIONS ------------------------------------
                     'wsLogin' => array(
                            'doc'    => 'ログインとセッション作成',
                            'input'  => array('param'  => 'loginInfo'),
                            'output' => array('return' => 'return_storeInfo')),

                     'wsLogout' => array(
                            'doc'    => 'ログアウトとセッション削除',
                            'input'  => array('sessionid' => 'xsd:string'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // CUSTOMER FUNCTIONS ------------------------------------------
                     'wsSearchCustomer' => array(
                            'doc'    => '顧客検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'customerSearchCriteria'),
                            'output' => array('return'    => 'return_customerInformation')),

                     'wsSearchCustomerHistory' => array(
                            'doc'    => '顧客歴史検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'ccode'     => 'xsd:string'),
                            'output' => array('return'    => 'return_storeTransactionInformation')),

                     'wsAddUpdateCustomer' => array(
                            'doc'    => '顧客追加・アップデート',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'customerInformation'),
                            'output' => array('return'    => 'return_customerIDs')),

                     'wsDeleteCustomer' => array(
                            'doc'    => '顧客削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'ccode'     => 'xsd:string'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // STAFF FUNCTIONS ---------------------------------------------
                     'wsSearchStaff' => array(
                            'doc'    => 'スタッフ検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'staffSearchCriteria'),
                            'output' => array('return'    => 'return_staffInformation')),

                     'wsSearchAvailableStaff' => array(
                            'doc'    => '有効スタッフ検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'availableStaffSearchCriteria'),
                            'output' => array('return'    => 'return_staffInformation')),

                     'wsAddUpdateStaff' => array(
                            'doc'    => 'スタッフの追加・アップデート',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'staffInformation'),
                            'output' => array('return'    => 'xsd:int')),

                     'wsAddUpdateStaffRowsHistory' => array(
                            'doc'    => 'スタッフ歴史行の追加と更新機能',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'staffRowsHistoryInformation'),
                            'output' => array('return'    => 'xsd:int')),

                     'wsDeleteStaff' => array(
                            'doc'    => 'スタッフ削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'staffcode' => 'xsd:int'),
                            'output' => array('return'    => 'xsd:boolean')),

                     'wsUpdateFlagsStaff' => array(
                            'doc'    => '表示フラグをアップデート',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'staffInformation'),
                            'output' => array('return'    => 'xsd:boolean')),
        
                     'wsUpdateStaffType' => array(
                            'doc'        => 'UpdateStaffType',
                            'input'      => array('sessionid'      => 'xsd:string',
                                                  'staffcode'      => 'xsd:int',
                                                  'stafftypecodes' => 'xsd:string'),
                            'output'     => array('return'         => 'xsd:boolean')),

        
                     //- ############################################################
                     'wsUpdateStaffDisplayGyoshukubun' => array(
                            'doc'        => 'UpdateStaffYoyakuView',
                            'input'      => array('sessionid'      => 'xsd:string',
                                                  'staffcode'      => 'xsd:int',
                                                  'stafftypecodes' => 'xsd:string'),
                            'output'     => array('return'         => 'xsd:boolean')),
                     //- ############################################################



                     // STAFF SHIFT FUNCTIONS ---------------------------------------
                     'wsSearchStaffShift' => array(
                            'doc'    => 'スタッフシフト検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'staffShiftSearchCriteria'),
                            'output' => array('return'    => 'return_staffShiftInformation')),

                     'wsAddUpdateDeleteStaffShift' => array(
                            'doc'    => 'スタッフシフト追加・アップデート・削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => '_staffShiftInformation'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // STORE FUNCTIONS ---------------------------------------------
                     'wsSearchStore' => array(
                            'doc'    => '店舗検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'storeSearchCriteria'),
                            'output' => array('return'    => 'return_storeInformation')),

                     'wsAddUpdateStore' => array(
                            'doc'    => '店舗追加・アップデート',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'storeInformation'),
                            'output' => array('return'    => 'xsd:int')),

                     'wsDeleteStore' => array(
                            'doc'    => '店舗削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'storecode' => 'xsd:int'),
                            'output' => array('return'    => 'xsd:boolean')),
                          //- ############################################################



                     // STORE HOLIDAY FUNCTIONS -------------------------------------
                     'wsSearchStoreHoliday' => array(
                            'doc'    => '店舗休日検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'storeHolidaySearchCriteria'),
                            'output' => array('return'    => 'return_storeHolidayInformation')),

                     'wsAddUpdateDeleteStoreHoliday' => array(
                            'doc'    => '店舗休日追加・アップデート・削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'storeHolidayInformation'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // SERVICE FUNCTIONS -------------------------------------------
                     'wsSearchService' => array(
                            'doc'    => '技術検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'serviceSearchCriteria'),
                            'output' => array('return'    => 'return_serviceInformation')),

                     'wsAddUpdateService' => array(
                            'doc'    => '技術追加・アップデート',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'serviceInformation'),
                            'output' => array('return'    => 'xsd:int')),

                     'wsDeleteService' => array(
                            'doc'    => '技術削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'gdcode'    => 'xsd:int'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // SHIFT FUNCTIONS ---------------------------------------------
                     'wsSearchShift' => array(
                            'doc'    => 'シフト検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'shiftSearchCriteria'),
                            'output' => array('return'    => 'return_shiftInformation')),

                     'wsAddUpdateShift' => array(
                            'doc'    => 'シフト追加・アップデート',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'shiftInformation'),
                            'output' => array('return'    => 'xsd:int')),

                     'wsDeleteShift' => array(
                            'doc'    => 'シフト削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'shiftid'   => 'xsd:int'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // STORE SERVICE FUNCTIONS -------------------------------------
                     'wsSearchStoreService' => array(
                            'doc'    => '店舗技術検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'storeServiceSearchCriteria'),
                            'output' => array('return'    => 'return_storeServiceInformation')),

                     'wsAddUpdateDeleteStoreService' => array(
                            'doc'    => '店舗技術追加・アップデート・削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'storeServiceInformation'),
                            'output' => array('return'    => 'return_serviceIDs')),

                     'wsSearchStoreServiceWhosUsing' => array(
                            'doc'    => '店舗技術利用検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'storeServiceWhosUsingSearchCriteria'),
                            'output' => array('return'    => 'return_storeInformation')),
                     //- ############################################################



                     // POSITION FUNCTIONS ------------------------------------------
                     'wsSearchPosition' => array(
                            'doc'    => 'Position検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'positionSearchCriteria'),
                            'output' => array('return'    => 'return_positionInformation')),
                     //- ############################################################



                     // SUBLEVEL FUNCTIONS ------------------------------------------
                     'wsSearchSublevel' => array(
                            'doc'    => 'Sublevel検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'sublevelSearchCriteria'),
                            'output' => array('return'    => 'return_sublevelInformation')),
                     //- ############################################################



                     // ZIPCODE FUNCTIONS -------------------------------------------
                     'wsSearchZipcode' => array(
                            'doc'    => '郵便番号検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'zipcodeSearchCriteria'),
                            'output' => array('return'    => 'return_zipcodeInformation')),
                     //- ############################################################



                     // BASIC SETTINGS FUNCTIONS ------------------------------------
                     'wsReadBasicSettings' => array(
                            'doc'    => '基本情報設定を読み込む',
                            'input'  => array('sessionid' => 'xsd:string'),
                            'output' => array('return'    => 'basicInformation')),
                     'wsWriteBasicSettings' => array(
                            'doc'    => '基本情報設定を書き込む',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'basicInformation'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // MESSAGE SETTINGS FUNCTIONS ----------------------------------
                     'wsReadMessageSettings' => array(
                            'doc'    => 'メセッジ設定を読み込む',
                            'input'  => array('sessionid' => 'xsd:string'),
                            'output' => array('return'    => 'messageInformation')),
                     'wsWriteMessageSettings' => array(
                            'doc'    => 'メセッジ設定を書き込む',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'messageInformation'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // COLOR FUNCTIONS ---------------------------------------------
                     'wsSearchColor' => array(
                            'doc'    => '店舗の色設定を検索する',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'colorSearchCriteria'),
                            'output' => array('return'    => 'return_colorInformation')),
                     'wsAddUpdateColor' => array(
                            'doc'    => '店舗の色設定を追加・編集する',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'colorInformation'),
                            'output' => array('return'    => 'xsd:int')),
                     'wsDeleteColor' => array(
                            'doc'    => '店舗の色設定を削除する',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'id'        => 'xsd:int'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // STORE TRANSACTION FUNCTIONS ---------------------------------
                     'wsSearchStoreTransaction' => array(
                            'doc'    => 'トランザクション検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'storeTransactionSearchCriteria'),
                            'output' => array('return'    => 'return_storeTransactionInformation')),

                     'wsAddUpdateStoreTransaction' => array(
                            'doc'    => 'トランザクションの追加・アップデート',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'storeTransactionInformation'),
                            'output' => array('return'    => 'return_transactionIDs')),

                     'wsDeleteStoreTransaction' => array(
                            'doc'    => 'トランザクション削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'transcode' => 'xsd:string'),
                            'output' => array('return'    => 'xsd:boolean')),

                     'wsCancelStoreTransaction' => array(
                            'doc'    => 'トランザクションキャンセル',
                            'input'  => array('sessionid'  => 'xsd:string',
                                              'transcode'  => 'xsd:string',
                                              'keyno'      => 'xsd:int'),
                            'output' => array('return'     => 'xsd:boolean')),
                     //- ############################################################

                     //----------------------------------   
                     //Add Update Staff Menu Service Time
                     //----------------------------------
                     'wsAddUpdateStaffMenuServiceTime' => array(
                            'doc'    => 'AddUpdateStaffMenuServiceTime',
                            'input'  => array('sessionid'           => 'xsd:string',
                                              'storecode'           => 'xsd:int',
                                              'staffcode'           => 'xsd:int',
                                              'gcode'               => 'xsd:int',
                                              'ismale'              => 'xsd:boolean',
                                              'time'                => 'xsd:int'),
                            'output' => array('return'    => 'xsd:boolean')),   
        

                     // BREAK TIME FUNCTIONS ----------------------------------------
                     'wsSearchBreakTime' => array(
                            'doc'    => '外出検索',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'breakTimeSearchCriteria'),
                            'output' => array('return'    => 'return_breakTimeInformation')),

                     'wsAddUpdateBreakTime' => array(
                            'doc'    => '外出の追加・アップデート',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'breakTimeInformation'),
                            'output' => array('return'    => 'xsd:int')),

                     'wsDeleteBreakTime' => array(
                            'doc'    => '外出削除',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'breakid'   => 'xsd:int'),
                            'output' => array('return'    => 'xsd:boolean')),
                     //- ############################################################



                     // DATA OF THE DAY FUNCTIONS -----------------------------------
                     'wsGetDataOfTheDay' => array(
                            'doc'    => '日のデータを取得',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'dataOfTheDaySearchCriteria'),
                            'output' => array('return'    => 'return_dataOfTheDayInformation')),
                     //- ############################################################



                     // TRANSACTION CALENDAR VIEW FUNCTIONS -------------------------
                     'wsGetTransactionCalendarView' => array(
                            'doc'    => 'トランザクションの検索カレンダー',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'param'     => 'transactionCalendarViewSearchCriteria'),
                            'output' => array('return'    => 'return_transactionCalendarViewInformation')),
                     //- ############################################################


                      // Get Staff Menu Service Time -------------------------
                     'wsGetStaffMenuServiceTime' => array(
                            'doc'    => 'wsGetStaffMenuServiceTime',
                            'input'  => array('sessionid'     => 'xsd:string',
                                              'storecode'     => 'xsd:int',
                                              'staffcode'     => 'xsd:int',
                                              'gcode'     => 'xsd:int'),
                            'output' => array('success'      => 'xsd:boolean',
                                              'female_time'      => 'xsd:int',
                                              'male_time'      => 'xsd:int')),
                     //- ############################################################
        
        
                     // Get Store Menu Service Time -------------------------
                     'wsGetStoreMenuServiceTime' => array(
                            'doc'    => 'wsGetStoreMenuServiceTime',
                            'input'  => array('sessionid'   => 'xsd:string',
                                              'storecode'   => 'xsd:int'),
                            'output' => array('return'      => '_storeMenuServiceTime')),
                     //- ############################################################   
        

                     // STAFF TAB FUNCTIONS -----------------------------------------
                     'wsGetAllOnStaffTab' => array(
                            'doc'    => '位置とサブレベルのと店舗リストデータを取得',
                            'input'  => array('sessionid' => 'xsd:string'),
                            'output' => array('return'    => 'return_staffTabInformation')),
                     //- ############################################################



                     // FIRST LOAD FUNCTIONS ----------------------------------------
                     'wsGetAllOnFirstLoad' => array(
                            'doc'    => '最初の負荷に関するデータを得て',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'date'      => 'xsd:string'),
                            'output' => array('return'    => 'return_firstLoadInformation')),
                     //- ############################################################



                     // YOYAKU DETAILS FUNCTIONS ------------------------------------
                     'wsSearchYoyakuDetails' => array(
                            'doc'    => '予約詳細検索',
                            'input'  => array('sessionid'         => 'xsd:string',
                                              'begindate'         => 'xsd:string',
                                              'enddate'           => 'xsd:string',
                                              'storecode'         => 'xsd:int',
                                              'staffcode'         => 'xsd:int',
                                              'uketsukestaffcode' => 'xsd:int'),
                                              'output' => array('return'    => 'return_yoyakuDetailsInformation')),
                     //- ############################################################


                      // GET MARKETING -----------------------------------
                     'wsGetMarketing' => array(
                            'doc'    => 'GetMarketing',
                            'input'  => array('sessionid'    => 'xsd:string',
                                              'storecode'    => 'xsd:int',
                                              'ymd'          => 'xsd:string'),
                            'output' => array('return'    => 'return_rejiMarketingInformation')),
                     //- ############################################################
        
                     // GET STAFFS RECORD  -----------------------------------
                     'wsGetStaffs' => array(
                            'doc'    => 'GetStaffs',
                            'input'  => array('sessionid'    => 'xsd:string',
                                              'storecode'    => 'xsd:int',
                                              'ymd'          => 'xsd:string'),
                            'output' => array('return'    => 'return_staffInformation')),
                     //- ############################################################

        
                     // GET GYOSHU KUBUN(servicesys)  -----------------------------------
                     'wsGetGyoshuKubun' => array(
                            'doc'    => 'GetGyoshuKubun',
                            'input'  => array('sessionid'    => 'xsd:string',
                                              'storecode'    => 'xsd:int'),
                            'output' => array('return'    => 'return_gyoshukubun')),
                     //- ############################################################
        
                     // GET HOW KNOWS THE STORE RECORDS  -----------------------------------
                     'wsGetHowKnowsTheStore' => array(
                            'doc'    => 'GetHowKnowsTheStore',
                            'input'  => array('sessionid'    => 'xsd:string'),
                            'output' => array('return'    => 'return_howKnowsTheStoreInformation')),
                     //- ############################################################
        
                     // MAIL FUNCTIONS ------------------------------------
                     'wsSendMail' => array(
                            'doc'    => 'メール送信',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'from'      => 'xsd:string',
                                              'to'        => 'xsd:string',
                                              'cc'        => 'xsd:string',
                                              'bcc'       => 'xsd:string',
                                              'subject'   => 'xsd:string',
                                              'body'      => 'xsd:string'),
                                              'output'    => 'xsd:boolean'),
                     //- ############################################################

                     // JIKAI_YOYAKU DETAILS FUNCTIONS ------------------------------------
                     'wsSearchJikaiYoyaku' => array(
                            'doc'    => '次回予約リスト',
                            'input'  => array('sessionid'         => 'xsd:string',
                                              'storecode'         => 'xsd:int'),
                            'output' => array('return'    => 'return_storeTransactionInformation')),

                     'wsDeleteJikaiYoyaku' => array(
                            'doc'    => '次回予約削除',
                            'input'  => array('sessionid' =>'xsd:string',
                                              'transcode' => 'xsd:string',
                         					  'storecode' => 'xsd:int',
                                              'changeyoyaku'=> 'xsd:boolean' ),
                            'output' => array('return'    =>'xsd:boolean')),
        
                     //- ############################################################           
                     // GetOkotowariTodaysCount -----------------------------------
                     'wsGetOkotowariTodaysCount' => array(
                            'doc'    => 'wsGetOkotowariTodaysCount',
                            'input'  => array('sessionid'    => 'xsd:string',
                                              'storecode'    => 'xsd:int',
                                              'ymd'          => 'xsd:string'),
                            'output' => array('unregistered'    => 'xsd:int',
                                              'registered'    => 'xsd:int')),
                     //- ############################################################
        
                     // wsAddOkotowari -----------------------------------
                     'wsAddOkotowari' => array(
                            'doc'    => 'wsAddOkotowari',
                            'input'  => array('sessionid'    => 'xsd:string',
                                              'storecode'    => 'xsd:int',
                                              'ymd'          => 'xsd:string',
                                              'time'          => 'xsd:string',
                                              'ccode'          => 'xsd:string'),
                            'output' => array()),
                     //- ############################################################
        
                     // wsUpdateOkotowari -----------------------------------
                     'wsUpdateOkotowari' => array(
                            'doc'    => 'wsUpdateOkotowari',
                            'input'  => array('sessionid'    => 'xsd:string',
                                              'oid'          => 'xsd:int',
                                              'ymd'          => 'xsd:string',
                                              'time'         => 'xsd:string',
                                              'ccode'        => 'xsd:string'),
                            'output' => array()),
                     //- ############################################################   
        
                     // wsCheckCustomer -----------------------------------
                     'wsCheckCustomer' => array(
                            'doc'    => 'wsCheckCustomer',
                            'input'  => array('sessionid'    => 'xsd:string',
                                              'cnumber'          => 'xsd:string',
                                              'ccode'          => 'xsd:string'),
                            'output' => array('ccode_output'    => 'xsd:string',
                                              'cnumber_output'    => 'xsd:string',
                                              'cname'    => 'xsd:string',
                                              'cnamekana'    => 'xsd:string',
                                              'sex'    => 'xsd:int')),
                     //- ############################################################c
        
                     // GetOkotowariHistory -----------------------------------
                     'GetOkotowariHistory' => array(
                            'doc'    => 'GetOkotowariHistory',
                            'input'  => array('sessionid'       => 'xsd:string',
                                              'storecode'       => 'xsd:int',
                                              'datefrom'        => 'xsd:string',
                                              'dateto'          => 'xsd:string',
                                              'cnumber'         => 'xsd:string',
                                              'cname'           => 'xsd:string',
                                              'sex'             => 'xsd:int',
                                              'category'        => 'xsd:int'),
                            'output' => array('return'    => '_okotowariInformation')),
                     //- ############################################################
        
                     // DeleteOkotowariRecord -----------------------------------
                     'wsDeleteOkotowariRecord' => array(
                            'doc'    => 'wsDeleteOkotowariRecord',
                            'input'  => array('sessionid'    => 'xsd:string',
                                              'oid'    => 'xsd:int'),
                            'output' => array()),
                     //- ############################################################ 
                       
                     // GetShiftSimulationPassword -----------------------------------
                     'wsGetShiftSimulationPassword' => array(
                            'doc'    => 'wsGetShiftSimulationPassword',
                            'input'  => array('sessionid'    => 'xsd:string',
                                              'storecode'    => 'xsd:int'),
                            'output' => array('pwd'          => 'xsd:string')),
                     //- ############################################################
                     
                     //wsGetYoyakuAllowTransToStore-------------------------------------
                     'wsGetYoyakuAllowTransToStore'   => array(
                            'doc'    => 'wsGetYoyakuAllowTransToStore',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'storecode' => 'xsd:int'),
                            'output' => array('return' => '_YoyakuAllowTransToStore')),
                     //- ############################################################ 
        
                     //wsGetTransactionByTransCode----------------------------------------
                     'wsGetTransactionByTransCode'   => array(
                            'doc'    => 'wsGetTransactionStatus',
                            'input'  => array('sessionid' => 'xsd:string',
                                              'transcode' => 'xsd:string'),
                            'output' => array('return'    =>'tns:storeTransactionInformation')),
                            
                     //--------------------------------------------------------------
                     //Add or Update Transaction Uketsuke
                     //--------------------------------------------------------------
                      'wsAddUpdateTransUketsuke' => array(
                            'doc'    => 'AddUpdateTransUketsuke',
                            'input'  => array('sessionid'  => 'xsd:string',
                                              'transcode'  => 'xsd:string',
                                              'uketsukedate'  => 'xsd:string',
                                              'uketsukestaff' => 'xsd:int'),
                            'output' => array('return'     => 'xsd:boolean')),
                     //- ############################################################
        
                     //==============================================================
                     //customer search listing for Ingtegration
                     //by albert 2015-11-18
                     //--------------------------------------------------------------
                      'wsGetCustomerList' => array(
                            'doc'    => 'GetCustomerListing',
                            'input'  => array('sessionid'   => 'xsd:string',
                                              'storecode'   => 'xsd:int',
                                              'datefr'      => 'xsd:string',
                                              'dateto'      => 'xsd:string',
                                              'firstdate'   => 'xsd:boolean',
                                              'pageindex'   => 'xsd:int',
                                              'filename1'   => 'xsd:string',
                                              'allstoreflg' => 'xsd:int',
                                              'basecode'    => 'xsd:string'),
                            'output' => array('return'      => 'tns:return_storeCustomerListing')),
        
                            'wsCustomerMergeSave' => array(
                            'doc'    => 'saveCustomerMerging',
                            'input'  => array('sessionid'   => 'xsd:string',
                                              'strcode'     => 'xsd:int',
                                              'fromccode'   => 'xsd:string',
                                              'toccode'     => 'xsd:string',
                                              'companyid'   => 'xsd:int',
                                              'param'       => 'customerInformation'),
                            'output' => array('return'      => 'xsd:int')),
        
                      'wsGetReservation' => array(
                            'doc'    => 'GetReservation',
                            'input'  => array('sessionid'   => 'xsd:string',
                                              'storecode'   => 'xsd:int',
                                              'origination' => 'xsd:int',  
                                              'datefr'      => 'xsd:string',
                                              'dateto'      => 'xsd:string',
                                              'pageno'      => 'xsd:int',
                                              'ascsort'     => 'xsd:int',
                                              'colsort'     => 'xsd:int',
                                              'syscode'     => 'xsd:int'),
                            'output' => array('return'      => 'tns:return_storeReservationListing')),
        
        
                      'wsGetReservationCounter' => array(
                            'doc'    => 'GetReservationCounter',
                            'input'  => array('sessionid'   => 'xsd:string',
                                              'storecode'   => 'xsd:int',
                                              'datefr'      => 'xsd:string',
                                              'dateto'      => 'xsd:string'),
                            'output' => array('return'      => 'tns:return_storeReservationCounter')),
        
        
                      'wsUpdateTransaction2' => array(
                            'doc'    => 'updatetransaction2',
                            'input'  => array('sessionid'   => 'xsd:string',
                                              'transcode'   => 'xsd:string',
                                              'keyno'       => 'xsd:int',  
                                              'alreadyread' => 'xsd:int',
                                              'syscode'     => 'xsd:int'),
                            'output' => array('return'      => 'xsd:int')),
        
                     //--------------------------------------------------------------
                     //customer search listing for Ingtegration
                     //by albert 2015-11-18
                     //==============================================================
        
                     //--------------------------------------------------------------
                     // GET STORE EMAIL DOMAIN
                     // Added by: MarvinC - 2015-12-05 14:34
                     //--------------------------------------------------------------
                      'wsGetMailDomain' => array(
                            'doc'    => 'wsGetMailDomain',
                            'input'  => array('sessionid'  => 'xsd:string',
                                              'companyid'  => 'xsd:int',
                                              'storecode'  => 'xsd:int'),
                            'output' => array('return'     => 'xsd:string')),
                     //- ############################################################
                     
        
                     //--------------------------------------------------------------
                     // GET POWERS SETTINGS
                     // Added by: MarvinC - 2015-12-28 16:35
                     //--------------------------------------------------------------
                      'wsGetReturningCustomerCountAll' => array(
                            'doc'    => 'wsGetReturningCustomerCountAll',
                            'input'  => array('sessionid'  => 'xsd:string'),
                            'output' => array('return'     => 'xsd:int'))
                     //- ############################################################
        
                     );

    //-- Complexタイプの参照 (Complex Type Definitions)
    var $complexTypes = array(// LOGIN ----------------------------------------------
                             'return_storeInfo' => array('struct' => array(
                                        'sessionid'         => 'xsd:string',
                                        'companyid'         => 'xsd:int',
                                        'storecode'         => 'xsd:int',
                                        'storename'         => 'xsd:string',
                                        'hasTenpo'          => 'xsd:int',
                                        'hasCust'           => 'xsd:int',
                                        'hasHonbu'          => 'xsd:int',
                                        'hasBrowser'        => 'xsd:int',
                                        'hasYoyaku'         => 'xsd:int',
                                        'YOYAKU_OPEN_TIME'  => 'xsd:int',
                                        'YOYAKU_CLOSE_TIME' => 'xsd:int',
                                        'YOYAKU_HYOU_OPEN_TIME'  => 'xsd:int',
                                        'YOYAKU_HYOU_CLOSE_TIME' => 'xsd:int',
                                        'YOYAKU_OPEN_TIME_SATSUN'  => 'xsd:int',
                                        'YOYAKU_CLOSE_TIME_SATSUN' => 'xsd:int',
                                        'UPPER_LIMIT'         => 'xsd:int',
                                        'UPPER_LIMIT_OP'      => 'xsd:string',
                                        'OPEN_TIME'         => 'xsd:int',
                                        'CLOSE_TIME'        => 'xsd:int',
                                        'storemail'         => 'xsd:string',
                                        'oemflg'            => 'xsd:int',
                                        'storetype'         => 'tns:storetypeInformation',
                                        'allstoretype'      => 'tns:AllStoreTypes')),
        
                             '_AllStoreTypes' => array('struct' => array(
                                                        'STORECODE'       => 'xsd:int',
                                                        'STORETYPES'      => 'xsd:string')),
                             'AllStoreTypes' => array(
                                                      'array' => '_AllStoreTypes'),
        
                             '_storetypeInformation' => array('struct' => array(
                                                    'STORECODE'       => 'xsd:int',
                                                    'STAFFCODE'       => 'xsd:int',
                                                    'SYSCODE'         => 'xsd:int',
                                                    'SHORTCUTCODE'    => 'xsd:string',
                                                    'DESCRIPTION'     => 'xsd:string')),
                             'storetypeInformation' => array(
                                        'array' => '_storetypeInformation'),
    
                             'loginInfo' => array('struct' => array(
                                        'username'     => 'xsd:string',
                                        'password'     => 'xsd:string',
                                        'computername' => 'xsd:string')),
                             //- ####################################################



                             // CUSTOMER --------------------------------------------
                             'customerSearchCriteria' => array('struct' => array(
                                        'CSTORECODE'  => 'xsd:int',
                                        'CCODE'       => 'xsd:string',
                                        'CNUMBER'     => 'xsd:string',
                                        'CNAME'       => 'xsd:string',
                                        'CNAMEKANA'   => 'xsd:string',
                                        'PHONE'       => 'xsd:string',
                                        'MAILADDRESS' => 'xsd:string',
                                        'ADDRESS'     => 'xsd:string',
                                        'keyword'     => 'xsd:string',
                                        'free_customer' => 'xsd:int',
                                        'orderby'     => 'xsd:string',
                                        'limit'       => 'xsd:int',
                                        'page'        => 'xsd:int')),

                             'customerInformation' => array('struct' => array(
                                        'CCODE'         => 'xsd:string',
                                        'CNUMBER'       => 'xsd:string',
                                        'CID'           => 'xsd:int',
                                        'CSTORECODE'    => 'xsd:int',
                                        'CNAME'         => 'xsd:string',
                                        'CNAMEKANA'     => 'xsd:string',
                                        'SEX'           => 'xsd:int',
                                        'REGULAR'       => 'xsd:int',
                                        'ZIPCODE1'      => 'xsd:string',
                                        'KEN1'          => 'xsd:string',
                                        'SITYO1'        => 'xsd:string',
                                        'MANSIONMEI'    => 'xsd:string',
                                        'SI1'           => 'xsd:string',
                                        'TYO1'          => 'xsd:string',
                                        'ADDRESS1'      => 'xsd:string',
                                        'ADDRESS1_1'    => 'xsd:string',
                                        'TEL1'          => 'xsd:string',
                                        'TEL2'          => 'xsd:string',
                                        'BIRTHDATE'     => 'xsd:string',
                                        'CHRISTIAN_ERA' => 'xsd:int',
                                        'MAILADDRESS1'  => 'xsd:string',
                                        'MAILADDRESS2'  => 'xsd:string',
                                        'MEMBERSCATEGORY' => 'xsd:int',
                                        'MAILKUBUN'     => 'xsd:int',
                                        'HOWKNOWSCODE'  => 'xsd:int',
                                        'HOWKNOWS'      => 'xsd:string',
                                        'FIRSTDATE'     => 'xsd:string',
                                        'LASTSTAFFCODE' => 'xsd:int',
                                        'LASTSTAFFNAME' => 'xsd:string',
                                    /*--------------------------------------------*/
                                    /* add by albert for bm connection 2015-11-05 */
                                    /*--------------------------------------------*/
                                        'BLOODTYPE'      => 'xsd:string', 
                                        'BMCODE'         => 'xsd:string', 
                                        'MEMBER_CODE'    => 'xsd:string', 
                                        'SMOKING'        => 'xsd:int', 
                                        'DMKUBUN'        => 'xsd:int', 
                                        'JOBINDUSTRYCODE'=> 'xsd:int', 
                                        'JOBINDUSTRY'    => 'xsd:string',
                                        'CREATEDFROMCODE'    => 'xsd:int',
                                    /*--------------------------------------------*/
                                    /* add by albert for bm connection 2015-11-05 */
                                    /*--------------------------------------------*/
                                        'FULLADDRESS'   => 'xsd:string')),
                             '_customerInformation' => array(
                                        'array' => 'customerInformation'),
                             'return_customerInformation' => array('struct' => array(
                                        'records'      => 'tns:_customerInformation',
                                        'record_count' => 'xsd:int')),

                             'return_customerIDs' => array('struct' => array(
                                        'CCODE'         => 'xsd:string',
                                        'CNUMBER'       => 'xsd:string')),
                             //- ####################################################



                             // STAFF -----------------------------------------------
                             'staffSearchCriteria' => array('struct' => array(
                                        'STORECODE'     => 'xsd:int',
                                        'STAFFCODE'     => 'xsd:int',
                                        'showfreestaff' => 'xsd:int',
                                        'orderby'       => 'xsd:string',
                                        'limit'         => 'xsd:int',
                                        'page'          => 'xsd:int',
                                        'syscode'       => 'xsd:int')),

                             'availableStaffSearchCriteria' => array('struct' => array(
                                        'STORECODE'     => 'xsd:int',
                                        'STAFFCODE'     => 'xsd:int',
                                        'date'          => 'xsd:string',
                                        'orderby'       => 'xsd:string',
                                        'limit'         => 'xsd:int',
                                        'page'          => 'xsd:int')),

                             'staffInformation' => array('struct' => array(
                                        'STAFFCODE'        => 'xsd:int',
                                        'STAFFNAME'        => 'xsd:string',
                                        'STAFFNAME2'       => 'xsd:string',
                                        'STORECODE'        => 'xsd:int',
                                        'STORENAME'        => 'xsd:string',
                                        'SUBLEVELCODE'     => 'xsd:int',
                                        'SUBLEVELNAME'     => 'xsd:string',
                                        'POSITIONCODE'     => 'xsd:int',
                                        'POSITIONNAME'     => 'xsd:string',
                                        'RETIREDATE'       => 'xsd:string',
                                        'HIREDATE'         => 'xsd:string',
                                        'TRAVEL_ALLOWANCE' => 'xsd:int',
                                        'SEX'              => 'xsd:int',
                                        'YOYAKU_DISPLAY'   => 'xsd:int',
                                        'WEB_DISPLAY'      => 'xsd:int',
                                        'ROWS'             => 'xsd:int',
                                        'PHONEROWS'        => 'xsd:int',
                                        'DISPLAY_ORDER'    => 'xsd:int',
                                        'SYSCODES'         => 'xsd:string',
                                        'STAFFTYPES'       => 'xsd:string',
                                        'STAFFTYPECODES'   => 'xsd:string',
                                        'STAFFVIEWS'       => 'xsd:string', //UPDATE by SHIMIZU 20150906
                                        'transaction'      => 'tns:return_storeTransactionInformation',
                                        'breaktime'        => 'tns:return_breakTimeInformation',
                                        'shift'            => 'tns:return_staffShiftInformation')),

                             '_staffInformation' => array(
                                        'array' => 'staffInformation'),
                             'return_staffInformation' => array('struct' => array(
                                        'records'      => 'tns:_staffInformation',
                                        'record_count' => 'xsd:int')),

                             'staffRowsHistoryInformation' => array('struct' => array(
                                        'STAFFCODE'        => 'xsd:int',
                                        'STORECODE'        => 'xsd:int',
                                        'date'             => 'xsd:string',
                                        'ROWS'             => 'xsd:int',
                                        'PHONEROWS'        => 'xsd:int')),
                             //- ####################################################



                             // STAFF SHIFT -----------------------------------------
                             'staffShiftSearchCriteria' => array('struct' => array(
                                        'storecode' => 'xsd:int',
                                        'STAFFCODE' => 'xsd:int',
                                        'year'      => 'xsd:int',
                                        'month'     => 'xsd:int',
                                        'day'       => 'xsd:int')),

                             'staffShiftInformation' => array('struct' => array(
                                        'year'              => 'xsd:int',
                                        'month'             => 'xsd:int',
                                        'STORECODE'         => 'xsd:int',
                                        'STAFFCODE'         => 'xsd:int',
                                        'STAFFNAME'         => 'xsd:string',
                                        'HIREDATE'          => 'xsd:string',
                                        'RETIREDATE'        => 'xsd:string',
                                        'STARTTIME'         => 'xsd:string',
                                        'ENDTIME'           => 'xsd:string',
                                        'SALARYTYPE'        => 'xsd:int',
                                        'SALARYAMOUNT'      => 'xsd:int',
                                        'TRAVEL_ALLOWANCE'  => 'xsd:int',
                                        'day1'              => 'xsd:string',
                                        'day2'              => 'xsd:string',
                                        'day3'              => 'xsd:string',
                                        'day4'              => 'xsd:string',
                                        'day5'              => 'xsd:string',
                                        'day6'              => 'xsd:string',
                                        'day7'              => 'xsd:string',
                                        'day8'              => 'xsd:string',
                                        'day9'              => 'xsd:string',
                                        'day10'             => 'xsd:string',
                                        'day11'             => 'xsd:string',
                                        'day12'             => 'xsd:string',
                                        'day13'             => 'xsd:string',
                                        'day14'             => 'xsd:string',
                                        'day15'             => 'xsd:string',
                                        'day16'             => 'xsd:string',
                                        'day17'             => 'xsd:string',
                                        'day18'             => 'xsd:string',
                                        'day19'             => 'xsd:string',
                                        'day20'             => 'xsd:string',
                                        'day21'             => 'xsd:string',
                                        'day22'             => 'xsd:string',
                                        'day23'             => 'xsd:string',
                                        'day24'             => 'xsd:string',
                                        'day25'             => 'xsd:string',
                                        'day26'             => 'xsd:string',
                                        'day27'             => 'xsd:string',
                                        'day28'             => 'xsd:string',
                                        'day29'             => 'xsd:string',
                                        'day30'             => 'xsd:string',
                                        'day31'             => 'xsd:string')),

                             '_staffShiftInformation' => array(
                                        'array' => 'staffShiftInformation'),
                             'return_staffShiftInformation' => array('struct' => array(
                                        'finished'     => 'xsd:int',
                                        'records'      => 'tns:_staffShiftInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################



                             // STORE -----------------------------------------------
                             'storeSearchCriteria' => array('struct' => array(
                                        'STORECODE'   => 'xsd:int',
                                        'STORENAME'   => 'xsd:string',
                                        'orderby'     => 'xsd:string',
                                        'limit'       => 'xsd:int',
                                        'page'        => 'xsd:int')),

                             'storeInformation' => array('struct' => array(
                                        'STORECODE'       => 'xsd:int',
                                        'STORENAME'       => 'xsd:string',
                                        'STORENAME_KANA'  => 'xsd:string',
                                        'TEL'             => 'xsd:string',
                                        'FAX'             => 'xsd:string',
                                        'ZIP'             => 'xsd:string',
                                        'ADDRESS1'        => 'xsd:string',
                                        'ADDRESS2'        => 'xsd:string',
                                        'LOCATION'        => 'xsd:int',
                                        'GROUPCODE'       => 'xsd:int',
                                        'WEBYAN_HOMEPAGE' => 'xsd:string',
                                        'PC_HOMEPAGE'     => 'xsd:string',
                                        'storeid'         => 'xsd:string')),
                             '_storeInformation' => array(
                                        'array' => 'storeInformation'),
                             'return_storeInformation' => array('struct' => array(
                                        'records'      => 'tns:_storeInformation',
                                        'record_count' => 'xsd:int')),
        
                             'storeMenuServiceTime'        => array('struct' => array(
                                        'staffcode'        => 'xsd:int',
                                        'gcode'            => 'xsd:int',
                                        'female_time'      => 'xsd:int',
                                        'male_time'        => 'xsd:int')),
                             '_storeMenuServiceTime'       => array(
                                        'array'            => 'storeMenuServiceTime'),
                           
                             //- ####################################################



                             // STORE HOLIDAY ---------------------------------------
                             'storeHolidaySearchCriteria' => array('struct' => array(
                                        'storecode' => 'xsd:int',
                                        'year'      => 'xsd:int',
                                        'month'     => 'xsd:int',
                                        'day'       => 'xsd:int')),

                             'storeHolidayInformation' => array('struct' => array(
                                        'year'        => 'xsd:int',
                                        'month'       => 'xsd:int',
                                        'STORECODE'   => 'xsd:int',
                                        'day1'        => 'xsd:string',
                                        'day2'        => 'xsd:string',
                                        'day3'        => 'xsd:string',
                                        'day4'        => 'xsd:string',
                                        'day5'        => 'xsd:string',
                                        'day6'        => 'xsd:string',
                                        'day7'        => 'xsd:string',
                                        'day8'        => 'xsd:string',
                                        'day9'        => 'xsd:string',
                                        'day10'       => 'xsd:string',
                                        'day11'       => 'xsd:string',
                                        'day12'       => 'xsd:string',
                                        'day13'       => 'xsd:string',
                                        'day14'       => 'xsd:string',
                                        'day15'       => 'xsd:string',
                                        'day16'       => 'xsd:string',
                                        'day17'       => 'xsd:string',
                                        'day18'       => 'xsd:string',
                                        'day19'       => 'xsd:string',
                                        'day20'       => 'xsd:string',
                                        'day21'       => 'xsd:string',
                                        'day22'       => 'xsd:string',
                                        'day23'       => 'xsd:string',
                                        'day24'       => 'xsd:string',
                                        'day25'       => 'xsd:string',
                                        'day26'       => 'xsd:string',
                                        'day27'       => 'xsd:string',
                                        'day28'       => 'xsd:string',
                                        'day29'       => 'xsd:string',
                                        'day30'       => 'xsd:string',
                                        'day31'       => 'xsd:string')),

                             'return_storeHolidayInformation' => array('struct' => array(
                                        'records'      => 'tns:storeHolidayInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################



                             // SERVICE ---------------------------------------------
                             'serviceSearchCriteria' => array('struct' => array(
                                        'STORECODE'            => 'xsd:int',
                                        'maxServiceIndex'      => 'xsd:string',
                                        'maxStoreServiceIndex' => 'xsd:string',
                                        'orderby'              => 'xsd:string',
                                        'limit'                => 'xsd:int',
                                        'page'                 => 'xsd:int',
                                        'syscode'              =>'xsd:int')
                                 ),

                             'serviceInformation' => array('struct' => array(
                                        'GDCODE'        => 'xsd:int',
                                        'BUNRUINAME'    => 'xsd:string',
                                        'SYSCODE'       => 'xsd:int',
                                        'store_service' => 'tns:return_storeServiceInformation')),
                             '_serviceInformation' => array(
                                        'array' => 'serviceInformation'),
                             'return_serviceInformation' => array('struct' => array(
                                        'records'              => 'tns:_serviceInformation',
                                        'record_count'         => 'xsd:int',
                                        'maxServiceIndex'      => 'xsd:string',
                                        'maxStoreServiceIndex' => 'xsd:string')),
                             //- ####################################################



                             // SHIFT -----------------------------------------------
                             'shiftSearchCriteria' => array('struct' => array(
                                        'orderby'     => 'xsd:string',
                                        'limit'       => 'xsd:int',
                                        'page'        => 'xsd:int')),

                             'shiftInformation' => array('struct' => array(
                                        'SHIFTID'      => 'xsd:int',
                                        'SHIFTNAME'    => 'xsd:string',
                                        'STARTTIME'    => 'xsd:string',
                                        'ENDTIME'      => 'xsd:string')),
                             '_shiftInformation' => array(
                                        'array' => 'shiftInformation'),
                             'return_shiftInformation' => array('struct' => array(
                                        'records'      => 'tns:_shiftInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################



                             // STORE SERVICE ---------------------------------------
                             'storeServiceSearchCriteria' => array('struct' => array(
                                        'STORECODE'   => 'xsd:int',
                                        'GDCODE'      => 'xsd:int',
                                        'hasHonbu'    => 'xsd:int',
                                        'orderby'     => 'xsd:string',
                                        'limit'       => 'xsd:int',
                                        'page'        => 'xsd:int',
                                        'STAFF_TANTOU_STORECODE'      => 'xsd:int',
                                        'STAFF_TANTOU_STAFFCODE'      => 'xsd:int')),

                             'storeServiceInformation' => array('struct' => array(
                                        'hasHonbu'     => 'xsd:int',
                                        'GDCODE'       => 'xsd:int',
                                        'GSCODE'       => 'xsd:int',
                                        'GCODE'        => 'xsd:int',
                                        'MENUNAME'     => 'xsd:string',
                                        'YOYAKUMARK'   => 'xsd:string',
                                        'INSTORE'      => 'xsd:int',
                                        'WEB_DISPLAY'  => 'xsd:int',
                                        'SERVICE_TIME' => 'xsd:int',
                                        'SERVICE_TIME_MALE' => 'xsd:int',
                                        'PRICE'        => 'xsd:int',
                                        'MEMBERPRICE'  => 'xsd:int',
                                        'ZEIKUBUN'     => 'xsd:int',
                                        'POINTKASAN1'  => 'xsd:float',
                                        'POINTKASAN2'  => 'xsd:float',
                                        'POINTKASAN3'  => 'xsd:float',
                                        'delete'       => 'xsd:int',
                                        'STAFF_TANTOU_STAFFCODE'  => 'xsd:int'
                                        , 'KEYCODE'    => 'xsd:string'
                                        )),
                             '_storeServiceInformation' => array(
                                        'array' => 'storeServiceInformation'),
                             'return_storeServiceInformation' => array('struct' => array(
                                        'records'      => 'tns:_storeServiceInformation',
                                        'record_count' => 'xsd:int')),

                             'storeServiceWhosUsingSearchCriteria' => array('struct' => array(
                                        'GSCODE'      => 'xsd:int',
                                        'orderby'     => 'xsd:string',
                                        'limit'       => 'xsd:int',
                                        'page'        => 'xsd:int')),

                             'return_serviceIDs' => array('struct' => array(
                                        'GCODE'        => 'xsd:int',
                                        'GSCODE'       => 'xsd:int')),
                             //- ####################################################



                             // POSITION --------------------------------------------
                             'positionSearchCriteria' => array('struct' => array(
                                        'POSITIONCODE' => 'xsd:int',
                                        'POSITIONNAME' => 'xsd:string',
                                        'orderby'      => 'xsd:string',
                                        'limit'        => 'xsd:int',
                                        'page'         => 'xsd:int')),

                             'positionInformation' => array('struct' => array(
                                        'POSITIONCODE' => 'xsd:int',
                                        'POSITIONNAME' => 'xsd:string')),
                             '_positionInformation' => array(
                                        'array' => 'positionInformation'),
                             'return_positionInformation' => array('struct' => array(
                                        'records'      => 'tns:_positionInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################



                             // SUBLEVEL --------------------------------------------
                             'sublevelSearchCriteria' => array('struct' => array(
                                        'SUBLEVELCODE' => 'xsd:int',
                                        'SUBLEVELNAME' => 'xsd:string',
                                        'orderby'      => 'xsd:string',
                                        'limit'        => 'xsd:int',
                                        'page'         => 'xsd:int')),

                             'sublevelInformation' => array('struct' => array(
                                        'SUBLEVELCODE' => 'xsd:int',
                                        'SUBLEVELNAME' => 'xsd:string')),
                             '_sublevelInformation' => array(
                                        'array' => 'sublevelInformation'),
                             'return_sublevelInformation' => array('struct' => array(
                                        'records'      => 'tns:_sublevelInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################



                             // ZIPCODE ---------------------------------------------
                             'zipcodeSearchCriteria' => array('struct' => array(
                                        'ZIPCODE'     => 'xsd:string',
                                        'ADDRESS'     => 'xsd:string',
                                        'orderby'     => 'xsd:string',
                                        'limit'       => 'xsd:int',
                                        'page'        => 'xsd:int')),

                             'zipcodeInformation' => array('struct' => array(
                                        'ID'       => 'xsd:int',
                                        'ZIPCODE'  => 'xsd:string',
                                        'KEN'      => 'xsd:string',
                                        'SI'       => 'xsd:string',
                                        'TYO'      => 'xsd:string',
                                        'SITYO'    => 'xsd:string')),
                             '_zipcodeInformation' => array(
                                        'array' => 'zipcodeInformation'),
                             'return_zipcodeInformation' => array('struct' => array(
                                        'records'      => 'tns:_zipcodeInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################



                             // BASIC SETTINGS --------------------------------------
                             'basicInformation' => array('struct' => array(
                                        'STORENAME'           => 'xsd:string',
                                        'TEL'                 => 'xsd:string',
                                        'FAX'                 => 'xsd:string',
                                        'ADDRESS1'            => 'xsd:string',
                                        'WEBYAN_HOMEPAGE'     => 'xsd:string',
                                        'PC_HOMEPAGE'         => 'xsd:string',
                                        'INTERVAL'            => 'xsd:int',
                                        'REMINDER'            => 'xsd:int',
                                        'CANCEL_LIMIT'        => 'xsd:int',
                                        'CUSTOMER_LIMIT'      => 'xsd:string',
                                        'LOWER_LIMIT'         => 'xsd:int',
                                        'LOWER_LIMIT_OP'      => 'xsd:string',
                                        'UPPER_LIMIT'         => 'xsd:int',
                                        'UPPER_LIMIT_OP'      => 'xsd:string',
                                        'AVAILABLE_TIMES'     => 'xsd:int',
                                        'OPEN_TIME'           => 'xsd:int',
                                        'CLOSE_TIME'          => 'xsd:int',
                                        'YOYAKU_OPEN_TIME'    => 'xsd:int',
                                        'YOYAKU_CLOSE_TIME'   => 'xsd:int',
                                        'YOYAKU_HYOU_OPEN_TIME'  => 'xsd:int',
                                        'YOYAKU_HYOU_CLOSE_TIME' => 'xsd:int',
                                        'YOYAKU_OPEN_TIME_SATSUN'  => 'xsd:int',
                                        'YOYAKU_CLOSE_TIME_SATSUN' => 'xsd:int',
                                        'SHOW_MENU_NAME_ONLY' => 'xsd:int',
                                        'AUTO_CUST_LIMIT' => 'xsd:int',
                                        'YOYAKU_MSG' => 'xsd:int',
                                        'YOYAKU_MENU_TANTOU' => 'xsd:int',
                                        'YOYAKU_TIME_SETTING' => 'xsd:int',
                                        'YOYAKU_TIME_SETTINGS_OP' => 'xsd:string',
                                        'YOYAKU_TIME_SECOND_SETTING' => 'xsd:int',
                                        'FOLLOW_MAIL_SETTING' => 'xsd:int',
                                        'HIDE_HOLIDAY_STAFF' => 'xsd:int',
                                        'RECORD_YOYAKU_DETAIL' => 'xsd:int',
                                        'MODIFYING_MAIL' => 'xsd:int',
                                        'YOYAKU_DRAGDROP_TIMEINTERVAL' => 'xsd:int',
                                        'HIDE_RAITEN' => 'xsd:int',
                                        'OKOTOWARI_TIME' => 'xsd:int',
                                        'HIDECUSTOMERINFO_FLG' => 'xsd:int')),
                             //- ####################################################



                             // MESSAGE SETTINGS ------------------------------------
                             'messageInformation' => array('struct' => array(
                                        'REGISTRATION'       => 'xsd:string',
                                        'NEWYOYAKU'          => 'xsd:string',
                                        'CANCEL'             => 'xsd:string',
                                        'MAILREGISTRATION'   => 'xsd:string',
                                        'MAILNEWYOYAKU'      => 'xsd:string',
                                        'MAILNOTICE'         => 'xsd:string',
                                        'MAILNOTICESECOND'   => 'xsd:string',
                                        'MODIFYING_MAIL_MSG' => 'xsd:string',
                                        'FOLLOW_MAIL_MSG'    => 'xsd:string',
                                        'MAILSIGNATURE'      => 'xsd:string')),
                             //- ####################################################



                             // COLOR -----------------------------------------------
                             'colorSearchCriteria' => array('struct' => array(
                                        'STORECODE'    => 'xsd:int',
                                        'limit'        => 'xsd:int',
                                        'page'         => 'xsd:int')),
                             'colorInformation' => array('struct' => array(
                                        'id'          => 'xsd:int',
                                        'color'       => 'xsd:string',
                                        'comment'     => 'xsd:string')),
                             '_colorInformation' => array(
                                        'array' => 'colorInformation'),
                             'return_colorInformation' => array('struct' => array(
                                        'records'      => 'tns:_colorInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################



                             // STORE TRANSACTION -----------------------------------
                             'storeTransactionSearchCriteria' => array('struct' => array(
                                        'STORECODE'   => 'xsd:int',
                                        'TRANSCODE'   => 'xsd:string',
                                        'CCODE'       => 'xsd:string',
                                        'date'        => 'xsd:string')),

                             'storeTransactionDetailInformation' => array('struct' => array(
                                        'ROWNO'            => 'xsd:int',
                                        'GDCODE'           => 'xsd:int',
                                        'BUNRUINAME'       => 'xsd:string',
                                        'GCODE'            => 'xsd:int',
                                        'MENUNAME'         => 'xsd:string',
                                        'YOYAKUMARK'       => 'xsd:string',
                                        'MENUTIME'         => 'xsd:int',
                                        'TRANTYPE'         => 'xsd:int',
                                        'QUANTITY'         => 'xsd:int',
                                        'PRICE'            => 'xsd:int',
                                        'CLAIMED'          => 'xsd:int',
                                        'POINTKASAN1'      => 'xsd:float',
                                        'POINTKASAN2'      => 'xsd:float',
                                        'POINTKASAN3'      => 'xsd:float',
                                        'ZEIKUBUN'         => 'xsd:int',
                                        'STAFFCODE'        => 'xsd:int',
                                        'STAFFNAME'        => 'xsd:string',
                                        'STAFFCODESIMEI'   => 'xsd:int',
                                        'TEMPSTATUS'       => 'xsd:int',
                                        'DELFLG'           => 'xsd:string',
                                        'KEYCODE'          => 'xsd:string',
                                        'STARTTIME'        => 'xsd:string',
                                        'ENDTIME'          => 'xsd:string',
                                        'SYSCODE'          => 'xsd:int',
                                        'TAX'              => 'xsd:int',
                                        'QUANTITY'         => 'xsd:int',
                                        'TOTALTAX'         => 'xsd:int',
                                        'PRICETAXINC'      => 'xsd:int')),
                             '_storeTransactionDetailInformation' => array(
                                        'array' => 'storeTransactionDetailInformation'),

                             'storeTransactionInformation' => array('struct' => array(
                                        'TRANSCODE'        => 'xsd:string',
                                        'KEYNO'            => 'xsd:int',
                                        'STORECODE'        => 'xsd:int',
                                        'IDNO'             => 'xsd:int',
                                        'TRANSDATE'        => 'xsd:string',
                                        'YOYAKUTIME'       => 'xsd:string',
                                        'ENDTIME'          => 'xsd:string',
                                        'ADJUSTED_ENDTIME' => 'xsd:string',
                                        'CCODE'            => 'xsd:string',
                                        'CNUMBER'          => 'xsd:string',
                                        'CSTORECODE'       => 'xsd:int',
                                        'REGULARCUSTOMER'  => 'xsd:int',
                                        'KYAKUKUBUN'       => 'xsd:int',
                                        'SEX'              => 'xsd:int',
                                        'RATETAX'          => 'xsd:float',
                                        'ZEIOPTION'        => 'xsd:int',
                                        'SOGOKEIOPTION'    => 'xsd:int',
                                        'TEMPSTATUS'       => 'xsd:int',
                                        'CNAME'            => 'xsd:string',
                                        'APT_COLOR'        => 'xsd:int',
                                        'NOTES'            => 'xsd:string',
                                        'RANKING'          => 'xsd:string',
                                        'PRIORITY'         => 'xsd:int',
                                        'PRIORITYTYPE'     => 'xsd:string',
                                        'STAFFCODE'        => 'xsd:int',
                                        'HASSERVICES'      => 'xsd:int',
                                        'DELFLG'           => 'xsd:string',
                                        'CNAMEKANA'        => 'xsd:string',
                                        'TEL1'             => 'xsd:string',
                                        'TEL2'             => 'xsd:string',
                                        'TEMPSTATUS'       => 'xsd:int',
                                        'BIRTHDATE'        => 'xsd:string',
                                        'CUST_TELNO'       => 'xsd:string',
                                        'MEMBERSCATEGORY'  => 'xsd:int',
                                        'CLAIMKYAKUFLG'    => 'xsd:int',
                                        'UPDATEDATE'       => 'xsd:string',
                                        'newcustomer'      => 'xsd:int',
                                        'ignoreConflict'   => 'xsd:int',
                                        'newRowValue'      => 'xsd:int',
                                        'newPhoneRowValue' => 'xsd:int',
                                        'UKETSUKEDATE'     => 'xsd:string',
                                        'UKETSUKESTAFF'    => 'xsd:int',
                                        'BEFORE_TRANSCODE' => 'xsd:string',
                                        'HOWKNOWSCODE'     => 'xsd:int',
                                        'HOWKNOWS'         => 'xsd:string',
                                        'STIME'            => 'xsd:string',
                                        'ETIME'            => 'xsd:string',
                                        'GCODE'            => 'xsd:int',
                                        'YOYAKU'           => 'xsd:int',
                                        'STARTTIME'        => 'xsd:string',
                                        'SERVICESNAME'     => 'xsd:string', //Update by MarvinC - Added param for syscode
                                        'YOYAKU_STATUS'    => 'xsd:string', //Update by MarvinC - Added param for syscode
                                        'INCOMPLETE'       => 'xsd:int',
                                        'UKETSUKESTAFFNAME'=> 'xsd:string',
                                    /*--------------------------------------------*/
                                    /* add by albert for bm connection 2015-10-29 */
                                    /*--------------------------------------------*/
                                        'route'            => 'xsd:string', 
                                        'reservation_system'=> 'xsd:string', 
                                        'reserve_date'      => 'xsd:string', 
                                        'reserve_code'      => 'xsd:string', 
                                        'v_date'            => 'xsd:string', 
                                        'start_time'        => 'xsd:string', 
                                        'end_time'          => 'xsd:string', 
                                        'coupon_info'       => 'xsd:string', 
                                        'comment'           => 'xsd:string', 
                                        'shop_comment'      => 'xsd:string', 
                                        'next_coming_comment'   => 'xsd:string', 
                                        'demand'            => 'xsd:string', 
                                        'site_customer_id'  => 'xsd:string', 
                                        'bmPrice'           => 'xsd:int', 
                                        'nomination_fee'    => 'xsd:int', 
                                        'bmTprice'          => 'xsd:int', 
                                        'use_point'         => 'xsd:int', 
                                        'grant_point'       => 'xsd:int', 
                                        'visit_num'         => 'xsd:int', 
                                        'firstname'         => 'xsd:string', 
                                        'lastname'          => 'xsd:string', 
                                        'bmsex'             => 'xsd:int', 
                                        'knfirstname'       => 'xsd:string', 
                                        'knlastname'        => 'xsd:string', 
                                        'bmtel'             => 'xsd:string', 
                                        'bmzip'             => 'xsd:string',  
                                        'bmaddress'         => 'xsd:string', 
                                        'bmmail'            => 'xsd:string',
                                        'menu_info'         => 'xsd:string',
                                        'origination'       => 'xsd:int',
                                        'bmstaff'           => 'xsd:string',
                                        'secondnote'        => 'xsd:string',
                                    /*--------------------------------------------*/
                                    /* add by albert for bm connection 2015-10-29 */
                                    /*--------------------------------------------*/
                                        'details'           => 'tns:_storeTransactionDetailInformation',
                                        'rejimarketing'     => 'tns:_rejiMarketingInformation')),
                             '_storeTransactionInformation' => array(
                                        'array' => 'storeTransactionInformation'),
                             'return_storeTransactionInformation' => array('struct' => array(
                                        'records'      => 'tns:_storeTransactionInformation',
                                        'record_count' => 'xsd:int')),

                             'return_transactionIDs' => array('struct' => array(
                                        'TRANSCODE'     => 'xsd:string',
                                        'KEYNO'         => 'xsd:int',
                                        'IDNO'          => 'xsd:int',
                                        'CCODE'         => 'xsd:string',
                                        'CNUMBER'       => 'xsd:string')),
                             //- ####################################################

                             'rejiMarketingInformation' => array('struct' => array(
                                        'TRANSCODE'        => 'xsd:string',
                                        'KEYNO'            => 'xsd:int',
                                        'STORECODE'        => 'xsd:int',
                                        'ROWNO'            => 'xsd:int',
                                        'TRANSDATE'        => 'xsd:string',
                                        'MARKETINGID'      => 'xsd:int',
                                        'MARKETINGIDNO'    => 'xsd:int',
                                        'MARKETINGDESC'    => 'xsd:string',
                                        'STAFFCODE'        => 'xsd:int',
                                        'STAFFNAME'        => 'xsd:string',
                                        'TEMPFLG'          => 'xsd:int',
                                        'LEAFLETSCOUNT'    => 'xsd:int',
                                        'QUANTITY'         => 'xsd:int',
                                        'MARKETINGCODE'    => 'xsd:int')),
                             '_rejiMarketingInformation' => array(
                                        'array' => 'rejiMarketingInformation'),
                             'return_rejiMarketingInformation' => array('struct' => array(
                                        'records'      => 'tns:_rejiMarketingInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################

                             //HOW KNOWS THE STORE COMPLEX tYPES
                             'howKnowsTheStoreInformation' => array('struct' => array(
                                        'HOWKNOWSCODE'        => 'xsd:int',
                                        'HOWKNOWS'            => 'xsd:string')),
                             '_howKnowsTheStoreInformation' => array(
                                        'array' => 'howKnowsTheStoreInformation'),
                             'return_howKnowsTheStoreInformation' => array('struct' => array(
                                        'records'      => 'tns:_howKnowsTheStoreInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################

                             // BREAK TIME ------------------------------------------
                             'breakTimeSearchCriteria' => array('struct' => array(
                                        'STORECODE'   => 'xsd:int',
                                        'STAFFCODE'   => 'xsd:int',
                                        'date'        => 'xsd:string')),

                             'breakTimeInformation' => array('struct' => array(
                                        'BREAKID'          => 'xsd:int',
                                        'STAFFCODE'        => 'xsd:int',
                                        'STORECODE'        => 'xsd:int',
                                        'DATE'             => 'xsd:string',
                                        'REMARKS'          => 'xsd:string',
                                        'PRIORITY'         => 'xsd:int',
                                        'STARTTIME'        => 'xsd:string',
                                        'ENDTIME'          => 'xsd:string')),
                             '_breakTimeInformation' => array(
                                        'array' => 'breakTimeInformation'),
                             'return_breakTimeInformation' => array('struct' => array(
                                        'records'      => 'tns:_breakTimeInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################



                             // DATA OF THE DAY -------------------------------------
                             'dataOfTheDaySearchCriteria' => array('struct' => array(
                                        'STORECODE'   => 'xsd:int',
                                        'STAFFCODE'   => 'xsd:int',
                                        'useYoyakuMessage'   => 'xsd:int',
                                        'date'        => 'xsd:string',
                                        'PRIORITYTYPE'        => 'xsd:int')),

                             'return_dataOfTheDayInformation' => array('struct' => array(
                                        'store'       => 'tns:basicInformation',
                                        'holiday'     => 'xsd:string',
                                        'messages'    => 'tns:return_yoyakuMessage',
                                        'calendar'    => 'tns:return_transactionCalendarViewInformation',
                                        'records'     => 'tns:return_staffInformation')),
                             //- ####################################################



                             // TRANSACTION CALENDAR VIEW ---------------------------
                             'transactionCalendarViewSearchCriteria' => array('struct' => array(
                                        'STORECODE'    => 'xsd:int',
                                        'year'         => 'xsd:int',
                                        'month'        => 'xsd:int')),

                             'transactionCalendarViewInformation' => array('struct' => array(
                                        'year'      => 'xsd:int',
                                        'month'     => 'xsd:int',
                                        'day1'      => 'xsd:string',
                                        'day2'      => 'xsd:string',
                                        'day3'      => 'xsd:string',
                                        'day4'      => 'xsd:string',
                                        'day5'      => 'xsd:string',
                                        'day6'      => 'xsd:string',
                                        'day7'      => 'xsd:string',
                                        'day8'      => 'xsd:string',
                                        'day9'      => 'xsd:string',
                                        'day10'     => 'xsd:string',
                                        'day11'     => 'xsd:string',
                                        'day12'     => 'xsd:string',
                                        'day13'     => 'xsd:string',
                                        'day14'     => 'xsd:string',
                                        'day15'     => 'xsd:string',
                                        'day16'     => 'xsd:string',
                                        'day17'     => 'xsd:string',
                                        'day18'     => 'xsd:string',
                                        'day19'     => 'xsd:string',
                                        'day20'     => 'xsd:string',
                                        'day21'     => 'xsd:string',
                                        'day22'     => 'xsd:string',
                                        'day23'     => 'xsd:string',
                                        'day24'     => 'xsd:string',
                                        'day25'     => 'xsd:string',
                                        'day26'     => 'xsd:string',
                                        'day27'     => 'xsd:string',
                                        'day28'     => 'xsd:string',
                                        'day29'     => 'xsd:string',
                                        'day30'     => 'xsd:string',
                                        'day31'     => 'xsd:string')),
                             '_transactionCalendarViewInformation' => array(
                                        'array' => 'transactionCalendarViewInformation'),
                             'return_transactionCalendarViewInformation' => array('struct' => array(
                                        'records'      => 'tns:_transactionCalendarViewInformation')),
                             //- ####################################################



                             // STAFF TAB -------------------------------------------
                             'return_staffTabInformation' => array('struct' => array(
                                        'store'       => 'tns:return_storeInformation',
                                        'position'    => 'tns:return_positionInformation',
                                        'sublevel'    => 'tns:return_sublevelInformation')),
                             //- ####################################################



                             // FIRST LOAD ------------------------------------------
                             'return_firstLoadInformation' => array('struct' => array(
                                        'transaction' => 'tns:return_dataOfTheDayInformation',
                                        'service'     => 'tns:return_serviceInformation',
                                        'color'       => 'tns:return_colorInformation')),
                             //- ####################################################

                             // NEW YOYAKU MESSAGE ------------------------------------------

                             'yoyakuMessage' => array('struct' => array(
                                        'CNAME'          => 'xsd:string',
                                        'YOYAKUDATETIME' => 'xsd:string',
                                        'STAFFNAME'      => 'xsd:string',
                                        'MSG'            => 'xsd:string')),
                             '_yoyakuMessage' => array(
                                        'array' => 'yoyakuMessage'),
                             'return_yoyakuMessage' => array('struct' => array(
                                        'messages'      => 'tns:_yoyakuMessage',
                                        'message_count' => 'xsd:int')),

                             //- ####################################################



                             // YOYAKU DETAILS --------------------------------------
                             'yoyakuDetailsInformation' => array('struct' => array(
                                        'STORECODE'         => 'xsd:int',
                                        'TRANSDATE'         => 'xsd:string',
                                        'BEGINTIME'         => 'xsd:string',
                                        'ENDTIME'           => 'xsd:string',
                                        'CUSTOMERNAME'      => 'xsd:string',
                                        'STAFFNAME'         => 'xsd:string',
                                        'PRIORITYTYPE'      => 'xsd:int',
                                        'YOYAKU'            => 'xsd:int',
                                        'UKETSUKEDATE'      => 'xsd:string',
                                        'UKETSUKESTAFFNAME' => 'xsd:string',
                                        'CANCEL'            => 'xsd:int',
                                        'YOYAKUNEXTFLG'     => 'xsd:int',
                                        'ORIGINATION'       => 'xsd:int', //add by albert 2016-01-27
                                        'TEMPSTATUS'        => 'xsd:int',
                                 )),
                             '_yoyakuDetailsInformation' => array(
                                        'array' => 'yoyakuDetailsInformation'),
                             'return_yoyakuDetailsInformation' => array('struct' => array(
                                        'records'      => 'tns:_yoyakuDetailsInformation')),
                             //- ####################################################
        
                             //OKOTOWARI COMPLEX tYPES
                             'okotowariInformation' => array('struct' => array(
                                        'OID'            => 'xsd:int',
                                        'STORECODE'      => 'xsd:int',
                                        'YMD'            => 'xsd:string',
                                        'OTIME'          => 'xsd:string',
                                        'CCODE'          => 'xsd:string',
                                        'CNUMBER'        => 'xsd:string',
                                        'CNAME'          => 'xsd:string',
                                        'SEX'            => 'xsd:int')),
                             '_okotowariInformation' => array(
                                        'array' => 'okotowariInformation'),
                             'return_okotowariInformation' => array('struct' => array(
                                        'records'      => 'tns:_okotowariInformation',
                                        'record_count' => 'xsd:int')),
                             //- ####################################################
        
                             //GYOSHUKUBUN COMPLEX TYPES
                             'gyoshukubunInformation' => array('struct' => array(
                                        'SYSCODE'            => 'xsd:int',
                                        'SERVICESNAME'         => 'xsd:string',
                                        'DESCRIPTION'          => 'xsd:string')),
                             '_gyoshukubunInformation' => array(
                                        'array' => 'gyoshukubunInformation'),
                             'return_gyoshukubun' => array('struct' => array(
                                        'records'      => 'tns:_gyoshukubunInformation',
                                        'record_count' => 'xsd:int')),
        
                            'YoyakuAllowTransToStore' => array('struct' => array(
                                    'storecode'         => 'xsd:int',
                                    'tostorecode'    => 'xsd:int',
                                    'tostorename'    => 'xsd:string')),
                             '_YoyakuAllowTransToStore' => array (
                                    'array'     =>   'YoyakuAllowTransToStore'),  
        
        
        
                            //==============================================================
                            //customer search listing for Integration
                            //by albert 2015-11-18
                            //--------------------------------------------------------------
                            'storeCustomerListing' => array('struct' => array(
                                        'ccode'         => 'xsd:string',
                                        'cnumber'       => 'xsd:string',
                                        'cname'         => 'xsd:string',
                                        'cnamekana'     => 'xsd:string',
                                        'sex'           => 'xsd:string',
                                        'tel1'          => 'xsd:string',
                                        'tel2'          => 'xsd:string',
                                        'birthdate'     => 'xsd:string',
                                        'christian_era' => 'xsd:string',
                                        'mailaddress1'  => 'xsd:string',
                                        'mailaddress2'  => 'xsd:string',
                                        'zipcode1'      => 'xsd:string',
                                        'address1'      => 'xsd:string',
                                        'mansionmei'    => 'xsd:string',
                                        'cstorecode'    => 'xsd:string',
                                        'firstdate'     => 'xsd:string',
                                        'lastdate'      => 'xsd:string',
                                        'storename'     => 'xsd:string')),
                            '_storeCustomerListing' => array(
                                        'array' => 'storeCustomerListing'),
                            'return_storeCustomerListing' => array('struct' => array(
                                        'records'      => 'tns:_storeCustomerListing',
                                        'record_count' => 'xsd:int',
                                        'totalrecords'  => 'xsd:string')),
        
                            'storeReservationListing'   => array('struct' => array(
                                        'transdate'     => 'xsd:string',
                                        'starttime'     => 'xsd:string',
                                        'cname'         => 'xsd:string',
                                        'staffname'     => 'xsd:string',
                                        'reservationdt' => 'xsd:string',
                                        'reservationtm' => 'xsd:string',
                                        'transstat'     => 'xsd:string',
                                        'alreadyread'   => 'xsd:int',
                                        'keyno'         => 'xsd:int',
                                        'transcode'     => 'xsd:string',
                                        'route'         => 'xsd:string',
                                        'syscode'       => 'xsd:string',
                                        'recctr'         => 'xsd:string')),
                            '_storeReservationListing'  => array(
                                        'array' => 'storeReservationListing'),
                            'return_storeReservationListing' => array('struct' => array(
                                        'records'       => 'tns:_storeReservationListing')),
        
                            
                            'storeReservationCounter'   => array('struct' => array(
                                        'wrkr'          => 'xsd:int',
                                        'bmr'           => 'xsd:int')),
                            '_storeReservationCounter'  => array(
                                        'array' => 'storeReservationCounter'),
                            'return_storeReservationCounter' => array('struct' => array(
                                        'records'       => 'tns:_storeReservationCounter')),
                            //--------------------------------------------------------------
                            //customer search listing for Integration
                            //by albert 2015-11-18
                            //==============================================================
        
                         );

    
// wsGetCustomerList  ------------------------------------------------------------------------------------------------------------------------
/* author : Albert 2015-11-18
 * Customer Listing posible for merging
 * @param array $param
 * @return customerlistinginfo
 */
 function wsGetCustomerList($sessionid, $storecode, $datefr, $dateto, $firstdate, $pageindex, $filename1, $allstoreflg, $basecode){

    //===================================================================================
    //(Verify Session and Get DB name)
    //-----------------------------------------------------------------------------------
    $storeinfo = $this->YoyakuSession->Check($this);
    if ($storeinfo == false) {
       $this->_soap_server->fault(1, '', INVALID_SESSION);
        return;
    } 
    $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);
    //===================================================================================
    
    if (strlen($storecode) == 3) {
        $tmpccode = $storecode . "0000000";
    }elseif (strlen($storecode) == 2) {
        $tmpccode = "0" . $storecode . "0000000";
    }elseif (strlen($storecode) == 1) {
        $tmpccode = "00" . $storecode . "0000000";
    }
    
    $strcond = "";
    if ($allstoreflg == 0){
        $strcond = " and cstorecode = " . $storecode;
    }else{
        $strcond = " and cstorecode > 0 ";
    }
    
    $firstdatecond = "";
    if ($firstdate == true){
        $firstdatecond = " and firstdate between '" . $datefr . "' and '" . $dateto . "'";
    }

    //===================================================================================
    // Get GetCustomerList Data
    //-----------------------------------------------------------------------------------
    $totalrec = "select count(ccode)
                 from customer
                 where delflg is null and ccode <> '" . $tmpccode . "' and ccode <> '" . $basecode . "'" . $strcond .  $filename1 . $firstdatecond;
    
    $Sql = "select ccode, cnumber, cname, cnamekana, sex, tel1, tel2, birthdate, christian_era, 
                     mailaddress1, mailaddress2, zipcode1, address1, mansionmei, cstorecode, firstdate, lastdate,
                     ($totalrec) as totalrecords
            from
                    (select ccode, cnumber, cname, cnamekana, sex, tel1, tel2, birthdate, christian_era, 
                            mailaddress1, mailaddress2, zipcode1, address1, mansionmei, cstorecode, firstdate, lastdate
                    from customer
                    where delflg is null and ccode <> '" . $tmpccode . "' and ccode <> '" . $basecode . "'" . $strcond . $filename1 . $firstdatecond . "
                    order by cnamekana, firstdate 
                    limit " . ($pageindex * 50) . ", 50) tblresult"; 
    //-----------------------------------------------------------------------------------
    $GetData = $this->Customer->query($Sql);
    //===================================================================================
    $arr_cust = $this->ParseDataToObjectArray($GetData, 'tblresult');
    //===================================================================================
    $ret = array();
    $ret['totalrecords'] = $GetData[0][0]["totalrecords"];
    $ret['records']      = $arr_cust;
    $ret['record_count'] = count($arr_cust);
    //=================================================================================================================
    return $ret;
    //=================================================================================================================
}


// wsCustomerMergeSave -----------------------------------------------------------------------------------------------------------------------
/* author : Albert 2015-11-24
 * Customer merging
 * @param array $param
 * @return customerlistinginfo
 */
 function wsCustomerMergeSave($sessionid, $strcode, $fromccode, $toccode, $companyid, $params){
                                             
        //===================================================================================
        //(Verify Session and Get DB name)
        //-----------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
           $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }
        $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);
        //===================================================================================
        
        $Sql = "Select cstorecode from customer where ccode = '" . $toccode . "'";
        $GetData = $this->Customer->query($Sql);
        
        $newstorecode = 0;
        if (count($GetData) > 0) {
            $newstorecode = $GetData[0]["customer"]["cstorecode"];
        }else{
            return false;
        }
        if($newstorecode == 0){
            return false;
        }
      
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //set old customer CNUMBER to NULL to remove the Duplicate entry -----------------------------------------------------------------------------------
//        $Sql = "update customer set cnumber = null where ccode = '" . $fromccode . "'";
//        $GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //get the addition information for customer --------------------------------------------------------------------------------------------------------
        $Sql = "Select STAFF_INCHARGE_SELECTED, AGERANGE, REFERRALRELATIONCODE, INTRODUCETYPE, STOREDATE, CHRISTIAN_ERA  from customer where ccode = '" . $fromccode . "'";
        $GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        $cnumnew = "null";
        if ($params['CNUMBER'] <> ""){
            $cnumnew = "'" . $params['CNUMBER'] . "'";
        }
        
        $newbdate =  "null";
        if ($params['BIRTHDATE']  <> ""){
            $newbdate = "'" . $params['BIRTHDATE'] . "'";
        }
        
        $newstrdate = "null";
        if ($GetData[0]["customer"]["STOREDATE"]  <> ""){
            $newstrdate = "'" . $GetData[0]["customer"]["STOREDATE"] . "'";
        }
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //undating of new customer information in customer table -------------------------------------------------------------------------------------------
        $GetData1 = "";
        $newCustName = '"' . $params['CNAME'] . '"';
        $newCustNameKN = '"' . $params['CNAMEKANA'] . '"';
        $Sql = "update customer
                set /*CNUMBER = " . $cnumnew . ",*/
                    CNAME = ". $newCustName .",
                    CNAMEKANA = ". $newCustNameKN .",
                    MEMBER_CODE = '". $params['MEMBER_CODE'] ."',
                    SEX = ". $params['SEX'] .",
                    SMOKING = ". $params['SMOKING'] .",
                    REGULAR = ". $params['REGULAR'] .",
                    TEL1 = '". $params['TEL1'] ."',
                    TEL2 = '". $params['TEL2'] ."',
                    MAILADDRESS1 = '". $params['MAILADDRESS1'] ."',
                    MAILADDRESS2 = '". $params['MAILADDRESS2'] ."',
                    ZIPCODE1 = '". $params['ZIPCODE1'] ."',
                    KEN1 = '". $params['KEN1'] ."',
                    SITYO1 = '". $params['SITYO1'] ."',
                    MANSIONMEI = '". $params['MANSIONMEI'] ."',
                    ADDRESS1 = '". $params['ADDRESS1'] ."',
                    BIRTHDATE = ". $newbdate .",
                    MEMBERSCATEGORY = ". $params['MEMBERSCATEGORY'] .",
                    DMKUBUN = ". $params['DMKUBUN'] .",    
                    MAILKUBUN = ". $params['MAILKUBUN'] .",
                    BLOODTYPE = '". $params['BLOODTYPE'] ."',
                    JOBINDUSTRYCODE = ". $params['JOBINDUSTRYCODE'] .",
                    HOWKNOWSCODE = ". $params['HOWKNOWSCODE'] .",
                        
                    CHRISTIAN_ERA = ". $GetData[0]["customer"]['CHRISTIAN_ERA'] .",
                    INTRODUCETYPE = ". $GetData[0]["customer"]["INTRODUCETYPE"] .",
                    STOREDATE = ". $newstrdate .",
                    REFERRALRELATIONCODE = ". $GetData[0]["customer"]["REFERRALRELATIONCODE"] .",
                    AGERANGE = ". $GetData[0]["customer"]["AGERANGE"] .",
                    STAFF_INCHARGE_SELECTED = ". $GetData[0]["customer"]["STAFF_INCHARGE_SELECTED"] ."
                where ccode = '". $toccode ."'";
        
        $GetData1 = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------

        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update ccode for the following table -------------------------------------------------------------------------------------------------------------
        $tablename = Array("store_transaction", "bm_reservation", "credit_trans", "coupon_trans", "customer_mails", "store_transaction_apsales", "store_transaction_item",
                           "yoyaku_message", "ks_yoyaku");
	for ($ctr = 0; $ctr < count($tablename); $ctr++) {
            $GetData = "";
            if ($ctr == 0) { // disable the trigger first to preserved transaction data 
                $Sql = "Update dbsettings set dbvalue = 1 where dbset = 'DISABLE_TRIGGER'";
                $GetData = $this->Customer->query($Sql);
                
                $GetData = "";
                $Sql = "Update store_transaction set cname = " . $newCustName . "  where ccode = '" . $toccode . "'";
                $GetData = $this->Customer->query($Sql);
                
                $GetData = "";
                $Sql = "Update store_transaction set ccode = '" . $toccode . "', cname = " . $newCustName . "  where ccode = '" . $fromccode . "'";
                $GetData = $this->Customer->query($Sql);
                
                // enable trigger
                $GetData = "";
                $Sql = "Update dbsettings set dbvalue = 0 where dbset = 'DISABLE_TRIGGER'";
                $GetData = $this->Customer->query($Sql);
                
            }elseif ($ctr == 1){ //update ccode to bm_reservation
                $Sql = "Update bm_reservation set ccode = '" . $toccode . "'
                        where ccode = '" . $fromccode . "'";
                $GetData = $this->Customer->query($Sql);
                
                /* remove by albert by on the intruction of kato-san
                 * 2016-03-28 at 19:40
                $bmCode = '"' . $params['BMCODE'] . '"';
                $Sql = "Update bm_reservation set ccode = '" . $toccode . "', 
                                                  mail =  '" . $params['MAILADDRESS1'] . "',  
                                                  zipcode =  '" . $params['ZIPCODE1'] . "',
                                                  tel =  '" . $params['TEL1'] . "', 
                                                  sex =  " . (($params['SEX'] == 1) ? 0 : 1) . "
                        where ccode = '" . $fromccode . "'";
                $GetData = $this->Customer->query($Sql);
                
                $GetData = "";
                $Sql = "Update bm_reservation set site_customer_id = " . $bmCode . "
                        where ccode = '" . $toccode . "'";
                $GetData = $this->Customer->query($Sql);
                */
                
            }else{//update ccode for the following table
                $Sql = "Update ".$tablename[$ctr]." set ccode = '" . $toccode . "' where ccode = '" .$fromccode . "'";
                $GetData = $this->Customer->query($Sql);
            }        
            
	}
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update reservia customer table -------------------------------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "update rv_customer set ccode = '" . $toccode . "'
                where ccode = '" . $fromccode . "'";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update dcustomerinfo table for new customer code -------------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Insert IGNORE into dcustomerinfo(ccode, ITEMCODE,ITEMLISTCODE, `STORECODE`,`DELFLG` )
			Select '" . $toccode . "', custinfo.ITEMCODE,ITEMLISTCODE, '" . $newstorecode . "', custinfo.DELFLG
			from dcustomerinfo custinfo
			where custinfo.ccode = '" . $fromccode . "'";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update customer relation table for new customer code ---------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Insert IGNORE into `customer_relation`(ccode, TORELATIONCCODE, RELATIONCODE, STORECODE, REMARKS, DELFLG )
			Select '" . $toccode . "', relation.TORELATIONCCODE, relation.RELATIONCODE, '" . $newstorecode . "', relation.REMARKS, relation.DELFLG
			from customer_relation relation
			where relation.ccode = '" . $fromccode . "'";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update customer total table for new customer code ------------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Insert into customertotal(storecode, ccode, pointtotal1, pointtotal2)
                 Select '" . $newstorecode . "', '" . $toccode . "', custot.pointtotal1, custot.pointtotal2
                     from customertotal custot
                    where custot.ccode =  '" . $fromccode . "'
             on duplicate key update customertotal.pointtotal1 = customertotal.pointtotal1 + custot.pointtotal1,
                customertotal.pointtotal2 = customertotal.pointtotal2 + custot.pointtotal2";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update customer total table for old customer code set the delflug --------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Update customertotal set delflg = now() where ccode = '" . $fromccode . "'";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update customer anniversary table for new customer code ------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Insert into customer_anniversary(ccode, anniversarycode, anniversarydate, storecode)
			Select '" . $toccode . "', custanni.anniversarycode, custanni.anniversarydate, '" . $newstorecode . "'
			from customer_anniversary custanni
			where custanni.ccode = '" . $fromccode . "'
			On Duplicate Key Update customer_anniversary.ANNIVERSARYDATE= custanni.anniversarydate,
			customer_anniversary.storecode = custanni.storecode";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update customer anniversary table for old customer code set the delflug --------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Update customer_anniversary set delflg = now() where ccode = '" . $fromccode . "'";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update customer free memo table for new customer code --------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Insert into freememo(ccode, memocode, `memo`)
			Select '" . $toccode . "', custmemo.memocode, custmemo.memo
			from freememo custmemo
			where custmemo.ccode = '" . $fromccode . "'
			On Duplicate Key Update freememo.memo = custmemo.memo";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update customer free memo table for old customer code set the delflug ----------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Update freememo set delflg = now() where ccode = '" . $fromccode . "'";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //add memo to the current customer that the old customer was merge ---------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Insert into freememo(ccode, memocode, `memo`) 
                       Values('" . $toccode . "', f_get_memocode('" . $toccode . "'), 
	 	concat((select cnumber from customer where ccode = '" . $fromccode . "'), '-この顧客は別の顧客に統合されました。', now()))";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update customer relation torelation code for new customer code -----------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Update customer_relation set TORELATIONCCODE = '" . $toccode . "' where TORELATIONCCODE = '" . $fromccode . "'";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update referralcode for new customer code -------------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Update customer set REFERRALCODE = '" . $toccode . "' where introducetype = 2 and REFERRALCODE = '" . $fromccode . "'";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update store transaction free customer referral code for new customer code -----------------------------------------------------------------------
        $GetData = "";
        $Sql = "Update store_transaction tr
                Set tr.referralcode = '" . $toccode . "'
                where tr.delflg is null and convert(substring(tr.ccode, 4), signed) = 0
                      and tr.tempstatus = 0 and tr.introducetype = 2 and tr.referralcode = '" . $fromccode . "'";
        $GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update delflug of customer to be merge -----------------------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "update customer SET DELFLG = NOW() where ccode = '" . $fromccode . "'";
        $GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //insert data into dcustomermerge table ------------------------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "Insert into dcustomermerge(ccodeold, ccodenew, updatedate) 
                       Values('" . $fromccode . "', '" . $toccode . "', now())";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        //update note copy of the comment ------------------------------------------------------------------------------------------------------------------
        $GetData = "";
        $Sql = "update customer as cust1, (SELECT CAUTIONMEMO FROM customer where ccode = '" . $fromccode . "') as cust2
                       set cust1.CAUTIONMEMO = concat(cust1.CAUTIONMEMO,' ', cust2.CAUTIONMEMO)
                where cust1.ccode = '" . $toccode . "'";
	$GetData = $this->Customer->query($Sql);
        //--------------------------------------------------------------------------------------------------------------------------------------------------
        
        return $newstorecode;
}


// wsGetReservationCounter -----------------------------------------------------------------------------------------------------------------------
/* author : Albert 2015-12-16
 * Get Reservation
 * @param array $param
 * @return reservation listing info counter
 */
 function wsGetReservationCounter($sessionid, $strcode, $datefr, $dateto){
             
    //===================================================================================
    //(Verify Session and Get DB name)
    //-----------------------------------------------------------------------------------
    $storeinfo = $this->YoyakuSession->Check($this);
    if ($storeinfo == false) {
       $this->_soap_server->fault(1, '', INVALID_SESSION);
        return;
    }
    $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);
    //===================================================================================

    //rule change 
    /*
    if ($dateto !== ""){
        $datatransdate = " and str_hdr.transdate between '" . $datefr . "' and '" . $dateto . "' ";
    }else{
        $datatransdate = " and str_hdr.transdate = '" . $datefr . "' ";
    }
     */
    $datatransdate = ' and str_hdr.transdate >= date(now()) ';
    
    $datastrcode = "";
    if ($strcode > 0){
        $datastrcode = " and str_hdr.storecode = " . $strcode;
    }
    //Bug: 1135 - Added By: MarvinC - Don't show servince if store dont have the industry (Beaty, Nail, Matsuke, Este)
    //count all reservation -------------------------------------------------------------------------------------------
    $sql = "select bmr, wrkr
            from (
                    select count(if(origination = 7 or origination = 8, transcode, null)) as bmr,
                           count(if(origination = 1 or origination = 2, transcode, null)) as wrkr
                    from (

                            select str_hdr.transcode, str_hdr.origination, ifnull(str_trans2_hdr.read, 0) as yread
                            from store_transaction as str_hdr
                                    join store_transaction_details as str_dtl on str_hdr.transcode = str_dtl.transcode and str_hdr.keyno = str_dtl.keyno
                                    left join store_services as str_svr on str_dtl.gcode = str_svr.gcode
                                    left join services as svr on str_svr.gdcode = svr.gdcode
                                    left join yoyaku_details as yk_dtl on str_hdr.transcode = yk_dtl.transcode
                                    left join staff as stff on str_hdr.staffcode = stff.staffcode
                                    join stafftype on stafftype.staffcode = stff.staffcode and stafftype.delflg is null or str_dtl.staffcode = 0
                                    join storetype on storetype.delflg is null and storetype.storecode = str_hdr.storecode
                                    left join store_transaction2 as str_trans2_hdr on str_hdr.transcode = str_trans2_hdr.transcode and str_hdr.keyno = str_trans2_hdr.keyno
                            where str_hdr.origination in (1,2,7,8) " . $datastrcode . $datatransdate . " 
                            group by str_hdr.transcode
                    ) tmptbl where yread = 0
                    
            ) as tblecount
                                ";
    //===================================================================================
    $GetData = $this->Customer->query($sql);
    $arr_reservation = $this->ParseDataToObjectArray($GetData, 'tblecount');
    //===================================================================================
    $ret = array();
    $ret['records'] = $arr_reservation;
    //=================================================================================================================
    
    return $ret;
    //===================================================================================
}


// wsGetReservation -----------------------------------------------------------------------------------------------------------------------
/* author : Albert 2015-12-02
 * Get Reservation
 * @param array $param
 * @return reservationlistinginfo
 */
 function wsGetReservation($sessionid, $strcode, $origination, $datefr, $dateto, $pageno, $ascsort, $colsort, $syscode){
             
    //===================================================================================
    //(Verify Session and Get DB name)
    //-----------------------------------------------------------------------------------
    $storeinfo = $this->YoyakuSession->Check($this);
    if ($storeinfo == false) {
       $this->_soap_server->fault(1, '', INVALID_SESSION);
        return;
    }
    $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);
    //===================================================================================
    
    $sorting  = " desc ";
    if ($ascsort == 1) {
        $sorting  = " desc ";
    }else{
        $sorting  = " asc ";
    }
    
    $orderby = " ";
    if ($colsort == 0){
        $orderby = ", alreadyread " . $sorting;
    }elseif ($colsort == 1){
        $orderby = ", starttime " . $sorting;
    }elseif ($colsort == 2){
        $orderby = ", transdate " . $sorting . ", reservationtm " . $sorting;
    }elseif ($colsort == 3){
        $orderby = ", cname " . $sorting;
    }elseif ($colsort == 4){
        $orderby = ", staffname " . $sorting;
    }elseif ($colsort == 5){
        $orderby = ", transstat " . $sorting;
    }elseif ($colsort == 6){
        $orderby = ", route " . $sorting;
    }
    
    $opeAnd = "";
    
    $dataoriginate = "";
    if ($origination == 0 ){
        $dataoriginate = " str_hdr.origination in (1, 2, 7, 8) ";
        $opeAnd = " and ";
    }elseif ($origination == 1){
        $dataoriginate = " str_hdr.origination in (1) ";
        $opeAnd = " and ";
    }elseif ($origination == 2){
        $dataoriginate = " str_hdr.origination in (2) ";
        $opeAnd = " and ";
    }elseif ($origination == 3){
        $dataoriginate = " str_hdr.origination in (7, 8) ";
        $opeAnd = " and ";
    }
    
    $datastrcode = "";
    if ($strcode > 0){
        $datastrcode = $opeAnd . " str_hdr.storecode = " . $strcode;
        $opeAnd = " and ";
    }
    
    $datatransdate = "";
    if ($dateto !== ""){
        $datatransdate = $opeAnd . " str_hdr.transdate between '" . $datefr . "' and '" . $dateto . "' ";
        $opeAnd = " and ";
    }
    
    $sysCon = "";
    if ($syscode !== 0){
        $sysCon = $opeAnd . " svr.syscode in ({$syscode})";
    }
    
    if ($pageno == 0){
        $curRec = 0;
        $maxRec = 51;
    }else{
        $curRec = ($pageno * 50) + 1;
        $maxRec = 50;
    }
    
    //Bug: 1135 - Added By: MarvinC - Don't show servince if store dont have the industry (Beaty, Nail, Matsuke, Este)
    //main query ------------------------------------------------------------------------------------------------------
    $sql = "select sql_calc_found_rows recctr, gnc, transcode, keyno, transdate, starttime, reservationdt, reservationtm, cname, staffname, transstat, route, alreadyread, syscode, origination
            from (
            
          
            select 0 as recctr, gnc, transcode, keyno, transdate, starttime, reservationdt, reservationtm, cname, staffname, transstat, route, alreadyread, syscode, origination
            from(
                    select 0 as gnc, str_hdr.transcode, str_hdr.keyno, DATE_FORMAT(str_hdr.transdate, '%Y年%m月%d日') as transdate, 
                                                    if(ifnull(str_trans2_hdr.datetimecreated, '') <> '', DATE_FORMAT(str_trans2_hdr.datetimecreated, '%Y年%m月%d日 %H:%i'), '') as starttime,
                                                    if(ifnull(yk_dtl.updatedate, '') <> '', DATE_FORMAT(yk_dtl.updatedate, '%Y年%m月%d日'), '') as reservationdt,
                                                    if(ifnull(str_hdr.YOYAKUTIME, '') <> '', DATE_FORMAT(str_hdr.YOYAKUTIME, '%H:%i'), '') as reservationtm,
                                                    str_hdr.cname, stff.staffname, 
                                                    case when str_hdr.delflg is not null then 'キャンセル' else '予約' end as transstat, 
                                                    if(str_hdr.origination = 7 or str_hdr.origination = 8, '連携', 'もばすて') as route, ifnull(str_trans2_hdr.read, 0) as alreadyread, 
                                                    group_concat(distinct svr.syscode) as syscode, str_hdr.origination
                    from store_transaction as str_hdr
                                                    left join store_transaction_details as str_dtl on str_hdr.transcode = str_dtl.transcode and str_hdr.keyno = str_dtl.keyno
                                                    left join store_services as str_svr on str_dtl.gcode = str_svr.gcode
                                                    left join services as svr on str_svr.gdcode = svr.gdcode
                                                    left join yoyaku_details as yk_dtl on str_hdr.transcode = yk_dtl.transcode
                                                    left join staff as stff on str_dtl.staffcode = stff.staffcode
                                                    join stafftype on stafftype.staffcode = stff.staffcode and stafftype.delflg is null or str_dtl.staffcode = 0
                                                    join storetype on storetype.delflg is null and storetype.storecode = str_hdr.storecode
                                                    left join store_transaction2 as str_trans2_hdr on str_hdr.transcode = str_trans2_hdr.transcode and str_hdr.keyno = str_trans2_hdr.keyno
                    where " . $dataoriginate . $datastrcode . $datatransdate . $sysCon . "
                    group by transcode
                ) as tmptbl 

            union all 

                select found_rows() as recctr, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0

            order by recctr desc " . $orderby . ", transcode desc, gnc
            limit  " . $curRec . "," . $maxRec . " 
                        ) tbllist ";
    //===================================================================================
    $GetData = $this->Customer->query($sql);
    $arr_reservation = $this->ParseDataToObjectArray($GetData, 'tbllist');
    //===================================================================================
    $ret = array();
    $ret['records'] = $arr_reservation;
    //=================================================================================================================
    return $ret;
    //=================================================================================================================
}


// wsUpdateTransaction2 --------------------------------------------------------------------------------------------------------------------
/* author : Albert 2015-12-02
 * Update Transaction2
 * @param array $param
 * @return update transaction 2
 */
 function wsUpdateTransaction2($sessionid, $transcode, $keyno, $read, $syscode){
    //===================================================================================
    //(Verify Session and Get DB name)
    //-----------------------------------------------------------------------------------
    $storeinfo = $this->YoyakuSession->Check($this);
    if ($storeinfo == false) {
       $this->_soap_server->fault(1, '', INVALID_SESSION);
        return;
    }
    $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);
    //===================================================================================
    
    $sql = "insert into store_transaction2(transcode, keyno, `read`, datetimecreated)
                                    values('" . $transcode . "', " . $keyno . ", " . $read . ", now())
			On Duplicate Key Update `read` = ". $read ;
   
    $GetData = $this->Customer->query($sql);
    //===================================================================================
    return true;    
    //===================================================================================
 }


    // LOGIN / LOGOUT FUNCTIONS -----------------------------------------------------
    /**
     * ログインチェックを実行して新規セッションを作成する
     * Performs login and creates a new session
     *
     * @param array $param
     * @return return_storeInfo
     */
    function wsLogin($param) {
        $condition1 = array('StoreAccount.username' => $param['username'],
                                       'StoreAccount.passwd'   => $param['password'],
                                       'StoreAccount.tenpo'    => 1,
                                       '(WebyanAccount.delflg IS NULL OR WebyanAccount.delflg = ?)'  => array(0));
        $condition2 = array('StoreAccount.username' => $param['username'],
                                       'StoreAccount.passwd'   => $param['password'],
                                       'StoreAccount.yoyakuf'    => 1);
        $condition['OR'] = array($condition1, $condition2);

        $rec = $this->StoreAccount->find('all', array(
                 'conditions' => $condition,
                 'fields' => array('Company.dbname',
                                   'Company.oemflg', 
                                   'StoreAccount.storecode',
                                   'StoreAccount.companyid',
                                   'StoreAccount.tenpo AS hasTenpo',
                                   'StoreAccount.cust AS hasCust',
                                   //'StoreAccount.honbu AS hasHonbu',
                                   'StoreAccount.browser AS hasBrowser',
                                   'StoreAccount.yoyakuf AS hasYoyaku')
                                ));

        if (!empty($rec)) {
            //-- リターンデータを準備する (Prepare Return Data) --
            $arrReturn = array_merge($rec[0]['StoreAccount'], $rec[0]['Company']);

            $honbu = $this->StoreAccount->find('all', array(
                 'conditions' => array('StoreAccount.companyid' => $arrReturn['companyid'],
                                       'StoreAccount.honbu' => 1)));
            if (count($honbu) > 0) {
                $arrReturn['hasHonbu'] = 1;
            } else {
                $arrReturn['hasHonbu'] = 0;
            }
            
            //-- 新規セッションを作成する (Create a New Session) --
            $arrReturn['sessionid'] = $this->YoyakuSession->Create($this, $arrReturn['storecode'], $arrReturn['companyid']);

            $this->Store->set_company_database($arrReturn['dbname'], $this->Store);
            $store_rec = $this->Store->find('all', array('conditions' => array('storecode' => $arrReturn['storecode'])));
            $arrReturn['storename'] = $store_rec[0]['Store']['STORENAME'];
            $arrReturn['storemail'] = $store_rec[0]['Store']['mail'];
            

            //-- 会社データベースを設定する (Set the Company Database)
            $this->StoreSettings->set_company_database($arrReturn['dbname'], $this->StoreSettings);

            $tmp  = "(OPTIONNAME = 'OpenTime' OR ";
            $tmp .= "OPTIONNAME  = 'CloseTime' OR ";
            $tmp .= "OPTIONNAME  = 'YoyakuHyouStart' OR ";
            $tmp .= "OPTIONNAME  = 'YoyakuHyouEnd' OR ";
            //--------------------------------------------
            //display time for saturday and sunday
            //--------------------------------------------
            $tmp .= "OPTIONNAME  = 'YoyakuStart_satsun' OR ";
            $tmp .= "OPTIONNAME  = 'YoyakuEnd_satsun' OR ";
            //--------------------------------------------
            //期限表示用
            $tmp .= "OPTIONNAME = 'YoyakuUpperLimit' OR ";
            $tmp .= "OPTIONNAME = 'YoyakuUpperLimitOption' OR ";
            //--------------------------------------------
            $tmp .= "OPTIONNAME  = 'YoyakuStart' OR ";
            $tmp .= "OPTIONNAME  = 'YoyakuEnd')";

            $criteria   = array('STORECODE' => $arrReturn['storecode']);
            $criteria[] = $tmp;

            $v = $this->StoreSettings->find('all', array('conditions' => $criteria));

            foreach ($v as $itm) {
                switch ($itm['StoreSettings']['OPTIONNAME']) {
                    case 'OpenTime':
                        $arrReturn['OPEN_TIME'] = intval($itm['StoreSettings']['OPTIONVALUES']);
                        break;
                    case 'CloseTime':
                        $arrReturn['CLOSE_TIME'] = intval($itm['StoreSettings']['OPTIONVALUES']);
                        break;
                    case 'YoyakuHyouStart':
                        $arrReturn['YOYAKU_HYOU_OPEN_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    case 'YoyakuHyouEnd':
                        $arrReturn['YOYAKU_HYOU_CLOSE_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    //-------------------------------------------------------------------------------
                    //yoyaku time for saturday and sunday
                    //-------------------------------------------------------------------------------
                    case 'YoyakuStart_satsun':
                        $arrReturn['YOYAKU_OPEN_TIME_SATSUN'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    case 'YoyakuEnd_satsun':
                        $arrReturn['YOYAKU_CLOSE_TIME_SATSUN'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    //-------------------------------------------------------------------------------
                    case 'YoyakuStart':
                        $arrReturn['YOYAKU_OPEN_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    case 'YoyakuEnd':
                        $arrReturn['YOYAKU_CLOSE_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    //-------------------------------------------------------------------------------
                    case 'YoyakuUpperLimit':
                        $arrReturn['UPPER_LIMIT'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    case 'YoyakuUpperLimitOption':
                        $arrReturn['UPPER_LIMIT_OP'] = $itm['StoreSettings']['OPTIONVALUES'];
                        break;
                  
                    
                }
            }

            if ($arrReturn['YOYAKU_HYOU_OPEN_TIME'] == "") {
                $arrReturn['YOYAKU_HYOU_OPEN_TIME'] = $arrReturn['OPEN_TIME'];
                $arrReturn['YOYAKU_HYOU_CLOSE_TIME'] = $arrReturn['CLOSE_TIME'];
            }
            
            //------------------------------------------------------------------
            //check if yoyaku time for saturday and sunday is null
            //------------------------------------------------------------------
            if ($arrReturn['YOYAKU_OPEN_TIME_SATSUN'] == "") {
                $arrReturn['YOYAKU_OPEN_TIME_SATSUN'] = $arrReturn['YOYAKU_OPEN_TIME'];
            }//end if
            if ($arrReturn['YOYAKU_CLOSE_TIME_SATSUN'] == "") {
                $arrReturn['YOYAKU_CLOSE_TIME_SATSUN'] = $arrReturn['YOYAKU_CLOSE_TIME'];
            }//end if
            //------------------------------------------------------------------
            unset($arrReturn['dbname']);
            $this->MiscFunction->DeleteKeitaiSession($this);
            //------------------------------------------------------------------
            $Sql = "SELECT tblresult.*
                    FROM (
                          SELECT ST.storecode AS storecode,
                                 -1 AS staffcode,
                                 ST.syscode AS syscode,
                                 SYS.servicesname AS shortcutcode,
                                 SYS.description AS description
                          FROM storetype ST
                              JOIN servicessys SYS
                                  ON SYS.syscode = ST.syscode
                          WHERE ST.delflg IS NULL
                              AND ST.storecode = ".$arrReturn['storecode']." 
                          ORDER BY ST.syscode 
                          ) tblresult";
            //------------------------------------------------------------------
            $recdata = $this->StoreSettings->query($Sql);
            //------------------------------------------------------------------
            $storetypes = null;
            //------------------------------------------------------------------
            if (count($recdata) > 0) {
                foreach ($recdata as $rec) {
                    $storetypes[] = array("STORECODE"       => (int)$rec["tblresult"]["storecode"],
                                          "STAFFCODE"       => (int)$rec["tblresult"]["staffcode"],
                                          "SYSCODE"         => (int)$rec["tblresult"]["syscode"],
                                          "SHORTCUTCODE"    => $rec["tblresult"]["shortcutcode"],
                                          "DESCRIPTION"     => $rec["tblresult"]["description"]);
                }//end foreach
            }//end if
            //------------------------------------------------------------------
            $arrReturn = array_merge($arrReturn, array("storetype" => $storetypes));
            //------------------------------------------------------------------
            //Get All Store Store Types
            //------------------------------------------------------------------
            $arr_storetypes_allstore = null;
            $rs_storetypes_allstore = null;
            $Sql = "SELECT STORECODE, 
                           CONVERT(group_concat(syscode SEPARATOR ',') USING UTF8) AS STORETYPES 
                    FROM storetype
                    WHERE delflg IS NULL 
                    GROUP BY storecode";
            $rs_storetypes_allstore = $this->StoreSettings->query($Sql);
            //------------------------------------------------------------------
            if (count($rs_storetypes_allstore) > 0) {
                //--------------------------------------------------------------
                $arr_storetypes_allstore = array();
                //--------------------------------------------------------------
                foreach ($rs_storetypes_allstore AS $rec) {
                    $arr_storetypes_allstore[] = array("STORECODE"  => $rec['storetype']['STORECODE'],
                                                       "STORETYPES" => $rec[0]['STORETYPES']);
                }//end foreach
                //--------------------------------------------------------------
            }//end if
            //------------------------------------------------------------------
            $arrReturn = array_merge($arrReturn, array("allstoretype" => $arr_storetypes_allstore));
            //------------------------------------------------------------------
            return $arrReturn;
            //------------------------------------------------------------------
        } else {
            //------------------------------------------------------------------
            $arrReturn['sessionid'] = "";
            return $arrReturn;
            //------------------------------------------------------------------
        }//end if else
    }//end function


    /**
     * ログアウト実行される、セッションは破壊されます
     * Performs logout and destroys the session
     *
     * @param string $sessionid
     * @return boolean
     */
    function wsLogout($sessionid) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- セッションを破壊します (Destroys the session)
        $this->YoyakuSession->Destroy($this, $storeinfo['session_no']);

        return true;
    }
    //- #############################################################################




    // CUSTOMER FUNCTIONS -----------------------------------------------------------
    /**
     * 顧客検索機能
     * Performs customer search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_customerInformation
     */
    function wsSearchCustomer($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        $honbu = $this->StoreAccount->find('all', array(
                 'conditions' => array('StoreAccount.companyid' => $storeinfo['companyid'],
                                       'StoreAccount.honbu' => 1)));
        if (count($honbu) > 0) {
            $this->loadModel('DataShare');
            $this->DataShare->set_company_database($storeinfo['dbname'], $this->DataShare);

            $condition['STORECODE'] = $storeinfo['storecode'];
            $condition['DELFLG'] = null;
            $datashare = $this->DataShare->find('all', array('conditions' => $condition));

            $arrstorecode[] = $storeinfo['storecode'];
            for ($i=0; $i<count($datashare); $i++) {
                $arrstorecode[] = $datashare[$i]['DataShare']['SHARESTORECODE'];
            }
            $datashare = 1;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);

        if ($param['free_customer'] == 0) {
            $criteria[] = array('CID <> ' => '0');
        }
        unset($param['free_customer']);

        foreach ($param as $key => $val) {
            if ((!empty($val) || $val === '0') && $key != 'limit' && $key != 'page' && $key != 'orderby') {
                if ($key == "PHONE") {
                    $criteria['(TEL1 LIKE ? OR TEL2 LIKE ?)'] = array('%'.$val.'%', '%'.$val.'%');
                } elseif ($key == "MAILADDRESS") {
                    $criteria['(MAILADDRESS1 LIKE ? OR MAILADDRESS2 LIKE ?)'] = array('%'.$val.'%', '%'.$val.'%');
                } elseif ($key == "CNAME") {
                    $val = ereg_replace("　", "", $val);
                    $val = ereg_replace(" ", "", $val);
                    $criteria['(REPLACE(CNAME, "　", "") LIKE ? OR REPLACE(CNAME, " ", "") LIKE ?)'] = array('%'.$val.'%','%'.$val.'%');
                } elseif ($key == "CNAMEKANA") {
                    //--------------------------------------------------------------------------------------------------------------------
                    $val = ereg_replace("　", "", $val);
                    $val = ereg_replace(" ", "", $val);
                    //--------------------------------------------------------------------------------------------------------------------
                    $kanafull = ereg_replace("　", "", $val);
                    $kanafull = ereg_replace(" ", "", $val);
                    //--------------------------------------------------------------------------------------------------------------------
                    $val = mb_convert_kana($val, "kV", "UTF8");
                    $kanafull = mb_convert_kana($kanafull, "KV", "UTF8");
                    //--------------------------------------------------------------------------------------------------------------------
                    $criteria['(REPLACE(CNAMEKANA, "　", "") LIKE ? 
                                OR REPLACE(CNAMEKANA, " ", "") LIKE ? 
                                OR REPLACE(CNAMEKANA, "　", "") LIKE ?
                                 OR REPLACE(CNAMEKANA, " ", "") LIKE ?)'] = array('%'.$val.'%',
                                                                                  '%'.$val.'%',
                                                                                  '%'.$kanafull.'%',
                                                                                  '%'.$kanafull.'%');
                    //--------------------------------------------------------------------------------------------------------------------
                    } elseif ($key == "ADDRESS") {
                    $criteria['(ADDRESS1_1 LIKE ? OR concat(KEN1, SITYO1, MANSIONMEI, ADDRESS1) LIKE ?)'] = array('%'.$val.'%','%'.$val.'%');
                } elseif ($key == "keyword") {
                    $kword['(TEL1 LIKE ? OR TEL2 LIKE ?)'] = array('%'.$val.'%', '%'.$val.'%');
                    $kword['(MAILADDRESS1 LIKE ? OR MAILADDRESS2 LIKE ?)'] = array('%'.$val.'%', '%'.$val.'%');
                    $kword['(CNAME LIKE ?)'] = array('%'.$val.'%');
                    //--------------------------------------------------------------------------------------------------------------------
                    $val = mb_convert_kana($val, "kV", "UTF8");
                    $kanafull = mb_convert_kana($val, "KV", "UTF8");
                    //--------------------------------------------------------------------------------------------------------------------
                    $kword['(CNAMEKANA LIKE ?  
                             OR CNAMEKANA LIKE ?)'] = array('%'.$val.'%',
                                                            '%'.$kanafull.'%'); 
                    //--------------------------------------------------------------------------------------------------------------------
                    $kword['(ADDRESS1_1 LIKE ? OR concat(KEN1, SITYO1, MANSIONMEI, ADDRESS1) LIKE ?)'] = array('%'.$val.'%','%'.$val.'%');
                    $kword['IF(CONVERT(?, SIGNED INTEGER) > 0, (CSTORECODE = ?), "")'] = array($val, $val);
                    $keyword['OR'] = $kword;
                    $criteria[] = $keyword;
                } else {
                    $criteria[$key] = $val;
                }
            }
        }

        if ($datashare == 1 && $criteria['CSTORECODE'] == -1) {
            $criteria['CSTORECODE'] = $arrstorecode;
        } elseif ($datashare == 1 && $criteria['CSTORECODE'] > 0) {
            $criteria[] = array('CSTORECODE' => $criteria['CSTORECODE'],
                                'CSTORECODE' => $arrstorecode);
        }

        if ($criteria['CSTORECODE'] == -1) {
            unset($criteria['CSTORECODE']);
        }

        $criteria[] = array('CNAME <> ' => null,
                            'CNAME <> ' => '');
        $criteria['DELFLG'] = null;

        $fields = array('CCODE', 'CNUMBER', 'CID', 'CSTORECODE', 'CNAME', 'CNAMEKANA',
                        'SEX', 'ZIPCODE1', 'KEN1', 'SITYO1', 'MANSIONMEI', 'SI1', 'REGULAR',
                        'TYO1', 'ADDRESS1', 'ADDRESS1_1', 'TEL1', 'TEL2', 'BIRTHDATE', 'MEMBERSCATEGORY',
                        'CHRISTIAN_ERA', 'MAILADDRESS1', 'MAILADDRESS2', 'MAILKUBUN', 'FIRSTDATE',
                        'HOWKNOWSCODE',
                        'BLOODTYPE', 'MEMBER_CODE', 'SMOKING', 'DMKUBUN', 'JOBINDUSTRYCODE' //, 'CREATEDFROMCODE' //add by albert 2105-10-27 --> customer integration information
                        );

        if (!in_array($param['orderby'], $fields)) {
            $param['orderby'] = "CNAME";    //$this->Customer->primaryKey
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        if ($param['limit'] <> -1) {
            $v = $this->Customer->find('all', array('conditions' => $criteria,
                                                    'fields'     => $fields,
                                                    'order'      => array($param['orderby']),
                                                    'limit'      => $param['limit'],
                                                    'page'       => $param['page']));
        } else {
            $v = $this->Customer->find('all', array('conditions' => $criteria,
                                                    'fields'     => $fields,
                                                    'order'      => array($param['orderby'])));
        }
        
        $ret = array();
        $ret['records']      = set::extract($v, '{n}.Customer');
        $ret['record_count'] = $this->Customer->find('count', array('conditions' => $criteria));
        
        //---------------------------------------------------------------------------
        //Get How Knows The Store data records and merge
        //---------------------------------------------------------------------------
        if (count($ret['records']) > 0) {
            //-----------------------------------------------------------------------
            $tmpObject = null;
            $ctr = 0;
            //-----------------------------------------------------------------------
            foreach($ret['records'] as $data) {
                 //------------------------------------------------------------------
                 $GetData = array();
                 //------------------------------------------------------------------
                 if ((int)$data['HOWKNOWSCODE'] > 0) {
                     //--------------------------------------------------------------
                     $Sql = "SELECT HOWKNOWSCODE, HOWKNOWS 
                             FROM howknows_thestore
                             WHERE HOWKNOWSCODE = ".$data['HOWKNOWSCODE'];
                     //--------------------------------------------------------------
                     $GetData = $this->Customer->query($Sql);
                     //--------------------------------------------------------------
                     if (count($GetData) > 0) {
                          $tmpObject = array_merge($data, $GetData[0]['howknows_thestore']);
                     } else {
                          $tmpObject = $data;
                     }//end if       
                     //--------------------------------------------------------------
                 }//end if
                 else {
                     //--------------------------------------------------------------
                     $GetData[0]['howknows_thestore']['HOWKNOWSCODE'] = -1;
                     $GetData[0]['howknows_thestore']['HOWKNOWS'] = "";
                     //--------------------------------------------------------------
                     $tmpObject = array_merge($data, $GetData[0]['howknows_thestore']);
                     //--------------------------------------------------------------
                 }//end else
                 //------------------------------------------------------------------
                 $ret['records'][$ctr] = $tmpObject;
                 $ctr++;
                 //------------------------------------------------------------------
            }//end for each
            //-----------------------------------------------------------------------
        }//end if
        //---------------------------------------------------------------------------
        // Add last tantou staff code and last tantou staff name
        // Added By: Marvin marvin@think-ahead.jp
        // Date Added: 2012-08-17
        //---------------------------------------------------------------------------
        if (count($ret['records']) > 0) {
            $ctr = 0;
            foreach ($ret['records'] as $rec) {
                $Sql = "SELECT f_get_last_staffcodename(".$storeinfo['storecode'].", "."'".$rec['CCODE']."') AS laststaffcodename";
                $datarec = $this->Customer->query($Sql);
                if (count($datarec) > 0) {
                    list($laststaffcode, $laststaffname) = split('[#]', $datarec[0][0]["laststaffcodename"]);
                    $ret['records'][$ctr] = array_merge($rec, array('LASTSTAFFCODE' => (int)$laststaffcode,
                                                                    'LASTSTAFFNAME' => $laststaffname));
                } else {
                    $ret['records'][$ctr] = array_merge($rec, array('LASTSTAFFCODE' => 0,
                                                                    'LASTSTAFFNAME' => ''));
                }//end if else
                $ctr++;
            }//end foreach
        }//end if   
        //---------------------------------------------------------------------------
        // Updated By: Homer Pasamba 2013/02/05
        // Comment: Set Customer Full Address
        //---------------------------------------------------------------------------
        
        if (count($ret['records']) > 0) {
            $ctr = 0;
            foreach ($ret['records'] as $rec) {
                $FullAddress['FULLADDRESS'] = '';
                $FullAddress['FULLADDRESS'] = $ret['records'][$ctr]['KEN1'].
                                              $ret['records'][$ctr]['SITYO1'].
                                              $ret['records'][$ctr]['MANSIONMEI'].
                                              $ret['records'][$ctr]['ADDRESS1'];
                $ret['records'][$ctr] = array_merge($rec, $FullAddress);
                $ctr++;
            }// End foreach ($ret['records'] as $rec)
        }// End if (count($ret['records']) > 0) 
        //---------------------------------------------------------------------------
        
        //---------------------------------------------------------------------------
        // by albert 2015-10-27 for BM connection -------------------------------
        //---------------------------------------------------------------------------
        if (count($ret['records']) > 0) { //check and get BMCODE if the customer have BM record
            $ctr = 0;
            foreach($ret['records'] as $data) {
                $BMCustID['BMCODE'] = "";
                
//                if ($data['CREATEDFROMCODE'] == 7) {
                    $Sql = "select distinct site_customer_id as BMCODE
                            from bm_reservation 
                            where ccode = '".$data['CCODE']."'";
                    $GetData = $this->Customer->query($Sql);
                    if (count($GetData) > 0) {
                         $BMCustID['BMCODE'] = $GetData[0]['bm_reservation']['BMCODE'];
                    }
//                }else if ($data['CREATEDFROMCODE'] == 8) {
//                    $Sql = "select distinct alliance_customer_id as BMCODE
//                            from rv_customer 
//                            where ccode = '".$data['CCODE']."'";
//                    $GetData = $this->Customer->query($Sql);
//                    if (count($GetData) > 0) {
//                         $BMCustID['BMCODE'] = $GetData[0]['rv_customer']['BMCODE'];
//                    }                    
//                }
                    
                $ret['records'][$ctr] = array_merge($data, $BMCustID);
                $ctr++;
            }
        }
        
        if (count($ret['records']) > 0) { //get jobindustry description
            $ctr = 0;
            
            foreach($ret['records'] as $data) {
                $JobInd['JOBINDUSTRY'] = "なし";
                if ($data['JOBINDUSTRYCODE'] > 0){
                    $Sql = "select jobindustry
                            from job_industry
                            where delflg is null and jobindustrycode = ".$data['JOBINDUSTRYCODE'];
                    $GetData = $this->Customer->query($Sql);
                    if (count($GetData) > 0) {
                         $JobInd['JOBINDUSTRY'] = $GetData[0]['job_industry']['jobindustry'];
                    }
                }
                $ret['records'][$ctr] = array_merge($data, $JobInd);
                $ctr++;
            }
        }
        //---------------------------------------------------------------------------
        // add by albert 2015-10-27 for BM connection -------------------------------
        //---------------------------------------------------------------------------
        
       return $ret;
       //---------------------------------------------------------------------------
    }//end function


    /**
     * 顧客歴史検索機能
     * Performs customer history search
     *
     * @param string $sessionid
     * @param string $ccode
     * @return return Array _storeTransactionInformation
     */
    function wsSearchCustomerHistory($sessionid, $ccode) {
        //-----------------------------------------------------------------------------------------------------------------
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        //-----------------------------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-----------------------------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }
        //-----------------------------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        //-----------------------------------------------------------------------------------------------------------------
        $this->StoreTransaction->set_company_database($storeinfo['dbname'], $this->StoreTransaction);
        //-----------------------------------------------------------------------------------------------------------------
        $sql =  "SELECT transaction.TRANSCODE,
                        transaction.KEYNO,
                        transaction.STORECODE,
                        transaction.IDNO,
                        transaction.TRANSDATE,
                        transaction.CCODE,
                        transaction.CLAIMKYAKUFLG,
                        transaction.UPDATEDATE,
                        transaction.PRIORITY,
                        transaction.YOYAKU,
                        transaction.REGULARCUSTOMER,
                        transaction.TEMPSTATUS,
                        transaction.KYAKUKUBUN,
                        transaction.ZEIOPTION,
                        transaction.RATETAX,
                        transaction.TAX,
                        transaction.SOGOKEIOPTION,
                        transaction.APT_COLOR,
                        transaction.NOTES,
                        transaction.STAFFCODE,
                        transaction.ENDTIME,
                        transaction.STARTTIME,
                        transaction.YOYAKUTIME,
                        transaction.CNAME,
                        transaction.SEX,
                        details.TRANSCODE,
                        details.ROWNO,
                        details.GCODE,
                        details.STAFFCODE,
                        details.STAFFCODESIMEI,
                        details.ZEIKUBUN,
                        details.CLAIMED,
                        details.KASANPOINT1,
                        details.KASANPOINT2,
                        details.KASANPOINT3,
                        details.TRANTYPE,
                        details.STARTTIME,
                        details.ENDTIME,
                        details.TAX,
                        details.PRICE,
                        details.UNITPRICE,
                        details.QUANTITY,
                        services.GDCODE,
                        services.BUNRUINAME,
                        services.SYSCODE,
                        service.KEYCODE,
                        service.YOYAKUMARK,
                        service.MENUNAME,
                        product.PRODUCTNAME,
                        customer.CNAMEKANA,
                        customer.TEL1,
                        customer.TEL2,
                        customer.BIRTHDATE,
                        customer.MEMBERSCATEGORY,
                        customer.CNUMBER,
                        customer.CSTORECODE,
                        customer.CNAME,
                        customer.SEX,
                        howknows_thestore.HOWKNOWSCODE,
                        howknows_thestore.HOWKNOWS,
                        staff.STAFFNAME,
                        servicessys.servicesname,
                        claim.claimname,

                        (CASE 
                        WHEN details.ZEIKUBUN = 0 THEN 
                                (CASE 
                                WHEN transaction.ZEIOPTION = 0 
                                    THEN FLOOR((transaction.RATETAX * details.UNITPRICE) * details.QUANTITY)
                                WHEN transaction.ZEIOPTION = 1 
                                    THEN ROUND(((transaction.RATETAX * details.UNITPRICE) * details.QUANTITY),0)
                                WHEN transaction.ZEIOPTION = 2 
                                    THEN CEILING((transaction.RATETAX * details.UNITPRICE) * details.QUANTITY)
                                END)
                        ELSE (details.TAX * details.QUANTITY) 
                        END) as TOTALTAX,

                        (details.PRICE + f_get_trans_detail_tax(transaction.TRANSCODE, transaction.KEYNO, details.ROWNO)) as PRICETAXINC

            FROM store_transaction transaction
            USE INDEX (CCODEDELFLGTEMPSTATUS)
                        LEFT JOIN store_transaction_details details
                                ON transaction.storecode = details.storecode
                                    AND transaction.transcode = details.TRANSCODE
                                    AND transaction.keyno = details.keyno
                                    AND details.delflg IS NULL
                        LEFT JOIN staff
                                ON details.staffcode = staff.staffcode
                        LEFT JOIN store_services service
                                ON details.GCODE = service.GCODE
                                    AND details.TRANTYPE = 1
                        LEFT JOIN store_products product
                                ON details.STORECODE = product.STORECODE
                                    AND details.GCODE = product.PRODUCTCODE
                                    AND details.TRANTYPE = 2
                        LEFT JOIN claim
                                ON claim.claimcode = details.claimcode
                                    AND details.claimed = 1
                        LEFT JOIN services
                                ON service.GDCODE =  services.GDCODE
                        LEFT JOIN servicessys
                                ON services.SYSCODE = servicessys.SYSCODE
                        LEFT JOIN customer as customer 
                                ON transaction.CCODE = customer.CCODE
                        LEFT JOIN howknows_thestore as howknows_thestore 
                                ON howknows_thestore.howknowscode = customer.howknowscode
                WHERE  transaction.tempstatus = 0
                        AND transaction.ccode = '".$ccode."'
                        AND transaction.delflg IS NULL
                GROUP BY transaction.transcode, transaction.keyno, details.trantype, details.rowno
                ORDER BY transaction.transdate DESC, transaction.idno, details.trantype, details.rowno"; 
        //----------------------------------------------------------------------------------------------------------------- 
        $customer_history = $this->StoreTransaction->query($sql);
        //-----------------------------------------------------------------------------------------------------------------
        $customer_history = $this->MiscFunction->ParseHistoryTransactionData($this, $customer_history);
        //-----------------------------------------------------------------------------------------------------------------
        $ret = array();
        $ret['records']      = $customer_history;
        $ret['record_count'] = count($customer_history);
        //=================================================================================================================
        return $ret;
        //=================================================================================================================
    }


    /**
     * 顧客の追加と更新機能
     * Adds or Updates a customer
     *
     * @param string $sessionid
     * @param array $param
     * @return return_customerIDs
     */
    function wsAddUpdateCustomer($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
            $storeinfo['storecode'] = $param['storecode'];
            unset($param['dbname'], $param['storecode']);
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);

        //-- STORECODEは設定してない場合、当店に設定(Check storecode, set default if none)
        if (empty($param['CSTORECODE'])) {
            $param['CSTORECODE'] = $storeinfo['storecode'];
        }
        
        //-- CCODEは設定してない場合、新規CCODEを作成 (Check CCODE, create new if none)
        if (empty($param['CCODE'])) {
            $sc = $param['CSTORECODE'];
            $querty_txt = "select ".
                          "f_get_sequence_key('cid',".$sc.", '') as cid, ".
                          "f_get_sequence_key('cnumber', ".$sc.", '') as cnumber";

            $tmp_data = $this->Customer->query($querty_txt);
            $param['CID']     = $tmp_data[0][0]['cid'];
            //$param['CNUMBER'] = sprintf("%03s%07s", $param['CSTORECODE'], $tmp_data[0][0]['cnumber']);
            $param['CNUMBER'] = null;
            $param['CCODE']   = sprintf("%03s%07s", $param['CSTORECODE'], $param['CID']);
            $mode = "insert";
            $param['CREATEDFROMCODE'] = 3;
            
            #-----------------------------------------------------------------------------------------------
            # Added by: MarvinC - 2016-01-14 15:29
            #-----------------------------------------------------------------------------------------------
            # GET THE MAILKUBUN AND DMKUBUN SETTINGS FROM TENPO
            #-----------------------------------------------------------------------------------------------
            $sql = "SELECT optionname, optionvalues
                    FROM store_settings
                    WHERE optionname IN ('EmailIssue', 'MailIssue')
                    AND storecode = {$param['CSTORECODE']}";

            $result = $this->StoreTransaction->query($sql);

            if (count($result) > 0){
                foreach ($result as $val){
                    if($val["store_settings"]["optionname"] == "EmailIssue"){
                        $param["MAILKUBUN"] = ($val["store_settings"]["optionvalues"] == "True") ? 1 : 0;
                    }elseif($val["store_settings"]["optionname"] == "MailIssue"){
                        $param["DMKUBUN"] = ($val["store_settings"]["optionvalues"] == "True") ? 1 : 0;
                    }
                }
            }
            #-----------------------------------------------------------------------------------------------
        }

        //----------------------------------------------------------------------------
        //override the cstorecode value same with ccode storecode [001]-0000001
        //----------------------------------------------------------------------------
        $param['CSTORECODE'] = (int)substr($param['CCODE'], 0, 3);
        if ($param['CSTORECODE'] == 0) $param['CSTORECODE'] = $storeinfo['storecode'];
        //----------------------------------------------------------------------------
        
        //-- Generates CNUMBER for customers who do not have
//        if (empty($param['CNUMBER'])) {
//            $sc = $param['CSTORECODE'];
//            $querty_txt = "select ".
//                          "f_get_sequence_key('cnumber', ".$sc.", '') as cnumber";
//            $tmp_data = $this->Customer->query($querty_txt);
//            $param['CNUMBER'] = sprintf("%03s%07s", $param['CSTORECODE'], $tmp_data[0][0]['cnumber']);
//        }

        $param['ADDRESS1_1'] = $param['KEN1'] . $param['SITYO1'] . $param['MANSIONMEI'] . $param['ADDRESS1'];

        //-- 顧客情報を準備する (prepare customer information)
        foreach ($param as $key => $val) {
            if ($key != 'CID' || $mode == "insert") {
                if ($key == 'BIRTHDATE' && $val == "") {
                    $this->Customer->set($key, null);
                } else if ($key == 'CNUMBER' && $val == "") {
                    $this->Customer->set($key, null);
                } else {
                    $this->Customer->set($key, $val);
                }
            }
        }

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->Customer->database_set == true) {

            $this->Customer->save(); // アップデートか追加を実行する (Update/Add Execute)
            $this->Customer->saveField('FIRSTDATE', $param['FIRSTDATE']);

            $ret['CCODE'] = $param['CCODE'];
            $ret['CNUMBER'] = $param['CNUMBER'];
            
            return $ret;
        } else {
            $this->_soap_server->fault(1, '', 'Error Processing Data');
        }
    }


    /**
     * 顧客の削除機能
     * Deletes a customer
     *
     * @param string $sessionid
     * @param string $ccode
     * @return boolean
     */
    function wsDeleteCustomer($sessionid, $ccode) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);

        //-- 顧客を削除フラグを設定 (Set Delete flag on Customer)
        $this->Customer->set('CCODE', $ccode);
        $this->Customer->set('DELFLG', date('Y-m-d h:i:s'));
        $this->Customer->save();

        return true;
    }
    //- #############################################################################




    // STAFF FUNCTIONS --------------------------------------------------------------
    /**
     * スタッフ検索機能
     * Performs staff search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_staffInformation
     */
    function wsSearchStaff($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        $this->Position->set_company_database($storeinfo['dbname'], $this->Position);
        $this->Sublevel->set_company_database($storeinfo['dbname'], $this->Sublevel);
        $this->StaffAssignToStore->set_company_database($storeinfo['dbname'], $this->StaffAssignToStore);
        
        $use_syscode = intval($param['syscode']) > 0 ? true : false;

        $bindarray = NULL;
        $this->Stafftype->set_company_database($storeinfo['dbname'], $this->Stafftype);
        if($use_syscode){
          //$this->Stafftype->set_company_database($storeinfo['dbname'], $this->Stafftype);
          $bindarray = array(
          'hasOne' => array('Sublevel' => array(
                                    'foreignKey' => false,
                                    'conditions' => array('Sublevel.SUBLEVELCODE = Staff.SUBLEVELCODE')),
                            'Position' => array(
                                    'foreignKey' => false,
                                    'conditions' => array('Position.POSITIONCODE = Staff.POSITIONCODE')),
                            'StaffAssignToStore' => array(
                                    'foreignKey' => false,
                                    'conditions' => array('StaffAssignToStore.STAFFCODE = Staff.STAFFCODE',
                                                          'StaffAssignToStore.STORECODE = ' . $storeinfo['storecode'])),
                            'Stafftype' => array(
                                    'type' => 'inner',
                                    'foreignKey' => 'STAFFCODE',
                                    'conditions' => array('Stafftype.STAFFCODE = Staff.STAFFCODE',
                                                          'Stafftype.SYSCODE ='.$param['syscode']))
                            ));
        } else {
            //--------------------------------------------------------------------------------------------------------------------
            $bindarray = array(
            'hasOne' => array('Sublevel' => array(
                                        'foreignKey' => false,
                                        'conditions' => array('Sublevel.SUBLEVELCODE = Staff.SUBLEVELCODE')),
                                'Position' => array(
                                        'foreignKey' => false,
                                        'conditions' => array('Position.POSITIONCODE = Staff.POSITIONCODE')),
                                'StaffAssignToStore' => array(
                                        'foreignKey' => false,
                                        'conditions' => array('StaffAssignToStore.STAFFCODE = Staff.STAFFCODE',
                                                              'StaffAssignToStore.STORECODE = ' . $storeinfo['storecode'])),
                                'Stafftype' => array(
                                        'type' => 'inner',
                                        'foreignKey' => 'STAFFCODE',
                                        'conditions' => array('Stafftype.STAFFCODE = Staff.STAFFCODE',
                                                              'Stafftype.delflg IS NULL OR (StaffAssignToStore.STAFFCODE = 0 
                                                                                                AND StaffAssignToStore.storecode = '.$storeinfo['storecode'].')',
                                                              'Stafftype.SYSCODE in (SELECT syscode
                                                                                    FROM storetype
                                                                                    WHERE delflg IS NULL
                                                                                            AND storecode = '.$storeinfo['storecode'].')'))
                                ));
            //--------------------------------------------------------------------------------------------------------------------
        }//end else
 
        /*
        $this->Staff->bindModel(array(
          'hasOne' => array('Sublevel' => array(
                                    'foreignKey' => false,
                                    'conditions' => array('Sublevel.SUBLEVELCODE = Staff.SUBLEVELCODE')),
                            'Position' => array(
                                    'foreignKey' => false,
                                    'conditions' => array('Position.POSITIONCODE = Staff.POSITIONCODE')),
                            'StaffAssignToStore' => array(
                                    'foreignKey' => false,
                                    'conditions' => array('StaffAssignToStore.STAFFCODE = Staff.STAFFCODE',
                                                          'StaffAssignToStore.STORECODE = ' . $storeinfo['storecode'])),
                            )));
         */
        $this->Staff->bindModel($bindarray);
        $this->Staff->unbindModel(array('belongsTo' => array('Sublevel', 'Position')));

        if (empty($param['orderby'])) {
            //$param['orderby'] = $this->Staff->primaryKey;
            //$param['orderby'] = 'IF(Staff.STAFFCODE = 0, 0, IFNULL(StaffAssignToStore.DISPLAY_ORDER, 9999999)),
            //                     Staff.STAFFCODE';
            $param['orderby'] = 'Staff.DISPLAY_ORDER, Staff.STAFFCODE';
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        foreach ($param as $key => $val) {
            if (    !empty($val) &&
                    $key != 'limit' &&
                    $key != 'page' &&
                    $key != 'orderby' &&
                    $key != 'showfreestaff' &&
                    $key != 'syscode'
                    ) {
                $criteria['Staff.'.$key] = $val;
            }
        }
        
        //-- Added 2010-2-19 by T.Springer, for use when showing honbu store --//
        if ($param['STORECODE'] == 0) {
            $criteria['Staff.STORECODE'] = 0;
            $criteria[] = 'Staff.STAFFCODE <> 0';
        } elseif ($param['STORECODE'] == -1) {
            // 店舗コードを検索条件に含めない(全店舗を対象とする)
            unset($criteria['Staff.STORECODE']);
        }

        if ($param['showfreestaff'] == 1) {
            $criteria_top['or'] = array($criteria,
                                    'Staff.staffcode' => 0);
        } else {
            $criteria_top = $criteria;
        }
       
        $criteria_top['Staff.DELFLG'] = null;
               
        //------------------------------------------------------------------------------------------------
        //Array Fields Definition
        //Update by: Marvin marvin@think-ahead.jp 2012-01-04
        //Add Field Array to prevent overflow and get only the fields needed not all fields in the table
        //------------------------------------------------------------------------------------------------
        $arrFields = array("DISTINCT Staff.STAFFCODE",
                           "Staff.STAFFNAME",
                           "Staff.STAFFNAME2",
                           "Staff.STORECODE",
                           "Staff.KEYNO",
                           "Staff.SUBLEVELCODE",
                           "Staff.POSITIONCODE",
                           "Staff.TRAVEL_ALLOWANCE",
                           "Staff.SEX",
                           "Staff.HIREDATE",
                           "Staff.RETIREDATE",
                           "Staff.YOYAKUKUBUN",
                           "Staff.STAFFIMGFLG",
                           "Staff.DISPLAY_ORDER",
                           "Staff.WEBFLG",
                           "Staff.WEBYAN_DISPLAY",
                           "Staff.c_member_id",
                           "Staff.email",
                           "Staff.create_comm_flag",
                           "Staff.BLOG_URL",
                           "Position.POSITIONCODE",
                           "Position.POSITIONNAME",
                           "Position.DISPLAY_ORDER",
                           "Sublevel.SUBLEVELCODE",
                           "Sublevel.SUBLEVELNAME",
                           "Sublevel.LEVELCODE",
                           "StaffAssignToStore.STORECODE",
                           "StaffAssignToStore.STAFFCODE",
                           "StaffAssignToStore.KEYNO",
                           "StaffAssignToStore.ASSIGN_YOYAKU",
                           "StaffAssignToStore.WEBYAN_DISPLAY",
                           "StaffAssignToStore.ASSIGN",
                           "StaffAssignToStore.DISPLAY_ORDER"
            );
        
            //------------------------------------------------------------------------------------------------
            if ($param['limit'] <> -1) {
                $v = $this->Staff->find('all', array('conditions' => $criteria_top,
                    //'order'        => array('Staff.'.$param['orderby']),
                    'fields' => $arrFields,
                    'order' => array($param['orderby']),
                    'limit' => $param['limit'],
                    'page' => $param['page']));
            } else {
                $v = $this->Staff->find('all', array('conditions' => $criteria_top,
                    //'order'        => array('Staff.'.$param['orderby'])));
                    'fields' => $arrFields,
                    'order' => array($param['orderby'])));
            }
 
        for ($i = 0; $i < count($v); $i++) {
            $v[$i]['Staff']['SUBLEVELNAME'] = $v[$i]['Sublevel']['SUBLEVELNAME'];
            $v[$i]['Staff']['POSITIONNAME'] = $v[$i]['Position']['POSITIONNAME'];
            $v[$i]['Staff']['WEB_DISPLAY'] = $v[$i]['StaffAssignToStore']['WEBYAN_DISPLAY'];
            $v[$i]['Staff']['YOYAKU_DISPLAY'] = $v[$i]['StaffAssignToStore']['ASSIGN_YOYAKU'];
            //staff assign to store display order no used
            //$v[$i]['Staff']['DISPLAY_ORDER'] = $v[$i]['StaffAssignToStore']['DISPLAY_ORDER'];
        }

        /*         * *** Old and Ugly Method **************************************************
         *  might have a faster execution time so we are keeping the code around
          $showcalendar_sql = "SELECT STAFFCODE,
          (   SELECT SHOWINCALENDAR
          FROM staffrowshistory
          WHERE staffcode = tblA.staffcode
          AND storecode = tblA.storecode
          ORDER BY datechange DESC
          LIMIT 1   ) as SHOWINCALENDAR
          FROM staffrowshistory  tblA
          WHERE storecode = ".$storeinfo['storecode']."
          GROUP BY staffcode";


          $showincalendar_result = $this->StaffRowsHistory->query($showcalendar_sql);
          $showincalendar_list = array();
          foreach($showincalendar_result as $entry) {
          $showincalendar_list[$entry['tblA']['STAFFCODE']] = $entry[0]['SHOWINCALENDAR'];
          }

          for ($i = 0; $i < count($v); $i++) {
          $staffcode = $v[$i]['Staff']['STAFFCODE'];
          $v[$i]['Staff']['SUBLEVELNAME']   = $v[$i]['Sublevel']['SUBLEVELNAME'];
          $v[$i]['Staff']['POSITIONNAME']   = $v[$i]['Position']['POSITIONNAME'];
          $v[$i]['Staff']['WEB_DISPLAY']    = $v[$i]['Staff']['WEBYAN_DISPLAY'];
          $v[$i]['Staff']['YOYAKU_DISPLAY'] = $showincalendar_list[$staffcode];
          $v[$i]['Staff']['YOYAKU_DISPLAY'] = $v[$i]['StaffRowsHistory'][0]['SHOWINCALENDAR'];
          }
         * ************************************************************************** */
         
        $ret = array();
        $ret['records'] = set::extract($v, '{n}.Staff');
        $ret['record_count'] = $this->Staff->find('count', array('conditions' => $criteria_top,
                                                  'fields' => 'DISTINCT Staff.STAFFCODE'));
        if (count($ret['records']) > 0) {
            $ctr = 0;
            foreach ($ret['records'] AS $rec) {
                $staffsycodes = "";
                $staffviewcodes = "";
                $Sql = "SELECT syscode,YOYAKU_VIEW
                        FROM stafftype 
                        WHERE delflg IS NULL 
                            AND staffcode = ".$rec["STAFFCODE"];
                $rs = $this->Staff->query($Sql);
                if (count($rs) > 0) {
                    foreach ($rs AS $da) {
                        if ($staffsycodes != ""){
                            $staffsycodes .= " ";                    
                        }
                        $staffsycodes .= $da["stafftype"]["syscode"];
                        
                        if ($staffviewcodes != ""){
                            $staffviewcodes .= " ";          
                        }
                        if($da["stafftype"]["YOYAKU_VIEW"] == 1)
                        {
                            $staffviewcodes .= $da["stafftype"]["syscode"];
                        }
                        
                    }//end foreach
                }//end if
                $rec["SYSCODES"] = $staffsycodes;
                $rec["STAFFVIEWS"] = $staffviewcodes;
                $ret['records'][$ctr] = $rec;
                $ctr++;
            }//end foreach
        }//end if
        return $ret;
    }

    /**
     * 利用可能スタッフ検索機能
     * Performs a search on available staffs
     *
     * @param string $sessionid
     * @param array $param
     * @return return_staffInformation
     */
    function wsSearchAvailableStaff($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
        }

        $this->StaffAssignToStore->set_company_database($storeinfo['dbname'], $this->StaffAssignToStore);

        if (empty($param['orderby'])) {
            //$param['orderby'] = "IF(Staff.STAFFCODE = 0, 0, IFNULL(StaffAssignToStore.DISPLAY_ORDER, 9999999)),
            //                     Staff.STAFFCODE";
            $param['orderby'] = "Staff.DISPLAY_ORDER, Staff.STAFFCODE";
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        $JoinStaffType = "";
        if ($param['STAFFCODE'] > -1) {
            $extra = " AND StaffAssignToStore.STAFFCODE = " . $param['STAFFCODE'];            
        } else {
            $JoinStaffType = " JOIN stafftype 
                                    ON stafftype.staffcode = StaffAssignToStore.staffcode 
                                        AND stafftype.delflg IS NULL 
                                        OR (StaffAssignToStore.staffcode = 0 AND StaffAssignToStore.storecode = ".$param['STORECODE'].") 
                                        AND stafftype.syscode IN (SELECT syscode
                                                                  FROM storetype
                                                                  WHERE delflg IS NULL
                                                                      AND storecode = ".$param['STORECODE'].") ";            
        }//end if else
        //----------------------------------------------------------------------------------------------------------------------------
        $sql = "/*wsSearchAvailableStaff*/
                SELECT DISTINCT  
                    StaffAssignToStore.STAFFCODE,
                    Staff.STORECODE,
                    Staff.STAFFNAME,
                    Store.STORENAME,
                    if(Staff.STORECODE = ".$param['STORECODE'].",
                      StaffAssignToStore.WEBYAN_DISPLAY,
                      if(Staff.STAFFCODE = 0,  StaffAssignToStore.WEBYAN_DISPLAY, 0)) as WEBYAN_DISPLAY,
                    IFNULL(StaffRowsHistory.PHONEROWS, ".DEFAULT_PHONEROWS.") as PHONEROWS,
                    IFNULL(StaffRowsHistory.ROWS, ".DEFAULT_ROWS.") as ROWS,
                    CONVERT(tblstafftypes.STAFFTYPES USING UTF8) AS STAFFTYPES,
                    CONVERT(tblstafftypes.STAFFVIEWS USING UTF8) AS STAFFVIEWS
                FROM staff_assign_to_store as StaffAssignToStore 
                    ".$JoinStaffType." 
                    LEFT JOIN staff as Staff ON StaffAssignToStore.STAFFCODE = Staff.STAFFCODE
                    LEFT JOIN store as Store ON Staff.STORECODE = Store.STORECODE
                    LEFT JOIN (SELECT *
                               FROM(
                               SELECT *
                                    FROM staffrowshistory as StaffRowsHistory
                                    WHERE StaffRowsHistory.STORECODE = " . $param['STORECODE'] ."
                                       AND StaffRowsHistory.DATECHANGE <= '". $param['date'] ."'
                                    ORDER BY StaffRowsHistory.DATECHANGE DESC
                                    ) as TMPTBL 
                                GROUP BY TMPTBL.staffcode
                               ) as StaffRowsHistory ON StaffRowsHistory.STAFFCODE = Staff.STAFFCODE
                    LEFT JOIN store_settings as Settings
                        ON Settings.STORECODE = StaffAssignToStore.STORECODE
                            AND Settings.OPTIONNAME = 'HIDE_HOLIDAY_STAFF'
                    LEFT JOIN ((SELECT STAFFCODE,YMD,MIN(HOLIDAYTYPE) as HOLIDAYTYPE,BIKOU,UPDATEDATE, DELFLG,UPDATE_INDEX,STORECODE,SHIFT
                        FROM staff_holiday
                        where YMD = '" . $param['date'] . "' and storecode = ".$param['STORECODE']." and staff_holiday.delflg is null
                        group by storecode,YMD,staffcode)
            		) as Holiday
                        ON Holiday.STAFFCODE = Staff.STAFFCODE
                            AND Holiday.YMD = '" . $param['date'] . "' 
                    LEFT JOIN (
                               SELECT STAFFCODE, GROUP_CONCAT(syscode SEPARATOR ',') AS STAFFTYPES,
                               ifnull(GROUP_CONCAT(case when YOYAKU_VIEW = 1 then syscode end),'') AS STAFFVIEWS
                               FROM stafftype
                               WHERE delflg IS NULL 
                               GROUP BY staffcode
                              ) tblstafftypes
			ON tblstafftypes.STAFFCODE = StaffAssignToStore.STAFFCODE
                WHERE StaffAssignToStore.STORECODE = " . $param['STORECODE'] . "
                    AND StaffAssignToStore.ASSIGN_YOYAKU = 1
                    AND Staff.DELFLG IS NULL
                    AND (Staff.HIREDATE IS NULL OR Staff.HIREDATE <= '" . $param['date'] . "')
                    AND (Staff.RETIREDATE IS NULL OR Staff.RETIREDATE >= '" . $param['date'] . "')
                    " . $extra . "
                    AND NOT (
                        COALESCE(Settings.OPTIONVALUEI, 0) = 1
                            AND COALESCE(Holiday.HOLIDAYTYPE, 4) < 4
                            AND Holiday.DELFLG IS NULL
                    )
                GROUP BY StaffAssignToStore.STAFFCODE
                ORDER BY " . $param['orderby'] . " ";
        //-------------------------------------------------------------------------------------------------
        $offset = $param['limit'] * ($param['page'] - 1);
        $limit_offset = "LIMIT " . $param['limit'] . "
                         OFFSET " . $offset;
        //-------------------------------------------------------------------------------------------------
        if ($param['limit'] <> -1) {
            $sql_limit_offset = $sql . $limit_offset;
            $v = $this->StaffAssignToStore->query($sql_limit_offset);
        } else {
            $v = $this->StaffAssignToStore->query($sql);
        }//end if else
        //-------------------------------------------------------------------------------------------------        
        for ($i = 0; $i < count($v); $i++) {
            $v[$i]['StaffAssignToStore']['STAFFNAME']  = $v[$i]['Staff']['STAFFNAME'];
            $v[$i]['StaffAssignToStore']['STORECODE']  = $v[$i]['Staff']['STORECODE'];
            $v[$i]['StaffAssignToStore']['WEB_DISPLAY']= $v[$i][0]['WEBYAN_DISPLAY'];
            $v[$i]['StaffAssignToStore']['STORENAME']  = $v[$i]['Store']['STORENAME'];
            if ($v[$i][0]['ROWS'] == "" ||
                $v[$i][0]['ROWS'] == 0) {
                   $v[$i][0]['ROWS'] = DEFAULT_ROWS;
            }
            if ($v[$i][0]['PHONEROWS'] == "" ||
                $v[$i][0]['PHONEROWS'] == 0) {
                    $v[$i][0]['PHONEROWS'] = DEFAULT_PHONEROWS;
            }
            $v[$i]['StaffAssignToStore']['ROWS']       = $v[$i][0]['ROWS'];
            $v[$i]['StaffAssignToStore']['PHONEROWS']  = $v[$i][0]['PHONEROWS'];
            $v[$i]['StaffAssignToStore']['STAFFTYPES']  = $v[$i][0]['STAFFTYPES'];
            $v[$i]['StaffAssignToStore']['STAFFVIEWS']  = $v[$i][0]['STAFFVIEWS'];
        }//end for

        $ret = array();
        $ret['records']      = set::extract($v, '{n}.StaffAssignToStore');
        $c = $this->StaffAssignToStore->query('SELECT count(*) as ctr FROM (' . $sql . ') as tmp');
        $ret['record_count'] = $c[0][0]['ctr'];
        //-------------------------------------------------------------------------------------------------
        return $ret;
        //-------------------------------------------------------------------------------------------------
    }//end function


    /**
     * スタッフ歴史行の追加と更新機能
     * Adds or Updates a staff rows history
     *
     * @param string $sessionid
     * @param array $param
     * @return STAFFCODE
     */
    function wsAddUpdateStaffRowsHistory($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StaffRowsHistory->set_company_database($storeinfo['dbname'], $this->StaffRowsHistory);

        $fields = "STORECODE, STAFFCODE, DATECHANGE, ROWS, PHONEROWS";
        $values = $param['STORECODE'] . "," .
                  $param['STAFFCODE'] . ", '" .
                  $param['date']      . "', " .
                  $param['ROWS']      . "," .
                  $param['PHONEROWS'];

        $sql = "REPLACE INTO staffrowshistory (".$fields.") VALUES(".$values.")";

        $this->StaffRowsHistory->query($sql);

        return $param['STAFFCODE'];
    }


    /**
     * スタッフの追加と更新機能
     * Adds or Updates a staff
     *
     * @param string $sessionid
     * @param array $param
     * @return STAFFCODE
     */
    function wsAddUpdateStaff($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        $this->StaffAssignToStore->set_company_database($storeinfo['dbname'], $this->StaffAssignToStore);

        //-- STORECODEは設定してない場合、当店に設定(Check storecode, set default if none)
        if ($param['STORECODE'] < 0) {
            $param['STORECODE'] = $storeinfo['storecode'];
        }

        //-- STAFFCODEは設定してない場合、新規STAFFCODEを作成 (Check STAFFCODE, create new if none)
        if (empty($param['STAFFCODE'])) {
            $tmp_data = $this->Staff->query("select f_get_sequence_key('staffcode', ".
                                             $param['STORECODE'].", '') as staffcode");
            $param['STAFFCODE'] = $tmp_data[0][0]['staffcode'];
        }

        //-- スタッフ情報を準備する (prepare staff information)
        foreach ($param as $key => $val) {
            if ( $key != 'WEB_DISPLAY' && $key != 'YOYAKU_DISPLAY' && $key != 'DISPLAY_ORDER') {
                $this->Staff->set($key, $val);
               
                if ($key == 'STAFFTYPECODES') {
                  $this->wsUpdateStaffType($sessionid, $param['STAFFCODE'], $val);
                } // end if
                
            }// end if
        } // end for

        //$this->Staff->set('WEBYAN_DISPLAY', intval($param['WEB_DISPLAY']));

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->Staff->database_set == true) {
            $this->Staff->save(); // アップデートか追加を実行する (Update/Add Execute)
            /*$this->StaffAssignToStore->set('STAFFCODE', intval($param['STAFFCODE']));
            $this->StaffAssignToStore->set('STORECODE', intval($storeinfo['storecode']));
            $this->StaffAssignToStore->set('ASSIGN',    intval($param['YOYAKU_DISPLAY']));
            $this->StaffAssignToStore->save();*/
            /*$sql = "REPLACE INTO staff_assign_to_store
                          (STAFFCODE, STORECODE, ASSIGN_YOYAKU, DISPLAY_ORDER)
                    VALUES(".$param['STAFFCODE'].", ".$storeinfo['storecode'].",
                           ".$param['YOYAKU_DISPLAY'].", IF('".$param['DISPLAY_ORDER']."' = '', null, '".$param['DISPLAY_ORDER']."'))";
            $this->StaffAssignToStore->query($sql);*/

            $this->wsUpdateFlagsStaff($sessionid, $param);
            return $param['STAFFCODE'];
        } else {
            $this->_soap_server->fault(1, '', 'Error Processing Data');
        }
    }

    /**
     * @uses Update Staff Type
     * @author Homer Pasamba Email: homer.pasamba@think-jp.com
     * @param <String> $sessionid
     * @param <Integer> $staffcode
     * @param <String> $stafftypecodes
     * @return boolean 
     */
    function wsUpdateStaffType($sessionid, $staffcode, $stafftypecodes){
        //=======================================================================
        // Verify Session and Get DB name
        //-----------------------------------------------------------------------
         $storeinfo = $this->YoyakuSession->Check($this);
        //-----------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }// End If
        //-----------------------------------------------------------------------
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        //=======================================================================
        // Mark delflg of staff
        //-----------------------------------------------------------------------
        $Sql_delflg = 'UPDATE stafftype
                       SET DELFLG = NOW()
                       WHERE STAFFCODE = '.$staffcode;
        //-----------------------------------------------------------------------
        $this->Staff->query($Sql_delflg);
        //=======================================================================
        // Check StaffType of Staff
        //-----------------------------------------------------------------------
        // BEAUTY , 1
        //-----------------------------------------------------------------------
        $val = "";
        if (strstr($stafftypecodes,'1') != ''){
            $val = "(".$staffcode.", 1),";
        } //End If
        //-----------------------------------------------------------------------
        // NAIL , 2
        //-----------------------------------------------------------------------
        if (strstr($stafftypecodes,'2') != ''){
            $val .= " (".$staffcode.", 2),";
        } //End If
        //-----------------------------------------------------------------------
        // Matsu , 3
        //-----------------------------------------------------------------------
        if (strstr($stafftypecodes,'3') != ''){
            $val .= " (".$staffcode.", 3),";
        }
        //-----------------------------------------------------------------------
        // ESTE , 4
        //-----------------------------------------------------------------------
        if (strstr($stafftypecodes,'4') != ''){
            $val .= " (".$staffcode.", 4),";
        } //End If
        //=======================================================================
        if ($val != '') {
            //-------------------------------------------------------------------
            $val = substr($val, 0, strlen($val)-1);
            //-------------------------------------------------------------------
            $Sql = "INSERT INTO stafftype (staffcode,syscode)
                VALUES ".$val.
                " ON DUPLICATE KEY UPDATE DELFLG = null";
            //-------------------------------------------------------------------
            $this->Staff->query($Sql);
            //-------------------------------------------------------------------
            return true;
            //-------------------------------------------------------------------
        }else {
            //-------------------------------------------------------------------
            return False;
            //-------------------------------------------------------------------
        } //End If
        //=======================================================================
    } // End Function
            

    /**
     * スタッフの削除機能
     * Deletes a staff
     *
     * @param string $sessionid
     * @param int $staffcode
     * @return boolean
     */
    function wsDeleteStaff($sessionid, $staffcode) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);

        //-- スタッフを削除フラグを設定 (Set Delete flag on Staff)
        $this->Staff->set('STAFFCODE', $staffcode);
        $this->Staff->set('DELFLG', date('Y-m-d h:i:s'));
        $this->Staff->save();

        return true;
    }


    /*
     * スタッフの表示業種区分をアップデートする機能
     * Updates the display flags for a Staff gyoshukubun( syscode view)
     * shimizu 20150318
     * @param string $sessionid
     * @param int $staffcode
     * @param string $stafftypecodes
     * @return boolean 
     */
    function wsUpdateStaffDisplayGyoshukubun($sessionid, $staffcode, $stafftypecodes) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
       //=======================================================================
        // Verify Session and Get DB name
        //-----------------------------------------------------------------------
         $storeinfo = $this->YoyakuSession->Check($this);
        //-----------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }// End If
        //-----------------------------------------------------------------------
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        //=======================================================================
        // Mark delflg of staff
        //-----------------------------------------------------------------------
        $Sql_delflg = 'UPDATE stafftype
                       SET YOYAKU_VIEW = 0
                       WHERE STAFFCODE = '.$staffcode;
        //-----------------------------------------------------------------------
        $this->Staff->query($Sql_delflg);
        //=======================================================================
        // Check StaffType of Staff
        //-----------------------------------------------------------------------
        // BEAUTY , 1
        //-----------------------------------------------------------------------
        $val = "";
        if (strstr($stafftypecodes,'1') != ''){
            $val = "(".$staffcode.", 1),";
        } //End If
        //-----------------------------------------------------------------------
        // NAIL , 2
        //-----------------------------------------------------------------------
        if (strstr($stafftypecodes,'2') != ''){
            $val .= " (".$staffcode.", 2),";
        } //End If
        //-----------------------------------------------------------------------
        // Matsu , 3
        //-----------------------------------------------------------------------
        if (strstr($stafftypecodes,'3') != ''){
            $val .= " (".$staffcode.", 3),";
        }
        //-----------------------------------------------------------------------
        // ESTE , 4
        //-----------------------------------------------------------------------
        if (strstr($stafftypecodes,'4') != ''){
            $val .= " (".$staffcode.", 4),";
        } //End If
        //=======================================================================
        if ($val != '') {
            //-------------------------------------------------------------------
            $val = substr($val, 0, strlen($val)-1);
            //-------------------------------------------------------------------
            $Sql = "INSERT INTO stafftype (staffcode,syscode)
                VALUES ".$val.
                " ON DUPLICATE KEY UPDATE YOYAKU_VIEW = 1";
            //-------------------------------------------------------------------
            $this->Staff->query($Sql);
            //-------------------------------------------------------------------
            return true;
            //-------------------------------------------------------------------
        }else {
            //-------------------------------------------------------------------
            return False;
            //-------------------------------------------------------------------
        } //End If
        //=======================================================================
    } // End Function
    
    
    /**
     * スタッフのフラグをアップデートする機能
     * Updates the display flags for a Staff
     *
     * @param string $sessionid
     * @param array $param
     * @return boolean
     */
    function wsUpdateFlagsStaff($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return false;
        }

        //-- 会社データベースを設定する (Set the Company Database)
      $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        $this->StaffAssignToStore->set_company_database($storeinfo['dbname'], $this->StaffAssignToStore);

        //-- スタッフのweb_displayフラグを設定 (Set web_display flag on Staff)
      /*  $this->Staff->set('STAFFCODE', $param['STAFFCODE']);
        $this->Staff->set('WEBYAN_DISPLAY', $param['WEB_DISPLAY']);
        $this->Staff->save($this->data);*/

       /* $sql = "REPLACE INTO staff_assign_to_store
                          (STAFFCODE, STORECODE,WEBYAN_DISPLAY, ASSIGN_YOYAKU, DISPLAY_ORDER)
                    VALUES(".$param['STAFFCODE'].", ".$storeinfo['storecode'].",
                           ".$param['WEB_DISPLAY'].",".$param['YOYAKU_DISPLAY'].", IF('".$param['DISPLAY_ORDER']."' = '', null, '".$param['DISPLAY_ORDER']."'))";
        */

        $sql = "INSERT INTO staff_assign_to_store (STORECODE, STAFFCODE, WEBYAN_DISPLAY, ASSIGN_YOYAKU, DISPLAY_ORDER)
                    VALUES(".$storeinfo['storecode'].",".$param['STAFFCODE'].",".
                             $param['WEB_DISPLAY'].",".$param['YOYAKU_DISPLAY'].", IF('".$param['DISPLAY_ORDER']."' = '', null, '".$param['DISPLAY_ORDER']."'))

                ON DUPLICATE KEY

                UPDATE WEBYAN_DISPLAY = ".$param['WEB_DISPLAY'].",
                       ASSIGN_YOYAKU = ".$param['YOYAKU_DISPLAY'].",
                       DISPLAY_ORDER = IF('".$param['DISPLAY_ORDER']."' = '', null, '".$param['DISPLAY_ORDER']."')";

        $this->StaffAssignToStore->query($sql);

        /*$this->StaffAssignToStore->query("REPLACE INTO staff_assign_to_store
                                          (STAFFCODE, STORECODE, ASSIGN ) VALUES
                                          (".$staffcode.",".$storeinfo['storecode'].",".intval($yoyaku_display).")");*/
        
        //=============================================================================================================================
        // Update yoyaku_staff_service_time of the Staff, Follow Sipss3 Mobaste Rule Dont Allow Staff from Other Store
        //-----------------------------------------------------------------------------------------------------------------------------
        If ((int)$param['YOYAKU_DISPLAY'] == 1) {
            //-------------------------------------------------------------------------------------------------------------------------
            $StaffServicesSql = "INSERT IGNORE INTO yoyaku_staff_service_time(storecode,
                                                                          staffcode,
                                                                          gcode,
                                                                          service_time,
                                                                          service_time_male)
                                SELECT STO.storecode,
                                    STA.staffcode,
                                    STO.gcode,
                                    STO.servicetime,
                                    STO.servicetime_male
                                FROM staff STA
                                    JOIN store_services STO
                                            ON STO.storecode  = ".$storeinfo['storecode']."
                                                    AND STO.delflg IS NULL
                                WHERE STA.delflg IS NULL
                                    AND STA.staffcode = ".$param['STAFFCODE'];
             //------------------------------------------------------------------------------------------------------------------------
             $this->StaffAssignToStore->query($StaffServicesSql);
             //------------------------------------------------------------------------------------------------------------------------
        } // End If
        //=============================================================================================================================

       return true;
    }
    //- #############################################################################




    // STAFF SHIFT FUNCTIONS --------------------------------------------------------
    /**
     * スタッフシフト検索機能
     * Performs staff shift search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_staffShiftInformation
     */
    function wsSearchStaffShift($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
        }

        //-- Checks if the shift for the month has been set already
        $this->FinishedShift->set_company_database($storeinfo['dbname'], $this->FinishedShift);
        $condition = array('FinishedShift.storecode' => $param['storecode'],
                           'FinishedShift.year'      => $param['year'],
                           'FinishedShift.month'     => $param['month'],
                           'FinishedShift.complete'  => 1);
        $finishedShift = $this->FinishedShift->find('all', array('conditions' => $condition));

        if (count($finishedShift) > 0) {
            $finished = 1;
        }

        for ($i=31; $i>=28; $i--) {
            if (checkdate($param['month'], $i, $param['year'])) {
                $this->enddate = $param['year'] . "-" . $param['month'] . "-" . $i;
                break;
            }
        }

        $this->StaffAssignToStore->set_company_database($storeinfo['dbname'], $this->StaffAssignToStore);
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        $this->Stafftype->set_company_database($storeinfo['dbname'], $this->Stafftype);
        $arrStaff = $this->MiscFunction->SearchStaffAssignToStore($this, $param);

        $this->Shift->set_company_database($storeinfo['dbname'], $this->Shift);
        $this->StaffShift->set_company_database($storeinfo['dbname'], $this->StaffShift);                

        $criteria = array('StaffShift.STORECODE'  => $param['storecode'],
                          'YEAR(StaffShift.YMD)'  => $param['year'],
                          'MONTH(StaffShift.YMD)' => $param['month'],
                          'StaffShift.DELFLG IS NULL',
                          'Shift.DELFLG IS NULL');

        if ($param['day'] <> 0) {
            $criteria['DAY(StaffShift.YMD)'] = $param['day'];
        }

        if ($param['STAFFCODE'] > -1) {
            $criteria['StaffShift.STAFFCODE'] = $param['STAFFCODE'];
        }

        $arrShift = $this->StaffShift->find('all', array('conditions' => $criteria));

        $arrDays = $this->arrDays;

        if (strlen($param['month']) == 1) {
            $month = "0" . $param['month'];
        } else {
            $month = $param['month'];
        }//end if ele
        //----------------------------------------------------------------------------------------------------
        //Get Staff Types Recordset Object
        //----------------------------------------------------------------------------------------------------
        $arr_stafftypes = null;
        $Sql = "SELECT STAFFCODE, 
                       CONVERT(group_concat(syscode SEPARATOR ',') USING UTF8) AS STAFFTYPES,
                       ifnull(GROUP_CONCAT(case when YOYAKU_VIEW = 1 then syscode end),'') AS STAFFVIEWS
                FROM stafftype
                WHERE delflg IS NULL 
                GROUP BY staffcode";
        $rs_stafftypes = null;
        $rs_stafftypes = $this->StaffShift->query($Sql);
        if (count($rs_stafftypes) > 0) {
            //------------------------------------------------------------------------------------------------
            $arr_stafftypes = array();
            //------------------------------------------------------------------------------------------------
            foreach ($rs_stafftypes AS $rec) {
                $arr_stafftypes[] = array("STAFFCODE" => $rec['stafftype']['STAFFCODE'],
                                          "STAFFTYPES" => $rec[0]['STAFFTYPES'],
                                          "STAFFVIEWS" => $rec[0]['STAFFVIEWS']
                    );
            }//end foreach
            //------------------------------------------------------------------------------------------------
        }//end if
        //----------------------------------------------------------------------------------------------------
        //- スタッフの配列をループ処理 (Loops through the array of Staff)
        for ($i = 0; $i < count($arrStaff); $i++) {
            $arrStaffShift[$i]['STORECODE']  = $arrStaff[$i]['StaffAssignToStore']['STORECODE'];
            $arrStaffShift[$i]['STAFFCODE']  = $arrStaff[$i]['StaffAssignToStore']['STAFFCODE'];
            $arrStaffShift[$i]['STAFFNAME']  = $arrStaff[$i]['StaffAssignToStore']['STAFFNAME'];
            $arrStaffShift[$i]['HIREDATE']   = $arrStaff[$i]['StaffAssignToStore']['HIREDATE'];
            $arrStaffShift[$i]['RETIREDATE'] = $arrStaff[$i]['StaffAssignToStore']['RETIREDATE'];
            //-----------------------------------------------------------------------------------
            $arrStaffShift[$i]['SALARYTYPE'] = $arrStaff[$i]['StaffAssignToStore']['SALARYTYPE'];
            $arrStaffShift[$i]['SALARYAMOUNT'] = $arrStaff[$i]['StaffAssignToStore']['SALARYAMOUNT'];
            $arrStaffShift[$i]['TRAVEL_ALLOWANCE'] = $arrStaff[$i]['StaffAssignToStore']['TRAVEL_ALLOWANCE'];
            //-----------------------------------------------------------------------------------
            $stafftypes = "";
            if (count($arr_stafftypes) > 0) {
                foreach ($arr_stafftypes AS $sdata) {
                    if ((int)$sdata['STAFFCODE'] === (int)$arrStaff[$i]['StaffAssignToStore']['STAFFCODE']) {
                        $stafftypes = $sdata['STAFFTYPES'];
                        $staffviews = $sdata['STAFFVIEWS'];
                        break;
                    }//end if
                }//end foreach
            }//end if
            $arrStaffShift[$i]['STAFFTYPES'] = $stafftypes;
            $arrStaffShift[$i]['STAFFVIEWS'] = $staffviews;
            //-----------------------------------------------------------------------------------
            $arrStaffShift[$i]['year']       = $param['year'];
            $arrStaffShift[$i]['month']      = $param['month'];

            //- 日間の配列をループ処理　(Loops through the array of Days)
            for ($j = 0; $j < count($arrDays); $j++) {
                $day = $j + 1;
                if (strlen($day) == 1) {
                    $zday = "0" . $day;
                } else {
                    $zday = $day;
                }
                $date = $param['year'] . "-" . $month . "-" . $zday;

                if (checkdate($param['month'], $day, $param['year'])) {
                    //- スタッフシフトの配列をループ処理　(Loops through the array of StaffShifts)
                    for ($k = 0; $k < count($arrShift); $k++) {

                        if ($arrStaff[$i]['StaffAssignToStore']['STAFFCODE'] == $arrShift[$k]['StaffShift']['STAFFCODE']
                                && $date == $arrShift[$k]['StaffShift']['YMD']) {
                            if ($arrShift[$k]['StaffShift']['SHIFT'] <> "") {
                                $val = $arrShift[$k]['StaffShift']['HOLIDAYTYPE'] . "-" .
                                       $arrShift[$k]['StaffShift']['SHIFT'];
                                if ($param['day'] <> 0) {
                                    $arrStaffShift[$i]['STARTTIME'] = substr($arrShift[$k]['Shift']['STARTTIME'],0,5);
                                    $arrStaffShift[$i]['ENDTIME']   = substr($arrShift[$k]['Shift']['ENDTIME'],0,5);
                                }
                            } else {
                                $val = $arrShift[$k]['StaffShift']['HOLIDAYTYPE'];
                            }
                            $arrStaffShift[$i][$arrDays[$j]] = $val;
                            break;
                        }
                    }
                }
            }
        }

        $ret = array();
        $ret['finished']     = $finished;
        $ret['records']      = $arrStaffShift;
        $ret['record_count'] = count($arrStaff);

        return $ret;
    }


    /**
     * スタッフシフトの追加と更新と削除機能
     * Adds or Updates or Deletes a staff　shift
     *
     * @param string $sessionid
     * @param array $param
     * @return boolean
     */
    function wsAddUpdateDeleteStaffShift($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        $this->StaffShift->set_company_database($storeinfo['dbname'], $this->StaffShift);

        $arrDays = $this->arrDays;

        for ($ctr=0; $ctr < count($param); $ctr++) {

            //- 日間の配列をループ処理　(Loops through the array of Days)
            for ($i = 0; $i < count($arrDays); $i++) {
                $day = $i + 1;
                $this->date = $param[$ctr]['year'] . "-" . $param[$ctr]['month'] . "-" . $day;
                //-----------------------------------------------------------------
                //Mark or Delete the existing data 
                //-----------------------------------------------------------------
                $SqlMark = "DELETE FROM staff_holiday 
                            WHERE STORECODE = " . $param[$ctr]['STORECODE'] . "
                                AND STAFFCODE = " . $param[$ctr]['STAFFCODE'] . "
                                AND YMD = '" . $this->date . "'";
                $this->StaffShift->query($SqlMark);
                //-----------------------------------------------------------------                   
                if (checkdate($param[$ctr]['month'], $day, $param[$ctr]['year']) &&
                    $param[$ctr][$arrDays[$i]] <> "") {
                     $hasData = $this->MiscFunction->CheckStaffShiftData($this, $param[$ctr]);
                     $sql = "";

                    if (substr($param[$ctr][$arrDays[$i]],2) == "") {
                        $shift_value = "NULL";
                    } else {
                        $shift_value = substr($param[$ctr][$arrDays[$i]],2);
                    }

                    if ($hasData == true) {
                        //準備UPDATE SQLステートメント (Prepare UPDATE Sql Statement)
                        $extra = "HOLIDAYTYPE = " . substr($param[$ctr][$arrDays[$i]],0,1) .
                                 ", SHIFT     = " . $shift_value;
                        $sql = "UPDATE staff_holiday SET " . $extra . "
                                    WHERE STORECODE = " . $param[$ctr]['STORECODE'] . "
                                        AND STAFFCODE = " . $param[$ctr]['STAFFCODE'] . "
                                        AND YMD = '" . $this->date . "'
                                        AND DELFLG IS NULL";
                    } elseif ($param[$ctr][$arrDays[$i]] <> "") {
                        //準備INSERT SQLステートメント (Prepare INSERT Sql Statement)
                        $fields = "STORECODE, STAFFCODE, YMD, HOLIDAYTYPE, SHIFT";
                        $values = $param[$ctr]['STORECODE'] . ", " .
                                  $param[$ctr]['STAFFCODE'] . ", '" .
                                  $this->date . "', " .
                                  substr($param[$ctr][$arrDays[$i]],0,1) . ", " .
                                  $shift_value;

                        $sql = "INSERT INTO staff_holiday (" . $fields . ")
                                    VALUES(" . $values . ")";
                    }

                    $this->StaffShift->query($sql);

                } elseif (checkdate($param[$ctr]['month'], $day, $param[$ctr]['year']) &&
                          $param[$ctr][$arrDays[$i]] == "") {
                    $sql = "DELETE FROM staff_holiday
                                WHERE STORECODE = " . $param[$ctr]['STORECODE'] . "
                                    AND STAFFCODE = " . $param[$ctr]['STAFFCODE'] . "
                                    AND YMD = '" . $this->date . "'";
                    $this->StaffShift->query($sql);
                }
            }
        }

        //-- Marks the month has already assigned its shift
        $fshift = "REPLACE INTO finished_shift (storecode, year, month, complete)
                          VALUES(".$param[0]['STORECODE'].", ".$param[0]['year'].
                          ", ".$param[0]['month'].",1)";
        $this->StaffShift->query($fshift);

        return true;
    }
    //- #############################################################################




    // STORE FUNCTIONS --------------------------------------------------------------
    /**
     * 店舗検索機能
     * Performs store search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_storeInformation
     */
    function wsSearchStore($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
            unset($param);
            $param['limit']      = -1;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);

        if (empty($param['orderby'])) {
            $param['orderby'] = $this->Store->primaryKey;
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        foreach ($param as $key => $val) {
            if (!empty($val) && $key != 'limit' && $key != 'page' && $key != 'orderby') {
                if ($key == "STORENAME") {
                    $criteria['(STORENAME LIKE ?)'] = array('%'.$val.'%');
                } else {
                    $criteria[$key] = $val;
                }
            }
        }

        //-- Commented out on 2010/2/19 by T.Springer, hides Honbu store --//
        //-- Its unclear why this condition was added                    --//
        //$criteria['(STORECODE <> ? )'] = array(0);

        $criteria['DELFLG'] = null;

        if ($param['limit'] <> -1) {
            $v = $this->Store->find('all', array('conditions' => $criteria,
                                                 'order'      => array($param['orderby']),
                                                 'limit'      => $param['limit'],
                                                 'page'       => $param['page']));
        } else {
            $v = $this->Store->find('all', array('conditions' => $criteria,
                                                 'order'      => array($param['orderby'])));
        }

        $ret = array();
        $ret['records']      = set::extract($v, '{n}.Store');
        $ret['record_count'] = $this->Store->find('count', array('conditions' => $criteria));

        return $ret;
    }


    /**
     * 店舗の追加と更新機能
     * Adds or Updates a store
     *
     * @param string $sessionid
     * @param array $param
     * @return STORECODE
     */
    function wsAddUpdateStore($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);

        //-- STORECODEは設定してない場合、新規STORECODEを作成 (Check STORECODE, create new if none)
        if (empty($param['STORECODE'])) {
            $querty_txt = "select ".
                          "f_get_sequence_key('storecode','', '') as storecode";
            $tmp_data = $this->Store->query($querty_txt);
            $param['STORECODE']     = $tmp_data[0][0]['storecode'];
        }

        //-- 店舗情報を準備する (prepare store information)
        foreach ($param as $key => $val) {
            $this->Store->set($key, $val);
        }

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->Store->database_set == true) {
            $this->Store->save(); // アップデートか追加を実行する (Update/Add Execute)
            return $param['STORECODE'];
        } else {
            $this->_soap_server->fault(1, '', 'Error Processing Data');
        }
    }


    /**
     * 店舗の削除機能
     * Deletes a store
     *
     * @param string $sessionid
     * @param int $storecode
     * @return boolean
     */
    function wsDeleteStore($sessionid, $storecode) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);

        //-- 店舗を削除フラグを設定 (Set Delete flag on Store)
        $this->Store->set('STORECODE', $storecode);
        $this->Store->set('DELFLG', date('Y-m-d h:i:s'));
        $this->Store->save();

        //-- WebYanアカウントを削除フラグを設定 (Set Delete flag on WebyanAccount)
        $sql = "UPDATE webyan_store_accounts SET delflg = 1
                WHERE companyid = " .$storeinfo['companyid']. "
                    AND storecode = " . $storecode;
        $this->WebyanAccount->query($sql, $cachequeries = false);

        return true;
    }
    //- #############################################################################




    // STORE HOLIDAY FUNCTIONS ------------------------------------------------------
    /**
     * 店舗休日検索機能
     * Performs store holiday search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_storeHolidayInformation
     */
    function wsSearchStoreHoliday($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
        }

        $this->StoreHoliday->set_company_database($storeinfo['dbname'], $this->StoreHoliday);

        $criteria = array('StoreHoliday.STORECODE'  => $param['storecode'],
                          'YEAR(StoreHoliday.YMD)'  => $param['year'],
                          'MONTH(StoreHoliday.YMD)' => $param['month'],
                          'StoreHoliday.DELFLG IS NULL');

        if ($param['day'] <> 0) {
            $criteria['DAY(StoreHoliday.YMD)'] = $param['day'];
        }

        $v = $this->StoreHoliday->find('all', array('conditions' => $criteria));

        $arrHoliday['STORECODE'] = $param['storecode'];
        $arrHoliday['year']      = $param['year'];
        $arrHoliday['month']     = $param['month'];

        $arrDays = $this->arrDays;

        if (strlen($param['month']) == 1) {
            $month = "0" . $param['month'];
        } else {
            $month = $param['month'];
        }

        //- 日間の配列をループ処理　(Loops through the array of Days)
        for ($j = 0; $j < count($arrDays); $j++) {
            $day = $j + 1;
            if (strlen($day) == 1) {
                $zday = "0" . $day;
            } else {
                $zday = $day;
            }
            $date = $param['year'] . "-" . $month . "-" . $zday;

            if (checkdate($param['month'], $day, $param['year'])) {
                //- スタッフシフトの配列をループ処理　(Loops through the array of StoreHolidays)
                for ($k = 0; $k < count($v); $k++) {

                    if ($date == $v[$k]['StoreHoliday']['YMD']) {
                        $val = $v[$k]['StoreHoliday']['REMARKS'];
                        if ($val == "") {
                            $val = 'whitespace';
                        }
                        $arrHoliday[$arrDays[$j]] = $val;
                        break;
                    }
                }
            }
        }

        $ret = array();
        $ret['records']      = $arrHoliday;
        $ret['record_count'] = count($v);

        return $ret;
    }


    /**
     * 店舗休日の追加と更新と削除機能
     * Adds or Updates or Deletes a store holiday
     *
     * @param string $sessionid
     * @param array $param
     * @return boolean
     */
    function wsAddUpdateDeleteStoreHoliday($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        $this->StoreHoliday->set_company_database($storeinfo['dbname'], $this->StoreHoliday);

        /*$condition = array('YEAR(StoreHoliday.YMD)'  => $param['year'],
                           'MONTH(StoreHoliday.YMD)' => $param['month'],
                           'StoreHoliday.STORECODE'  => $param['STORECODE']);
        $this->StoreHoliday->primaryKey = "STORECODE";
        $this->StoreHoliday->deleteAll($condition);*/

        //-- Delete old Store Holidays
        $del_sql = "DELETE FROM store_holiday
                    WHERE STORECODE  = " . $param['STORECODE'] . "
                          AND YEAR(YMD)  = " . $param['year'] . "
                          AND MONTH(YMD) = " . $param['month'];

        $this->StoreHoliday->query($del_sql);

        $arrDays = $this->arrDays;

        //- 日間の配列をループ処理　(Loops through the array of Days)
        for ($i = 0; $i < count($arrDays); $i++) {
            $day = $i + 1;
            $this->date = $param['year'] . "-" . $param['month'] . "-" . $day;

            if (checkdate($param['month'], $day, $param['year']) &&
                $param[$arrDays[$i]] <> "") {

                //'whitespace'削除　(Removed 'whitespace' string)
                $param[$arrDays[$i]] = ereg_replace('whitespace', '', $param[$arrDays[$i]]);
                $param[$arrDays[$i]] = addslashes($param[$arrDays[$i]]);

                //準備INSERT SQLステートメント (Prepare INSERT Sql Statement)
                $fields = "STORECODE, YMD, REMARKS";
                $values = $param['STORECODE'] . ", " .
                          "'" . $this->date . "', " .
                          "'" . $param[$arrDays[$i]] . "'";

                $sql = "INSERT INTO store_holiday (" . $fields . ")
                        VALUES(" . $values . ")";
                $this->StoreHoliday->query($sql);
            }
        }

        return true;
    }
    //- #############################################################################




    // SERVICE FUNCTIONS ------------------------------------------------------------
    /**
     * 技術大分類検索機能
     * Performs services search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_serviceInformation
     */
    function wsSearchService($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname']    = $param['dbname'];
            $storeinfo['storecode'] = $param['storecode'];
            $param['limit']         = -1;
        }
        
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Service->set_company_database($storeinfo['dbname'], $this->Service);

        $sUpdateIndex = $this->Service->find('all', array('fields'  => array('MAX(UPDATEDATE) as UPDATE_INDEX'),
                                                          'limit'   => 1));
        $serviceMaxIndex = $sUpdateIndex[0][0]['UPDATE_INDEX'];


        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreService->set_company_database($storeinfo['dbname'], $this->StoreService);

        $sSUpdateIndex = $this->StoreService->find('all', array('fields'  => array('MAX(UPDATEDATE) as UPDATE_INDEX'),
                                                                'limit'   => 1));
        $storeServiceMaxIndex = $sSUpdateIndex[0][0]['UPDATE_INDEX'];

        $ret['records']      = array();
        $ret['record_count'] = 0;
            
//        if ($param['maxServiceIndex'] < $serviceMaxIndex ||
//            $param['maxStoreServiceIndex'] < $storeServiceMaxIndex) {

            $fields = array('Service.GDCODE', 'Service.BUNRUINAME', 'Service.SYSCODE');

            if (!in_array($param['orderby'], $fields)) {
                $param['orderby'] = $this->Service->primaryKey;
            }

            if (intval($param['limit']) == 0) {
                $param['limit'] = DEFAULT_LIMIT;
            }

            if (intval($param['page']) == 0) {
                $param['page'] = DEFAULT_STARTPAGE;
            }

            /*$criteria = array("Service.DELFLG IS NULL ",
                              "StoreService.DELFLG IS NULL");*/
            $criteria = array("Service.DELFLG IS NULL");
            
            if(intval($param['syscode']) > 0 ){
                $criteria["Service.SYSCODE"] = $param['syscode'];
            }

            if ($param['STORECODE'] <> 0) {
                $criteria['StoreService.DELFLG'] = NULL;
                $criteria['StoreService.STORECODE'] = $param['STORECODE'];
            }

            if ($param['limit'] <> -1) {
                $v = $this->Service->find('all', array('conditions' => $criteria,
                                                       'fields'     => $fields,
                                                       'order'      => array($param['orderby']),
                                                       'group'      => array("Service.GDCODE"),
                                                       'limit'      => $param['limit'],
                                                       'page'       => $param['page']));
            } else {
                $v = $this->Service->find('all', array('conditions' => $criteria,
                                                       'fields'     => $fields,
                                                       'order'      => array($param['orderby']),
                                                       'group'      => array("Service.GDCODE")));
            }
            $param['ignoreSessionCheck'] = 1;
            $param['dbname'] = $storeinfo['dbname'];
            
            //==================================================================
            //Updated By: Homer Pasamba <homer.pasamba@think-ahead.jp>
            //Date:2013-04-17
            //Use $param['STORECODE'] instead of $storeinfo['storecode']
            //Since $param['STORECODE'] holds the other storecode
            //------------------------------------------------------------------
            $param['infoSTORECODE'] = $param['STORECODE'];
            //------------------------------------------------------------------
            //$param['infoSTORECODE'] = $storeinfo['storecode'];
            //==================================================================
           
            for ($i=0; $i<count($v); $i++) {
                $param['GDCODE'] = $v[$i]['Service']['GDCODE'];
                $service = array();
                $service = $this->wsSearchStoreService($sessionid, $param);
                $v[$i]['Service']['store_service'] = $service;
            }

            $ret = array();
            $ret['records']      = set::extract($v, '{n}.Service');
            $ret['record_count'] = $this->Service->find('count', array('conditions' => $criteria,
                                                                       'fields'     => 'DISTINCT Service.GDCODE'));
//        }

        $ret['maxServiceIndex']      = $serviceMaxIndex;
        $ret['maxStoreServiceIndex'] = $storeServiceMaxIndex;

        return $ret;
    }


    /**
     * 技術大分の追加と更新機能
     * Adds or Updates a service
     *
     * @param string $sessionid
     * @param array $param
     * @return GDCODE
     */
    function wsAddUpdateService($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Service->set_company_database($storeinfo['dbname'], $this->Service);

        //-- GDCODEは設定してない場合、新規GDCODEを作成 (Check GDCODE, create new if none)
        if (empty($param['GDCODE'])) {
            $querty_txt = "select ".
                          "f_get_sequence_key('gdcode','', '') as GDCODE";

            $tmp_data = $this->Service->query($querty_txt);
            $param['GDCODE'] = $tmp_data[0][0]['GDCODE'];
        }

        //-- 技術大分情報を準備する (prepare service information)
        foreach ($param as $key => $val) {
            $this->Service->set($key, $val);
        }

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->Service->database_set == true) {
            $this->Service->save(); // アップデートか追加を実行する (Update/Add Execute)
            return $param['GDCODE'];
        } else {
            $this->_soap_server->fault(1, '', 'Error Processing Data');
        }
    }


    /**
     * 技術大分の削除機能
     * Deletes a service
     *
     * @param string $sessionid
     * @param int $gdcode
     * @return boolean
     */
    function wsDeleteService($sessionid, $gdcode) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Service->set_company_database($storeinfo['dbname'], $this->Service);

        //-- 技術大分を削除フラグを設定 (Set Delete flag on Service)
        $this->Service->set('GDCODE', $gdcode);
        $this->Service->set('DELFLG', date('Y-m-d h:i:s'));
        $this->Service->save();

        return true;
    }
    //- #############################################################################




    // STORE SERVICE FUNCTIONS ------------------------------------------------------
    /**
     * 店舗技術大分類検索機能
     * Performs store service search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_storeServiceInformation
     */
    function wsSearchStoreService($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname']    = $param['dbname'];
            $storeinfo['storecode'] = $param['infoSTORECODE'];
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreService->set_company_database($storeinfo['dbname'], $this->StoreService);

        $fields = array('StoreService.GDCODE', 'StoreService.GSCODE',
                        'StoreService.GCODE', 'StoreService.MENUNAME',
                        'StoreService.SERVICETIME AS SERVICE_TIME',
                        'StoreService.SERVICETIME_MALE AS SERVICE_TIME_MALE',
                        'StoreService.PRICE', 'StoreService.POINTKASAN1',
                        'StoreService.POINTKASAN2', 'StoreService.POINTKASAN3',
                        'StoreService.ZTYPE AS ZEIKUBUN', 'StoreService.MEMBERPRICE',
                        'StoreService.YOYAKUMARK'
                        , 'StoreService.KEYCODE'
                        );
        
        if (!in_array($param['orderby'], $fields)) {
            $param['orderby'] = $this->StoreService->primaryKey;
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        $criteria = array("StoreService.STORECODE" => $storeinfo['storecode'],
                          "StoreService.GDCODE" => $param['GDCODE'],
                          "StoreService.DELFLG IS NULL");
        
        if ($param['hasHonbu'] <> 1) {
            //-----------------------------------------------------------------------------------------------
             if ($param['STAFF_TANTOU_STAFFCODE'] >= 0) {
                    //---------------------------------------------------------------------------------------
                    $Sql = "SELECT optionvaluei 
                            FROM store_settings 
                            WHERE storecode = ".$storeinfo['storecode']." 
                                AND optionname = 'YOYAKU_MENU_TANTOU'";
                    //---------------------------------------------------------------------------------------
                    $rs_yoyaku_menu_tantou_settings = $this->StoreService->query($Sql);
                    //---------------------------------------------------------------------------------------
                    $yoyaku_menu_tantou_settings_on = 0;
                    //---------------------------------------------------------------------------------------
                    if (count($rs_yoyaku_menu_tantou_settings) > 0) {
                        $yoyaku_menu_tantou_settings_on = (int)$rs_yoyaku_menu_tantou_settings[0]['[store_settings']['optionvaluei'];
                    }//end if
                    //---------------------------------------------------------------------------------------
                    if ($yoyaku_menu_tantou_settings_on == 1) {    
                        //---------------------------------------------------------------------------------------
                        //SET DATA TO yoyaku_staff_service_time table if not exists from store_services table
                        //---------------------------------------------------------------------------------------
                        $SqlSTime = "INSERT IGNORE INTO yoyaku_staff_service_time(storecode,
                                                                                staffcode,
                                                                                gcode,
                                                                                service_time,
                                                                                service_time_male)
                                    SELECT ".$storeinfo['storecode'].", 
                                            ST.staffcode, 
                                            STO.gcode,
                                            STO.servicetime,
                                            STO.servicetime_male
                                    FROM staff ST
                                            JOIN store_services STO
                                                    ON STO.storecode = ".$storeinfo['storecode']." 
                                                            AND STO.gdcode = ".$param['GDCODE']." 
                                                            AND STO.delflg IS NULL
                                    WHERE ST.delflg IS NULL
                                            AND ST.staffcode = ".$param['STAFF_TANTOU_STAFFCODE'];
                        $this->StoreService->query($SqlSTime);
                    }//end if 
                    //---------------------------------------------------------------------------------------
                    $sql = "SELECT
                                    GDCODE, GSCODE, GCODE, MENUNAME, INSTORE,
                                    WEB_DISPLAY, SERVICE_TIME, SERVICE_TIME_MALE,
                                    POINTKASAN1, POINTKASAN2, POINTKASAN3, ZEIKUBUN,
                                    PRICE, MEMBERPRICE, MAX(YOYAKUMARK) as YOYAKUMARK
                                    , KEYCODE
                            FROM (
                                SELECT st.GDCODE as GDCODE, 
                                       st.GSCODE as GSCODE, 
                                       st.GCODE as GCODE, 
                                       st.MENUNAME as MENUNAME, 
                                       1 as INSTORE,
                                       st.SHOWONCELLPHONE as WEB_DISPLAY, 
                                       IFNULL(tblservicetime.SERVICE_TIME, 0) as SERVICE_TIME,
                                       IFNULL(tblservicetime.SERVICE_TIME_MALE, 0) as SERVICE_TIME_MALE,
                                       st.POINTKASAN1 as POINTKASAN1, 
                                       st.POINTKASAN2 as POINTKASAN2, 
                                       st.POINTKASAN3 as POINTKASAN3, 
                                       st.ZTYPE AS ZEIKUBUN,
                                       st.PRICE as PRICE, 
                                       st.MEMBERPRICE as MEMBERPRICE, 
                                       st.YOYAKUMARK as YOYAKUMARK 
                                    , st.KEYCODE
                                FROM store_services st
                                    LEFT JOIN yoyaku_staff_service_time tblservicetime
                                        ON tblservicetime.gcode = st.gcode
                                            AND tblservicetime.storecode = ".$param['STAFF_TANTOU_STORECODE']."
                                            AND tblservicetime.staffcode = ".$param['STAFF_TANTOU_STAFFCODE']."    
                                WHERE st.GDCODE = " . $param['GDCODE'] . "
                                    AND st.STORECODE = " . $storeinfo['storecode'] . "
                                    AND st.DELFLG IS NULL
                            UNION ALL
                                SELECT GDCODE, GSCODE, NULL as GCODE, MENUNAME, 0 as INSTORE,
                                    0 as WEB_DISPLAY, SERVICETIME as SERVICE_TIME,
                                    SERVICETIME_MALE as SERVICE_TIME_MALE,
                                    POINTKASAN1, POINTKASAN2, POINTKASAN3, ZTYPE AS ZEIKUBUN,
                                    0 as PRICE, 0 as MEMBERPRICE, YOYAKUMARK
                                    , KEYCODE
                                FROM subservices
                                WHERE GDCODE = " . $param['GDCODE'] . "
                                    AND DELFLG IS NULL
                                ) as data
                            GROUP BY GSCODE ";
                    //---------------------------------------------------------------------------------------
             } else {           
                    //---------------------------------------------------------------------------------------
                     $sql = "SELECT
                                    GDCODE, GSCODE, GCODE, MENUNAME, INSTORE,
                                    WEB_DISPLAY, SERVICE_TIME, SERVICE_TIME_MALE,
                                    POINTKASAN1, POINTKASAN2, POINTKASAN3, ZEIKUBUN,
                                    PRICE, MEMBERPRICE, MAX(YOYAKUMARK) as YOYAKUMARK
                                    , KEYCODE
                            FROM (
                                SELECT GDCODE, GSCODE, GCODE, MENUNAME, 1 as INSTORE,
                                    SHOWONCELLPHONE as WEB_DISPLAY, SERVICETIME as SERVICE_TIME,
                                    SERVICETIME_MALE as SERVICE_TIME_MALE,
                                    POINTKASAN1, POINTKASAN2, POINTKASAN3, ZTYPE AS ZEIKUBUN,
                                    PRICE, MEMBERPRICE, YOYAKUMARK
                                    , KEYCODE
                                FROM store_services
                                WHERE GDCODE = " . $param['GDCODE'] . "
                                    AND STORECODE = " . $storeinfo['storecode'] . "
                                    AND DELFLG IS NULL
                            UNION ALL
                                SELECT GDCODE, GSCODE, NULL as GCODE, MENUNAME, 0 as INSTORE,
                                    0 as WEB_DISPLAY, SERVICETIME as SERVICE_TIME,
                                    SERVICETIME_MALE as SERVICE_TIME_MALE,
                                    POINTKASAN1, POINTKASAN2, POINTKASAN3, ZTYPE AS ZEIKUBUN,
                                    0 as PRICE, 0 as MEMBERPRICE, YOYAKUMARK
                                    , KEYCODE
                                FROM subservices
                                WHERE GDCODE = " . $param['GDCODE'] . "
                                    AND DELFLG IS NULL
                                ) as data
                            GROUP BY GSCODE ";
                     //--------------------------------------------------------------------------------------
             }//end if else
        } else {
            
             if ($param['STAFF_TANTOU_STAFFCODE'] >= 0) {
                //---------------------------------------------------------------------------------------
                //SET DATA TO yoyaku_staff_service_time table if not exists from store_services table
                //---------------------------------------------------------------------------------------
                $SqlSTime = "INSERT IGNORE INTO yoyaku_staff_service_time(storecode,
                                                                          staffcode,
                                                                          gcode,
                                                                          service_time,
                                                                          service_time_male)
                             SELECT ".$storeinfo['storecode'].", 
                                    ST.staffcode, 
                                    STO.gcode,
                                    STO.servicetime,
                                    STO.servicetime_male
                             FROM staff ST
                                JOIN store_services STO
                                        ON STO.storecode = ".$storeinfo['storecode']." 
                                                AND STO.gdcode = ".$param['GDCODE']." 
                                                AND STO.delflg IS NULL
                             WHERE ST.delflg IS NULL
                                AND ST.staffcode = ".$param['STAFF_TANTOU_STAFFCODE'];
                $this->StoreService->query($SqlSTime);
                //---------------------------------------------------------------------------------------
                $sql = "SELECT *
                        FROM (
                            SELECT store_services.GDCODE, 
                                   store_services.GSCODE, 
                                   store_services.GCODE, 
                                   store_services.MENUNAME, 
                                   1 as INSTORE,
                                   store_services.SHOWONCELLPHONE as WEB_DISPLAY, 
                                   IFNULL(tblservicetime.service_time, 15) as SERVICE_TIME,
                                   IFNULL(tblservicetime.service_time_male, 15) as SERVICE_TIME_MALE,
                                   store_services.POINTKASAN1, 
                                   store_services.POINTKASAN2, 
                                   store_services.POINTKASAN3, 
                                   store_services.ZTYPE AS ZEIKUBUN,
                                   store_services.PRICE, 
                                   store_services.MEMBERPRICE, 
                                   store_services.YOYAKUMARK
                                , store_services.KEYCODE
                            FROM store_services
                                LEFT JOIN yoyaku_staff_service_time tblservicetime
                                    ON tblservicetime.storecode = ".$param['STAFF_TANTOU_STORECODE']." 
                                        AND tblservicetime.staffcode = ".$param['STAFF_TANTOU_STAFFCODE']." 
                                        AND tblservicetime.gcode = store_services.gcode     
                            WHERE store_services.GDCODE = " . $param['GDCODE'] . "
                                AND store_services.STORECODE = " . $storeinfo['storecode'] . "
                                AND store_services.DELFLG IS NULL
                            ) as data ";
                 //------------------------------------------------------------------------------   
             } else {
                    $sql = "SELECT *
                            FROM (
                                SELECT GDCODE, GSCODE, GCODE, MENUNAME, 1 as INSTORE,
                                    SHOWONCELLPHONE as WEB_DISPLAY, SERVICETIME as SERVICE_TIME,
                                    SERVICETIME_MALE as SERVICE_TIME_MALE,
                                    POINTKASAN1, POINTKASAN2, POINTKASAN3, ZTYPE AS ZEIKUBUN,
                                    PRICE, MEMBERPRICE, YOYAKUMARK
                                    , KEYCODE
                                FROM store_services
                                WHERE GDCODE = " . $param['GDCODE'] . "
                                    AND STORECODE = " . $storeinfo['storecode'] . "
                                    AND DELFLG IS NULL
                                ) as data ";
             }//end if ($param['STAFF_TANTOU_STAFFCODE'] >= 0)       
        }

        $offset = $param['limit'] * ($param['page'] - 1);
        $limit_offset = "LIMIT " . $param['limit'] . "
                         OFFSET " . $offset;

        if ($param['limit'] <> -1) {
            if ($param['STORECODE'] <> 0) {
                $v = $this->StoreService->find('all', array('conditions' => $criteria,
                                                            'fields'     => $fields,
                                                            'order'      => array($param['orderby']),
                                                            'limit'      => $param['limit'],
                                                            'page'       => $param['page']));
            } else {
                $sql_limit_offset = $sql . $limit_offset;
                $v = $this->StoreService->query($sql_limit_offset);

                if ($param['hasHonbu'] <> 1) {
                    for($i=0; $i<count($v); $i++) {
                        $v[$i]['data']['YOYAKUMARK'] = $v[$i][0]['YOYAKUMARK'];
                    }
                }
            }
        } else {
            if ($param['STORECODE'] <> 0) {
                $v = $this->StoreService->find('all', array('conditions' => $criteria,
                                                            'fields'     => $fields,
                                                            'order'      => array($param['orderby'])));
                
            } else {
                $v = $this->StoreService->query($sql);
            }
        }

        $ret = array();
        if ($param['STORECODE'] <> 0) {
            $ret['records'] = set::extract($v, '{n}.StoreService');
            $ret['record_count'] = $this->StoreService->find('count', array('conditions' => $criteria));
        } else {
            $ret['records'] = set::extract($v, '{n}.data');
            $c = $this->StoreService->query('SELECT count(*) as ctr FROM (' . $sql . ') as tmp');
            $ret['record_count'] = $c[0][0]['ctr'];
        }
        return $ret;
    }


    /**
     * 使用技術で店舗検索実行する
     * Performs store search based on services used
     *
     * @param string $sessionid
     * @param array $param
     * @return return_storeInformation
     */
    function wsSearchStoreServiceWhosUsing($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreService->set_company_database($storeinfo['dbname'], $this->StoreService);
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);

        $fields = array('StoreService.STORECODE', 'Store.STORENAME');

        if (!in_array($param['orderby'], $fields)) {
            $param['orderby'] = $this->StoreService->primaryKey;
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        $criteria = array("StoreService.GSCODE" => $param['GSCODE'],
                          "StoreService.DELFLG IS NULL");

        if ($param['limit'] <> -1) {
            $v = $this->StoreService->find('all', array('conditions' => $criteria,
                                                        'fields'     => $fields,
                                                        'order'      => array($param['orderby']),
                                                        'group'      => 'StoreService.STORECODE',
                                                        'limit'      => $param['limit'],
                                                        'page'       => $param['page']));
        } else {
            $v = $this->StoreService->find('all', array('conditions' => $criteria,
                                                        'fields'     => $fields,
                                                        'order'      => array($param['orderby']),
                                                        'group'      => 'StoreService.STORECODE'));
        }

        for ($i = 0; $i < count($v); $i++) {
            $v[$i]['StoreService']['STORENAME'] = $v[$i]['Store']['STORENAME'];
        }

        $ret = array();
        $ret['records'] = set::extract($v, '{n}.StoreService');
        $ret['record_count'] = $this->StoreService->find('count', array('conditions' => $criteria));

        return $ret;
    }


    /**
     * 店舗技術大分の追加と更新と削除機能
     * Adds or Updates or Deletes a store service
     *
     * @param string $sessionid
     * @param array $param
     * @return GCODE
     */
    function wsAddUpdateDeleteStoreService($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreService->set_company_database($storeinfo['dbname'], $this->StoreService);

        //-- GCODEは設定してない場合、新規GCODEを作成 (Check GCODE, create new if none)
        if (empty($param['GCODE'])) {
            $querty_txt = "select ".
                          "f_get_sequence_key('gcode','', '') as GCODE,".
                          "f_get_sequence_key('gscode','', '') as GSCODE";

            $tmp_data = $this->StoreService->query($querty_txt);
            $param['GCODE'] = $tmp_data[0][0]['GCODE'];

            if (empty($param['GSCODE'])) {
                $param['GSCODE'] = $tmp_data[0][0]['GSCODE'];
            }
        }

        if (empty($param['GSCODE'])) {
            $this->SubService->set_company_database($storeinfo['dbname'], $this->SubService);
            $querty_txt = "select ".
                          "f_get_sequence_key('gscode','', '') as GSCODE";
            $tmp_data = $this->SubService->query($querty_txt);
            $param['GSCODE'] = $tmp_data[0][0]['GSCODE'];

            $subServiceParam['GSCODE']   = $tmp_data[0][0]['GSCODE'];
            $subServiceParam['GDCODE']   = $tmp_data[0][0]['GDCODE'];
            $subServiceParam['MENUNAME'] = $tmp_data[0][0]['MENUNAME'];

            $this->MiscFunction->AddUpdateSubService($this, $subServiceParam);
        }

        if ($param['delete'] == 1) {
            $del_temp = true;
        }
        $hasHonbu = $param['hasHonbu'];
        unset($param['delete'], $param['hasHonbu']);

        //-- 店舗技術大分情報を準備する (prepare store service information)
        foreach ($param as $key => $val) {
            if ($key == 'SERVICE_TIME') {
                if ($param['STAFF_TANTOU_STAFFCODE'] < 0) $this->StoreService->set('SERVICETIME', $val);
            } elseif ($key == 'SERVICE_TIME_MALE') {
                if ($param['STAFF_TANTOU_STAFFCODE'] < 0) $this->StoreService->set('SERVICETIME_MALE', $val);
            } elseif ($key == 'WEB_DISPLAY') {
                $this->StoreService->set('SHOWONCELLPHONE', $val);
            } elseif ($key == 'INSTORE' && $val == 0) {
                $this->StoreService->set('DELFLG', date('Y-m-d h:i:s'));
            } else {
                $this->StoreService->set($key, $val);
            }
        }

        $this->StoreService->set('STORECODE', $storeinfo['storecode']);

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->StoreService->database_set == true) {
            $this->StoreService->save(); // アップデートか追加を実行する (Update/Add Execute)

            $subparam['GSCODE']     = $param['GSCODE'];
            $subparam['GDCODE']     = $param['GDCODE'];
            $subparam['MENUNAME']   = $param['MENUNAME'];
            $subparam['YOYAKUMARK'] = $param['YOYAKUMARK'];

            $inUse = $this->MiscFunction->CheckStoreServiceInUse($this, $param['GSCODE']);
            if ($inUse == false && $del_temp == true) {
                $subparam['DELFLG'] = date('Y-m-d h:i:s');
            }

            if ($hasHonbu <> 1) {
                $this->MiscFunction->AddUpdateSubService($this, $subparam);
                $this->MiscFunction->AddUpdateOtherStoreService($this, $param);
            }

            if ( isset($subparam['DELFLG'])) {
                // 削除されているテンプレート(Template has been removed)
                $ret['GCODE']  = -1;
                $ret['GSCODE'] = -1;
            } else {
                $ret['GCODE']  = $param['GCODE'];
                $ret['GSCODE'] = $param['GSCODE'];
            }
            return $ret;
        } else {
            $this->_soap_server->fault(1, '', 'Error Processing Data');
        }
    }
    //- #############################################################################




    // SHIFT FUNCTIONS --------------------------------------------------------------
    /**
     * シフト検索機能
     * Performs shift search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_serviceInformation
     */
    function wsSearchShift($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Shift->set_company_database($storeinfo['dbname'], $this->Shift);

        $fields = array('SHIFTID', 'SHIFTNAME', 'STARTTIME', 'ENDTIME');

        if (!in_array($param['orderby'], $fields)) {
            $param['orderby'] = 'SHIFTNAME';
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        $criteria = array('STORECODE' => $storeinfo['storecode'],
                          'DELFLG IS NULL');

        if ($param['limit'] <> -1) {
            $v = $this->Shift->find('all', array('conditions' => $criteria,
                                                 'fields'     => $fields,
                                                 'order'      => array($param['orderby']),
                                                 'limit'      => $param['limit'],
                                                 'page'       => $param['page']));
        } else {
            $v = $this->Shift->find('all', array('conditions' => $criteria,
                                                 'fields'     => $fields,
                                                 'order'      => array($param['orderby']) ));
        }

        $v_count = count($v);
        for ($i = 0 ; $i < $v_count; $i++) {
            $v[$i]['Shift']['STARTTIME'] = substr($v[$i]['Shift']['STARTTIME'],0,5);
            $v[$i]['Shift']['ENDTIME']   = substr($v[$i]['Shift']['ENDTIME'],0,5);
        }

        $ret = array();
        $ret['records']      = set::extract($v, '{n}.Shift');
        $ret['record_count'] = $this->Shift->find('count', array('conditions' => $criteria,
                                                                 'fields'     => 'DISTINCT SHIFTID'));

        return $ret;
    }


    /**
     * シフトの追加と更新機能
     * Adds or Updates a shift
     *
     * @param string $sessionid
     * @param array $param
     * @return SHIFTID
     */
    function wsAddUpdateShift($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 「出勤」のシフトを書き直さないようなチェック (Prevent editing of 出勤 shift)
        if ($param['SHIFTID'] == 1) {
            return 0;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Shift->set_company_database($storeinfo['dbname'], $this->Shift);

        //-- SHIFTIDは設定してない場合、新規SHIFTIDを作成 (Check SHIFTID, create new if none)
        if (empty($param['SHIFTID'])) {
            $querty_txt = "select ".
                          "f_get_sequence_key('shiftid','', '') as SHIFTID";

            $tmp_data = $this->Shift->query($querty_txt);
            $param['SHIFTID'] = $tmp_data[0][0]['SHIFTID'];
        }

        //-- 技術大分情報を準備する (prepare service information)
        foreach ($param as $key => $val) {
            $this->Shift->set($key, $val);
        }
        $this->Shift->set('STORECODE', $storeinfo['storecode']);

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->Shift->database_set == true) {
            $this->Shift->save(); // アップデートか追加を実行する (Update/Add Execute)
            return $param['SHIFTID'];
        } else {
            $this->_soap_server->fault(1, '', 'Error Processing Data');
        }
    }


    /**
     * シフトの削除機能
     * Deletes a shift
     *
     * @param string $sessionid
     * @param int $shiftid
     * @return boolean
     */
    function wsDeleteShift($sessionid, $shiftid) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return false;
        }

        //-- 「出勤」を削除できないように (Prevent Deletion of 出勤 shift)
        if ( $shiftid == 1) {
            return false;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Shift->set_company_database($storeinfo['dbname'], $this->Shift);

        //-- 技術大分を削除フラグを設定 (Set Delete flag on Shift)
        $this->Shift->set('SHIFTID', $shiftid);
        $this->Shift->set('DELFLG', date('Y-m-d h:i:s'));
        $this->Shift->save();

        return true;
    }
    //- #############################################################################




    // POSITION FUNCTIONS -----------------------------------------------------------
    /**
     * 位置検索機能
     * Performs position search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_positionInformation
     */
    function wsSearchPosition($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
            unset($param);
            $param['limit']      = -1;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Position->set_company_database($storeinfo['dbname'], $this->Position);

        if (empty($param['orderby'])) {
            $param['orderby'] = $this->Position->primaryKey;
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        foreach ($param as $key => $val) {
            if (!empty($val) && $key != 'limit' && $key != 'page' && $key != 'orderby') {
                $criteria[$key] = $val;
            }
        }

        $criteria['DELFLG'] = null;

        if ($param['limit'] <> -1) {
            $v = $this->Position->find('all', array('conditions' => $criteria,
                                                    'order'      => array($param['orderby']),
                                                    'limit'      => $param['limit'],
                                                    'page'       => $param['page']));
        } else {
            $v = $this->Position->find('all', array('conditions' => $criteria,
                                                    'order'      => array($param['orderby'])));
        }

        $ret = array();
        $ret['records']      = set::extract($v, '{n}.Position');
        $ret['record_count'] = $this->Position->find('count', array('conditions' => $criteria));

        return $ret;
    }
    //- #############################################################################




    // SUBLEVEL FUNCTIONS -----------------------------------------------------------
    /**
     * サブレベル検索機能
     * Performs sublevel search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_sublevelInformation
     */
    function wsSearchSublevel($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
            unset($param);
            $param['limit']      = -1;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Sublevel->set_company_database($storeinfo['dbname'], $this->Sublevel);

        if (empty($param['orderby'])) {
            $param['orderby'] = $this->Sublevel->primaryKey;
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        foreach ($param as $key => $val) {
            if (!empty($val) && $key != 'limit' && $key != 'page' && $key != 'orderby') {
                $criteria[$key] = $val;
            }
        }

        $criteria['DELFLG'] = null;

        if ($param['limit'] <> -1) {
            $v = $this->Sublevel->find('all', array('conditions' => $criteria,
                                                    'order'      => array($param['orderby']),
                                                    'limit'      => $param['limit'],
                                                    'page'       => $param['page']));
        } else {
            $v = $this->Sublevel->find('all', array('conditions' => $criteria,
                                                    'order'      => array($param['orderby'])));
        }

        $ret = array();
        $ret['records']      = set::extract($v, '{n}.Sublevel');
        $ret['record_count'] = $this->Sublevel->find('count', array('conditions' => $criteria));

        return $ret;
    }
    //- #############################################################################




    // ZIPCODE FUNCTIONS ------------------------------------------------------------
    /**
     * 郵便番号類検索機能
     * Performs zip code search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_zipcodeInformation
     */
    function wsSearchZipcode($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        if (empty($param['orderby'])) {
            $param['orderby'] = 'ZIPCODE';  //$this->Zipcode->primaryKey;
        }

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        $fields = array('Zipcode.*',
                        'concat(Zipcode.SI, Zipcode.TYO) AS SITYO');

        foreach ($param as $key => $val) {
            if (!empty($val) && $key != 'limit' && $key != 'page' && $key != 'orderby') {
                if ($key == "ADDRESS") {
                    $criteria['(ALLNAME LIKE ? OR concat(KEN, SI, TYO) LIKE ?)'] = array('%'.$val.'%','%'.$val.'%');
                } else {
                    $criteria[$key] = $val;
                }
            }
        }

        $criteria['DELFLG'] = null;

        if ($param['limit'] <> -1) {
            $v = $this->Zipcode->find('all',array('conditions' => $criteria,
                                                  'fields'     => $fields,
                                                  'order'      => array($param['orderby']),
                                                  'limit'      => $param['limit'],
                                                  'page'       => $param['page']));
        } else {
            $v = $this->Zipcode->find('all',array('conditions' => $criteria,
                                                  'fields'     => $fields,
                                                  'order'      => array($param['orderby'])));
        }

        $ret = array();
        $ret['records'] = set::extract($v, '{n}.Zipcode');
        foreach ($ret['records'] as &$item) {
            $item['SITYO'] = $item['SI'] . $item['TYO'];
        }
        $ret['record_count'] = $this->Zipcode->find('count', array('conditions' => $criteria));

        return $ret;
    }
    //- #############################################################################




    // BASIC SETTINGS FUNCTIONS -----------------------------------------------------
    /**
     * 基本情報設定を読み込む機能
     * Function to Read the Basic Settings
     *
     * @param string $sessionid
     * @return basicInformation
     */
    function wsReadBasicSettings($sessionid) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);
        $this->StoreSettings->set_company_database($storeinfo['dbname'], $this->StoreSettings);

        $criteria   = array('STORECODE' => $storeinfo['storecode']);
        $fields     = array('STORENAME','TEL','FAX','ADDRESS1','WEBYAN_HOMEPAGE','PC_HOMEPAGE','HIDECUSTOMERINFO_FLG');

        $v = $this->Store->find('all',array('conditions' => $criteria,
                                              'fields'     => $fields));

        $settings_one = set::extract($v, '{n}.Store');

        $tmp = "(OPTIONNAME = 'YoyakuTime' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuReminderLimit' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuCancelLimit' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuCustomersLimit' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuApptLimit' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuLimitOption' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuUpperLimit' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuUpperLimitOption' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuCellTimeDisplay' OR ";
        $tmp .= "OPTIONNAME = 'OpenTime' OR ";
        $tmp .= "OPTIONNAME = 'CloseTime' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuStart' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuEnd' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuHyouStart' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuHyouEnd' OR ";
        //-----------------------------------------------------
        // added yoyaku time display for saturdan and sunday
        //-----------------------------------------------------
        $tmp .= "OPTIONNAME = 'YoyakuStart_satsun' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuEnd_satsun' OR ";
        //-----------------------------------------------------
        $tmp .= "OPTIONNAME = 'YoyakuShowMenuNameOnly' OR ";
        
        //-----------------------------------------------------
        // added YoyakuCustomersLimit AutoSetting
        //-----------------------------------------------------
        $tmp .= "OPTIONNAME = 'YoyakuCustomersLimitAuto' OR ";
        //-----------------------------------------------------

        $tmp .= "OPTIONNAME = 'YOYAKU_MSG' OR ";
        $tmp .= "OPTIONNAME = 'YOYAKU_MENU_TANTOU' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuNoticeSetting' OR ";
        //-----------------------------------------------------
        // added YoyakuNoticeSetting hour or day option
        //-----------------------------------------------------
        $tmp .= "OPTIONNAME = 'YoyakuSettingsOption' OR ";    
        //-----------------------------------------------------
        
        $tmp .= "OPTIONNAME = 'YoyakuNoticeSecondSetting' OR ";
        $tmp .= "OPTIONNAME = 'FOLLOW_MAIL_SETTING' OR ";
        $tmp .= "OPTIONNAME = 'HIDE_HOLIDAY_STAFF' OR ";
        $tmp .= "OPTIONNAME = 'RECORD_YOYAKU_DETAIL' OR ";
        $tmp .= "OPTIONNAME = 'MODIFYING_MAIL' OR ";
        $tmp .= "OPTIONNAME = 'YOYAKU_DRAGDROP_TIMEINTERVAL' OR ";
        $tmp .= "OPTIONNAME = 'HIDE_RAITEN' OR ";
        $tmp .= "OPTIONNAME = 'OKOTOWARI_TIME')";
        
        $criteria[] = $tmp;

        $vv = $this->StoreSettings->find('all', array('conditions' => $criteria));

        $settings_two = array();
        foreach ($vv as $itm) {
            switch ($itm['StoreSettings']['OPTIONNAME']) {
                case 'YoyakuTime':
                    $settings_two['INTERVAL'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuReminderLimit':
                    $settings_two['REMINDER'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuCancelLimit':
                    $settings_two['CANCEL_LIMIT'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuCustomersLimit':
                    $settings_two['CUSTOMER_LIMIT'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuApptLimit':
                    $settings_two['LOWER_LIMIT'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuLimitOption':
                    $settings_two['LOWER_LIMIT_OP'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuUpperLimit':
                    $settings_two['UPPER_LIMIT'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuUpperLimitOption':
                    $settings_two['UPPER_LIMIT_OP'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuCellTimeDisplay':
                    $settings_two['AVAILABLE_TIMES'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'OpenTime':
                    $settings_two['OPEN_TIME'] = intval($itm['StoreSettings']['OPTIONVALUES']);
                    break;
                case 'CloseTime':
                    $settings_two['CLOSE_TIME'] = intval($itm['StoreSettings']['OPTIONVALUES']);
                    break;
                case 'YoyakuStart':
                    $settings_two['YOYAKU_OPEN_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuEnd':
                    $settings_two['YOYAKU_CLOSE_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuHyouStart':
                    $settings_two['YOYAKU_HYOU_OPEN_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuHyouEnd':
                    $settings_two['YOYAKU_HYOU_CLOSE_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                //-----------------------------------------------------
                // added yoyaku time for saturdan and sunday
                //-----------------------------------------------------
                case 'YoyakuStart_satsun':
                    $settings_two['YOYAKU_OPEN_TIME_SATSUN'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuEnd_satsun':
                    $settings_two['YOYAKU_CLOSE_TIME_SATSUN'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                //-----------------------------------------------------
                case 'YoyakuNoticeSetting':
                    $settings_two['YOYAKU_TIME_SETTING'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuSettingsOption':
                    $settings_two['YOYAKU_TIME_SETTINGS_OP'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuNoticeSecondSetting':
                    $settings_two['YOYAKU_TIME_SECOND_SETTING'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'FOLLOW_MAIL_SETTING':
                    $settings_two['FOLLOW_MAIL_SETTING'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YOYAKU_MSG':
                    $settings_two['YOYAKU_MSG'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YOYAKU_MENU_TANTOU':
                    $settings_two['YOYAKU_MENU_TANTOU'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YoyakuShowMenuNameOnly':
                    $settings_two['SHOW_MENU_NAME_ONLY'] = intval($itm['StoreSettings']['OPTIONVALUES']);
                    break;
                //-----------------------------------------------------
                //customer limit auto
                //-----------------------------------------------------
                case 'YoyakuCustomersLimitAuto':
                    $settings_two['AUTO_CUST_LIMIT'] = intval($itm['StoreSettings']['OPTIONVALUES']);
                    break;
                //-----------------------------------------------------
                case 'HIDE_HOLIDAY_STAFF':
                    $settings_two['HIDE_HOLIDAY_STAFF'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'RECORD_YOYAKU_DETAIL':
                    $settings_two['RECORD_YOYAKU_DETAIL'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'MODIFYING_MAIL':
                    $settings_two['MODIFYING_MAIL'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'YOYAKU_DRAGDROP_TIMEINTERVAL':
                    $settings_two['YOYAKU_DRAGDROP_TIMEINTERVAL'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'HIDE_RAITEN':
                    $settings_two['HIDE_RAITEN'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
                case 'OKOTOWARI_TIME':
                    $settings_two['OKOTOWARI_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                    break;
            }

        }
                
        if ($settings_two['YOYAKU_HYOU_OPEN_TIME'] == "") {
            $settings_two['YOYAKU_HYOU_OPEN_TIME'] = $settings_two['OPEN_TIME'];
            $settings_two['YOYAKU_HYOU_CLOSE_TIME'] = $settings_two['CLOSE_TIME'];
        }
        
        //---------------------------------------------------------------------------
        // check if yoyaku time for saturday and sunday is null
        //---------------------------------------------------------------------------
        if ($settings_two['YOYAKU_OPEN_TIME_SATSUN'] == "") {
            $settings_two['YOYAKU_OPEN_TIME_SATSUN'] = $settings_two['YOYAKU_OPEN_TIME'];
        }//end if
        if ($settings_two['YOYAKU_CLOSE_TIME_SATSUN'] == "") {
            $settings_two['YOYAKU_CLOSE_TIME_SATSUN'] = $settings_two['YOYAKU_CLOSE_TIME'];
        }//end if
        //---------------------------------------------------------------------------
        
        $arrReturn = array_merge($settings_one[0], $settings_two);
        return $arrReturn;
    }


    /**
     * 基本情報設定を書き込む機能
     * Function to Write the Basic Settings
     *
     * @param string $sessionid
     * @param basicInformation $param
     * @return boolean
     */
    function wsWriteBasicSettings($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);
        $this->StoreSettings->set_company_database($storeinfo['dbname'], $this->StoreSettings);

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->Store->database_set == true && $this->StoreSettings->database_set == true) {
            $this->Store->set('STORECODE',          $storeinfo['storecode']);
            $this->Store->set('STORENAME',          $param['STORENAME']);
            $this->Store->set('TEL',                $param['TEL']);
            $this->Store->set('FAX',                $param['FAX']);
            $this->Store->set('ADDRESS1',           $param['ADDRESS1']);
            $this->Store->set('WEBYAN_HOMEPAGE',    $param['WEBYAN_HOMEPAGE']);
            $this->Store->set('PC_HOMEPAGE',        $param['PC_HOMEPAGE']);
            $this->Store->save();

            $option_int = "REPLACE INTO store_settings (STORECODE, OPTIONNAME, OPTIONVALUEI) VALUES (".$storeinfo['storecode'].",";
            $option_str = "REPLACE INTO store_settings (STORECODE, OPTIONNAME, OPTIONVALUES) VALUES (".$storeinfo['storecode'].",";

            if ( $param['LOWER_LIMIT_OP'] != "days") {
                $param['LOWER_LIMIT_OP'] = "hours";
            }

            if ( $param['UPPER_LIMIT_OP'] != "months") {
                $param['UPPER_LIMIT_OP'] = "days";
            }

            $this->StoreSettings->query($option_int."'YoyakuTime','".intval($param['INTERVAL'])."')");
            $this->StoreSettings->query($option_int."'YoyakuReminderLimit','".intval($param['REMINDER'])."')");
            $this->StoreSettings->query($option_int."'YoyakuCancelLimit','".intval($param['CANCEL_LIMIT'])."')");
            $this->StoreSettings->query($option_str."'YoyakuCustomersLimit','".$param['CUSTOMER_LIMIT']."')");
            $this->StoreSettings->query($option_int."'YoyakuApptLimit','".intval($param['LOWER_LIMIT'])."')");
            $this->StoreSettings->query($option_str."'YoyakuLimitOption','".$param['LOWER_LIMIT_OP']."')");
            $this->StoreSettings->query($option_int."'YoyakuUpperLimit','".intval($param['UPPER_LIMIT'])."')");
            $this->StoreSettings->query($option_str."'YoyakuUpperLimitOption','".$param['UPPER_LIMIT_OP']."')");
            $this->StoreSettings->query($option_int."'YoyakuCellTimeDisplay','".intval($param['AVAILABLE_TIMES'])."')");
            $this->StoreSettings->query($option_str."'OpenTime','".sprintf("%04d",intval($param['OPEN_TIME']))."')");
            $this->StoreSettings->query($option_str."'CloseTime','".sprintf("%04d",intval($param['CLOSE_TIME']))."')");
            $this->StoreSettings->query($option_int."'YoyakuStart','".intval($param['YOYAKU_OPEN_TIME'])."')");
            $this->StoreSettings->query($option_int."'YoyakuEnd','".intval($param['YOYAKU_CLOSE_TIME'])."')");
            //---------------------------------------------------------------------------------------------------------------
            $this->StoreSettings->query($option_int."'YoyakuHyouStart','".intval($param['YOYAKU_HYOU_OPEN_TIME'])."')");
            $this->StoreSettings->query($option_int."'YoyakuHyouEnd','".intval($param['YOYAKU_HYOU_CLOSE_TIME'])."')");
            //---------------------------------------------------------------------------------------------------------------
            //yoyaku time for saturday and sunday
            //---------------------------------------------------------------------------------------------------------------
            $this->StoreSettings->query($option_int."'YoyakuStart_satsun','".intval($param['YOYAKU_OPEN_TIME_SATSUN'])."')");
            $this->StoreSettings->query($option_int."'YoyakuEnd_satsun','".intval($param['YOYAKU_CLOSE_TIME_SATSUN'])."')");
            //---------------------------------------------------------------------------------------------------------------
            $this->StoreSettings->query($option_int."'YoyakuNoticeSetting','".intval($param['YOYAKU_TIME_SETTING'])."')");
            $this->StoreSettings->query($option_str."'YoyakuSettingsOption','".$param['YOYAKU_TIME_SETTINGS_OP']."')");
            $this->StoreSettings->query($option_int."'YoyakuNoticeSecondSetting','".intval($param['YOYAKU_TIME_SECOND_SETTING'])."')");
            $this->StoreSettings->query($option_int."'FOLLOW_MAIL_SETTING','".intval($param['FOLLOW_MAIL_SETTING'])."')");
            $this->StoreSettings->query($option_str."'YoyakuShowMenuNameOnly','".intval($param['SHOW_MENU_NAME_ONLY'])."')");
            //---------------------------------------------------------------------------------------------------------------
            //customer limit auto
            //---------------------------------------------------------------------------------------------------------------
            $this->StoreSettings->query($option_str."'YoyakuCustomersLimitAuto','".intval($param['AUTO_CUST_LIMIT'])."')");
            //---------------------------------------------------------------------------------------------------------------
            $this->StoreSettings->query($option_int."'YOYAKU_MSG','".intval($param['YOYAKU_MSG'])."')");
            
            $this->StoreSettings->query($option_int."'YOYAKU_MENU_TANTOU','".intval($param['YOYAKU_MENU_TANTOU'])."')");          
            
            $this->StoreSettings->query($option_int."'HIDE_HOLIDAY_STAFF','".intval($param['HIDE_HOLIDAY_STAFF'])."')");          
            
            $this->StoreSettings->query($option_int."'RECORD_YOYAKU_DETAIL','".intval($param['RECORD_YOYAKU_DETAIL'])."')");          
            
            $this->StoreSettings->query($option_int."'MODIFYING_MAIL','".intval($param['MODIFYING_MAIL'])."')");
            
            $this->StoreSettings->query($option_int."'YOYAKU_DRAGDROP_TIMEINTERVAL','".intval($param['YOYAKU_DRAGDROP_TIMEINTERVAL'])."')");
            
            $this->StoreSettings->query($option_int."'HIDE_RAITEN','".intval($param['HIDE_RAITEN'])."')");
            
            $this->StoreSettings->query($option_int."'OKOTOWARI_TIME','".intval($param['OKOTOWARI_TIME'])."')");
            
            if ((int)$param['YOYAKU_MENU_TANTOU'] == 1) {
                //-----------------------------------------------------------------------------------
                //if true then update yoyaku_staff_service_time table -> get time from honbu settings
                //-----------------------------------------------------------------------------------
                //SET DATA TO yoyaku_staff_service_time table if not exists from store_services table
                //---------------------------------------------------------------------------------------
                $SqlSTime = "INSERT IGNORE INTO yoyaku_staff_service_time(storecode,
                                                                            staffcode,
                                                                            gcode,
                                                                            service_time,
                                                                            service_time_male)
                                SELECT ".$storeinfo['storecode'].", 
                                        ST.staffcode, 
                                        STO.gcode,
                                        STO.servicetime,
                                        STO.servicetime_male
                                FROM staff ST
                                    JOIN store_services STO
                                            ON STO.storecode = ".$storeinfo['storecode']." 
                                                    AND STO.delflg IS NULL
                                WHERE ST.delflg IS NULL";
                $this->StoreSettings->query($SqlSTime);
                //-----------------------------------------------------------------------------------
            }//end if
            
            return true;
        }
        else {
            return false;
        }

    }
    //- #############################################################################




    // MESSAGE SETTINGS FUNCTIONS ---------------------------------------------------
    /**
     * メセッジ設定を読み込む機能
     * Function to Read the Message Settings
     *
     * @param string $sessionid
     * @return basicInformation
     */
    function wsReadMessageSettings($sessionid) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreSettings->set_company_database($storeinfo['dbname'], $this->StoreSettings);

        $tmp = "(OPTIONNAME = 'YoyakuRegistration' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuNewYoyaku' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuCancelMsg' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuMailRegistration' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuMailNewYoyaku' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuNoticeMsg' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuNoticeSecondMsg' OR ";
        $tmp .= "OPTIONNAME = 'MODIFYING_MAIL_MSG' OR ";
        $tmp .= "OPTIONNAME = 'FOLLOW_MAIL_MSG' OR ";
        $tmp .= "OPTIONNAME = 'YoyakuMailSignature')";

        $criteria   = array('STORECODE' => $storeinfo['storecode']);
        $criteria[] = $tmp;

        $vv = $this->StoreSettings->find('all', array('conditions' => $criteria));

        $settings = array();
        $mailItems = $this->MiscFunction->GetMailItems($this, $storeinfo);

        if ($mailItems) {
            $settings['MAILNOTICE'] = $mailItems["notice"];
            $settings['MAILNOTICESECOND'] = $mailItems["noticesecond"];
            $settings['MODIFYING_MAIL_MSG'] = $mailItems["modifying"];
            $settings['FOLLOW_MAIL_MSG'] = $mailItems["follow"];
            $settings['MAILSIGNATURE'] = $mailItems["signature"];
        }

        foreach ($vv as $itm) {
            switch ($itm['StoreSettings']['OPTIONNAME']) {
                case 'YoyakuRegistration':
                    $settings['REGISTRATION'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuNewYoyaku':
                    $settings['NEWYOYAKU'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuCancelMsg':
                    $settings['CANCEL'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuMailRegistration':
                    $settings['MAILREGISTRATION'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuMailNewYoyaku':
                    $settings['MAILNEWYOYAKU'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuNoticeMsg':
                    $settings['MAILNOTICE'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuNoticeSecondMsg':
                    $settings['MAILNOTICESECOND'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'MODIFYING_MAIL_MSG':
                    $settings['MODIFYING_MAIL_MSG'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'FOLLOW_MAIL_MSG':
                    $settings['FOLLOW_MAIL_MSG'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
                case 'YoyakuMailSignature':
                    $settings['MAILSIGNATURE'] = $itm['StoreSettings']['OPTIONVALUES'];
                    break;
            }
        }

        return $settings;
    }


    /**
     * メセッジ設定を書き込む機能
     * Function to Write the Message Settings
     *
     * @param string $sessionid
     * @param basicInformation $param
     * @return boolean
     */
    function wsWriteMessageSettings($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreSettings->set_company_database($storeinfo['dbname'], $this->StoreSettings);

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->StoreSettings->database_set == true) {

            foreach ($param as &$itm) {
                $itm = str_replace("'", "`", $itm);
            }

            $replace_str = "REPLACE INTO store_settings (STORECODE, OPTIONNAME, OPTIONVALUES) VALUES ({$storeinfo["storecode"]}, ";
            $delete_str = "DELETE FROM store_settings WHERE STORECODE = {$storeinfo["storecode"]} AND OPTIONNAME = ";

            $queryParams = array(
                "YoyakuRegistration" => $param["REGISTRATION"],
                "YoyakuNewYoyaku" => $param["NEWYOYAKU"],
                "YoyakuCancelMsg" => $param["CANCEL"],
                "YoyakuMailRegistration" => $param["MAILREGISTRATION"],
                "YoyakuMailNewYoyaku" => $param["MAILNEWYOYAKU"],
                "YoyakuNoticeMsg" => $param["MAILNOTICE"],
                "YoyakuNoticeSecondMsg" => $param["MAILNOTICESECOND"],
                "MODIFYING_MAIL_MSG" => $param["MODIFYING_MAIL_MSG"],
                "FOLLOW_MAIL_MSG" => $param["FOLLOW_MAIL_MSG"],
                "YoyakuMailSignature" => $param["MAILSIGNATURE"]
            );

            foreach ($queryParams as $queryKey => $queryValue) {
                if ($queryValue !== null) {
                    $this->StoreSettings->query("{$replace_str} '{$queryKey}', '{$queryValue}')");
                } else {
                    $this->StoreSettings->query("{$delete_str} '{$queryKey}'");
                }
            }

            return true;
        }
        else {
            return false;
        }

    }
    //- #############################################################################




    // COLOR FUNCTIONS --------------------------------------------------------------
    /**
     * サブレベル検索機能
     * Performs sublevel search
     *
     * @param string $sessionid
     * @return return_colorInformation
     */
    function wsSearchColor($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname']    = $param['dbname'];
            $storeinfo['storecode'] = $param['storecode'];
            $param['limit']         = -1;
        }

        if($param['STORECODE'] == 0){
            $param['STORECODE'] = $storeinfo['storecode'];
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreTransactionColors->set_company_database($storeinfo['dbname'], $this->StoreTransactionColors);

        $criteria = array('storecode' => $param['STORECODE']);

        if (intval($param['limit']) == 0) {
            $param['limit'] = DEFAULT_LIMIT;
        }

        if (intval($param['page']) == 0) {
            $param['page'] = DEFAULT_STARTPAGE;
        }

        if ($param['limit'] <> -1) {
            $v = $this->StoreTransactionColors->find('all',array('conditions' => $criteria,
                                                                 'fields' => array('id',
                                                                                   'color',
                                                                                   'comment'),
                                                                 'limit'      => $param['limit'],
                                                                 'page'       => $param['page']));
        }
        else {
             $v = $this->StoreTransactionColors->find('all',array('conditions' => $criteria,
                                                             'fields' => array('id',
                                                                               'color',
                                                                               'comment')));
        }

        $ret = array();
        $ret['records']      = set::extract($v, '{n}.StoreTransactionColors');
        $ret['record_count'] = $this->StoreTransactionColors->find('count', array('conditions' => $criteria));

        return $ret;
    }


    /**
     * 色の追加と更新機能
     * Adds or Updates a color
     *
     * @param string $sessionid
     * @param array $param
     * @return id color index
     */
    function wsAddUpdateColor($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreTransactionColors->set_company_database($storeinfo['dbname'], $this->StoreTransactionColors);

        //-- 技術大分情報を準備する (prepare service information)
        foreach ($param as $key => $val) {
            $this->StoreTransactionColors->set($key, $val);
        }
        $this->StoreTransactionColors->set('storecode', $storeinfo['storecode']);

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->StoreTransactionColors->database_set == true) {
            $this->StoreTransactionColors->save(); // アップデートか追加を実行する (Update/Add Execute)
            return $this->StoreTransactionColors->id;
        } else {
            $this->_soap_server->fault(1, '', 'Error Processing Data');
        }
    }


    /**
     * 色の削除機能
     * Deletes a color
     *
     * @param string $sessionid
     * @param id color index
     * @return boolean
     */
    function wsDeleteColor($sessionid, $id) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreTransactionColors->set_company_database($storeinfo['dbname'], $this->StoreTransactionColors);

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->StoreTransactionColors->database_set == true) {
            $this->StoreTransactionColors->delete($id);
            return true;
        } else {
            $this->_soap_server->fault(1, '', 'Error Processing Data');
        }
    }
    //- #############################################################################




    // STORE TRANSACTION FUNCTIONS --------------------------------------------------
    /**
     * トランザクションの検索機能
     * Performs store transaction search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_transactionInformation
     */
    function wsSearchStoreTransaction($sessionid, $param) {
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['ignoreSessionCheck'] <> 1) {
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
        }

        //---------------------------------------------------------------------------------------------------------------------
        $this->StoreTransaction->set_company_database($storeinfo['dbname'], $this->StoreTransaction);
        //---------------------------------------------------------------------------------------------------------------------
        $condition = "";
        $storecond = "";
        $trantype1 = " AND details.TRANTYPE = 1 ";
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['STORECODE'] <> 0) {
            $condition .= " AND transaction.STORECODE = " . $param['STORECODE'];
            //Bug: 1135 - Added By: MarvinC - Don't show servince if store dont have the industry (Beaty, Nail, Matsuke, Este)
            $storecond = " AND (services.syscode in (select syscode from storetype where delflg is null
                           AND storecode = {$param['STORECODE']}) or services.syscode = -2) ";
        }
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['date'] <> "") {
            $condition .= " AND transaction.TRANSDATE = '" . $param['date'] . "'";
        }
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['TRANSCODE'] <> "") {
            $condition .= " AND transaction.TRANSCODE = '" . $param['TRANSCODE'] . "'";
        }
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['CCODE'] <> "") {
            $condition .= " AND transaction.CCODE = '" . $param['CCODE'] . "'";
        }
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['STAFFCODE'] > -1) {
            $condition .= " AND transaction.STAFFCODE = " . $param['STAFFCODE'] ;
        }
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['PRIORITYTYPE'] > 0) {
            $condition .= " AND transaction.PRIORITYTYPE = " . $param['PRIORITYTYPE'] ;
        }
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['NOTTRANSCODE'] <> "") {
            $condition .= " AND transaction.TRANSCODE != '" . $param['NOTTRANSCODE'] . "'";
        }//end if
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['history'] == "check") {
            $condition .= " AND transaction.TEMPSTATUS = '" . $param['TEMPSTATUS'] . "'";
            $misc_order = " transaction.TRANSDATE DESC,";
            $trantype1  = "";
            $order_trantype = " details.TRANTYPE, ";
        }//end if
        //---------------------------------------------------------------------------------------------------------------------
        $sql = "/*wsSearchStoreTransaction 1*/
                SELECT  tbld.min_staffcode, tbld.max_staffcode,
                        transaction.TRANSCODE, transaction.KEYNO, transaction.STORECODE, transaction.STARTTIME, 
                        transaction.IDNO, transaction.TRANSDATE, transaction.YOYAKUTIME, transaction.STARTSERVICETIME, 
                        transaction.ENDTIME, transaction.CCODE, transaction.REGULARCUSTOMER, 
                        transaction.KYAKUKUBUN, transaction.SEX, transaction.RATETAX, transaction.ZEIOPTION, 
                        transaction.SOGOKEIOPTION, transaction.TEMPSTATUS, transaction.CNAME, transaction.APT_COLOR, 
                        transaction.NOTES, transaction.RANKING, transaction.PRIORITY, transaction.PRIORITYTYPE, 
                        transaction.STAFFCODE, transaction.HASSERVICES, transaction.DELFLG, transaction.YOYAKU, 
                        transaction.CUST_TELNO, transaction.CLAIMKYAKUFLG, transaction.UPDATEDATE, transaction.INCOMPLETE,
                            details.STAFFCODE, details.STAFFCODESIMEI, details.ZEIKUBUN,
                            details.PRICE, details.CLAIMED, details.KASANPOINT1, details.KASANPOINT2,
                            details.KASANPOINT3, details.TRANTYPE, details.TRANSCODE, details.STARTTIME,
                            details.ENDTIME, details.GCODE, details.ROWNO, customer.CNUMBER,
                                customer.CCODE, customer.CNAME, customer.CNAMEKANA,
                                customer.CSTORECODE, customer.SEX, customer.TEL1, customer.TEL2,
                                customer.BIRTHDATE, customer.MEMBERSCATEGORY, 
                       howknows_thestore.HOWKNOWSCODE, howknows_thestore.HOWKNOWS,
                            service.MENUNAME, service.YOYAKUMARK, 
                            service.SERVICETIME_MALE, service.SERVICETIME, service.KEYCODE,
                       services.GDCODE, services.BUNRUINAME, services.SYSCODE,
                       product.PRODUCTNAME,
                       jikaiyoyaku.TRANSCODE, 
                       staff.STAFFNAME,
                       yoyaku.UKETSUKEDATE, yoyaku.UKETSUKESTAFF, yoyaku.CANCEL, staff2.staffname as UKETSUKESTAFFNAME,
                       #---------------------------------------------------------------------------------
                        #Added By MarvinC - 2015-07-06
                        YND.YOYAKU_STATUS,
                        YND.NEXTCODE,
                        #---------------------------------------------------------------------------------
                        
                        /* add by albert for bm connection 2015-10-29 */
                        bmtble.route, bmtble.reservation_system, bmtble.reserve_date, bmtble.reserve_code,
                        bmtble.date as v_date, bmtble.start as start_time, bmtble.end as end_time, bmtble.coupon_info,
                        bmtble.comment, bmtble.shop_comment, bmtble.next_coming_comment, bmtble.demand, bmtble.site_customer_id, 
                        bmtble.price as bmPrice, bmtble.nomination_fee, bmtble.total_price as bmTprice, bmtble.use_point, 
                        bmtble.grant_point, bmtble.visit_num, bmtble.name_sei as firstname, bmtble.name_mei as lastname,
                        bmtble.sex as bmsex, bmtble.name_kn_sei as knfirstname, bmtble.name_kn_mei as knlastname, bmtble.tel as bmtel,
			bmtble.zipcode as bmzip, bmtble.address as bmaddress, bmtble.mail as bmmail, bmtble.menu_info, 
                        transaction.origination, bmtble.staffname as bmstaff, str_bm_notes.secondnote as secondnote
                        /* add by albert for bm connection 2015-10-29 */
                            
                FROM store_transaction as transaction
                    LEFT JOIN store_transaction_details as details ON
                        transaction.TRANSCODE = details.TRANSCODE AND
                        transaction.KEYNO = details.KEYNO
                     LEFT JOIN (
                                SELECT transcode, min(staffcode) as min_staffcode, max(staffcode) as max_staffcode
                                FROM store_transaction_details
                                WHERE trantype = 1 AND delflg IS NULL AND claimed = 0	
                                    AND storecode = ".$param['STORECODE']." 
                                    AND transdate = '".$param['date']."'    
                                GROUP BY transcode
                                ) tbld 
                                    ON tbld.transcode = transaction.transcode
                    LEFT JOIN yoyaku_next as jikaiyoyaku ON
                        transaction.TRANSCODE = jikaiyoyaku.NEXTCODE
                    LEFT JOIN customer as customer ON
                        transaction.CCODE = customer.CCODE
                    LEFT JOIN howknows_thestore as howknows_thestore 
                        ON howknows_thestore.howknowscode = customer.howknowscode 
                    LEFT JOIN store_services as service ON
                        service.GCODE = details.GCODE
                        AND service.STORECODE = details.STORECODE
                        AND details.TRANTYPE = 1
                    LEFT JOIN services as services ON
                        services.GDCODE = service.GDCODE
                    #---------------------------------------------------------------------------------
                    #Added By MarvinC - 2015-07-06
                    LEFT JOIN yoyaku_next_details as YND USE INDEX (NEXTCODE)
                        ON YND.NEXTCODE = details.TRANSCODE 
                        AND if(YND.syscode = 0 and YND.yoyaku_status = 2, YND.syscode = 0,YND.syscode = services.syscode) 
                    #---------------------------------------------------------------------------------
                    LEFT JOIN store_products as product ON
                        product.PRODUCTCODE = details.GCODE
                        AND details.TRANTYPE = 2
                        AND details.STORECODE = product.STORECODE
                    LEFT JOIN staff as staff ON
                        details.STAFFCODE = staff.STAFFCODE
                    LEFT JOIN yoyaku_details as yoyaku ON
                        yoyaku.TRANSCODE = details.TRANSCODE
                    LEFT JOIN staff as staff2 ON 
                        yoyaku.uketsukestaff = staff2.staffcode
                        
                    /* add by albert for bm connection 2015-10-29 */
                    left join store_transaction_second_notes as str_bm_notes on 
                        transaction.transcode = str_bm_notes.transcode and transaction.keyno = str_bm_notes.keyno
                    
                    left join (select 7 as origination, 
                                        bm_reservation.route, 
                                        bm_reservation.reservation_system, 
                                        date_format(bm_reservation.reserve_date, '%Y-%m-%d') as reserve_date, 
                                        bm_reservation.reserve_code,
                                        bm_reservation.date,
                                        TIME_FORMAT(bm_reservation.start, '%H:%i') as start, 
                                        TIME_FORMAT(bm_reservation.end, '%H:%i') as end,
                                        bm_reservation.coupon_info, 
                                        bm_reservation.comment, 
                                        bm_reservation.shop_comment, 
                                        bm_reservation.site_customer_id, 
                                        bm_reservation.demand, 
                                        bm_reservation.next_coming_comment,
                                        bm_reservation.price, 
                                        bm_reservation.nomination_fee,
                                        bm_reservation.total_price, 
                                        bm_reservation.use_point,
                                        bm_reservation.grant_point, 
                                        bm_reservation.visit_num, 
                                        bm_reservation.name_sei, 
                                        bm_reservation.name_kn_sei, 
                                        bm_reservation.sex,  
                                        bm_reservation.name_mei, 
                                        bm_reservation.name_kn_mei, 
                                        bm_reservation.tel,
                                        bm_reservation.zipcode, 
                                        bm_reservation.address, 
                                        bm_reservation.mail,
                                        stf.staffname, 
                                        bm_reservation.menu_info, 
                                        bm_reservation.transcode, 
                                        bm_reservation.ccode 
                              from bm_reservation
                                        left join staff as stf ON bm_reservation.site_stylist_id = stf.STAFFCODE
                                        
                            UNION ALL
                            
                              select 8 as origination, 
                                        '' as route,  
                                        '' as reservation_system,  
                                        date_format(rvRes.datecreated, '%Y-%m-%d %H:%i') as reserve_date,  
                                        rvRes.alliance_reserve_id as reserve_code, 
                                        date_format(rvRes.start_datetime, '%Y-%m-%d %H:%i') as date,  
                                        rvRes.minutes as start,  
                                        if(rvRes.is_use_coupon = 1, '使用', 'なし') as end, 
                                        rvRes.coupon_name as coupon_info,   
                                        rvRes.customer_note as comment,  
                                        if(rvRes.is_use_hairstyle = 1, '選択', 'なし') as shop_comment,    
                                        rvRes.alliance_customer_id as site_customer_id,  
                                        rvRes.hairstyle_image_url as demand,   
                                        if(rvRes.is_new_reserve = 1, '初めて', '過去に予約済み、または不明') as next_coming_comment, 
                                        0 as price,  
                                        0 as nomination_fee,   
                                        0 as total_price,   
                                        0 as use_point, 
                                        0 as grant_point,   
                                        0 as visit_num,  
                                        rvRes.customer_name as name_sei,    
                                        rvRes.hairstyle_name as name_kn_sei,   
                                        0  as sex,   
                                        '' as name_mei,   
                                        '' as name_kn_mei,   
                                        rvRes.customer_tel as tel, 
                                        '' as zipcode,  
                                        rvRes.introducer_customer_name as address,   
                                        rvRes.customer_email as mail, 
                                        stf.staffname,
                                        
                                        (select group_concat(strSvcs.menuname SEPARATOR '>%')
                                         from rv_reservation_menu rvResMenu
                                              left join store_services as strSvcs on rvResMenu.menuid = strSvcs.GCODE
                                         where rvResMenu.alliance_reserve_id = rvRes.alliance_reserve_id and rvRes.delflg is null) as menu_info,
                                         
                                        rvKey.transcode,   
                                        '' as ccode 
                            from rv_reservation_key as rvKey
                                        join rv_reservation as rvRes on rvKey.alliance_reserve_id = rvRes.alliance_reserve_id
                                        left join staff as stf ON rvRes.staff_id = stf.STAFFCODE
                            where rvRes.delflg is null
                              ) as bmtble on bmtble.transcode = transaction.TRANSCODE and bmtble.origination = transaction.origination

                            /*add by albert for bm connection 2015-10-29 */
                        
                WHERE transaction.DELFLG IS NULL
                    AND details.DELFLG IS NULL
                    " . $trantype1 . $condition . $storecond. "
                GROUP BY transaction.transcode, details.rowno
                ORDER BY " . $misc_order . " transaction.TRANSCODE, 
                           details.STARTTIME, 
                           details.ROWNO, 
                           details.STAFFCODE, 
                           transaction.PRIORITYTYPE,
                           transaction.TRANSCODE, " . $order_trantype . " details.ROWNO";
        //print_r($sql);die();
        //---------------------------------------------------------------------------------------------------------------------
        $v = $this->StoreTransaction->query($sql);
        //---------------------------------------------------------------------------------------------------------------------
        $subparam['dbname']    = $storeinfo['dbname'];
        $subparam['date']      = $param['date'];
        $subparam['STORECODE'] = $param['STORECODE'];
        $subparam['ignoreSessionCheck'] = 1;
        $subparam['onsave'] = $param['onsave'];
        //---------------------------------------------------------------------------------------------------------------------
        $data = $this->MiscFunction->ParseTransactionData($this, $v, $subparam);
        //---------------------------------------------------------------------------------------------------------------------
        if ($param['onsave'] != 1){
            if (count($data) > 0) {
                for ($ctr = 0; $ctr < count($data); $ctr++) {
                        //---------------------------------------------------------------------------------------------------------
                        $GetDataMarketing = array();
                        //---------------------------------------------------------------------------------------------------------
                        $Sql_RejiMarketing = "/*wsSearchStoreTransaction 2*/
                                            SELECT tblresult.*
                                             FROM (
                                                    select drejimarketing.*, drejimarketing.MARKETINGID as MARKETINGIDNO, 
                                                        concat(marketing.marketingdesc, if(marketing_entry.remarks <> '' 
                                                                                        and marketing_entry.remarks is not null,
                                                        concat(' [', marketing_entry.remarks, ']'), '')) as MARKETINGDESC, 
                                                        staff.STAFFNAME
                                                    from drejimarketing 
                                                        left join marketing_entry on drejimarketing.MARKETINGID = marketing_entry.marketingidno
                                                    left join marketing on marketing_entry.MARKETINGID = marketing.MARKETINGID
                                                    left join staff on staff.STAFFCODE = drejimarketing.STAFFCODE and staff.DELFLG is null
                                                    where drejimarketing.DELFLG is null 
                                                        and drejimarketing.transcode = '".$data[$ctr]['TRANSCODE']."' 
                                                        and drejimarketing.keyno = ".$data[$ctr]['KEYNO']."
                                                  ) tblresult";
                        //---------------------------------------------------------------------------------------------------------
                        $GetDataMarketing = $this->StoreTransaction->query($Sql_RejiMarketing);
                        //---------------------------------------------------------------------------------------------------------
                        $data[$ctr]['rejimarketing'] = $GetDataMarketing;
                        //---------------------------------------------------------------------------------------------------------
                }//end for
                //-----------------------------------------------------------------------------------------------------------------
            }//end if
            //---------------------------------------------------------------------------------------------------------------------
            $arr_reji_marketing = array();
            $ctr = 0;
            $trans_ctr = 0;
            foreach ($data as $arr_reji) {
               $arr_reji_marketing = array();
               foreach ($arr_reji['rejimarketing'] as $arr_marketing) {
                    $arr_reji_marketing[$ctr] = $arr_marketing['tblresult'];
                    $ctr++;
               }//end foreach
               $data[$trans_ctr]['rejimarketing'] = $arr_reji_marketing;
               $trans_ctr++;
            }//end foreach
        } 
        //---------------------------------------------------------------------------------------------------------------------
        $ret = array();
        $ret['records']      = $data;
        $ret['record_count'] = count($data);
        //---------------------------------------------------------------------------------------------------------------------
        if (count($data) > 0) {
            $ret['checked_times'] = $data[0]['checked_times'];
        }//end if
        //---------------------------------------------------------------------------------------------------------------------
        
        return $ret;
        //---------------------------------------------------------------------------------------------------------------------
    }//end function


    /**
     * トランザクションの追加と更新機能
     * Adds or Updates a transaction
     *
     * @param string $sessionid
     * @param array $param
     * @return return_transactionIDs
     */
    function wsAddUpdateStoreTransaction($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }
        
        $param['PRIORITYTYPE'] = substr($param['PRIORITYTYPE'],0,1);

        $subparam['ignoreSessionCheck'] = 1;
        $subparam['dbname'] = $storeinfo['dbname'];

        //-- transactionの衝突を無視するかどうかチェックします (Checks if transaction will ignore conflicts or not)
        if ($param['ignoreConflict'] == 0) {
            $subparam['STARTTIME']    = $param['YOYAKUTIME'];
            $subparam['ENDTIME']      = $param['ENDTIME'];
            $subparam['date']         = $param['TRANSDATE'];
            $subparam['STORECODE']    = $param['STORECODE'];
            $subparam['STAFFCODE']    = $param['STAFFCODE'];
            $subparam['PRIORITYTYPE'] = $param['PRIORITYTYPE'];
            $subparam['NOTTRANSCODE'] = $param['TRANSCODE'];
            $subparam['onsave'] = 1;
            $reply = $this->MiscFunction->CheckTransactionConflict($this, $subparam);

            if ($reply['response'] == 'CONFLICT') {
                $ret['TRANSCODE'] = "0";
                $ret['KEYNO']     = $reply['Row'];
                $ret['IDNO']      = $reply['PhoneRow'];

                return $ret;
            }
        } elseif ($param['ignoreConflict'] == 1) {
            $subparam['STORECODE'] = $param['STORECODE'];
            $subparam['STAFFCODE'] = $param['STAFFCODE'];
            $subparam['date']      = $param['TRANSDATE'];
            $subparam['ROWS']      = $param['newRowValue'];
            $subparam['PHONEROWS'] = $param['newPhoneRowValue'];

            $this->wsAddUpdateStaffRowsHistory($sessionid, $subparam);
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreTransaction->set_company_database($storeinfo['dbname'], $this->StoreTransaction);

        //---------------------------------------------------------------------------
        //-- 開始のデータベーストランザクション (Starts Database Transaction)
        $this->StoreTransaction->begin();
        $sqlctr = 0;
        //---------------------------------------------------------------------------
        //-- TRANSCODEとTRANSDATEの日付をチェックします (Check date on TRANSCODE and TRANSDATE)
        
        //仕様変更20160225-shimizu 日付変更時にtranscode-keynoを変更せず、店舗変更時のみ変更します。
        //if (ereg_replace("-","", $param['TRANSDATE']) <> substr($param['TRANSCODE'],9,8)) {
        //削除は　サーバーのみの変更で済ますためtranscodeのstorecode利用。出来れば変更前店舗コードを取得
        if (intval($param['STORECODE']) <> intval(substr($param['TRANSCODE'],0,7))) {
            if ($param['TRANSCODE'] <> ""){
//              $del_sql = "DELETE FROM store_transaction
//                            WHERE TRANSCODE = '" . $param['TRANSCODE'] . "'";
//              $del_dtlsql = "DELETE FROM store_transaction_details
//                               WHERE TRANSCODE = '" . $param['TRANSCODE'] . "'";
                $del_sql = "UPDATE store_transaction set delflg = now()
                            WHERE TRANSCODE = '" . $param['TRANSCODE'] . "'  and delflg is null;";
                $del_dtlsql = "UPDATE store_transaction_details set delflg = now()
                               WHERE TRANSCODE = '" . $param['TRANSCODE'] . "' and delflg is null;";
                
                //-- 削除古いトランザクションおよびトランザクション細部 (Delete old transaction & transaction details)
                $retQuery[$sqlctr] = $this->StoreTransaction->query($del_sql);
                $sqlctr++;
                $retQuery[$sqlctr] = $this->StoreTransaction->query($del_dtlsql);
                $sqlctr++;
                $oldTransCode = $param['TRANSCODE'];
                unset($param['TRANSCODE']);
            }
        }

        //$param['TEMPSTATUS']  = 4;
        //$param['YOYAKU']      = 1;
        $param['HASSERVICES'] = 1;
        //$param['STORECODE']   = $storeinfo['storecode'];
        $param['TRANTYPE']    = 1;
        $param['QUANTITY']    = 1;

        $priority = explode("-", $param['PRIORITYTYPE']);
        $param['PRIORITYTYPE'] = $priority[0];

        if (substr($param['CCODE'],3) == "0000000") {
            $custsql = "UPDATE customer set SEX = " . $param['SEX'] .
                       " WHERE CCODE = " . $param['CCODE'];
            $retQuery[$sqlctr] = $this->StoreTransaction->query($custsql);
            $sqlctr++;
        }

        if (empty($param['CCODE']) || empty($param['CNUMBER'])) {
            $cust_param['CCODE']     = $param['CCODE'];
            $cust_param['CNUMBER']   = $param['CNUMBER'];
            $cust_param['CSTORECODE']= $param['CSTORECODE'];
            $cust_param['CNAME']     = $param['CNAME'];
            $cust_param['CNAMEKANA'] = $param['CNAMEKANA'];
            $cust_param['FIRSTDATE'] = $param['TRANSDATE'];
            $cust_param['TEL1']      = $param['TEL1'];
            $cust_param['TEL2']      = $param['TEL2'];
            $cust_param['SEX']       = $param['SEX'];
            //-------------------------------------------------------------
            //Add How Knows The Store
            //-------------------------------------------------------------
            $cust_param['HOWKNOWSCODE'] = $param['HOWKNOWSCODE'];
            //-------------------------------------------------------------
            $cust_param['ignoreSessionCheck'] = 1;
            $cust_param['dbname']             = $storeinfo['dbname'];
            $cust_param['storecode']          = $param['STORECODE']; //$storeinfo['storecode'];

            $customer = $this->wsAddUpdateCustomer($sessionid, $cust_param);
            $param['CCODE']   = $customer['CCODE'];
            $param['CNUMBER'] = $customer['CNUMBER'];
        }

        $param['CNAME'] = addslashes($param['CNAME']);
        $param['NOTES'] = addslashes($param['NOTES']);

        //-- TRANSCODEは設定してない場合、新規TRANSCODEを作成 (Check TRANSCODE, create new if none)
        if (empty($param['TRANSCODE'])) {
            $sql_idno = "select ".
                        "f_get_sequence_key('idno',
                                            ".$param['STORECODE']." ,
                                            '". $param['TRANSDATE'] ."') as IDNO"; //$storeinfo['storecode']
            $tmp_data = $this->StoreTransaction->query($sql_idno);

            $idno = $tmp_data[0][0]['IDNO'];
            $roop = true;
            while($roop) {
                $subparam['storecode'] =  $param['STORECODE'];  //$storeinfo['storecode'];
                $subparam['date']      = ereg_replace("-","", $param['TRANSDATE']);
                $subparam['idno']      = $idno;

            //存在する場合、idnoに＋1をして再チェックを繰り返す add 20160308
            $temptranscode = $this->MiscFunction->GenerateTranscode($subparam);
            $checksql = "select transcode from store_transaction where transcode = '{$temptranscode}' limit 1";
            $tmp_data = $this->StoreTransaction->query($checksql);
            
            if( count($tmp_data) == 0)//重複がなければ
                {
                    $roop = false;//ループを抜ける
                }else{
                    $idno++;
                }
            }
            
            $param['TRANSCODE']  = $temptranscode;
            $param['IDNO']       = $idno;
            $param['KEYNO']      = 1;
            
            
            //-------------------------------------------------------------
            $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);
            $sql_regular = "select REGULAR from customer where ccode ='".$param['CCODE']."'";
            $tmp_data = $this->Customer->query($sql_regular);
            $param['KYAKUKUBUN'] = 0;
            $param['REGULARCUSTOMER'] = 0;
            
            if($tmp_data[0]["customer"]['REGULAR']== 0){
            //if ($param['REGULARCUSTOMER'] == 0){
            //-------------------------------------------------------------
                $sql_kyakukubun = "SELECT
                        f_get_kyakukubun('". $param['CCODE']."',
                                         '" . $param['TRANSCODE'] . "') as KYAKUKUBUN";
                $tmp_kyaku = $this->StoreTransaction->query($sql_kyakukubun);
                $param['KYAKUKUBUN'] = $tmp_kyaku[0][0]['KYAKUKUBUN'];

                if ($param['KYAKUKUBUN'] == 0) {
                    $param['REGULARCUSTOMER'] = 1;
                } else {
                    $param['REGULARCUSTOMER'] = 0;
                }
            } else {
                $param['KYAKUKUBUN'] = 0;
            }

            //-- 会社データベースを設定する (Set the Company Database)
            $this->StoreSettings->set_company_database($storeinfo['dbname'], $this->StoreSettings);

            $tmp  = "(OPTIONNAME = 'Tax' OR ";
            $tmp .= "OPTIONNAME  = 'TaxOption' OR ";
            $tmp .= "OPTIONNAME  = 'TotalOption')";

            $criteria   = array('STORECODE' => $param['STORECODE']); //$storeinfo['storecode']);
            $criteria[] = $tmp;

            $v = $this->StoreSettings->find('all', array('conditions' => $criteria));
            
            foreach ($v as $itm) {
                switch ($itm['StoreSettings']['OPTIONNAME']) {
                    case 'Tax':
                        $param['RATETAX'] = $itm['StoreSettings']['OPTIONVALUEI']/100;
                        break;
                    case 'TaxOption':
                        $param['ZEIOPTION'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    case 'TotalOption':
                        $param['SOGOKEIOPTION'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                }
            }
        }// if empty transcode 

        $s = "'";
        //-----------------------------------------------------------------------------
        // if tempstatus == 1 or reception then update else replace
        //-----------------------------------------------------------------------------
        if ((int)$param['TEMPSTATUS'] == 1) {
//            //-------------------------------------------------------------------------
//            $tmpField = ""; 
//            //-------------------------------------------------------------------------
//            if ((int)$param['YOYAKU'] === 1) {
//                $tmpField = "YOYAKUTIME";
//            }else {
//                $tmpField = "STARTTIME";
//            }
            //-------------------------------------------------------------------------
            //".$tmpField." = ".$s.$param['YOYAKUTIME'].$s.",                        
            $sql = "UPDATE store_transaction 
                    SET IDNO = ".$param['IDNO'].", 
                        TRANSDATE = ".$s.$param['TRANSDATE'].$s.", 
                        ENDTIME = ".$s.$param['ENDTIME'].$s.", 
                        CCODE = ".$s.$param['CCODE'].$s.", 
                        REGULARCUSTOMER = ".$param['REGULARCUSTOMER'].", 
                        KYAKUKUBUN = ".$param['KYAKUKUBUN'].", 
                        RATETAX = ".$param['RATETAX'].", 
                        ZEIOPTION = ".$param['ZEIOPTION'].",
                        SOGOKEIOPTION = ".$param['SOGOKEIOPTION'].", 
                        CNAME = ".$s.$param['CNAME'].$s.", 
                        APT_COLOR = ".$s.$param['APT_COLOR'].$s.", 
                        NOTES = ".$s.addslashes($param['NOTES']).$s.", 
                        PRIORITY = ".$param['PRIORITY'].", 
                        STAFFCODE = ".$param['STAFFCODE'].",
                        YOYAKU = ".$param['YOYAKU'].", 
                        CUST_TELNO = ".$s.$param['CUST_TELNO'].$s.", 
                        HASSERVICES = ".$param['HASSERVICES'].", 
                        TEMPSTATUS = ".$param['TEMPSTATUS'].", 
                        PRIORITYTYPE = ".$param['PRIORITYTYPE'].", 
                        SEX = ".$param['SEX']." 
                     WHERE TRANSCODE = ".$s.$param['TRANSCODE'].$s." 
                            AND KEYNO = ".$param['KEYNO'];     
            //-------------------------------------------------------------------------
        } else {
            
            //===============================================================================================
            //Add by Albert 2016-01-20                                           ----------------------------
            //redmine 1037                                                       ----------------------------
            //-----------------------------------------------------------------------------------------------
            
            $fields = "TRANSCODE, KEYNO, STORECODE, IDNO, TRANSDATE, YOYAKUTIME,
                   ENDTIME, CCODE, REGULARCUSTOMER, KYAKUKUBUN, RATETAX, ZEIOPTION,
                   SOGOKEIOPTION, CNAME, APT_COLOR, NOTES, PRIORITY, STAFFCODE,
                   YOYAKU, CUST_TELNO, HASSERVICES, TEMPSTATUS, PRIORITYTYPE, SEX, ORIGINATION ";
            $values = "'" . $param['TRANSCODE']      . "', " . $param['KEYNO']      . " ,
                        " . $param['STORECODE']      . " , " . $param['IDNO']       . " ,
                    '" . $param['TRANSDATE']      . "','" . $param['YOYAKUTIME'] . "',
                    '" . $param['ENDTIME']        . "','" . $param['CCODE']      . "',
                        " . $param['REGULARCUSTOMER']. " , " . $param['KYAKUKUBUN'] . " ,
                        " . $param['RATETAX']        . " , " . $param['ZEIOPTION']  . " ,
                        " . $param['SOGOKEIOPTION']  . " ,'" . $param['CNAME']      . "',
                        " . $param['APT_COLOR']      . " ,'" . $param['NOTES']      . "',
                        " . $param['PRIORITY']       . " , " . $param['STAFFCODE']  . " ,
                        " . $param['YOYAKU']         . " ,'" . $param['CUST_TELNO'] . "',
                        " . $param['HASSERVICES']    . " , " . $param['TEMPSTATUS'] . " ,
                        " . $param['PRIORITYTYPE']   . " , " . $param['SEX'] . " , " . $param['origination'];
            $sql = "REPLACE INTO store_transaction (".$fields.") VALUES(".$values.")";
            
            //===============================================================================================
            
        }//end ifelse
        
        //-- 挿入または更新トランザクション (Insert or Update transaction)
        $retQuery[$sqlctr] = $this->StoreTransaction->query($sql);
        $sqlctr++;
        
        $del_dtlsql = "DELETE
                       FROM store_transaction_details
                       WHERE TRANSCODE = '" . $param['TRANSCODE'] . "'";

        //-- 削除古いトランザクション細部  (Delete old transaction details)
        $retQuery[$sqlctr] = $this->StoreTransaction->query($del_dtlsql);
        $sqlctr++;

        $fld = "TRANSCODE, KEYNO, ROWNO, STORECODE, TRANSDATE, GCODE, TRANTYPE,
                QUANTITY, UNITPRICE, PRICE, ZEIKUBUN, KASANPOINT1, KASANPOINT2,
                KASANPOINT3, STAFFCODE, STAFFCODESIMEI, TEMPSTATUS, STARTTIME, ENDTIME";

        #------------------------------------------------------------------------------------------------------------------------
        # ADDED BY MARVINC - 2015-06-22
        # For Updating Next Reservation
        #------------------------------------------------------------------------------------------------------------------------
        $gdcode = Array();
        #------------------------------------------------------------------------------------------------------------------------
        
        //-- トランザクション細部を通したループ (Loops through transaction details)
        //$starttime = $param['YOYAKUTIME'];
        //$endtime = "";
        for ($i=0; $i<count($param['details']); $i++) {

            //$totalminutes = $param['details'][$i]['MENUTIME'];
            //$old_date_format = strtotime('+'.$totalminutes.' minutes', strtotime($starttime));
            //$endtime = date("H:i", $old_date_format);
                #---------------------------------------------------------------------------------------
                #UPDATE BY MARVINC - 2015-06-19
                #---------------------------------------------------------------------------------------
                $gdcode[$i] = $param['details'][$i]["GDCODE"];
                #---------------------------------------------------------------------------------------
                $val = "";
                $val = "'" . $param['TRANSCODE']                  . "', " . $param['KEYNO']                         . ",
                         " . $param['details'][$i]['ROWNO']       . " , " . $param['STORECODE']                     . ",
                        '" . $param['TRANSDATE']                  . "', " . $param['details'][$i]['GCODE']          . ",
                         " . $param['TRANTYPE']                   . " , " . $param['QUANTITY']                      . ",
                         " . $param['details'][$i]['PRICE']       . " , " . $param['details'][$i]['PRICE']          . ",
                         " . $param['details'][$i]['ZEIKUBUN']    . " , " . $param['details'][$i]['POINTKASAN1']    . ",
                         " . $param['details'][$i]['POINTKASAN2'] . " , " . $param['details'][$i]['POINTKASAN3']    . ",
                         " . $param['details'][$i]['STAFFCODE']   . " , " . $param['details'][$i]['STAFFCODESIMEI'] . ",
                         " . $param['TEMPSTATUS'].", '".$param['details'][$i]['STARTTIME']."', 
                         '".$param['details'][$i]['ENDTIME']."'";

                $dtlsql[$i] = "REPLACE INTO store_transaction_details (".$fld.") VALUES(".$val.")";

                //-- 新しいトランザクション細部挿入 (Insert new transaction detail)
                $retQuery[$sqlctr] = $this->StoreTransaction->query($dtlsql[$i]);

                //$starttime = $endtime;

                $sqlctr++;
            }
        
        #------------------------------------------------------------------------------------------------------------------------
        # ADDED BY MARVINC - 2015-07-27
        # For Updating Next Reservation
        #------------------------------------------------------------------------------------------------------------------------
        $gdcodelist = implode(",",array_unique($gdcode));
                $this->Syscode->set_company_database($storeinfo['dbname'], $this->Syscode);
                $sql = "select SYSCODE, GDCODE from services where GDCODE in (".$gdcodelist.") and DELFLG is null group by SYSCODE order by SYSCODE";
                $newsyscodes = $this->Syscode->query($sql);
                
                $x = 0;
                foreach ($newsyscodes as $item){
                    $newsyscode[$x] = $item["services"]["SYSCODE"];
                    $x++;
                }

        $syscodes = trim(implode(",",array_unique($newsyscode)),",");
        #------------------------------------------------------------------------------------------------------------------------
        
        
        if(trim($param['BEFORE_TRANSCODE']) <> "" || $param['YOYAKU_STATUS'] == 2){
        	//$sql = "UPDATE yoyaku_next SET YOYAKU_STATUS = 2 ,NEXTCODE = '" .$param['TRANSCODE']."' WHERE TRANSCODE ='". $param['BEFORE_TRANSCODE']."'";     
        	//$sql = "UPDATE yoyaku_next SET CHANGEFLG = CASE WHEN NEXTCODE IS NULL AND CHANGEFLG = 0 THEN 0 ELSE 1 END, YOYAKU_STATUS = 2 ,NEXTCODE = '" .$param['TRANSCODE']."' WHERE TRANSCODE ='". $param['BEFORE_TRANSCODE']."'";
        	$sql = "UPDATE yoyaku_next 
        	        SET CHANGEFLG = CASE WHEN NEXTCODE IS NULL AND CHANGEFLG = 0 THEN 0 ELSE 1 END,
        	        YOYAKU_STATUS = 2 ,
        	        FIRST_YOYAKUDATE = CASE WHEN FIRST_YOYAKUDATE IS NULL THEN '". $param['TRANSDATE']."' ELSE FIRST_YOYAKUDATE END,
        	        NEXTCODE = '" .$param['TRANSCODE']."' WHERE TRANSCODE ='". $param['BEFORE_TRANSCODE']."'";
                //-- 挿入または更新トランザクション (Insert or Update transaction)
                $retQuery[$sqlctr] = $this->StoreTransaction->query($sql);
                $sqlctr++;
                
                #------------------------------------------------------------------------------------------------------------------------
                # ADDED BY MARVINC - 2015-06-22
                # For Updating Next Reservation
                #------------------------------------------------------------------------------------------------------------------------
                if ($param['YOYAKU_STATUS'] == 2){
                    $sql = "UPDATE yoyaku_next_details
                            SET CHANGEFLG = CASE WHEN NEXTCODE IS NULL AND CHANGEFLG = 0 THEN 0 ELSE 1 END, 
                            YOYAKU_STATUS = 2,
                            FIRST_YOYAKUDATE = CASE WHEN FIRST_YOYAKUDATE IS NULL THEN '". $param['TRANSDATE']."' ELSE FIRST_YOYAKUDATE END,
                            NEXTCODE = '" .$param['TRANSCODE']."' WHERE NEXTCODE ='".$param['TRANSCODE']."' OR NEXTCODE = '". $param['BEFORE_TRANSCODE']."'";
                }else{
                    
                    #Update by: MarvinC - 2016-01-14 15:07
                    #Check Powers Flag
                    if($this->MiscFunction->GetReturningCustomerCountAll($this) == 1){
                        #check if the service is existing
                        $this->Syscode->set_company_database($storeinfo['dbname'], $this->Syscode);
                        //$sql = "select SYSCODE from yoyaku_next_details where transcode = '". $param['BEFORE_TRANSCODE']."' and syscode in (".$syscodes.") and nextcode is null";
                        $sql = "select SYSCODE from yoyaku_next_details where transcode = '". $param['BEFORE_TRANSCODE']."' and syscode in (".$syscodes.") and yoyaku_status = 1";
                        $exists = $this->Syscode->query($sql);

                        if(empty($exists)){
                            #get the staff incharge
                            $sql = "INSERT INTO yoyaku_next_details 
                                    SELECT TRANSCODE, MAX(ROWNO) + 1, 2, 0, '".$param['TRANSCODE']."', STAFFCODE_INCHARGE, STAFFCODE_INCHARGE,  '". $param['TRANSDATE']."', 0, null, CURRENT_DATE()
                                    FROM yoyaku_next_details YND
                                    WHERE TRANSCODE = '". $param['BEFORE_TRANSCODE']."' LIMIT 1";
                        }else{
                            $sql = "UPDATE yoyaku_next_details
                                    SET CHANGEFLG = CASE WHEN NEXTCODE IS NULL AND CHANGEFLG = 0 THEN 0 ELSE 1 END, 
                                    YOYAKU_STATUS = 2,
                                    FIRST_YOYAKUDATE = CASE WHEN FIRST_YOYAKUDATE IS NULL THEN '". $param['TRANSDATE']."' ELSE FIRST_YOYAKUDATE END,
                                    NEXTCODE = '".$param['TRANSCODE']."' WHERE TRANSCODE = '". $param['BEFORE_TRANSCODE']."' 
                                    AND SYSCODE IN (".$syscodes.") AND YOYAKU_STATUS < 2";
                        }
                    }else{
                        $sql = "UPDATE yoyaku_next_details
                                    SET CHANGEFLG = CASE WHEN NEXTCODE IS NULL AND CHANGEFLG = 0 THEN 0 ELSE 1 END, 
                                    YOYAKU_STATUS = 2,
                                    FIRST_YOYAKUDATE = CASE WHEN FIRST_YOYAKUDATE IS NULL THEN '". $param['TRANSDATE']."' ELSE FIRST_YOYAKUDATE END,
                                    NEXTCODE = '".$param['TRANSCODE']."' WHERE TRANSCODE = '". $param['BEFORE_TRANSCODE']."' 
                                    AND YOYAKU_STATUS < 2";
                    }
                    #----------------------------------------------------------------------------------------------------------------------
                }
                
                $retQuery[$sqlctr] = $this->StoreTransaction->query($sql);
                $sqlctr++;	
                
        }    
        $SqlInsertRejiMarketing = "";
        //===========================================================================================================
        //Add Insert Reji Marketing
        //-----------------------------------------------------------------------------------------------------------
        //Delete Reji Marketing First if Exists
        //-----------------------------------------------------------------------------------------------------------
        $SqlDeleteRejiMarketing = "DELETE FROM drejimarketing 
                                   WHERE transcode = '".$param['TRANSCODE']."' 
                                       AND keyno = ".$param['KEYNO'];
        $this->StoreTransaction->query($SqlDeleteRejiMarketing);
        //-----------------------------------------------------------------------------------------------------------
        if (count($param['rejimarketing']) > 0) {
            //-------------------------------------------------------------------------------------------------------
            $SqlInsertRejiMarketing = $this->GenerateSqlInsertRejiMarketing($param['TRANSCODE'],
                                                                            $param['KEYNO'],
                                                                            $param['rejimarketing']);
            //-------------------------------------------------------------------------------------------------------
            if ($SqlInsertRejiMarketing != "") $this->StoreTransaction->query($SqlInsertRejiMarketing);
            //-------------------------------------------------------------------------------------------------------
        }//end if
        //===========================================================================================================

        // 履歴を詳細に記録する場合
        if (!is_null($param['UKETSUKEDATE'], $param['UKETSUKESTAFF']) && (trim($param['UKETSUKEDATE']) != "" && trim($param['UKETSUKESTAFF']) != "")) {
            $sql = "INSERT INTO yoyaku_details(TRANSCODE, UKETSUKEDATE, UKETSUKESTAFF)
                    VALUES('{$param['TRANSCODE']}', '{$param['UKETSUKEDATE']}', {$param['UKETSUKESTAFF']})
                    ON DUPLICATE KEY UPDATE UKETSUKEDATE = '{$param['UKETSUKEDATE']}', UKETSUKESTAFF = {$param['UKETSUKESTAFF']}";
                    
            //-- 挿入または更新トランザクション (Insert or Update transaction)
            $retQuery[$sqlctr] = $this->StoreTransaction->query($sql);
            $sqlctr++;   

        }
        else{ //if(trim($param['UKETSUKESTAFF']) == "") {
            $uketsukedate = date('Y-m-d'); //詳細に記録しないケースで初回受付日はサーバー日時を使用、受付スタッフは常にフリー
              $sql = "INSERT INTO yoyaku_details(TRANSCODE, UKETSUKEDATE, UKETSUKESTAFF)
                    VALUES('{$param['TRANSCODE']}', '{$uketsukedate}', 0)
                    ON DUPLICATE KEY UPDATE UKETSUKEDATE = '{$uketsukedate}', UKETSUKESTAFF = 0";
                    
            //-- 挿入または更新トランザクション (Insert or Update transaction)
            $retQuery[$sqlctr] = $this->StoreTransaction->query($sql);
            $sqlctr++;   

        }
         

        //-----------------------------------------------------------------------------------------------------------------
        //add by albert for BM second notes information 2015-12-01 --------------------------------------------------------
        if (!is_null($param['secondnote'])){
            //delete old record for second notes
            $GetData = "";
            $Sql = "delete from store_transaction_second_notes where transcode = '" . $oldTransCode . "'";
            $GetData = $this->StoreTransaction->query($Sql);
            
            //add new record for second notes
            $GetData = "";
            $Sql = "insert into store_transaction_second_notes (transcode, keyno, secondnote)
                                                        values ('" . $param['TRANSCODE'] . "', " . $param['KEYNO'] . ", '" . $param['secondnote'] . "')
                    on duplicate key update secondnote = '" . $param['secondnote'] . "'";
            $GetData = $this->StoreTransaction->query($Sql);
        }
        //-----------------------------------------------------------------------------------------------------------------
        
        //-----------------------------------------------------------------------------------------------------------------
        //add by albert to BM Table to a new transcode 2015-12-23 --------------------------------------------------------
        $GetData = "";
        $Sql = "update bm_reservation
                       set transcode = '" . $param['TRANSCODE'] . "'
                where transcode = '" . $oldTransCode . "'";
        $GetData = $this->StoreTransaction->query($Sql);
        //-----------------------------------------------------------------------------------------------------------------
        
        //---------------------------------------------------------------------------
        //-- エラーのためのチェック (Checks if there are errors on all cued queries)
        $error = "false";
        for ($i=0; $i<count($retQuery); $i++) {
            if ($retQuery[$i] === false) {
                $error = "true";
                $param['TRANSCODE'] = "ROLLBACK";
                break;
            }
        }

        if ($error <> "true") {
            $this->StoreTransaction->commit();      //-- トランザクションをコミット (Commit Transactions)
            #------------------------------------------------------------------------------------------------------------------------
            # ADDED BY MARVINC - 2015-07-27
            # Note: Add condition for syscode
            #------------------------------------------------------------------------------------------------------------------------
            //-- Deletes customer mail reservation
            #Check Powers Flag
            if($this->MiscFunction->GetReturningCustomerCountAll($this) == 1){
                $del_mailsql = "DELETE CMRD FROM customer_mail_reservation_details CMRD
                                WHERE CMRD.STORECODE = ".$param['STORECODE']." AND CMRD.CCODE = '".$param['CCODE']."'
                                    AND CMRD.TRANSDATE < '".$param['TRANSDATE']."'
                                    AND CMRD.SYSCODE IN(".$syscodes.")"; 
            }else{
                $del_mailsql = "DELETE CMRD FROM customer_mail_reservation_details CMRD
                                WHERE CMRD.STORECODE = ".$param['STORECODE']." AND CMRD.CCODE = '".$param['CCODE']."'
                                    AND CMRD.TRANSDATE < '".$param['TRANSDATE']."'"; 
            }
            $this->StoreTransaction->query($del_mailsql);
            
            $del_mailsql_d = "DELETE CMR FROM customer_mail_reservation CMR
                              WHERE STORECODE = ".$param['STORECODE']." AND CCODE = '".$param['CCODE']."'
                                    AND TRANSDATE < '".$param['TRANSDATE']."'
                                    AND NOT EXISTS(
                                                    SELECT NULL
                                                    FROM customer_mail_reservation_details CMRD
                                                    WHERE CMRD.transcode = CMR.transcode
                                                   )"; 
            $this->StoreTransaction->query($del_mailsql_d);
            $this->StoreTransaction->commit();
            #------------------------------------------------------------------------------------------------------------------------
        } else {
            $this->StoreTransaction->rollback();    //-- トランザクションをロールバック (Rollback Transactions)
            //$this->_soap_server->fault(1, '', ROLLBACK_MSG);
        }
        //---------------------------------------------------------------------------

        //$ret['CCODE'] = $log;
        $ret['CCODE']     = $param['CCODE'];
        $ret['CNUMBER']   = $param['CNUMBER'];
        $ret['TRANSCODE'] = $param['TRANSCODE'];
        $ret['KEYNO']     = $param['KEYNO'];
        $ret['IDNO']      = $param['IDNO'];
        
        //file_put_contents('../controllers/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
        
        return $ret;
    }


    /**
     * Add Update Staff Menu Service Time
     * Date Created: 2011-07-08 M
     * 
     * @param String $sessionid = session key
     * @param Int $storecode - store code
     * @param Int $staffcode - staff code
     * @param Int $gcode - service code
     * @param Int $ismale male or female flag
     * @param Int $time - service time for male or female
     * @return Boolean  - success flag
     */
    function wsAddUpdateStaffMenuServiceTime($sessionid, 
                                                  $storecode,
                                                  $staffcode,
                                                  $gcode,
                                                  $ismale,
                                                  $time) {
        //---------------------------------------------------------------
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        //---------------------------------------------------------------
        $staffservicetime = $this->YoyakuSession->Check($this);
        //---------------------------------------------------------------
        if ($staffservicetime == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-- 会社データベースを設定する (Set the Company Database)
        $this->YoyakuStaffServiceTime->set_company_database($staffservicetime['dbname'], $this->YoyakuStaffServiceTime);
        //---------------------------------------------------------------
        $field = "service_time";
        if ($ismale) $field = "service_time_male";
        //---------------------------------------------------------------
        $querty_txt = "INSERT INTO yoyaku_staff_service_time(storecode, 
                                                             staffcode,
                                                             gcode,
                                                             ".$field.") 
                       VALUES(".$storecode.",
                              ".$staffcode.",
                              ".$gcode.",
                              ".$time.") 
                       ON DUPLICATE KEY UPDATE ".$field." = ".$time;
        //---------------------------------------------------------------
        $this->YoyakuStaffServiceTime->query($querty_txt);
        //---------------------------------------------------------------
        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->YoyakuStaffServiceTime->database_set == true) {
            $this->YoyakuStaffServiceTime->save(); // アップデートか追加を実行する (Update/Add Execute)
            return true;
        } else {
            $this->_soap_server->fault(1, '', 'Error Processing Data');
            return false;
        }//end if else
        //---------------------------------------------------------------
    }//end function
    
    
    
    /**
     * トランザクションの削除機能
     * Deletes a transaction
     *
     * @param string $sessionid
     * @param string $transcode
     * @return boolean
     */
    function wsDeleteStoreTransaction($sessionid, $transcode) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreTransaction->set_company_database($storeinfo['dbname'], $this->StoreTransaction);
        
        $sql_search = "select tempstatus
                      from store_transaction
                      where delflg is null and transcode = '$transcode'";
        $tmpstat = $this->StoreTransaction->query($sql_search);
        
        if ((int)$tmpstat[0]['store_transaction']['tempstatus'] > 1){
            
            $del_sql = "UPDATE store_transaction
                        SET DELFLG =  NOW()
                        WHERE TRANSCODE = '" . $transcode . "'";

            $del_dtlsql = "UPDATE store_transaction_details
                           SET DELFLG =  NOW()
                           WHERE TRANSCODE = '" . $transcode . "'";

            $del_jkisql = "UPDATE yoyaku_next SET CHANGEFLG = 2,YOYAKU_STATUS = 0
                           WHERE NEXTCODE = '" . $transcode . "'";
            #-----------------------------------------------------------------------------------------------------
            #Added by MarvinC - 2015-06-18
            #-----------------------------------------------------------------------------------------------------
            $del_jkisql_details = "UPDATE yoyaku_next_details SET CHANGEFLG = 2,YOYAKU_STATUS = 0
                           WHERE NEXTCODE = '" . $transcode . "'";
            #-----------------------------------------------------------------------------------------------------

            //-- DELETE TRANSACTION & TRANSACTION DETAILS
            $this->StoreTransaction->query($del_sql);
            $this->StoreTransaction->query($del_dtlsql);
            $this->StoreTransaction->query($del_jkisql);
            $this->StoreTransaction->query($del_jkisql_details);
            //----------------------------------------------------------------------------------------
            //Mark reji marketing records as deleted
            //----------------------------------------------------------------------------------------
            $SqlRejiMarketing = "UPDATE drejimarketing 
                                 SET delflg = now() 
                                 WHERE transcode = '" . $transcode . "'";
            $this->StoreTransaction->query($SqlRejiMarketing);
            //----------------------------------------------------------------------------------------

            return true;
        }else{
            return false;
        }
        
    }
    //- #############################################################################


    /**
     * トランザクションのキャンセル機能
     * Cancels a transaction
     *
     * @param string $sessionid
     * @param string $transcode
     * @param string $keyno
     * @return boolean
     */
    function wsCancelStoreTransaction($sessionid, $transcode, $keyno) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreTransaction->set_company_database($storeinfo['dbname'], $this->StoreTransaction);

        $ins_sql = "INSERT INTO yoyaku_details(TRANSCODE, CANCEL)
                    VALUES('{$transcode}', 1)
                    ON DUPLICATE KEY UPDATE CANCEL = 1";

        $upd_jkisql = "UPDATE yoyaku_next SET CHANGEFLG = 2, YOYAKU_STATUS = 0
                       WHERE NEXTCODE = '" . $transcode . "'";

        //-- INSERT YOYAKUCANCEL
        $this->StoreTransaction->query($ins_sql);
        $this->StoreTransaction->query($upd_jkisql);

        return true;
    }
    //- #############################################################################
    

    // BREAK TIME FUNCTIONS ---------------------------------------------------------
    /**
     * 外出の検索機能
     * Performs break time search
     *
     * @param string $sessionid
     * @param array $param
     * @return return_breakTimeInformation
     */
    function wsSearchBreakTime($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->BreakTime->set_company_database($storeinfo['dbname'], $this->BreakTime);

        $criteria = array('BreakTime.STORECODE' => $param['STORECODE'],
                          'BreakTime.DATE'      => $param['date']);

        if ($param['STAFFCODE'] > -1) {
            $criteria['BreakTime.STAFFCODE'] = $param['STAFFCODE'];
        }

        $fields = array("BREAKID", "STAFFCODE", "STORECODE", "DATE", "PRIORITY",
                        "STARTTIME", "ENDTIME", "REMARKS");

        $v = $this->BreakTime->find('all',array('conditions' => $criteria,
                                                'fields'     => $fields,
                                                'order'      => array('BreakTime.STAFFCODE',
                                                                      'TIMEDIFF(ENDTIME, STARTTIME) DESC')));

        $staff = $param['staff'];
        for ($i=0; $i<count($v); $i++) {
            for ($m=0; $m<count($staff); $m++) {
                if ($staff[$m]['STAFFCODE'] == $v[$i]['BreakTime']['STAFFCODE']) {
                    $v[$i]['BreakTime']['PRIORITY'] = $staff[$m]['ROWS'];
                    break;
                }
            }
        }

        if (empty($param['staff'])) {
            for ($i=0; $i<count($v); $i++) {
                $v[$i]['BreakTime']['STARTTIME'] = substr($v[$i]['BreakTime']['STARTTIME'],0,5);
                $v[$i]['BreakTime']['ENDTIME']   = substr($v[$i]['BreakTime']['ENDTIME'],0,5);

                if ($v[$i]['BreakTime']['PRIORITY'] == 0) {
                    $v[$i]['BreakTime']['PRIORITY'] = 1;
                }
            }
        } else {
            for ($i=0; $i<count($v); $i++) {
                $v[$i]['BreakTime']['STARTTIME'] = substr($v[$i]['BreakTime']['STARTTIME'],0,5);
                $v[$i]['BreakTime']['ENDTIME']   = substr($v[$i]['BreakTime']['ENDTIME'],0,5);

                $starttime = $v[$i]['BreakTime']['STARTTIME'];
                $starttime_c = strtotime($starttime);
                $endtime = $v[$i]['BreakTime']['ENDTIME'];
                $endtime_c = strtotime($endtime);
                $staffcode = $v[$i]['BreakTime']['STAFFCODE'];

                $position_confirmed = false;
                while (!$position_confirmed) {
                    $position_confirmed = true;
                    foreach ($checked_times[$staffcode][$v[$i]['BreakTime']['PRIORITY']] as $entry) {
                        if (($starttime_c  > $entry["starttime"] && $starttime_c <  $entry["endtime"]) ||
                            ($endtime_c    > $entry["starttime"] && $endtime_c   <  $entry["endtime"]) ||
                            ($starttime_c <= $entry["starttime"] && $endtime_c   >= $entry["endtime"]) ) {
                            $position_confirmed = false;
                            $v[$i]['BreakTime']['PRIORITY']--;
                            break;
                        }
                    }
                }

                $checked_times[$staffcode][$v[$i]['BreakTime']['PRIORITY']][] = array("starttime" => $starttime_c,
                                                                "endtime"   => $endtime_c);

                if (intval($checked_priority[$staffcode]) == 0 || $v[$i]['BreakTime']['PRIORITY'] > $checked_priority[$staffcode])  {
                    $checked_priority[$staffcode] = $v[$i]['BreakTime']['PRIORITY'];
                }

            }
        }

        $ret = array();
        $ret['records']      = set::extract($v, '{n}.BreakTime');
        $ret['record_count'] = $this->BreakTime->find('count', array('conditions' => $criteria));

        return $ret;
    }


    /**
     * 外出の追加と更新機能
     * Adds or Updates a break time
     *
     * @param string $sessionid
     * @param array $param
     * @return BREAKID
     */
    function wsAddUpdateBreakTime($sessionid, $param) {
    //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->BreakTime->set_company_database($storeinfo['dbname'], $this->BreakTime);
        $this->BreakTime->begin();

        //-- BREAKIDは設定してない場合、新規BREAKIDを作成 (Check BREAKID, create new if none)
        if (empty($param['BREAKID'])) {
            $querty_txt = "select ".
                          "f_get_sequence_key('breakid','', '') as breakid ";
            $tmp_data = $this->BreakTime->query($querty_txt);
            $param['BREAKID'] = $tmp_data[0][0]['breakid'];
        }

        //-- 休憩時間に情報を準備する (prepare break time information)
        foreach ($param as $key => $val) {
            $this->BreakTime->set($key, $val);
        }

        //-- 会社データベース設定を再確認する (double check that company database is set)
        if ($this->BreakTime->database_set == true) {
            $this->BreakTime->save(); // アップデートか追加を実行する (Update/Add Execute)
            
            $query = "insert into break_time_log(BREAKID,STAFFCODE,STORECODE,DATE,STARTTIME,ENDTIME,PRIORITY,REMARKS,STATUS ,CREATEDATE )
            select BREAKID,STAFFCODE,STORECODE,DATE,STARTTIME,ENDTIME,PRIORITY,REMARKS,1 as STATUS ,now() as CREATEDATE from break_time where breakid = {$param['BREAKID']}";
            $this->BreakTime->query($query);
            $this->BreakTime->commit();
            
            return $this->BreakTime->id;
        } else {
            $this->BreakTime->rollback();
            $this->_soap_server->fault(1, '', 'Error Processing Data');
        }
    }


    /**
     * 外出の削除機能
     * Deletes a break time
     *
     * @param string $sessionid
     * @param int $breakid
     * @return boolean
     */
    function wsDeleteBreakTime($sessionid, $breakid) {
    //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $ret = true;
        $this->BreakTime->set_company_database($storeinfo['dbname'], $this->BreakTime);
        $this->BreakTime->begin();
        
        //削除ログの挿入
        $query = "insert into break_time_log(BREAKID,STAFFCODE,STORECODE,DATE,STARTTIME,ENDTIME,PRIORITY,REMARKS,STATUS ,CREATEDATE )
            select BREAKID,STAFFCODE,STORECODE,DATE,STARTTIME,ENDTIME,PRIORITY,REMARKS,2 as STATUS ,now() as CREATEDATE from break_time where breakid = {$breakid}";
        $ret = $this->BreakTime->query($query);
        
        //$this->BreakTime->delete($breakid);
        if($ret !== false){
            $query = "delete from breaktime where breakid = {$breakid}";
            $this->BreakTime->query($query);
        }
        if($ret !== false){
            $this->BreakTime->commit();
        }else{
            $this->BreakTime->rollback(); 
        }

        return true;
    }
    //- #############################################################################




    // DATA OF THE DAY FUNCTIONS ----------------------------------------------------
    /**
     * 日のデータを取得機能
     * Get the data of the day
     *
     * @param string $sessionid
     * @param array $param
     * @return return_dataOfTheDayInformation
     */
    function wsGetDataOfTheDay($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }
        $param['storecode']  = $param['STORECODE'];
        $date                = explode("-",$param['date']);
        $param['year']       = intval($date[0]);
        $param['month']      = intval($date[1]);
        $param['day']        = intval($date[2]);
        $param['limit']      = -1;
        //--------------------------------------------------------------------------------------------------------
        $param['ignoreSessionCheck'] = 1;
        $param['dbname'] = $storeinfo['dbname'];
        //--------------------------------------------------------------------------------------------------------
        $transaction    = $this->wsSearchStoreTransaction($sessionid, $param);
        //--------------------------------------------------------------------------------------------------------
        $staff          = $this->wsSearchAvailableStaff($sessionid, $param);
        $param['staff'] = $staff['records'];
        $breaktime      = $this->wsSearchBreakTime($sessionid, $param);
        $shift          = $this->wsSearchStaffShift ($sessionid, $param);
        //--------------------------------------------------------------------------------------------------------
        for ($s = 0; $s < count($staff['records']); $s++) {
            //-- スタッフに割り当てるトランザクション (Assign transactions to staff)
            $ctr = 0;
            for ($i = 0; $i < count($transaction['records']); $i++) {
                //------------------------------------------------------------------------------------------------
                if ((int)$transaction['records'][$i]['STAFFCODE'] === (int)$staff['records'][$s]['STAFFCODE']) {
                    //if ($staff['records'][$s]['WEB_DISPLAY'] == 1) {
                    if (($staff['records'][$s]['WEB_DISPLAY'] == 1 &&
                            $transaction['records'][$i]['PRIORITYTYPE'] == 1) ||
                                $transaction['records'][$i]['PRIORITYTYPE'] == 2) {
                        //------------------------------------------------------------------------------
                        $staff['records'][$s]['transaction']['records'][$ctr] = $transaction['records'][$i];
                        //------------------------------------------------------------------------------
                        $ctr++;
                    //---------------------------------------------------------------
                    $col      = explode('-', $transaction['records'][$i]['PRIORITYTYPE']);
                    $prioritytype = intval($col[0]);
                    $priority     = intval($col[1]);

                    if ($prioritytype == 1 && $priority > $staff['records'][$s]['ROWS']) {
                        $staff['records'][$s]['ROWS'] = $priority;
                    } elseif ($prioritytype == 2 && $priority > $staff['records'][$s]['PHONEROWS']) {
                        $staff['records'][$s]['PHONEROWS'] = $priority;
                    }
                    //---------------------------------------------------------------
                    }//end if (($staff['records'][$s]['WEB_DISPLAY']
                }
            }

            if ($staff['records'][$s]['WEB_DISPLAY'] == 1) {
                //-- 外出がスタッフに割り当てる(Assign breaktime to staff)
                $ctr = 0;
                for ($i = 0; $i < count($breaktime['records']); $i++) {
                    if ($breaktime['records'][$i]['STAFFCODE'] == $staff['records'][$s]['STAFFCODE'] &&
                        $breaktime['records'][$i]['PRIORITY'] > 0) {
                        $staff['records'][$s]['breaktime']['records'][$ctr] = $breaktime['records'][$i];
                        $ctr++;
                    }
                }

                //-- シフトがスタッフに割り当てる(Assign shift to staff)
                $ctr = 0;
                for ($i = 0; $i < count($shift['records']); $i++) {
                    if ($shift['records'][$i]['STAFFCODE'] == $staff['records'][$s]['STAFFCODE'] &&
                        $shift['records'][$i]['day'.$param['day']] <> "") {
                            $staff['records'][$s]['shift']['records'][$ctr] = $shift['records'][$i];
                            $ctr++;
                    }
                }
            }

        }

        $ret = array();

        $ret['store'] = array();
        if ($storeinfo['storecode'] <> $param['STORECODE']) {
            //-- 会社データベースを設定する (Set the Company Database)
            $this->StoreSettings->set_company_database($arrReturn['dbname'], $this->StoreSettings);

            $tmp  = "(OPTIONNAME = 'OpenTime' OR ";
            $tmp .= "OPTIONNAME  = 'CloseTime' OR ";
            $tmp .= "OPTIONNAME  = 'YoyakuHyouStart' OR ";
            $tmp .= "OPTIONNAME  = 'YoyakuHyouEnd' OR ";
            $tmp .= "OPTIONNAME  = 'YoyakuStart' OR ";
            $tmp .= "OPTIONNAME  = 'YoyakuEnd')";

            $criteria   = array('STORECODE' => $param['STORECODE']);
            $criteria[] = $tmp;

            $v = $this->StoreSettings->find('all', array('conditions' => $criteria));

            foreach ($v as $itm) {
                switch ($itm['StoreSettings']['OPTIONNAME']) {
                    case 'OpenTime':
                        $arrReturn['OPEN_TIME'] = intval($itm['StoreSettings']['OPTIONVALUES']);
                        break;
                    case 'CloseTime':
                        $arrReturn['CLOSE_TIME'] = intval($itm['StoreSettings']['OPTIONVALUES']);
                        break;
                    case 'YoyakuHyouStart':
                        $arrReturn['YOYAKU_HYOU_OPEN_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    case 'YoyakuHyouEnd':
                        $arrReturn['YOYAKU_HYOU_CLOSE_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    case 'YoyakuStart':
                        $arrReturn['YOYAKU_OPEN_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                    case 'YoyakuEnd':
                        $arrReturn['YOYAKU_CLOSE_TIME'] = $itm['StoreSettings']['OPTIONVALUEI'];
                        break;
                }
            }

            if ($arrReturn['YOYAKU_HYOU_OPEN_TIME'] == "") {
                $arrReturn['YOYAKU_HYOU_OPEN_TIME'] = $arrReturn['OPEN_TIME'];
                $arrReturn['YOYAKU_HYOU_CLOSE_TIME'] = $arrReturn['CLOSE_TIME'];
            }

            $ret['store'] = $arrReturn;
        }

        $holiday = $this->wsSearchStoreHoliday($sessionid, $param);
        if ($holiday['record_count'] > 0) {
            $ret['holiday'] = $holiday['records']['day'.$param['day']];
        } else {
            $ret['holiday'] = "";
        }

        $calendar = $this->wsGetTransactionCalendarView($sessionid, $param);
        if ($calendar['records'] > 0) {
            $ret['calendar'] = $calendar;
        } else {
            $ret['calendar'] = "";
        }

        $ret['records']  = $staff;

        //新着メッセージ取得
        if (array_key_exists('useYoyakuMessage',$param)) {
            if ($param['useYoyakuMessage']==1){
                $ret['messages'] = array();
                $ret['messages'] = $this->wsGetYoyakuMessage($sessionid, $param);
            }
        }
        //---------------------------------------------------------------------------------------------
        return $ret;
        //---------------------------------------------------------------------------------------------
    }//end function
    //- #############################################################################




    // TRANSACTION CALENDAR VIEW FUNCTIONS ------------------------------------------
    /**
     * トランザクションの検索カレンダー機能
     * Search calendar if transaction exists
     *
     * @param string $sessionid
     * @param array $param
     * @return return_transactionCalendarViewInformation
     */
    function wsGetTransactionCalendarView($sessionid, $param) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
        }

        //-- 会社データベースを設定する (Set the Company Database)
        //$this->StoreTransaction->set_company_database($storeinfo['dbname'], $this->StoreTransaction);
        $this->StoreHoliday->set_company_database($storeinfo['dbname'], $this->StoreHoliday);

        $date = $param['year'] . "-" . $param['month'] . "-1";

        /*$criteria['STORECODE'] = $param['STORECODE'];
        $criteria['DELFLG']    = null;
        $criteria['YOYAKU']    = 1;
        $criteria['TRANSDATE BETWEEN ? AND
                   LAST_DAY(DATE_ADD(?, INTERVAL 1 MONTH))'] = array($date, $date);

        $v = $this->StoreTransaction->find('all', array('conditions' => $criteria,
                                                        'fields'     => array('TRANSDATE'),
                                                        'group'      => 'TRANSDATE',
                                                        'order'      => 'TRANSDATE'));*/

        $hcriteria = array('StoreHoliday.STORECODE'  => $param['STORECODE'],
                           'StoreHoliday.YMD BETWEEN ? AND
                            LAST_DAY(DATE_ADD(?, INTERVAL 1 MONTH))'  => array($date, $date));

        $h = $this->StoreHoliday->find('all', array('conditions' => $hcriteria));

        $arrDays = $this->arrDays;

       for ($m=0; $m<2; $m++) {

        if ($m == 1) {
               $param['month']++;
               if ($param['month'] == 13) {
                   $param['month'] = 1;
                   $param['year']++;
               }
           }

           if (strlen($param['month']) == 1) {
               $month = "0" . $param['month'];
           } else {
            $month = $param['month'];
           }

           $arrData[$m]['year']  = $param['year'];
           $arrData[$m]['month'] = $param['month'];

           //- 日間の配列をループ処理　(Loops through the array of Days)
           for ($j = 0; $j < count($arrDays); $j++) {
               $day = $j + 1;
               if (strlen($day) == 1) {
                   $zday = "0" . $day;
               } else {
                   $zday = $day;
               }
               $date = $param['year'] . "-" . $month . "-" . $zday;
               if (checkdate($param['month'], $day, $param['year'])) {
                   //- トランザクションの配列をループ処理　(Loops through the array of Transaction)
                   /*for ($i = 0; $i < count($v); $i++) {
                       if ($date == $v[$i]['StoreTransaction']['TRANSDATE']) {
                           $arrData[$m][$arrDays[$j]] = "1";
                           break;
                       }
                   }*/

                  //- 休日の配列をループ処理　(Loops through the array of Holidays)
                    for ($i = 0; $i < count($h); $i++) {
                        if ($date == $h[$i]['StoreHoliday']['YMD']) {
                            $arrData[$m][$arrDays[$j]].= "h";
                            break;
                        }
                    }

                    // 1 => transaction; h => holiday;
               }
           }
       }

       $ret = array();
       $ret['records'] = $arrData;

       return $ret;
    }
    //- #############################################################################




    // STAFF TAB FUNCTIONS ----------------------------------------------------------
    /**
     * 位置とサブレベルのと店舗リストデータを取得
     * Get data on Staff Tab (Store / Staff / Position / Sublevel)
     *
     * @param string $sessionid
     * @return return_staffTabInformation
     */
    function wsGetAllOnStaffTab($sessionid) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        $param['ignoreSessionCheck'] = 1;
        $param['dbname'] = $storeinfo['dbname'];

        $store    = $this->wsSearchStore($sessionid, $param);
        $position = $this->wsSearchPosition($sessionid, $param);
        $sublevel = $this->wsSearchSublevel($sessionid, $param);

        $ret = array();
        $ret['store']    = $store;
        $ret['staff']    = $staff;
        $ret['position'] = $position;
        $ret['sublevel'] = $sublevel;

        return $ret;
    }
    //- #############################################################################




    // FIRST LOAD FUNCTIONS ---------------------------------------------------------
    /**
     * 最初の負荷に関するデータを得て
     * Get data on First load (Transaction / Service / Color)
     *
     * @param string $sessionid
     * @param string $date
     * @return return_firstLoadInformation
     */
    function wsGetAllOnFirstLoad($sessionid, $date) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        $param['ignoreSessionCheck'] = 1;
        $param['date']      = $date;
        $param['dbname']    = $storeinfo['dbname'];
        $param['STORECODE'] = $storeinfo['storecode'];
        $param['storecode'] = $storeinfo['storecode'];

        $transaction  = $this->wsGetDataOfTheDay($sessionid, $param);
        $service = $this->wsSearchService($sessionid, $param);
        $color   = $this->wsSearchColor($sessionid, $param);

        $ret = array();
        $ret['transaction'] = $transaction;
        $ret['service']     = $service;
        $ret['color']       = $color;

        return $ret;
    }
    //- #############################################################################

    /**
     * 新着メッセージの取得
     *
     *
     * @param string $sessionid
     * @param string $date
     * @return return_yoyakuMessage
     */
    function wsGetYoyakuMessage($sessionid, $param) {
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);

        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }
        $this->YoyakuMessage->set_company_database($storeinfo['dbname'], $this->YoyakuMessage);
        $ret = array();
        $msgs = $this->YoyakuMessage->find('all',           array( 'conditions' => array('STORECODE' => $param['STORECODE']),
                                                                'order'      => 'YOYAKUDATETIME ASC'));
        $ret['messages']      = set::extract($msgs, '{n}.YoyakuMessage');
        $ret['message_count'] = $this->YoyakuMessage->find('count',
                                                            array('conditions' => array('STORECODE' => $param['STORECODE']),
                                                                  'fields'     => 'YoyakuMessage.CODE'));
        $this->YoyakuMessage->query("delete from yoyaku_message where STORECODE=".$param['STORECODE']);
        return $ret;
        }
    
    /**
     * Get Staff Menu Service Time
     * Date Created: 2011-07-08 M
     * 
     * @param String $sessionid - session key
     * @param Int $storecode - given storecode
     * @param Int $staffcode - given staffcode
     * @param Int $gcode - store service code
     * @return Array - Object Array results
     */
    function wsGetStaffMenuServiceTime($sessionid,
                                           $storecode,
                                           $staffcode,
                                           $gcode) {
        //-----------------------------------------------------------------
        $success = true;
        $female_time = 0;
        $male_time = 0;
        //-----------------------------------------------------------------
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $staffservicetime = $this->YoyakuSession->Check($this);
        //-----------------------------------------------------------------
        if ($staffservicetime == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-----------------------------------------------------------------
        $this->YoyakuStaffServiceTime->set_company_database($staffservicetime['dbname'], $this->YoyakuStaffServiceTime);
        //-----------------------------------------------------------------
        $Sql = "SELECT IFNULL(service_time, 0) AS service_time,
                       IFNULL(service_time_male, 0) AS service_time_male  
                FROM yoyaku_staff_service_time
                WHERE storecode = ".$storecode."
                     AND staffcode = ".$staffcode."
                     AND gcode = ".$gcode;
        //-----------------------------------------------------------------
        $rs = $this->YoyakuStaffServiceTime->query($Sql);
        //-----------------------------------------------------------------
        if (count($rs) > 0) {
            $female_time = $rs[0][0]['service_time'];
            $male_time = $rs[0][0]['service_time_male'];
        }//end if
        //-----------------------------------------------------------------
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        //-----------------------------------------------------------------
        $staffservicetime = $this->YoyakuSession->Check($this);
        if ($staffservicetime == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            $success = false;
        }//end if
        //-----------------------------------------------------------------
        return array($success, $female_time, $male_time);
        //-----------------------------------------------------------------
    }
    
     // YOYAKU DETAILS FUNCTIONS -----------------------------------------------------
    /**
     * 予約詳細を検索する
     *
     * @param string $sessionid
     * @param string $begindate
     * @param string $enddate
     * @param int $storedate
     * @param int $staffcode
     * @param int $uketsukestaffcode
     * @return return_yoyakuDetailsInformation
     */
    function wsSearchYoyakuDetails($sessionid, $begindate, $enddate, $storecode, $staffcode, $uketsukestaffcode) {
        $whereQuery = "";
        if ($begindate !== null) $whereQuery .= "AND s_t.TRANSDATE >= '{$begindate}' ";
        if ($enddate !== null) $whereQuery .= "AND s_t.TRANSDATE <= '{$enddate}' ";
        if ($storecode > -1) $whereQuery .= "AND s_t.STORECODE = {$storecode} ";
        if ($staffcode > -1) $whereQuery .= "AND s_t.UKETSUKESTAFF = {$staffcode} ";
        if ($uketsukestaffcode > -1) $whereQuery .= "AND y_d.UKETSUKESTAFF = {$uketsukestaffcode} ";
        $whereQuery .=" AND s_t.YOYAKU > 0 ";

        $query =
            "SELECT " .
            "  s_t.STORECODE, " .
            "  s_t.TRANSDATE, " .
            "  s_t.YOYAKUTIME, " .
            "  s_t.ENDTIME, " .
            "  s_t.CNAME, " .
            "  s.STAFFNAME, " .
            "  s_t.PRIORITYTYPE, " .
            "  s_t.YOYAKU, ".
            "  y_d.UKETSUKEDATE, " .
            "  u_s.STAFFNAME UKETSUKESTAFFNAME, " .
            "  y_d.CANCEL, " .
            "  (CASE WHEN y_n.NEXTCODE IS NOT NULL AND y_n.YOYAKU_STATUS = 2 THEN 1 ELSE 0 END) as YOYAKUNEXTFLG, ".
            " s_t.ORIGINATION, " .
            "  s_t.TEMPSTATUS ".
            " " .
            "FROM store_transaction s_t " .
            " " .
            "LEFT JOIN yoyaku_next_details y_n " .
            "ON s_t.TRANSCODE = y_n.NEXTCODE " .
            " " .
            "JOIN staff s " .
            "ON s.STAFFCODE = s_t.STAFFCODE " .
            " " .
            "LEFT JOIN yoyaku_details y_d " .
            "ON y_d.TRANSCODE = s_t.TRANSCODE " .
            " " .
            "LEFT JOIN staff u_s " .
            "ON u_s.STAFFCODE = y_d.UKETSUKESTAFF " .
            " " .
            "WHERE" .
            "  NOT ( " .
            "    s_t.DELFLG IS NOT NULL AND " .
            "    y_d.CANCEL = 0 " .
            "  ) " .
            "{$whereQuery} " .
            " GROUP BY  s_t.transcode " .
            "ORDER BY " .
            "  s_t.STORECODE, " .
            "  s_t.TRANSDATE, " .
            "  s_t.YOYAKUTIME, " .
            "  s_t.ENDTIME, " .
            "  s.STAFFNAME ";

        $storeinfo = $this->YoyakuSession->Check($this);
        $this->StoreTransaction->set_company_database($storeinfo['dbname'], $this->StoreTransaction);
        $rs = $this->StoreTransaction->query($query);

        $results = array();
        $results["records"] = array();

        foreach ($rs as $row) {
            $result = array();
            $result["STORECODE"]         = $row["s_t"]["STORECODE"];
            $result["TRANSDATE"]         = $row["s_t"]["TRANSDATE"];
            $result["BEGINTIME"]         = $row["s_t"]["YOYAKUTIME"];
            $result["ENDTIME"]           = $row["s_t"]["ENDTIME"];
            $result["CUSTOMERNAME"]      = $row["s_t"]["CNAME"];
            $result["STAFFNAME"]         = $row["s"]["STAFFNAME"];
            $result["PRIORITYTYPE"]      = $row["s_t"]["PRIORITYTYPE"];
            $result["YOYAKU"]            = $row["s_t"]["YOYAKU"];
            $result["UKETSUKEDATE"]      = $row["y_d"]["UKETSUKEDATE"];
            $result["UKETSUKESTAFFNAME"] = $row["u_s"]["UKETSUKESTAFFNAME"];
            $result["CANCEL"]            = $row["y_d"]["CANCEL"];
            $result["YOYAKUNEXTFLG"]     = $row[0]["YOYAKUNEXTFLG"];
            $result["ORIGINATION"]       = $row["s_t"]["ORIGINATION"];
            $result["TEMPSTATUS"]        = $row["s_t"]["TEMPSTATUS"];
            $results["records"][]        = $result;
        }

        return $results;
    }
    //- #############################################################################

    // MAIL FUNCTIONS -----------------------------------------------------
    /**
     * メールを送信する
     *
     * @param string $sessionid
     * @param string $from
     * @param string $to
     * @param string $cc
     * @param string $bcc
     * @param string $subject
     * @param string $body
     * @return boolean
     */
    function wsSendMail($sessionid, $from, $to, $cc, $bcc, $subject, $body) {
        App::import("Vendor", "qdmail/qdmail");
        $mail = new Qdmail("UTF-8");
        $mail->from($from);
        $mail->to($to);
        if ($cc !== "") $mail->cc($cc);
        if ($bcc !== "") $mail->bcc($bcc);
        $mail->subject($subject);
        $mail->text($body);
        return $mail->send();
    }//function close
    
    //- #############################################################################

    // jikai YOYAKU FUNCTIONS -----------------------------------------------------
    /**
     * 次回予約客の一覧表示
     * JikaiYoyaku List search
     *
     * @param string $sessionid
     * @param int $storecode
     * @return return_storeTransactionInformation
     */
    function wsSearchJikaiYoyaku($sessionid, $storecode) {
        if ($param['ignoreSessionCheck'] <> 1) {
            //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
            $storeinfo = $this->YoyakuSession->Check($this);
            if ($storeinfo == false) {
                $this->_soap_server->fault(1, '', INVALID_SESSION);
                return;
            }
        } else {
            $storeinfo['dbname'] = $param['dbname'];
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreTransaction->set_company_database($storeinfo['dbname'], $this->StoreTransaction);

        $condition = "";
        $trantype1 = " AND details.TRANTYPE = 1 ";

        if ($storecode <> 0) {
        	$condition .= " AND transaction.STORECODE = " . $storecode;
        }


         $sql = "select
                       yoyaku_next.*,
                       servicessys.servicesname,
		       transaction.*,
                       details.*,
                       customer.*,
                       service.*,
                       services.*,
                       howknows_thestore.*,
                       product.*,
                            staff.STAFFCODE,
                            staff.STAFFNAME,
                            staff.STAFFNAME2,
                            staff.STORECODE,
                            staff.KEYNO,
                            staff.SUBLEVELCODE,
                            staff.POSITIONCODE,
                            staff.TRAVEL_ALLOWANCE,
                            staff.SEX,
                            staff.HIREDATE,
                            staff.RETIREDATE,
                            staff.YOYAKUKUBUN,
                            staff.DELFLG,
                            staff.STAFFIMGFLG,
                            staff.DISPLAY_ORDER,
                            staff.NOTE,
                            staff.WEBFLG,
                            staff.WEBYAN_DISPLAY,
                            staff.c_member_id,
                            staff.email,
                            staff.`password`,
                            staff.create_comm_flag,
                            staff.BLOG_URL 
                    FROM yoyaku_next
                    INNER JOIN store_transaction as transaction use index (primary) ON
                        yoyaku_next.TRANSCODE = transaction.TRANSCODE
                    LEFT JOIN store_transaction_details as details use index (primary) ON
                        transaction.TRANSCODE = details.TRANSCODE AND
                        transaction.KEYNO = details.KEYNO
                    LEFT JOIN customer as customer use index (primary) ON
                        transaction.CCODE = customer.CCODE
                    LEFT JOIN store_services as service ON
                        service.GCODE = details.GCODE
                        AND details.TRANTYPE = 1
                    LEFT JOIN services as services ON
                        services.GDCODE = service.GDCODE
                    LEFT JOIN servicessys 
                        ON servicessys.syscode = services.syscode
                    JOIN yoyaku_next_details YND
                        On YND.transcode = details.transcode
                        AND YND.rowno = details.rowno
                        AND YND.yoyaku_status = 1
                    LEFT JOIN howknows_thestore as howknows_thestore
                        ON howknows_thestore.howknowscode = customer.howknowscode
                    LEFT JOIN store_products as product ON
                        product.PRODUCTCODE = details.GCODE
                        AND details.TRANTYPE = 2
                        AND details.STORECODE = product.STORECODE
                    LEFT JOIN staff as staff ON
                        details.STAFFCODE = staff.STAFFCODE
                WHERE transaction.DELFLG IS NULL AND details.DELFLG IS NULL #AND yoyaku_next.yoyaku_status = 1
                    " . $trantype1 . $condition . "
                GROUP BY transaction.TRANSCODE, details.ROWNO
                ORDER BY transaction.transdate,transaction.TRANSCODE, details.ROWNO";

        $v = $this->StoreTransaction->query($sql);
        $data = $this->MiscFunction->ParseJikaiYoyakuTransactionData($this, $v, null);
        $ret = array();
        $ret['records']      = $data;
  
        $ret['record_count'] = count($data); //count($v) -> count($data)
        if (count($v) > 0){
            $ret['record_count'] = count($data);
        }else{
            $ret['record_count'] = 0;
        }
       
        if (count($data) > 0) {
            $ret['checked_times'] = $data[0]['checked_times'];
        }
        return $ret;
    }



    /**
     * wsDeleteJikaiYoyaku
     *
     * @param string $sessionid
     * @param string $transcode
     * @param boolean $changeyoyaku
     * @return boolean
     */
    function wsDeleteJikaiYoyaku($sessionid , $transcode ,$storecode,$changeyoyaku = false){
        //-- セッションを確認してデータベース名を取り込む (Verify Session and Get DB name)
        $storeinfo = $this->YoyakuSession->Check($this);
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }

        //-- 会社データベースを設定する (Set the Company Database)
        $this->YoyakuNext->set_company_database($storeinfo['dbname'], $this->YoyakuNext);

        //-- 顧客を削除フラグを設定 (Set Start flag on yoyaku_next)
        # REMOVE BY MARVINC - 2015-12-08 16:54
        #$this->YoyakuNext->set('TRANSCODE', $transcode);
        #$this->YoyakuNext->set('YOYAKU_STATUS', "0");
        //$data = array("YOYAKU_STATUS" => "0");
        #$this->YoyakuNext->save();
        //$this->YoyakuNext->delete($transcode);
        #----------------------------------------------------------------------
        # ADDED BY: MARVINC - 2015-12-08 16:54
        #----------------------------------------------------------------------
        $sql = "UPDATE yoyaku_next YN 
                LEFT JOIN yoyaku_next_details YND
                ON YND.transcode = YN.transcode
                SET YN.yoyaku_status = 0, YND.yoyaku_status = 0
                WHERE YN.transcode = '".$transcode."' and YND.yoyaku_status  = 1";
        $this->YoyakuNext->query($sql);
        #----------------------------------------------------------------------        

        if($changeyoyaku == true){
            $sql = "UPDATE store_transaction as tran SET NEXTCOMINGDATE = ( SELECT * FROM (
			SELECT DATE_ADD( (SELECT IFNULL(trans.TRANSDATE,NOW())  FROM store_transaction as trans where trans.TRANSCODE ='".$transcode."' AND trans.DELFLG IS NULL 
			), INTERVAL (SELECT IFNULL(OPTIONVALUES,60)  FROM store_settings WHERE STORECODE ='".$storecode."' AND OPTIONNAME = 'CardYotei' ) DAY) as temp
		    ) as next_coming_date) WHERE tran.TRANSCODE = '".$transcode."' AND tran.DELFLG IS NULL;";

        	$this->YoyakuNext->query($sql);
        	
        	$sql = "REPLACE INTO customer_mail_reservation(storecode,
                                                          ccode,
                                                          transcode,
                                                          transdate,
                                                          senddate,
                                                          title,
                                                          body,
                                                          sendflg,
                                                          updatedate)
                                                   SELECT trans.storecode,
                                                          trans.ccode,
                                                          trans.transcode,
                                                          trans.transdate,
                                                          DATE_ADD( (SELECT IFNULL(trans.TRANSDATE,NOW())  FROM store_transaction as trans where trans.TRANSCODE ='".$transcode."' AND trans.DELFLG IS NULL 
                                                          ), INTERVAL (SELECT IFNULL(OPTIONVALUES,60)  FROM store_settings WHERE STORECODE ='".$storecode."' AND OPTIONNAME = 'CardYotei' ) DAY) as senddate,
                                                          ifnull(mail.title,\"\") as title,
                                                          ifnull(mail.body,\"\") as body,
                                                          0 as sendflg,
                                                          now() as updatedate
				FROM store_transaction trans LEFT JOIN staff_mail_setting mail ON 
				trans.STORECODE = mail.STORECODE and
				trans.STAFFCODE = mail.STAFFCODE and
				mail.MAILID = 'promotionmail'
				WHERE trans.transcode = '".$transcode."' and trans.delflg is null";
			
			$this->YoyakuNext->query($sql);
        }
        return true;
    }//function close

//<editor-fold defaultstate="collapsed" desc="wsGetMarketing($sessionid, $storecode, $ymd)">    
    /**
     * Get Marketing
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-01-24
     * 
     * @param String $sessionid - session key
     * @param Int $storecode - given store code
     * @param Date $ymd - given date
     * @return Object - Object Array 
     */
    function wsGetMarketing($sessionid, $storecode, $ymd) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        $this->StoreTransaction->set_company_database($storeinfo['dbname'], $this->StoreTransaction);
        //-------------------------------------------------------------------------------------------
        $DisplayCondition = "";
        //-------------------------------------------------------------------------------------------
        //Check the marketing Display in store settings
        //-------------------------------------------------------------------------------------------
        $Sql = "select ifnull(optionvalues , 0) as optionvalues
                from store_settings
                where storecode = ".$storecode."
                and optionname = 'MarketingDisplay'";
        //-------------------------------------------------------------------------------------------
        $GetData = $this->StoreTransaction->query($Sql);
        //-------------------------------------------------------------------------------------------
        if (count($GetData) > 0) {
             if ($GetData[0][0]['optionvalues'] == 1) {
                  $DisplayCondition = " and (mke.begindate <= date('".$ymd."')
                                        and mke.enddate >= date('".$ymd."'))";
             }
        }//end if
        //-------------------------------------------------------------------------------------------
        $GetData = null;
        //-------------------------------------------------------------------------------------------
        //Main Query
        //-------------------------------------------------------------------------------------------
        $Sql = "SELECT tblresult.*
                FROM (
                    Select mke.MARKETINGID,
                            concat(mk.marketingdesc, if(mke.remarks <> '' and mke.remarks is not null,
                                                    concat(' [', mke.remarks, ']'), '')) as MARKETINGDESC,
                            mk.LEAFLETSCOUNT,
                            store_marketing.quantity as QUANTITY,
                            mke.MARKETINGIDNO,
                            mk.MARKETINGCODE
                    from marketing_entry mke
                    join marketing mk
                            on mk.marketingid = mke.marketingid
                            and mk.delflg is null
                    join marketingdivision
                            on marketingdivision.marketingcode = mk.marketingcode
                            and marketingdivision.delflg is null
                    left join store_marketing on store_marketing.storecode = ".$storecode."
                            and store_marketing.ymd = '".$ymd."'
                            and store_marketing.marketingid = mke.marketingidno 
                    where mke.delflg is null
                            and (mke.storecode <= 0 or mke.storecode = ".$storecode.") ".$DisplayCondition."
                      ) tblresult";
        //-------------------------------------------------------------------------------------------
        $GetData = $this->StoreTransaction->query($Sql);
        //-------------------------------------------------------------------------------------------
        //Parse Result Data
        //-------------------------------------------------------------------------------------------
        $arr_marketing = $this->ParseDataToObjectArray($GetData, 'tblresult');
        //-------------------------------------------------------------------------------------------
        $ret = array();
        $ret['records']      = $arr_marketing;
        $ret['record_count'] = count($arr_marketing);
        //-------------------------------------------------------------------------------------------
        return $ret;
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collapsed" desc="ParseDataToObjectArray($rs, $tablename)">    
    /**
     * Parse Data To Object Array
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-01-25
     * 
     * @param Object $rs - result set
     * @param String $tablename - table name
     * @return Object - Object Array
     */
    function ParseDataToObjectArray($rs, $tablename) {
        //-------------------------------------------------------------------------------------------
        //Parse Resultset Array
        //-------------------------------------------------------------------------------------------
        $arr_object = array();
        //-------------------------------------------------------------------------------------------
        $ctr = 0;
        //-------------------------------------------------------------------------------------------
        if (count($rs) > 0) {
            //---------------------------------------------------------------------------------------
            foreach($rs as $data) {
                //-----------------------------------------------------------------------------------
                 $arr_object[$ctr] = $data[$tablename];
                 //----------------------------------------------------------------------------------
                 $ctr++;
                 //----------------------------------------------------------------------------------
            }//end foreach
            //---------------------------------------------------------------------------------------
        }//end if
        
        //-------------------------------------------------------------------------------------------
        return $arr_object;
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collapsed" desc="GetStaffs($sessionid, $storecode, $ymd)">    
    /**
     * Get Staffs
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-01-25
     * 
     * @param String $sessionid - session key
     * @param Int $storecode - given store code
     * @param Date $ymd - given date
     * @return Object - Object Array or Null
     */
    function wsGetStaffs($sessionid, $storecode, $ymd) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        //-------------------------------------------------------------------------------------------
        //Set Sql Condition
        //-------------------------------------------------------------------------------------------
        $SqlCond = "";
        if ($storecode >= 0) $SqlCond = " AND aaa.storecode = ".$storecode." ";
        //-------------------------------------------------------------------------------------------
        $Sql = "SELECT tblresult.*
                FROM (
                    SELECT aaa.STAFFCODE, 
                           aaa.STAFFNAME, 
                           aaa.STAFFNAME2, 
                           aaa.STORECODE, 
                           aaa.RETIREDATE,
                           aaa.SUBLEVELCODE, 
                           aaa.POSITIONCODE, 
                           aaa.TRAVEL_ALLOWANCE, 
                           aaa.SEX,
                           aaa.HIREDATE, 
                           store.STORENAME, 
                           aaa.YOYAKUKUBUN, 
                           staff_sublevel.SUBLEVELNAME,
                           staff_assign_to_store.KEYNO,
                           staff_assign_to_store.assign AS STAFF_ASSIGN_TO_STORE 
                    FROM staff aaa
                            LEFT JOIN store ON aaa.storecode = store.storecode
                            LEFT JOIN staff_sublevel ON staff_sublevel.SUBLEVELCODE = aaa.sublevelcode
                            LEFT JOIN staff_assign_to_store ON aaa.staffcode = staff_assign_to_store.staffcode
                                    AND staff_assign_to_store.storecode = ".$storecode."
                    WHERE aaa.delflg IS NULL 
                            AND (aaa.hiredate <= '".$ymd."' OR aaa.hiredate IS NULL) 
                                    AND
                                    (aaa.retiredate > '".$ymd."' OR aaa.retiredate IS NULL)
                            ".$SqlCond."
                            AND aaa.staffcode > 0
                    ORDER BY aaa.staffcode
                    ) tblresult";
        //-------------------------------------------------------------------------------------------
        $GetData = $this->Staff->query($Sql);
        //-------------------------------------------------------------------------------------------
        //Parse Data Result Set
        //-------------------------------------------------------------------------------------------
        $arr_staff = $this->ParseDataToObjectArray($GetData, 'tblresult');
        //-------------------------------------------------------------------------------------------
        $ret = array();
        $ret['records']      = $arr_staff;
        $ret['record_count'] = count($arr_staff);
        //-------------------------------------------------------------------------------------------
        return $ret;
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>
    
//<editor-fold defaultstate="collapsed" desc="wsGetHowKnowsTheStore">    
    /**
     * Get How Knows The Store
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-01-25
     * 
     * @param String $sessionid - session key
     * @return Object - Object Array or Null 
     */
    function wsGetHowKnowsTheStore($sessionid) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);
        //-------------------------------------------------------------------------------------------
        $Sql = "SELECT howknowscode AS HOWKNOWSCODE,
                       howknows AS HOWKNOWS
                FROM howknows_thestore
                WHERE delflg IS NULL";
        //-------------------------------------------------------------------------------------------
        $GetData = $this->Store->query($Sql);        
        //-------------------------------------------------------------------------------------------
        //Parse Data Result Set
        //-------------------------------------------------------------------------------------------
        $arr_howknows = $this->ParseDataToObjectArray($GetData, 'howknows_thestore');
        //-------------------------------------------------------------------------------------------
        $ret = array();
        $ret['records']      = $arr_howknows;
        $ret['record_count'] = count($arr_howknows);
        //-------------------------------------------------------------------------------------------
        return $ret;
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collapsed" desc="GenerateSqlInsertRejiMarketing($transcode, $keyno, $rejimarketing)">    
    /**
     * Generate Sql Insert Statement Reji 
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-01-26
     * 
     * @param String $transcode - given transcode
     * @param Int $keyno - given key number
     * @param Object $rejimarketing - marketing object array
     * @return String - Reji Marketing Insert Sql Statement
     */
    function GenerateSqlInsertRejiMarketing($transcode, $keyno, $rejimarketing) {
        //-------------------------------------------------------------------------------------------------------------
        $SqlInsert = "";
        //-------------------------------------------------------------------------------------------------------------
        if (count($rejimarketing) > 0) {
            //---------------------------------------------------------------------------------------------------------
            //Generate Sql Insert Reji Marketing
            //---------------------------------------------------------------------------------------------------------
            $SqlInsert = "INSERT INTO drejimarketing(transcode,
                                                     keyno,
                                                     rowno,
                                                     storecode,
                                                     transdate,
                                                     marketingid,
                                                     staffcode,
                                                     tempflg) VALUES";
            //---------------------------------------------------------------------------------------------------------
            $NextValues = false;
            //---------------------------------------------------------------------------------------------------------
            foreach ($rejimarketing as $rm) {
                //-----------------------------------------------------------------------------------------------------
                //Add Comma for Multiple Values
                //-----------------------------------------------------------------------------------------------------
                $tmpSql = (($NextValues)?", ":"")."(";
                //-----------------------------------------------------------------------------------------------------
                $tmpSql .= "'".$transcode."', ";
                $tmpSql .= $keyno.", ";
                $tmpSql .= $rm['ROWNO'].", ";
                $tmpSql .= $rm['STORECODE'].", ";
                $tmpSql .= "'".$rm['TRANSDATE']."', ";
                $tmpSql .= $rm['MARKETINGID'].", ";
                $tmpSql .= $rm['STAFFCODE'].", ";
                $tmpSql .= $rm['TEMPFLG'];
                $tmpSql .= ")";
                //-----------------------------------------------------------------------------------------------------
                //Set Values to Sql Insert Statement 
                //-----------------------------------------------------------------------------------------------------
                $SqlInsert .= $tmpSql;
                //-----------------------------------------------------------------------------------------------------
                $NextValues = true;
                //-----------------------------------------------------------------------------------------------------
            }//end if
            //---------------------------------------------------------------------------------------------------------
        }//end if
        //---------------------------------------------------------------------------------------------------------
        return $SqlInsert;
        //---------------------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collapsed" desc="wsGetOkotowariTodaysCount">    
    /**
     * Get Okotowari Todays Count
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-02-20
     * Update:
     * 
     * @param String $sessionid - session key-
     * @param String $storecode - given store -code
     * @param Date $ymd - given date
     * @return Object - Soap Object or Null
     */
    function wsGetOkotowariTodaysCount($sessionid, $storecode, $ymd) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        //-------------------------------------------------------------------------------------------
        //Query
        //-------------------------------------------------------------------------------------------
        $Sql = "SELECT COUNT(IF(ccode IS NULL OR ccode = '', storecode, NULL)) AS unregistered,
                       COUNT(IF(ccode IS NOT NULL AND ccode <> '', storecode, NULL)) AS registered  
                FROM okotowari 
                        USE INDEX (STORECODEYMD)
                WHERE delflg IS NULL
                        AND storecode = ".$storecode."
                        AND ymd = '".$ymd."'";
        //-------------------------------------------------------------------------------------------
        $GetData = $this->Staff->query($Sql);
        //-------------------------------------------------------------------------------------------
        $unregistered = 0;
        $registered = 0;
        //-------------------------------------------------------------------------------------------
        if (count($GetData) > 0) {
            //---------------------------------------------------------------------------------------
            $unregistered = $GetData[0][0]['unregistered'];
            //---------------------------------------------------------------------------------------
            $registered = $GetData[0][0]['registered'];
            //---------------------------------------------------------------------------------------
        }//end if
        //-------------------------------------------------------------------------------------------
        return Array($unregistered, $registered);
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collapsed" desc="wsAddOkotowari">    
    /**
     * Add Okotowari Record
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-02-20
     * Update:
     * 
     * @param String $sessionid
     * @param Int $storecode
     * @param Date $ymd
     * @param Time $time
     * @param String $ccode
     * @return Null 
     */
    function wsAddOkotowari($sessionid,
                              $storecode,
                              $ymd,
                              $time,
                              $ccode) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        //-------------------------------------------------------------------------------------------
        $oid = 1;
        //-------------------------------------------------------------------------------------------
        $SqlGetID = "SELECT IFNULL(MAX(oid), 0)+1 AS oid 
                     FROM okotowari";
        $rsID = $this->Staff->query($SqlGetID);
        if (count($rsID) > 0) {
            $oid = $rsID[0][0]['oid'];
        }//end if
        //-------------------------------------------------------------------------------------------
        $CCode_Param = "NULL";
        //-------------------------------------------------------------------------------------------
        if ($ccode != "") $CCode_Param = "'".$ccode."'";
        //-------------------------------------------------------------------------------------------
        $Sql = "INSERT INTO okotowari(oid, storecode, ymd, otime, ccode)
                VALUES(".$oid.",
                        ".$storecode.",
                        '".$ymd."',
                        '".$time."',
                        $CCode_Param)";
        //-------------------------------------------------------------------------------------------
        $this->Staff->query($Sql);
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collpased" desc="wsUpdateOkotowari">    
    /**
     * wsUpdateOkotowari
     */
    function wsUpdateOkotowari($sessionid,
                               $oid,
                               $ymd,
                               $time,
                               $ccode) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        //-------------------------------------------------------------------------------------------
        $Sql = "UPDATE okotowari
                    SET ymd = '".$ymd."',
                        otime = '".$time."',
                        ccode = '".$ccode."' 
                    WHERE oid = ".$oid;
        //-------------------------------------------------------------------------------------------
        $this->Staff->query($Sql);
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collapsed" desc="wsCheckCustomer">    
    /**
     * Check and Get Customer Record
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-02-20
     * Update:
     * 
     * @param String $sessionid - session key
     * @param String $cnumber - customer number
     * @param String $ccode - customer code
     * @return Object - Soap Object or Null
     */
    function wsCheckCustomer($sessionid, $cnumber, $ccode) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);
        //-------------------------------------------------------------------------------------------
        //Query
        //-------------------------------------------------------------------------------------------
        $condition_param = " AND cnumber = '".$cnumber."' ";
        //-------------------------------------------------------------------------------------------
        if ($ccode != "") $condition_param = " AND ccode = '".$ccode."' ";
        //-------------------------------------------------------------------------------------------
        $Sql = "SELECT ccode, cnumber, cname, cnamekana, sex
                FROM customer
                WHERE delflg IS NULL ".$condition_param;
        //-------------------------------------------------------------------------------------------
        $GetData = $this->Customer->query($Sql);
        //-------------------------------------------------------------------------------------------
        if (count($GetData) > 0) {
            //---------------------------------------------------------------------------------------
            return Array($GetData[0]['customer']['ccode'],
                         $GetData[0]['customer']['cnumber'],
                         $GetData[0]['customer']['cname'],
                         $GetData[0]['customer']['cnamekana'],
                         $GetData[0]['customer']['sex']);
            //---------------------------------------------------------------------------------------
        } else {
            return null;
        }//end if else
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collapsed" desc="GetOkotowariHistory">    
    /**
     * Get Okotowari History Records
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-02-20
     * Update:
     * 
     * @param String $sessionid - session key
     * @param Integer $storecode - given store code
     * @param Date $datefrom - given date from
     * @param Date $dateto - given date to
     * @param String $cnumber - customer number
     * @param String $cname - customer name
     * @param Int $sex - gender
     * @param Int $category - category (new, registered)
     * @return Object - Soap Object Array or Null
     */
    function GetOkotowariHistory($sessionid,
                                    $storecode,
                                    $datefrom,
                                    $dateto,
                                    $cnumber = "",
                                    $cname = "",
                                    $sex = -1,
                                    $category = -1) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Customer->set_company_database($storeinfo['dbname'], $this->Customer);
        //-------------------------------------------------------------------------------------------
        //Query
        //-------------------------------------------------------------------------------------------
        
        //----------------------------------------------------------------- 
        //CNUMBER Condition
        //-----------------------------------------------------------------
        $cnumber_Condition = "";
        if ($cnumber != "") $cnumber_Condition = " AND cust.cnumber LIKE '".$cnumber."%' ";
        //----------------------------------------------------------------- 
        //CNAME Condition
        //-----------------------------------------------------------------
        $cname_Condition = "";
        if ($cname != "") $cname_Condition = " AND cust.cname LIKE '%".$cname."%' ";
        //----------------------------------------------------------------- 
        //Gender Condition
        //-----------------------------------------------------------------
        $gender_Condition = "";
        if ($sex >= 0) $gender_Condition = " AND cust.sex = ".$sex." ";
        //----------------------------------------------------------------- 
        //Transaction TO Condition
        //-----------------------------------------------------------------
        $category_Condition = "";
        switch ($category) {
            case 0: //new customer
                $category_Condition = " AND (o.ccode IS NULL OR
                                                o.ccode = '') ";
                break;
            case 1: //registered customer
                $category_Condition = " AND o.ccode IS NOT NULL
                                        AND o.ccode <> '' ";
                break;
        }//end switch 
        //-----------------------------------------------------------------
        $Sql = "SELECT tblresult.*
                FROM (
                    SELECT o.oid AS OID, 
                            o.storecode AS STORECODE,
                            o.ymd AS YMD,
                            o.otime AS OTIME,
                            o.ccode AS CCODE,
                            cust.cnumber AS CNUMBER,
                            cust.cname AS CNAME,
                            cust.sex AS SEX 
                    FROM okotowari o
                            LEFT JOIN customer cust
                                    ON cust.ccode = o.ccode
                    WHERE o.delflg IS NULL
                            AND o.storecode = ".$storecode."
                            AND o.ymd BETWEEN '".$datefrom."' AND '".$dateto."'
                                ".$cnumber_Condition."
                                ".$cname_Condition."
                                ".$gender_Condition."
                                ".$category_Condition."
                        ORDER BY o.ymd DESC, o.otime DESC
                ) tblresult";
        //-----------------------------------------------------------------     
        $GetData = null;
        //-------------------------------------------------------------------------------------------
        $GetData = $this->Customer->query($Sql);
        //-------------------------------------------------------------------------------------------
        //Parse Data Result Set
        //-------------------------------------------------------------------------------------------
        $arr_okotowari = $this->ParseDataToObjectArray($GetData, 'tblresult');
        //-------------------------------------------------------------------------------------------
        //$ret = array();
        //$ret['records']      = $arr_okotowari;
        //$ret['record_count'] = count($arr_okotowari);
        //-------------------------------------------------------------------------------------------
        return $arr_okotowari;
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collapsed" desc="wsDeleteOkotowariRecord">    
    /**
     * Delete Okotowari Record
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-02-20
     * Update:
     * 
     * @param String $sessionid - session id
     * @param Int $oid - okotowari id
     * @return Null
     */
    function wsDeleteOkotowariRecord($sessionid, $oid) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        //-------------------------------------------------------------------------------------------
        $Sql = "UPDATE okotowari
                    SET delflg = now()
                    WHERE oid = ".$oid;
        //-------------------------------------------------------------------------------------------
        $this->Staff->query($Sql);
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>    
    
//<editor-fold defaultstate="collapsed" desc="wsGetShiftSimulationPassword">    
    /**
     * wsGetShiftSimulationPassword
     * @author Marvin marvin@think-ahead.jp
     * Date Created: 2012-06-26
     * Updates:
     * 
     * @param String $sessionid - session key
     * @return String - Password 
     */
    function wsGetShiftSimulationPassword($sessionid, $storecode) {
        //-------------------------------------------------------------------------------------------
        $retval = "";
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //-------------------------------------------------------------------------------------------
        $this->Staff->set_company_database($storeinfo['dbname'], $this->Staff);
        //-------------------------------------------------------------------------------------------
        //Query
        //-------------------------------------------------------------------------------------------
        $Sql = "SELECT optionvalues 
                FROM store_settings 
                WHERE STORECODE = ".$storecode."   
                        AND OPTIONNAME = 'SHIFT_SIMULATION_PASSWORD' ";
        //-------------------------------------------------------------------------------------------
        $GetData = $this->Staff->query($Sql);
        //-------------------------------------------------------------------------------------------
        if (count($GetData) > 0) {
            //---------------------------------------------------------------------------------------
            $retval = $GetData[0]['store_settings']['optionvalues'];
            //---------------------------------------------------------------------------------------
        }//end if
        //-------------------------------------------------------------------------------------------
        return $retval;
        //-------------------------------------------------------------------------------------------
    }//end function
//</editor-fold>   
    
//<editor-fold defaultstate="collapsed" desc="wsGetGyoshuKubun">   
    /**
     * Get Gyoushu Kubun
     * 
     * @param String $sessonid
     * @param Int $storecode
     * @return Object 
     */
    function wsGetGyoshuKubun($sessonid,$storecode) {
        //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        //
        //-- 会社データベースを設定する (Set the Company Database)
        $this->Service->set_company_database($storeinfo['dbname'], $this->Service);
        //-------------------------------------------------------------------------------------------
        //Query
        //-------------------------------------------------------------------------------------------
        $Sql = "select tblresult.* 
                from 
                    (select servicessys.syscode as SYSCODE,
                            servicessys.SERVICESNAME as SERVICESNAME,
                            servicessys.DESCRIPTION as DESCRIPTION
                     from storetype 
                        left join servicessys 
                            on storetype.SYSCODE = servicessys.SYSCODE
                     WHERE STORECODE = ".$storecode."  
                        and storetype.delflg is null 
                        and servicessys.DELFLG is null
                     ) tblresult";
        //-------------------------------------------------------------------------------------------
        $GetData = $this->Service->query($Sql);
        //-------------------------------------------------------------------------------------------
        //Parse Result Data
        //-------------------------------------------------------------------------------------------
        $ret_data =  $this->ParseDataToObjectArray($GetData, 'tblresult');
        
        $ret = array();
        $ret['records']      = $ret_data;
        $ret['record_count'] = count($ret_data);
        //-------------------------------------------------------------------------------------------
        return $ret;
        //-------------------------------------------------------------------------------------------
     }//end function
//</editor-fold>     
    
//<editor-fold defaultstate="collapsed" desc="wsGetYoyakuAllowTransToStore">
/**
    *
    * @param <String> $sessionid
    * @param type $storecode
    * @return Object Array 
    */
function wsGetYoyakuAllowTransToStore($sessionid,$storecode) {
    //-------------------------------------------------------------------------------------------
    $storeinfo = $this ->YoyakuSession -> Check ($this);
    //-------------------------------------------------------------------------------------------
    if ($storeinfo==false){
        $this -> _soap_server -> fault(1,'',INVALID_SESSION);
        return;
    }
    //-------------------------------------------------------------------------------------------
    $this->Store->set_company_database($storeinfo['dbname'], $this->Store);
    //-------------------------------------------------------------------------------------------                      
    $sql= 'select tblresult.* from (select tblyayaku.STORECODE as storecode,
                                           tblyayaku.TOSTORECODE as tostorecode,
                                           store.STORENAME as tostorename 
                                    from yoyaku_allow_trans tblyayaku 
                                        left join store 
                                            on tostorecode = store.STORECODE
                                    where tblyayaku.STORECODE = '.$storecode.' 
                                        AND tblyayaku.DELFLG is NULL
                                    ) tblresult';
    //-------------------------------------------------------------------------------------------
    $GetData = $this -> Store ->query ($sql);
    //-------------------------------------------------------------------------------------------
    $retdata =  $this->ParseDataToObjectArray($GetData, 'tblresult');
    //-------------------------------------------------------------------------------------------
    return $retdata;
    //-------------------------------------------------------------------------------------------
    }
//</editor-fold>
          
//<editor-fold defaultstate="collapsed" desc="wsGetStoreMenuServiceTime">
    /**
     * @uses Get Store Menu Services Time for each Staff
     * @author Homer Pasamba Email: homer.pasamba@think-ahead.jp
     * @param <String> $sessionid
     * @param <Integer> $storecode 
     * @return Array - Object Array results
     */
    function wsGetStoreMenuServiceTime($sessionid,$storecode) {
        //===================================================================================
        //(Verify Session and Get DB name)
        //-----------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-----------------------------------------------------------------------------------
        if ($storeinfo == false) {
           $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        } // End if
        //-----------------------------------------------------------------------------------
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);
        //===================================================================================
        // Get StoreMenuServiceTime Data
        //-----------------------------------------------------------------------------------
         $Sql = "SELECT * FROM(SELECT staffcode,
                                      gcode,
                                      IFNULL(service_time, 0) AS female_time,
                                      IFNULL(service_time_male, 0) AS male_time
                               FROM yoyaku_staff_service_time
                               WHERE storecode = ".$storecode.") as tblresult"; 
        //-----------------------------------------------------------------------------------
         $GetData = $this->Store->query($Sql);
        //-----------------------------------------------------------------------------------
         $retdata =  $this->ParseDataToObjectArray($GetData, 'tblresult');
         //-----------------------------------------------------------------------------------
         return ($retdata);
         //===================================================================================
    } // End Function
 //</editor-fold>
    
//<editor-fold defaultstate="collapsed" desc="wsGetTransactionByTransCode">
    /**
     * @author Homer Pasamba Email:homer.pasamba@think-ahead.jp
     * @uses Get the Transaction Status before updating Transaction
     * @param type $sessionid
     * @param type $transcode 
     */
 function wsGetTransactionByTransCode($sessionid,$transcode) {
        //===================================================================================
        //(Verify Session and Get DB name)
        //-----------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-----------------------------------------------------------------------------------
        if ($storeinfo == false) {
           $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        } // End if
        //-----------------------------------------------------------------------------------
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);
        //===================================================================================
        // Get store_transaction Data
        //-----------------------------------------------------------------------------------
         $Sql = "SELECT trans.TRANSCODE,  
                        trans.KEYNO,
                        trans.STORECODE,
                        trans.IDNO,
                        trans.TRANSDATE,
                        trans.CCODE,
                        trans.CLAIMKYAKUFLG,
                        trans.UPDATEDATE,
                        trans.PRIORITY,
                        trans.YOYAKU,
                        trans.REGULARCUSTOMER,
                        trans.TEMPSTATUS,
                        trans.KYAKUKUBUN,
                        trans.ZEIOPTION,
                        trans.RATETAX,
                        trans.TAX,
                        trans.SOGOKEIOPTION,
                        trans.APT_COLOR,
                        trans.NOTES,
                        trans.STAFFCODE
                FROM store_transaction AS trans
                WHERE trans.transcode = '".$transcode."'
                    AND trans.delflg IS NULL"; 
        //-----------------------------------------------------------------------------------
         $Trans = $this->Store->query($Sql);
        //===================================================================================
        return ($Trans[0]['trans']);
        //===================================================================================
 }
//</editor-fold>
    
//<editor-fold defaultstate="collapsed" desc="wsAddUpdateTransUketsuke">
 /**
  * @author Homer Pasamba 2013/01/28
  * @param string $sessionid
  * @param string $transcode
  * @param string $uketsukedate
  * @param integer $uketsukestaff
  * @return boolean 
  */
 function wsAddUpdateTransUketsuke($sessionid,$transcode,$uketsukedate,$uketsukestaff) {
        //===================================================================================
        //(Verify Session and Get DB name)
        //-----------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-----------------------------------------------------------------------------------
        if ($storeinfo == false) {
           $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        } // End if
        //-----------------------------------------------------------------------------------
        $this->Store->set_company_database($storeinfo['dbname'], $this->Store);
        //===================================================================================
        // Add or Update Transaction Uketsuke Data
        //-----------------------------------------------------------------------------------
        $RetVal = False;
        //-----------------------------------------------------------------------------------
        if (!is_null($transcode) && !is_null($uketsukedate) && $uketsukestaff <> -1) {
            //-------------------------------------------------------------------------------
            $Sql = "INSERT INTO yoyaku_details (TRANSCODE, UKETSUKEDATE, UKETSUKESTAFF)
                    VALUES ('".$transcode."','".$uketsukedate."','".$uketsukestaff."')
                    ON DUPLICATE KEY UPDATE UKETSUKEDATE = '".$uketsukedate."', 
                                            UKETSUKESTAFF = '".$uketsukestaff."'";
            //-------------------------------------------------------------------------------
            $this->Store->query($Sql);
            //-------------------------------------------------------------------------------
            $RetVal = true;
            //-------------------------------------------------------------------------------
        } // End If (!is_null($transcode) && !is_null($uketsukedate) && $uketsukestaff <> -1)
        //===================================================================================
        return $RetVal;
        //===================================================================================
 }
//</editor-fold>
 
 
 //<editor-fold defaultstate="collapsed" desc="wsGetMailDomain">
    /**
     * @author MCUNANAN :mcunanan@think-ahead.jp
     * Date: 2015-12-05 14:34
     * @uses Get Mail Domain
     * @param type $sessionid
     * @param type $companyid
     * @param type $storecode
     */
 function wsGetMailDomain($sessionid,$companyid,$storecode) {
     
     //-------------------------------------------------------------------------------------------
        $storeinfo = $this->YoyakuSession->Check($this);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $this->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        
        #-------------------------------------------------------------------
        # ADDED BY: MARVINC - 2015-12-05 14:34
        #-------------------------------------------------------------------
        $Sql = "SELECT WSA.storeid 
                FROM sipssbeauty_server.webyan_store_accounts WSA
                WHERE WSA.companyid = ".$companyid." 
                    AND WSA.storecode = ".$storecode;
        $emailadd = $this->Store->query($Sql);
        $arrReturn = $emailadd[0]["WSA"]["storeid"]. "@". EMAIL_DOMAIN;
        #-------------------------------------------------------------------
        //===================================================================================
        return $arrReturn;
        //===================================================================================
 }
//</editor-fold>
 
 //<editor-fold defaultstate="collapsed" desc="wsGetReturningCustomerCountAll">
    /**
     * @author MCUNANAN :mcunanan@think-ahead.jp
     * Date: 2015-12-05 14:34
     * @uses Get Mail Domain
     * @param type $sessionid
     * @param type $companyid
     * @param type $storecode
     */
 function wsGetReturningCustomerCountAll($sessionid) {
        return $this->MiscFunction->GetReturningCustomerCountAll($this);
 }
//</editor-fold>
 
}//end class ServersController

?>
