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


class MiscFunctionComponent extends Object
{

    /**
     * サブ技術大分の追加と更新機能
     * Adds or Updates a subservice
     *
     * @param controller &$controller
     * @param array $param
     * @return GSCODE
     */
    function AddUpdateSubService(&$controller, $param) {
    	//-- 会社データベースを設定する (Set the Company Database)
    	$controller->SubService->set_company_database($storeinfo['dbname'], $controller->SubService);

        //-- 店舗技術大分情報を準備する (prepare store service information)
        foreach ($param as $key => $val) {
            $controller->SubService->set($key, $val);
        }

        $controller->SubService->save();

        return $param['GSCODE'];
    }


    /**
     * その他技術大分の追加と更新機能
     * Adds or Updates other store service
     *
     * @param controller &$controller
     * @param array $param
     * @return GSCODE
     */
    function AddUpdateOtherStoreService(&$controller, $param) {
        //-- primaryKeyのGSCODEに変更する (Change primaryKey to GSCODE)
        $controller->StoreService->primaryKey = 'GSCODE';

        //-- 店舗技術大分情報を準備する (prepare store service information)
        unset($param['GCODE']);
        foreach ($param as $key => $val) {
            /*if ($key == 'SERVICE_TIME') {
            $controller->StoreService->set('SERVICETIME', $val);
            } elseif ($key == 'SERVICE_TIME_MALE') {
            $controller->StoreService->set('SERVICETIME_MALE', $val);
            } elseif ($key == 'WEB_DISPLAY') {*/
            //    $controller->StoreService->set('SHOWONCELLPHONE', $val);
            //} else {
            $controller->StoreService->set($key, $val);
            //}
        }

        $controller->StoreService->save();

        return $param['GSCODE'];
    }


    /**
     * 店他技術大分が使用中であるかどうかチェックします
     * Checks if store service is in use
     *
     * @param controller &$controller
     * @param array $param
     * @return boolean
     */
    function CheckStoreServiceInUse(&$controller, $gscode) {
    	$criteria = array('StoreService.GSCODE' => $gscode,
    	                  'StoreService.DELFLG IS NULL');

        $v = $controller->StoreService->find('all', array('conditions' => $criteria));

        if (!empty($v)) {
        	return true;
        } else {
            return false;
        }
    }


    /**
     * 店舗対してスタッフリストを作成する
     * Makes a list of staff assigned to the store
     *
     * @param controller &$controller
     * @param array $param
     * @return arrStaff
     */
    function SearchStaffAssignToStore(&$controller, $param) {

        //$criteria['StaffAssignToStore.ASSIGN_YOYAKU'] = 1;
        //$criteria['StaffAssignToStore.STORECODE'] = $param['storecode'];

        $startdate = $param['year'] . "-" . $param['month'] . "-" . "1";
        $enddate = $controller->enddate;

        //$level2aa['OR'] = array('Staff.HIREDATE IS NULL',
        //                        'Staff.HIREDATE <= ?' => array($enddate));
        //$level2ab['OR'] = array('Staff.RETIREDATE IS NULL',
        //                        'Staff.RETIREDATE >= ?' => array($enddate));

        //$level2a['AND'] = array($level2aa, $level2ab);
        //$level2b = array('Staff.HIREDATE BETWEEN ? AND ?' => array($startdate, $enddate));
        //$level2c = array('Staff.RETIREDATE BETWEEN ? AND ?' => array($startdate, $enddate));

        //$level['OR'] = array($level2a, $level2b, $level2c);

        //$and_condition = array($level);

        //$criteria_top = array($criteria,
        //                      $and_condition,
        //                      'StaffAssignToStore.STAFFCODE <> ?' => array(0),
        //                      'Staff.DELFLG IS NULL');

        //        //$orderby = array('StaffAssignToStore.STAFFCODE');
        //        //$orderby = array('IF(Staff.STAFFCODE = 0, 0, IFNULL(StaffAssignToStore.DISPLAY_ORDER, 9999999)),
        //        //                  Staff.STAFFCODE');

        //$orderby = array('Staff.DISPLAY_ORDER, Staff.STAFFCODE');

        $Sql = "SELECT StaffAssignToStore.STORECODE,
                       StaffAssignToStore.STAFFCODE,
                       StaffAssignToStore.KEYNO,
                       StaffAssignToStore.ASSIGN,
                       StaffAssignToStore.ASSIGN_YOYAKU,
                       StaffAssignToStore.WEBYAN_DISPLAY,
                       Staff.STAFFNAME,
                       Staff.HIREDATE,
                       Staff.RETIREDATE,
                       Staff.SALARYTYPE,
                       Staff.SALARYAMOUNT,
                       Staff.TRAVEL_ALLOWANCE,
                       Staff.DISPLAY_ORDER
                FROM staff_assign_to_store StaffAssignToStore
                       JOIN staff Staff
                           ON Staff.STAFFCODE = StaffAssignToStore.STAFFCODE
                                AND Staff.DELFLG IS NULL
                                AND (((Staff.HIREDATE IS NULL OR Staff.HIREDATE <= '".$enddate."')
                                AND (Staff.RETIREDATE IS NULL OR Staff.RETIREDATE >= '".$enddate."'))
                                OR Staff.HIREDATE BETWEEN '".$startdate."' AND '".$enddate."'
                                OR Staff.RETIREDATE BETWEEN '".$startdate."' AND '".$enddate."')
                WHERE StaffAssignToStore.STAFFCODE > 0
                        AND StaffAssignToStore.ASSIGN_YOYAKU = 1
                        AND StaffAssignToStore.STORECODE = ".$param['storecode']."
                ORDER BY Staff.DISPLAY_ORDER, Staff.STAFFCODE";

        $arrStaff = $controller->StaffAssignToStore->query($Sql);

        //$arrStaff = $controller->StaffAssignToStore->find('all', array('conditions' => $criteria_top,
        //                                                               'order'      => $orderby));

        for ($i = 0; $i < count($arrStaff); $i++) {
            //---------------------------------------------------------------------------------------
            $arrStaff[$i]['StaffAssignToStore']['STAFFNAME']  = $arrStaff[$i]['Staff']['STAFFNAME'];
            $arrStaff[$i]['StaffAssignToStore']['HIREDATE']   = $arrStaff[$i]['Staff']['HIREDATE'];
            $arrStaff[$i]['StaffAssignToStore']['RETIREDATE'] = $arrStaff[$i]['Staff']['RETIREDATE'];
            //---------------------------------------------------------------------------------------
            $arrStaff[$i]['StaffAssignToStore']['SALARYTYPE'] = $arrStaff[$i]['Staff']['SALARYTYPE'];
            $arrStaff[$i]['StaffAssignToStore']['SALARYAMOUNT'] = $arrStaff[$i]['Staff']['SALARYAMOUNT'];
            $arrStaff[$i]['StaffAssignToStore']['TRAVEL_ALLOWANCE'] = $arrStaff[$i]['Staff']['TRAVEL_ALLOWANCE'];
            //---------------------------------------------------------------------------------------
        }//end for

        return $arrStaff;
    }


    /**
     * スタッフシフトデータが存在確認機能
     * Checks if staff shift data exists
     *
     * @param controller &$controller
     * @param array $param
     * @return boolean
     */
    function CheckStaffShiftData(&$controller, $param) {

        $criteria = array('StaffShift.STORECODE' => $param['STORECODE'],
					      'StaffShift.STAFFCODE' => $param['STAFFCODE'],
					      'StaffShift.YMD'       => $controller->date,
                          'StaffShift.DELFLG IS NULL');

        $v = $controller->StaffShift->find('all', array('conditions' => $criteria));

        if (!empty($v)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * クライアントが予想する形式で,次回予約用にデータをアレンジします
     * Arranges the data in the format that the client expects
     *
     * @param controller &$controller
     * @param array $arrData
     * @param array $param
     * @return $arrList
     */
    function ParseJikaiYoyakuTransactionData(&$controller, $arrData, $param) {
        //------------------------------------------------------------------------------------------------------
        $ctr       = -1;
        $transcode = "";
        //$tempstatus = array(0, 1, 2);
        //$arrStaff = array();
        //------------------------------------------------------------------------------------------------------
        $detail_staff = -1;
        //------------------------------------------------------------------------------------------------------
        $is_same_staff = false;
        for ($i = 0; $i < count($arrData); $i++) {
            //print_r($arrData[$i]); die();
            $flagcond = false;
            if ((int)$arrData[$i]['tbld']['min_staffcode'] === (int)$arrData[$i]['tbld']['max_staffcode']) {
                $flagcond = ($transcode !== $arrData[$i]['transaction']['TRANSCODE']);
                $is_same_staff = true;
            } else {
                $flagcond = ($detail_staff !== (int)$arrData[$i]['details']['STAFFCODE']);
                $is_same_staff = false;
            }//end if else
            //$flagcond = ($detail_staff !== (int)$arrData[$i]['details']['STAFFCODE']);
            //if ($transcode !== $arrData[$i]['transaction']['TRANSCODE']) {
            //if ($detail_staff !== (int)$arrData[$i]['details']['STAFFCODE']) {

            if ($flagcond) {
                $ctr++;
                $dtl = 0;
                $transcode = $arrData[$i]['transaction']['TRANSCODE'];
                $detail_staff = $arrData[$i]['details']['STAFFCODE'];

                $arrList[$ctr]['TRANSCODE']   = $arrData[$i]['transaction']['TRANSCODE'];
                $arrList[$ctr]['KEYNO']       = $arrData[$i]['transaction']['KEYNO'];
                $arrList[$ctr]['STORECODE']   = $arrData[$i]['transaction']['STORECODE'];
                $arrList[$ctr]['IDNO']        = $arrData[$i]['transaction']['IDNO'];
                $arrList[$ctr]['TRANSDATE']   = $arrData[$i]['transaction']['TRANSDATE'];
                $arrList[$ctr]['STARTTIME'] = substr($arrData[$i]['transaction']['STARTTIME'], 0, 5);

                $arrList[$ctr]['ENDTIME']     = substr($arrData[$i]['transaction']['ENDTIME'], 0, 5);

                $arrList[$ctr]['CCODE']       = $arrData[$i]['transaction']['CCODE'];
                $arrList[$ctr]['CNUMBER']     = $arrData[$i]['customer']['CNUMBER'];
                $arrList[$ctr]['CSTORECODE']  = $arrData[$i]['customer']['CSTORECODE'];
                $arrList[$ctr]['TEMPSTATUS']  = $arrData[$i]['transaction']['TEMPSTATUS'];
                $arrList[$ctr]['KYAKUKUBUN']  = $arrData[$i]['transaction']['KYAKUKUBUN'];
                $arrList[$ctr]['REGULARCUSTOMER'] = $arrData[$i]['transaction']['REGULARCUSTOMER'];
                if (substr($arrData[$i]['transaction']['CCODE'],3) == "0000000"){
                    $arrList[$ctr]['CNAME']   = $arrData[$i]['transaction']['CNAME'];
                    $arrList[$ctr]['SEX']     = $arrData[$i]['transaction']['SEX'];
                } else {
                    $arrList[$ctr]['CNAME']   = $arrData[$i]['customer']['CNAME'];
                    $arrList[$ctr]['SEX']     = $arrData[$i]['customer']['SEX'];
                }
                $arrList[$ctr]['ZEIOPTION']   = $arrData[$i]['transaction']['ZEIOPTION'];
                $arrList[$ctr]['RATETAX']     = $arrData[$i]['transaction']['RATETAX'];
                $arrList[$ctr]['SOGOKEIOPTION'] = $arrData[$i]['transaction']['SOGOKEIOPTION'];
                $arrList[$ctr]['APT_COLOR']   = $arrData[$i]['transaction']['APT_COLOR'];
                $arrList[$ctr]['NOTES']       = $arrData[$i]['transaction']['NOTES'];
                //$arrList[$ctr]['STAFFCODE']   = $arrData[$i]['transaction']['STAFFCODE'];
                $arrList[$ctr]['STAFFCODE']   = $arrData[$i]['details']['STAFFCODE'];
                $arrList[$ctr]['CNAMEKANA']   = $arrData[$i]['customer']['CNAMEKANA'];
                $arrList[$ctr]['TEL1']        = $arrData[$i]['customer']['TEL1'];
                $arrList[$ctr]['TEL2']        = $arrData[$i]['customer']['TEL2'];
                $arrList[$ctr]['BIRTHDATE']   = $arrData[$i]['customer']['BIRTHDATE'];
                $arrList[$ctr]['MEMBERSCATEGORY'] = $arrData[$i]['customer']['MEMBERSCATEGORY'];
                $arrList[$ctr]['CLAIMKYAKUFLG']   = $arrData[$i]['transaction']['CLAIMKYAKUFLG'];
                $arrList[$ctr]['UPDATEDATE']  = $arrData[$i]['transaction']['UPDATEDATE'];
                $arrList[$ctr]['PRIORITY']    = $arrData[$i]['transaction']['PRIORITY'];
                $arrList[$ctr]['YOYAKU']    = $arrData[$i]['transaction']['YOYAKU'];
                $arrList[$ctr]['HOWKNOWSCODE']    = $arrData[$i]['howknows_thestore']['HOWKNOWSCODE'];
                $arrList[$ctr]['HOWKNOWS']    = $arrData[$i]['howknows_thestore']['HOWKNOWS'];

                /////////////////////////////////////////////////////////////////////
                $arrList[$ctr]['YOYAKUTIME'] = substr($arrData[$i]['details']['STARTTIME'], 0, 5); //substr($arrData[$i]['transaction']['STARTTIME'], 0, 5);

                //-- ADJUSTED_ENDTIMEで値の用意をします (Sets the value for ADJUSTED_ENDTIME)
                $starttime = $arrList[$ctr]['YOYAKUTIME'];
                $endtime = "";

                //====================================================================================
                //Use Transaction EndTime for SameStaff & Details EndTime for Not SameStaff
                //------------------------------------------------------------------------------------
                if ($is_same_staff) {
                    //--------------------------------------------------------------------------------
                    $endtime   = substr($arrData[$i]['transaction']['ENDTIME'],0,5);
                    //--------------------------------------------------------------------------------
                }else {
                    //--------------------------------------------------------------------------------
                    $endtime   = substr($arrData[$i]['details']['ENDTIME'], 0, 5);
                    //--------------------------------------------------------------------------------
                }//End if ($is_same_staff) Else
                //------------------------------------------------------------------------------------
                //$endtime = substr($arrData[$i]['transaction']['ENDTIME'],0,5);
                //====================================================================================

                $a1 = explode(":",$starttime);
                $a2 = explode(":",$endtime);
                $time1 = (($a1[0]*60*60)+($a1[1]*60));
                $time2 = (($a2[0]*60*60)+($a2[1]*60));
                $diff = abs($time1-$time2);
                $mins = floor(($diff-($hours*60*60))/(60));
                $mins_diff = $mins;

                if ($mins_diff <= MIN_SERVICE_TIME) {
                    $arrEnd = explode(":",$starttime);
                    $arrEnd[1] += MIN_SERVICE_TIME;

                    if ($arrEnd[1] >= 60) {
                        $arrEnd[1] = fmod($arrEnd[1], 60);
                        $arrEnd[0]++;
                    }

                    $arrList[$ctr]['ADJUSTED_ENDTIME'] = date("G:i", mktime($arrEnd[0], $arrEnd[1], 0, 1, 1, 2010));
                } else {
                    //===============================================================================================
                    //Use Transaction EndTime for SameStaff & Details EndTime for Not SameStaff
                    //-----------------------------------------------------------------------------------------------
                    if ($is_same_staff) {
                        //-------------------------------------------------------------------------------------------
                        $arrList[$ctr]['ADJUSTED_ENDTIME']   = substr($arrData[$i]['transaction']['ENDTIME'],0,5);
                        //-------------------------------------------------------------------------------------------
                    }else {
                        //-------------------------------------------------------------------------------------------
                        $arrList[$ctr]['ADJUSTED_ENDTIME']   = substr($arrData[$i]['details']['ENDTIME'], 0, 5);
                        //-------------------------------------------------------------------------------------------
                    }//End if ($is_same_staff) Else
                    //-----------------------------------------------------------------------------------------------
                    //$arrList[$ctr]['ADJUSTED_ENDTIME'] = substr($arrData[$i]['transaction']['ENDTIME'],0,5);
                    //===============================================================================================
                }

                $starttime = $arrList[$ctr]['YOYAKUTIME'];
                $starttime_c = strtotime($starttime);
                //$endtime = $arrList[$ctr]['ENDTIME'];
                //$endtime = $arrList[$ctr]['ADJUSTED_ENDTIME'];
                if ($arrList[$ctr]['ADJUSTED_ENDTIME'] < OVER_MAXTIME) {
                    $endtime = MAXTIME;
                } else {
                    $endtime = $arrList[$ctr]['ADJUSTED_ENDTIME'];
                }
                $endtime_c = strtotime($endtime);

                $staffcode = $arrList[$ctr]['STAFFCODE'];
                $priority = 1;
                $prioritytype = $arrData[$i]['transaction']['PRIORITYTYPE'];

                //-- 最低のサービス時間を置く[900秒 OR 15分] (Sets minimum service time [900 seconds OR 15 minutes])
                if (($endtime_c - $starttime_c) < MIN_SERVICE_TIME) {
                    $endtime_c = $starttime_c + MIN_SERVICE_TIME;
                }

                $arrList[$ctr]['UKETSUKEDATE'] = @$arrData[$i]['yoyaku']['UKETSUKEDATE'];
                $arrList[$ctr]['UKETSUKESTAFF'] = @$arrData[$i]['yoyaku']['UKETSUKESTAFF'];
                $arrList[$ctr]['CANCEL'] = @$arrData[$i]['yoyaku']['CANCEL'];
                $arrList[$ctr]['BEFORE_TRANSCODE'] = @$arrData[$i]['jikaiyoyaku']['TRANSCODE'];

                $position_confirmed = false;
                while (!$position_confirmed) {
                    $position_confirmed = true;
                    foreach ($checked_times[$staffcode][$prioritytype][$priority] as $entry) {
                        if (($starttime_c  > $entry["starttime"] && $starttime_c <  $entry["endtime"]) ||
	                        ($endtime_c    > $entry["starttime"] && $endtime_c   <  $entry["endtime"]) ||
	                        ($starttime_c <= $entry["starttime"] && $endtime_c   >= $entry["endtime"]) &&
	                         $arrData[$i]['transaction']['PRIORITYTYPE'] == $entry["prioritytype"] ) {
	                        $position_confirmed = false;
	                        $priority++;
	                        break;
                        }
                    }
                }

                $checked_times[$staffcode][$prioritytype][$priority][] = array("starttime" => $starttime_c,
                                                                "endtime"   => $endtime_c,
                                                                "prioritytype" => $arrData[$i]['transaction']['PRIORITYTYPE']);

                if (intval($checked_priority[$staffcode]) == 0 || $priority > $checked_priority[$staffcode])  {
                    $checked_priority[$staffcode] = $priority;
                }

                $arrList[$ctr]['PRIORITYTYPE'] = $arrData[$i]['transaction']['PRIORITYTYPE'] . "-" .$priority;

                //--------------------------------------------------------------------------------------------
                //SET EACH TRANSACTION START TIME AND END TIME SAME AS DETAILS PER TRANSACTION
                //--------------------------------------------------------------------------------------------
                $arrList[$ctr]['STIME'] = $arrData[$i]['details']['STARTTIME'];
                $arrList[$ctr]['ETIME'] = $arrData[$i]['details']['ENDTIME'];
                //--------------------------------------------------------------------------------------------
                $arrList[$ctr]['GCODE'] = $arrData[$i]['details']['GCODE'];
                //--------------------------------------------------------------------------------------------
            }// end if ($detail_staff !== $arrData[$i]['details']['STAFFCODE'])
            //-------------------------------------------------------------------------
            //if use tantou service time or get service time from store services
            //-------------------------------------------------------------------------
            $use_tantou_service_time = 0;
            //-------------------------------------------------------------------------
            $Sql = "SELECT optionvaluei
                    FROM store_settings
                    WHERE optionname = 'YOYAKU_MENU_TANTOU'";
            $data_serv_time = $controller->StoreTransaction->query($Sql);
            if (count($data_serv_time) > 0) {
                $use_tantou_service_time = (int)$data_serv_time[0]['store_settings']['optionvaluei'];
            }//end if
            unset($data_serv_time);
            //-------------------------------------------------------------------------
            $rs_tantou_service_time = null;
            $DEFAULT_MINUTES = 15;
            //-------------------------------------------------------------------------
            if ($use_tantou_service_time) {
                $Sql = "SELECT staffcode, gcode, service_time, service_time_male
                        FROM yoyaku_staff_service_time rs
                        WHERE storecode = ".$param['STORECODE'];
                $rs_tantou_service_time = $controller->StoreTransaction->query($Sql);
            }//end if
            //-------------------------------------------------------------------------
            $tmpYoyakuTime = $arrList[$ctr]['YOYAKUTIME'];
            $tmpEndTIme = $arrList[$ctr]['ENDTIME'];
            //-------------------------------------------------------------------------
            if (!$is_same_staff) {
                $arrList[$ctr]['YOYAKUTIME'] = substr($arrList[$ctr]['STIME'], 0, 5);
                $arrList[$ctr]['ENDTIME'] = substr($arrList[$ctr]['ETIME'], 0, 5);
                $arrList[$ctr]['ADJUSTED_ENDTIME'] = substr($arrList[$ctr]['ETIME'], 0, 5);
            }//end if
            //-------------------------------------------------------------------------
            $arrList[$ctr]['STIME'] = $tmpYoyakuTime;
            $arrList[$ctr]['ETIME'] = $tmpEndTIme;
            //-------------------------------------------------------------------------
            $dtl = 0;
            foreach ($arrData as $transd_data) {
                //---------------------------------------------------------------------------------------------------
                if ($transd_data['transaction']['TRANSCODE'] === $transcode) {
                    //-----------------------------------------------------------------------------------------------
                    $arrList[$ctr]['details'][$dtl]['ROWNO']          = $transd_data['details']['ROWNO'];
                    $arrList[$ctr]['details'][$dtl]['GDCODE']         = $transd_data['services']['GDCODE'];
                    $arrList[$ctr]['details'][$dtl]['BUNRUINAME']     = $transd_data['services']['BUNRUINAME'];
                    $arrList[$ctr]['details'][$dtl]['GCODE']          = $transd_data['details']['GCODE'];
                    //-----------------------------------------------------------------------------------------------
                    if ((int)$arrData[$i]['details']['TRANTYPE'] === 1) {
                        $arrList[$ctr]['details'][$dtl]['MENUNAME']   = $transd_data['service']['MENUNAME'];
                        $arrList[$ctr]['details'][$dtl]['YOYAKUMARK'] = $transd_data['service']['YOYAKUMARK'];
                    } else {
                        $arrList[$ctr]['details'][$dtl]['MENUNAME']   = $transd_data['product']['PRODUCTNAME'];
                    }//end if else
                    //-----------------------------------------------------------------------------------------------
                    //set default minutes (staff tantou service time or store_services table service time
                    //-----------------------------------------------------------------------------------------------
                    if ($use_tantou_service_time) {
                        //-------------------------------------------------------------------------------------------
                        if (count($rs_tantou_service_time) > 0) {
                            //---------------------------------------------------------------------------------------
                            foreach ($rs_tantou_service_time as $data_tantou) {
                                //-----------------------------------------------------------------------------------
                                if ((int)$data_tantou['rs']['staffcode'] === (int)$transd_data['details']['STAFFCODE']
                                        && (int)$data_tantou['rs']['gcode'] === (int)$transd_data['details']['GCODE']) {
                                    //-------------------------------------------------------------------------------
                                    if ((int)$transd_data['customer']['SEX'] === 1) {
                                        //---------------------------------------------------------------------------
                                        if ((int)$data_tantou['rs']['service_time_male'] < $DEFAULT_MINUTES) {
                                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $DEFAULT_MINUTES;
                                        } else {
                                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $data_tantou['rs']['service_time_male'];
                                        }//end if else
                                        //---------------------------------------------------------------------------
                                    } else {
                                        //---------------------------------------------------------------------------
                                        if ((int)$data_tantou['rs']['service_time'] <  $DEFAULT_MINUTES) {
                                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $DEFAULT_MINUTES;
                                        } else {
                                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $data_tantou['rs']['service_time'];
                                        }//end if
                                        //---------------------------------------------------------------------------
                                    }//end if else
                                    //-------------------------------------------------------------------------------
                                    break; //exit for
                                    //-------------------------------------------------------------------------------
                                }//end if
                                //-----------------------------------------------------------------------------------
                            }//end foreach
                            //---------------------------------------------------------------------------------------
                        } else {
                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $DEFAULT_MINUTES;
                        }//end if else
                        //-------------------------------------------------------------------------------------------
                    } else {
                        //-------------------------------------------------------------------------------------------
                        if ((int)$transd_data['customer']['SEX'] === 1) {
                            $arrList[$ctr]['details'][$dtl]['MENUTIME']   = $transd_data['service']['SERVICETIME_MALE'];
                        } else {
                            $arrList[$ctr]['details'][$dtl]['MENUTIME']   = $transd_data['service']['SERVICETIME'];
                        }//end if else
                        //-------------------------------------------------------------------------------------------
                    }//end if else
                    //$arrList[$ctr]['details'][$dtl]['MENUTIME']   = 60;
                    //-----------------------------------------------------------------------------------------------
                    $arrList[$ctr]['details'][$dtl]['STAFFCODE']      = $transd_data['details']['STAFFCODE'];
                    $arrList[$ctr]['details'][$dtl]['STAFFNAME']      = $transd_data['staff']['STAFFNAME'];
                    $arrList[$ctr]['details'][$dtl]['STAFFCODESIMEI'] = $transd_data['details']['STAFFCODESIMEI'];
                    $arrList[$ctr]['details'][$dtl]['ZEIKUBUN']       = $transd_data['details']['ZEIKUBUN'];
                    $arrList[$ctr]['details'][$dtl]['PRICE']          = $transd_data['details']['UNITPRICE'] + $transd_data['details']['TAX'];
                    $arrList[$ctr]['details'][$dtl]['CLAIMED']        = $transd_data['details']['CLAIMED'];
                    $arrList[$ctr]['details'][$dtl]['POINTKASAN1']    = $transd_data['details']['KASANPOINT1'];
                    $arrList[$ctr]['details'][$dtl]['POINTKASAN2']    = $transd_data['details']['KASANPOINT2'];
                    $arrList[$ctr]['details'][$dtl]['POINTKASAN3']    = $transd_data['details']['KASANPOINT3'];
                    $arrList[$ctr]['details'][$dtl]['TRANTYPE']       = $transd_data['details']['TRANTYPE'];
                    $arrList[$ctr]['details'][$dtl]['KEYCODE']        = $transd_data['service']['KEYCODE'];
                    //--------------------------------------------------------------------------------------------
                    //temporary added start end time - not yet used
                    //--------------------------------------------------------------------------------------------
                    $arrList[$ctr]['details'][$dtl]['TRANSCODE']        = $transd_data['details']['TRANSCODE'];
                    $arrList[$ctr]['details'][$dtl]['STARTTIME']        = $transd_data['details']['STARTTIME'];
                    $arrList[$ctr]['details'][$dtl]['ENDTIME']          = $transd_data['details']['ENDTIME'];
                    //--------------------------------------------------------------------------------------------
                    $arrList[$ctr]['details'][$dtl]['SYSCODE']          = $transd_data['services']['SYSCODE'];
                    $dtl++;
                    //--------------------------------------------------------------------------------------------
                }//end if
            }//end for

            #-----------------------------------------------------------------------------------------------------
            #Added by MarvinC - 2015-06-18
            #-----------------------------------------------------------------------------------------------------
            $arrList[$ctr]['SERVICESNAME'] .= $arrData[$i]['servicessys']['servicesname'] . ",";
            #-----------------------------------------------------------------------------------------------------
        }//end for

        //        #-----------------------------------------------------------------------------------------------------
        //        #Added by MarvinC - 2015-06-18
        //        #-----------------------------------------------------------------------------------------------------
        //        $arrList[$ctr]['SERVICESNAME'] = rtrim($servicesname,",");
        //        $servicesname = "";
        //        #-----------------------------------------------------------------------------------------------------
        $arrList[0]['checked_times'] = $checked_times;
        //-------------------------------------------------------------------------------------------------------
        return $arrList;
        //-------------------------------------------------------------------------------------------------------
    }//end function


    /**
     * クライアントが予想する形式でデータをアレンジします
     * Arranges the data in the format that the client expects
     *
     * @param controller &$controller
     * @param array $arrData
     * @param array $param
     * @return $arrList
     */
    function ParseTransactionData(&$controller, $arrData, $param) {
        function first_transaction_sort($prev, $next) {
            if ($prev['details']['STAFFCODE'] === $next['details']['STAFFCODE']) {
                if ($prev['transaction']['PRIORITYTYPE'] === $next['transaction']['PRIORITYTYPE']) {
                    if ($prev['transaction']['TRANSCODE'] === $next['transaction']['TRANSCODE']) {
                        if ($prev['details']['STARTTIME'] === $next['details']['STARTTIME']) {
                            return 0;
                        } else {
                            return $prev['details']['STARTTIME'] < $next['details']['STARTTIME'] ? -1 : 1;
                        }
                    } else {
                        return $prev['transaction']['TRANSCODE'] < $next['transaction']['TRANSCODE'] ? -1 : 1;
                    }
                } else {
                    return $prev['transaction']['PRIORITYTYPE'] - $next['transaction']['PRIORITYTYPE'];
                }
            } else {
                return $prev['details']['STAFFCODE'] - $next['details']['STAFFCODE'];
            }
        }

        function second_transaction_sort($prev, $next) {
            if ($prev['details']['STAFFCODE'] === $next['details']['STAFFCODE']) {
                if ($prev['transaction']['PRIORITYTYPE'] === $next['transaction']['PRIORITYTYPE']) {
                    if ($prev['transaction']['YOYAKUTIME'] === $next['transaction']['YOYAKUTIME']) {
                        if ($prev['transaction']['ADJUSTED_ENDTIME'] === $next['transaction']['ADJUSTED_ENDTIME']) {
                            if ($prev['transaction']['TRANSCODE'] === $next['transaction']['TRANSCODE']) {
                                return 0;
                            } else {
                                return $prev['transaction']['TRANSCODE'] < $next['transaction']['TRANSCODE'] ? -1 : 1;
                            }
                        } else {
                            return $prev['transaction']['ADJUSTED_ENDTIME'] > $next['transaction']['ADJUSTED_ENDTIME'] ? -1 : 1;
                        }
                    } else {
                        return $prev['transaction']['YOYAKUTIME'] < $next['transaction']['YOYAKUTIME'] ? -1 : 1;
                    }
                } else {
                    return $prev['transaction']['PRIORITYTYPE'] - $next['transaction']['PRIORITYTYPE'];
                }
            } else {
                return $prev['details']['STAFFCODE'] < $next['details']['STAFFCODE'] ? -1 : 1;
            }
        }

        // スタッフ、予約行・来店行、伝票番号、開始時刻でソート
        usort($arrData, 'first_transaction_sort');

        $mergedArrData = array();

        // 連続するメニューを統合する
        foreach ($arrData as $current) {
            $last_index = count($mergedArrData) - 1;
            $last = $last_index > 0 ? $mergedArrData[$last_index] : null;

            if (
                $last &&
                $last['details']['STAFFCODE'] === $current['details']['STAFFCODE'] &&
                $last['transaction']['TRANSCODE'] === $current['transaction']['TRANSCODE'] &&
                $last['transaction']['ADJUSTED_ENDTIME'] >= $current['details']['STARTTIME']
            ) {
                // 同一の予約、および時間が連続している場合
                $mergedArrData[$last_index]['transaction']['ADJUSTED_ENDTIME'] = $current['details']['ENDTIME'];
            } else {
                $current['transaction']['YOYAKUTIME'] = $current['details']['STARTTIME'];
                $current['transaction']['ADJUSTED_ENDTIME'] = $current['details']['ENDTIME'];
                $mergedArrData[] = $current;
            }
        }

        $arrData = $mergedArrData;

        // スタッフ、予約行・来店行、開始時刻、終了時刻、伝票番号でソート
        usort($arrData, 'second_transaction_sort');

        $assinged_start_index = 0;

        // PRIORITYを設定する
        foreach ($arrData as $i => $current) {
            $arrData[$i]['transaction']['PRIORITY'] = '1';

            if (
                $i === 0 ||
                $current['details']['STAFFCODE'] !== $arrData[$i - 1]['details']['STAFFCODE'] ||
                $current['transaction']['PRIORITYTYPE'] !== $arrData[$i - 1]['transaction']['PRIORITYTYPE']
            ) {
                // 初回ループ、スタッフが変化、または予約行・来店行が変化した場合
                $assinged_start_index = $i;
            }

            $conflicts = array();

            for ($j = $assinged_start_index; $j < $i; $j++) {
                $assinged = $arrData[$j];

                if (
                    $current['transaction']['YOYAKUTIME'] < $assinged['transaction']['ADJUSTED_ENDTIME'] &&
                    $current['transaction']['ADJUSTED_ENDTIME'] > $assinged['transaction']['YOYAKUTIME']
                ) {
                    // 時刻が衝突している場合
                    $conflicts[] = +$assinged['transaction']['PRIORITY'];
                }
            }

            for ($j = 1; $j <= max($conflicts) + 1; $j++) {
                if (!in_array($j, $conflicts)) {
                    $arrData[$i]['transaction']['PRIORITY'] = $j + '';
                    break;
                }
            }
        }

        //------------------------------------------------------------------------------------------------------
        $ctr       = -1;
        for ($i = 0; $i < count($arrData); $i++) {
            $ctr++;
            $transcode = $arrData[$i]['transaction']['TRANSCODE'];

            $arrList[$ctr]['TRANSCODE']   = $arrData[$i]['transaction']['TRANSCODE'];
            $arrList[$ctr]['KEYNO']       = $arrData[$i]['transaction']['KEYNO'];
            $arrList[$ctr]['STORECODE']   = $arrData[$i]['transaction']['STORECODE'];
            $arrList[$ctr]['IDNO']        = $arrData[$i]['transaction']['IDNO'];
            $arrList[$ctr]['TRANSDATE']   = $arrData[$i]['transaction']['TRANSDATE'];
            $arrList[$ctr]['STARTTIME']   = substr($arrData[$i]['transaction']['STARTTIME'], 0, 5);
            $arrList[$ctr]['ENDTIME']     = substr($arrData[$i]['transaction']['ENDTIME'], 0, 5);
            $arrList[$ctr]['CCODE']       = $arrData[$i]['transaction']['CCODE'];
            $arrList[$ctr]['INCOMPLETE']  = $arrData[$i]['transaction']['INCOMPLETE'];
            $arrList[$ctr]['CNUMBER']     = $arrData[$i]['customer']['CNUMBER'];
            $arrList[$ctr]['CSTORECODE']  = $arrData[$i]['customer']['CSTORECODE'];
            $arrList[$ctr]['TEMPSTATUS']  = $arrData[$i]['transaction']['TEMPSTATUS'];
            $arrList[$ctr]['KYAKUKUBUN']  = $arrData[$i]['transaction']['KYAKUKUBUN'];
            $arrList[$ctr]['REGULARCUSTOMER'] = $arrData[$i]['transaction']['REGULARCUSTOMER'];
            if (substr($arrData[$i]['transaction']['CCODE'],3) == "0000000"){
                $arrList[$ctr]['CNAME']   = $arrData[$i]['transaction']['CNAME'];
                $arrList[$ctr]['SEX']     = $arrData[$i]['transaction']['SEX'];
            } else {
                $arrList[$ctr]['CNAME']   = $arrData[$i]['customer']['CNAME'];
                $arrList[$ctr]['SEX']     = $arrData[$i]['customer']['SEX'];
            }
            $arrList[$ctr]['ZEIOPTION']   = $arrData[$i]['transaction']['ZEIOPTION'];
            $arrList[$ctr]['RATETAX']     = $arrData[$i]['transaction']['RATETAX'];
            $arrList[$ctr]['SOGOKEIOPTION'] = $arrData[$i]['transaction']['SOGOKEIOPTION'];
            $arrList[$ctr]['APT_COLOR']   = $arrData[$i]['transaction']['APT_COLOR'];
            $arrList[$ctr]['NOTES']       = $arrData[$i]['transaction']['NOTES'];
            //$arrList[$ctr]['STAFFCODE']   = $arrData[$i]['transaction']['STAFFCODE'];
            $arrList[$ctr]['STAFFCODE']   = $arrData[$i]['details']['STAFFCODE'];
            $arrList[$ctr]['CNAMEKANA']   = $arrData[$i]['customer']['CNAMEKANA'];
            $arrList[$ctr]['TEL1']        = $arrData[$i]['customer']['TEL1'];
            $arrList[$ctr]['TEL2']        = $arrData[$i]['customer']['TEL2'];
            $arrList[$ctr]['BIRTHDATE']   = $arrData[$i]['customer']['BIRTHDATE'];
            $arrList[$ctr]['MEMBERSCATEGORY'] = $arrData[$i]['customer']['MEMBERSCATEGORY'];
            $arrList[$ctr]['CLAIMKYAKUFLG']   = $arrData[$i]['transaction']['CLAIMKYAKUFLG'];
            $arrList[$ctr]['UPDATEDATE']  = $arrData[$i]['transaction']['UPDATEDATE'];
            $arrList[$ctr]['PRIORITY']    = $arrData[$i]['transaction']['PRIORITY'];
            $arrList[$ctr]['YOYAKU']    = $arrData[$i]['transaction']['YOYAKU'];
            $arrList[$ctr]['HOWKNOWSCODE']    = $arrData[$i]['howknows_thestore']['HOWKNOWSCODE'];
            $arrList[$ctr]['HOWKNOWS']    = $arrData[$i]['howknows_thestore']['HOWKNOWS'];
            #------------------------------------------------------------------------------------------------------------------------
            # ADDED BY MARVINC - 2015-06-22
            # For Updating Next Reservation
            #------------------------------------------------------------------------------------------------------------------------
            $arrList[$ctr]['YOYAKU_STATUS'] = $arrData[$i]['YND']['YOYAKU_STATUS'];
            #------------------------------------------------------------------------------------------------------------------------

            /*----------------------------------------------------------------------------------------------------------------------*/
            /*add by albert 2015-10-30 BM connection information -------------------------------------------------------------------*/
            /*----------------------------------------------------------------------------------------------------------------------*/
            $arrList[$ctr]['route']                 = $arrData[$i]['bmtble']['route'];
            $arrList[$ctr]['reservation_system']    = $arrData[$i]['bmtble']['reservation_system'];
            $arrList[$ctr]['reserve_date']          = $arrData[$i]['bmtble']['reserve_date'];
            $arrList[$ctr]['reserve_code']          = $arrData[$i]['bmtble']['reserve_code'];
            $arrList[$ctr]['v_date']                = $arrData[$i]['bmtble']['v_date'];
            $arrList[$ctr]['start_time']            = $arrData[$i]['bmtble']['start_time'];
            $arrList[$ctr]['end_time']              = $arrData[$i]['bmtble']['end_time'];
            $arrList[$ctr]['coupon_info']           = $arrData[$i]['bmtble']['coupon_info'];
            $arrList[$ctr]['comment']               = $arrData[$i]['bmtble']['comment'];
            $arrList[$ctr]['shop_comment']          = $arrData[$i]['bmtble']['shop_comment'];
            $arrList[$ctr]['next_coming_comment']   = $arrData[$i]['bmtble']['next_coming_comment'];
            $arrList[$ctr]['demand']                = $arrData[$i]['bmtble']['demand'];
            $arrList[$ctr]['site_customer_id']      = $arrData[$i]['bmtble']['site_customer_id'];
            $arrList[$ctr]['bmPrice']               = $arrData[$i]['bmtble']['bmPrice'];
            $arrList[$ctr]['nomination_fee']        = $arrData[$i]['bmtble']['nomination_fee'];
            $arrList[$ctr]['bmTprice']              = $arrData[$i]['bmtble']['bmTprice'];
            $arrList[$ctr]['use_point']             = $arrData[$i]['bmtble']['use_point'];
            $arrList[$ctr]['grant_point']           = $arrData[$i]['bmtble']['grant_point'];
            $arrList[$ctr]['visit_num']             = $arrData[$i]['bmtble']['visit_num'];
            $arrList[$ctr]['firstname']             = $arrData[$i]['bmtble']['firstname'];
            $arrList[$ctr]['lastname']              = $arrData[$i]['bmtble']['lastname'];
            $arrList[$ctr]['bmsex']                 = $arrData[$i]['bmtble']['bmsex'];
            $arrList[$ctr]['knfirstname']           = $arrData[$i]['bmtble']['knfirstname'];
            $arrList[$ctr]['knlastname']            = $arrData[$i]['bmtble']['knlastname'];
            $arrList[$ctr]['bmtel']                 = $arrData[$i]['bmtble']['bmtel'];
            $arrList[$ctr]['bmzip']                 = $arrData[$i]['bmtble']['bmzip'];
            $arrList[$ctr]['bmaddress']             = $arrData[$i]['bmtble']['bmaddress'];
            $arrList[$ctr]['bmmail']                = $arrData[$i]['bmtble']['bmmail'];
            $arrList[$ctr]['menu_info']             = $arrData[$i]['bmtble']['menu_info'];
            $arrList[$ctr]['origination']           = $arrData[$i]['transaction']['origination'];
            $arrList[$ctr]['bmstaff']               = $arrData[$i]['bmtble']['bmstaff'];
            $arrList[$ctr]['secondnote']            = $arrData[$i]['str_bm_notes']['secondnote'];
            /*----------------------------------------------------------------------------------------------------------------------*/
            /*add by albert 2015-10-30 BM connection information -------------------------------------------------------------------*/
            /*----------------------------------------------------------------------------------------------------------------------*/

            /////////////////////////////////////////////////////////////////////
            $arrList[$ctr]['YOYAKUTIME'] = substr($arrData[$i]['details']['STARTTIME'], 0, 5); //substr($arrData[$i]['transaction']['STARTTIME'], 0, 5);
            $arrList[$ctr]['ADJUSTED_ENDTIME'] = substr($arrData[$i]['transaction']['ADJUSTED_ENDTIME'], 0, 5);
            $arrList[$ctr]['UKETSUKEDATE'] = @$arrData[$i]['yoyaku']['UKETSUKEDATE'];
            $arrList[$ctr]['UKETSUKESTAFF'] = @$arrData[$i]['yoyaku']['UKETSUKESTAFF'];
            $arrList[$ctr]['UKETSUKESTAFFNAME'] = @$arrData[$i]['staff2']['UKETSUKESTAFFNAME'];
            $arrList[$ctr]['CANCEL'] = @$arrData[$i]['yoyaku']['CANCEL'];

            #---------------------------------------------------------------------------------------------
            #Added by MarvinC - 2015-07-01
            #---------------------------------------------------------------------------------------------
            if($arrData[$i]['YND']['YOYAKU_STATUS'] == 2){
                $arrList[$ctr]['BEFORE_TRANSCODE'] = @$arrData[$i]['YND']['NEXTCODE'];
            }else{
                $arrList[$ctr]['BEFORE_TRANSCODE'] = @$arrData[$i]['jikaiyoyaku']['TRANSCODE'];
            }

            $arrList[$ctr]['PRIORITYTYPE'] = $arrData[$i]['transaction']['PRIORITYTYPE'] . "-" . $arrData[$i]['transaction']['PRIORITY'];

            //--------------------------------------------------------------------------------------------
            //SET EACH TRANSACTION START TIME AND END TIME SAME AS DETAILS PER TRANSACTION
            //--------------------------------------------------------------------------------------------
            $arrList[$ctr]['STIME'] = $arrData[$i]['details']['STARTTIME'];
            $arrList[$ctr]['ETIME'] = $arrData[$i]['details']['ENDTIME'];
            //--------------------------------------------------------------------------------------------
            $arrList[$ctr]['GCODE'] = $arrData[$i]['details']['GCODE'];
            //-------------------------------------------------------------------------
            //if use tantou service time or get service time from store services
            //-------------------------------------------------------------------------
            $use_tantou_service_time = 0;
            //-------------------------------------------------------------------------
            $Sql = "SELECT optionvaluei
                    FROM store_settings
                    WHERE optionname = 'YOYAKU_MENU_TANTOU'";
            $data_serv_time = $controller->StoreTransaction->query($Sql);
            if (count($data_serv_time) > 0) {
                $use_tantou_service_time = (int)$data_serv_time[0]['store_settings']['optionvaluei'];
            }//end if
            unset($data_serv_time);
            //-------------------------------------------------------------------------
            $rs_tantou_service_time = null;
            $DEFAULT_MINUTES = 15;
            //-------------------------------------------------------------------------
            if ($use_tantou_service_time) {
                $Sql = "SELECT staffcode, gcode, service_time, service_time_male
                        FROM yoyaku_staff_service_time rs
                        WHERE storecode = ".$param['STORECODE'];
                $rs_tantou_service_time = $controller->StoreTransaction->query($Sql);
            }//end if
            //-------------------------------------------------------------------------
            $arrList[$ctr]['STIME'] = $arrList[$ctr]['YOYAKUTIME'];
            $arrList[$ctr]['ETIME'] = $arrList[$ctr]['ENDTIME'];
            //-------------------------------------------------------------------------
            $dtl = 0;
            foreach ($arrData as $transd_data) {
                //---------------------------------------------------------------------------------------------------
                if ($transd_data['transaction']['TRANSCODE'] === $transcode) {
                    //-----------------------------------------------------------------------------------------------
                    $arrList[$ctr]['details'][$dtl]['ROWNO']          = $transd_data['details']['ROWNO'];
                    $arrList[$ctr]['details'][$dtl]['GDCODE']         = $transd_data['services']['GDCODE'];
                    $arrList[$ctr]['details'][$dtl]['BUNRUINAME']     = $transd_data['services']['BUNRUINAME'];
                    $arrList[$ctr]['details'][$dtl]['GCODE']          = $transd_data['details']['GCODE'];
                    //-----------------------------------------------------------------------------------------------
                    if ((int)$arrData[$i]['details']['TRANTYPE'] === 1) {
                        $arrList[$ctr]['details'][$dtl]['MENUNAME']   = $transd_data['service']['MENUNAME'];
                        $arrList[$ctr]['details'][$dtl]['YOYAKUMARK'] = $transd_data['service']['YOYAKUMARK'];
                    } else {
                        $arrList[$ctr]['details'][$dtl]['MENUNAME']   = $transd_data['product']['PRODUCTNAME'];
                    }//end if else
                    //-----------------------------------------------------------------------------------------------
                    //set default minutes (staff tantou service time or store_services table service time
                    //-----------------------------------------------------------------------------------------------
                    if ($use_tantou_service_time) {
                        //-------------------------------------------------------------------------------------------
                        if (count($rs_tantou_service_time) > 0) {
                            //---------------------------------------------------------------------------------------
                            foreach ($rs_tantou_service_time as $data_tantou) {
                                //-----------------------------------------------------------------------------------
                                if ((int)$data_tantou['rs']['staffcode'] === (int)$transd_data['details']['STAFFCODE']
                                        && (int)$data_tantou['rs']['gcode'] === (int)$transd_data['details']['GCODE']) {
                                    //-------------------------------------------------------------------------------
                                    if ((int)$transd_data['customer']['SEX'] === 1) {
                                        //---------------------------------------------------------------------------
                                        if ((int)$data_tantou['rs']['service_time_male'] < $DEFAULT_MINUTES) {
                                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $DEFAULT_MINUTES;
                                        } else {
                                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $data_tantou['rs']['service_time_male'];
                                        }//end if else
                                        //---------------------------------------------------------------------------
                                    } else {
                                        //---------------------------------------------------------------------------
                                        if ((int)$data_tantou['rs']['service_time'] <  $DEFAULT_MINUTES) {
                                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $DEFAULT_MINUTES;
                                        } else {
                                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $data_tantou['rs']['service_time'];
                                        }//end if
                                        //---------------------------------------------------------------------------
                                    }//end if else
                                    //-------------------------------------------------------------------------------
                                    break; //exit for
                                    //-------------------------------------------------------------------------------
                                }//end if
                                //-----------------------------------------------------------------------------------
                            }//end foreach
                            //---------------------------------------------------------------------------------------
                        } else {
                            $arrList[$ctr]['details'][$dtl]['MENUTIME'] = $DEFAULT_MINUTES;
                        }//end if else
                        //-------------------------------------------------------------------------------------------
                    } else {
                        //-------------------------------------------------------------------------------------------
                        if ((int)$transd_data['customer']['SEX'] === 1) {
                            $arrList[$ctr]['details'][$dtl]['MENUTIME']   = $transd_data['service']['SERVICETIME_MALE'];
                        } else {
                            $arrList[$ctr]['details'][$dtl]['MENUTIME']   = $transd_data['service']['SERVICETIME'];
                        }//end if else
                        //-------------------------------------------------------------------------------------------
                    }//end if else
                    //$arrList[$ctr]['details'][$dtl]['MENUTIME']   = 60;
                    //-----------------------------------------------------------------------------------------------
                    $arrList[$ctr]['details'][$dtl]['STAFFCODE']      = $transd_data['details']['STAFFCODE'];
                    $arrList[$ctr]['details'][$dtl]['STAFFNAME']      = $transd_data['staff']['STAFFNAME'];
                    $arrList[$ctr]['details'][$dtl]['STAFFCODESIMEI'] = $transd_data['details']['STAFFCODESIMEI'];
                    $arrList[$ctr]['details'][$dtl]['ZEIKUBUN']       = $transd_data['details']['ZEIKUBUN'];
                    $arrList[$ctr]['details'][$dtl]['PRICE']          = $transd_data['details']['PRICE'];
                    $arrList[$ctr]['details'][$dtl]['CLAIMED']        = $transd_data['details']['CLAIMED'];
                    $arrList[$ctr]['details'][$dtl]['POINTKASAN1']    = $transd_data['details']['KASANPOINT1'];
                    $arrList[$ctr]['details'][$dtl]['POINTKASAN2']    = $transd_data['details']['KASANPOINT2'];
                    $arrList[$ctr]['details'][$dtl]['POINTKASAN3']    = $transd_data['details']['KASANPOINT3'];
                    $arrList[$ctr]['details'][$dtl]['TRANTYPE']       = $transd_data['details']['TRANTYPE'];
                    $arrList[$ctr]['details'][$dtl]['KEYCODE']        = $transd_data['service']['KEYCODE'];
                    //--------------------------------------------------------------------------------------------
                    //temporary added start end time - not yet used
                    //--------------------------------------------------------------------------------------------
                    $arrList[$ctr]['details'][$dtl]['TRANSCODE']        = $transd_data['details']['TRANSCODE'];
                    $arrList[$ctr]['details'][$dtl]['STARTTIME']        = $transd_data['details']['STARTTIME'];
                    $arrList[$ctr]['details'][$dtl]['ENDTIME']        = $transd_data['details']['ENDTIME'];
                    //--------------------------------------------------------------------------------------------
                    $arrList[$ctr]['details'][$dtl]['SYSCODE']        = $transd_data['services']['SYSCODE'];
                    //--------------------------------------------------------------------------------------------
                    $dtl++;
                    //--------------------------------------------------------------------------------------------
                }//end if
            }//end for
        }//end for
        //-------------------------------------------------------------------------------------------------------
        return $arrList;
        //-------------------------------------------------------------------------------------------------------
    }//end function

    /**
     * @author Homer Pasamba Email: homer.pasamba@think-ahead
     * @param type $controller
     * @param type $arrData
     * @return type arrayList
     */
    function ParseHistoryTransactionData(&$controller, $arrData) {
        $arrList = array();
        $ctrArr = 0;
        $ctrTrans = 0;
        $ctrDtl = 0;
        $TransCode = null;
        foreach ($arrData as $trans) {
            //-------------------------------------------------------------------------------------------------------
            if ($TransCode != $trans['transaction']['TRANSCODE']) {
                //==========================================================================================
                // Set Transaction Array
                //------------------------------------------------------------------------------------------
                $TransCode = $trans['transaction']['TRANSCODE'];
                $arrList[$ctrTrans]['TRANSCODE']       = $trans['transaction']['TRANSCODE'];
                $arrList[$ctrTrans]['KEYNO']           = $trans['transaction']['KEYNO'];
                $arrList[$ctrTrans]['STORECODE']       = $trans['transaction']['STORECODE'];
                $arrList[$ctrTrans]['IDNO']            = $trans['transaction']['IDNO'];
                $arrList[$ctrTrans]['TRANSDATE']       = $trans['transaction']['TRANSDATE'];
                $arrList[$ctrTrans]['CCODE']           = $trans['transaction']['CCODE'];
                $arrList[$ctrTrans]['CLAIMKYAKUFLG']   = $trans['transaction']['CLAIMKYAKUFLG'];
                $arrList[$ctrTrans]['UPDATEDATE']      = $trans['transaction']['UPDATEDATE'];
                $arrList[$ctrTrans]['PRIORITY']        = $trans['transaction']['PRIORITY'];
                $arrList[$ctrTrans]['YOYAKU']          = $trans['transaction']['YOYAKU'];
                $arrList[$ctrTrans]['REGULARCUSTOMER'] = $trans['transaction']['REGULARCUSTOMER'];
                $arrList[$ctrTrans]['TEMPSTATUS']      = $trans['transaction']['TEMPSTATUS'];
                $arrList[$ctrTrans]['KYAKUKUBUN']      = $trans['transaction']['KYAKUKUBUN'];
                $arrList[$ctrTrans]['ZEIOPTION']       = $trans['transaction']['ZEIOPTION'];
                $arrList[$ctrTrans]['RATETAX']         = $trans['transaction']['RATETAX'];
                $arrList[$ctrTrans]['TAX']             = $trans['transaction']['TAX'];
                $arrList[$ctrTrans]['SOGOKEIOPTION']   = $trans['transaction']['SOGOKEIOPTION'];
                $arrList[$ctrTrans]['APT_COLOR']       = $trans['transaction']['APT_COLOR'];
                $arrList[$ctrTrans]['NOTES']           = $trans['transaction']['NOTES'];
                $arrList[$ctrTrans]['STAFFCODE']       = $trans['transaction']['STAFFCODE'];
                $arrList[$ctrTrans]['ENDTIME']         = substr($trans['transaction']['ENDTIME'], 0, 5);
                $arrList[$ctrTrans]['STAFFCODE']       = $trans['details']['STAFFCODE'];
                $arrList[$ctrTrans]['CNAMEKANA']       = $trans['customer']['CNAMEKANA'];
                $arrList[$ctrTrans]['TEL1']            = $trans['customer']['TEL1'];
                $arrList[$ctrTrans]['TEL2']            = $trans['customer']['TEL2'];
                $arrList[$ctrTrans]['BIRTHDATE']       = $trans['customer']['BIRTHDATE'];
                $arrList[$ctrTrans]['MEMBERSCATEGORY'] = $trans['customer']['MEMBERSCATEGORY'];
                $arrList[$ctrTrans]['CNUMBER']         = $trans['customer']['CNUMBER'];
                $arrList[$ctrTrans]['CSTORECODE']      = $trans['customer']['CSTORECODE'];
                $arrList[$ctrTrans]['HOWKNOWSCODE']    = $trans['howknows_thestore']['HOWKNOWSCODE'];
                $arrList[$ctrTrans]['HOWKNOWS']        = $trans['howknows_thestore']['HOWKNOWS'];
                $arrList[$ctrTrans]['DATETIMECREATED']  = $trans['store_transaction2']['DATETIMECREATED'];
                $arrList[$ctrTrans]['UKETSUKESTAFFNAME']  = $trans['staff_yk']['UKETSUKESTAFFNAME'] == null ? "" :$trans['staff_yk']['UKETSUKESTAFFNAME'] ;
                #---------------------------------------------------------------------------------------------
                #Added by MarvinC - 2015-06-18
                #---------------------------------------------------------------------------------------------
                $arrList[$ctrTrans]['YOYAKU_STATUS']  = $trans['YND']['YOYAKU_STATUS'];
                #---------------------------------------------------------------------------------------------
                //==========================================================================================
                // Get Yoyaku Time
                //------------------------------------------------------------------------------------------
                if ($trans['transaction']['STARTTIME'] <> "") {
                    $arrList[$ctrTrans]['YOYAKUTIME'] = substr($trans['transaction']['STARTTIME'], 0, 5);
                } else {
                    $arrList[$ctrTrans]['YOYAKUTIME'] = substr($trans['transaction']['YOYAKUTIME'], 0, 5);
                }
                //==========================================================================================
                // Get Customer Name And Sex
                //------------------------------------------------------------------------------------------
                if (substr($trans['transaction']['CCODE'],3) == "0000000"){
                    $arrList[$ctrTrans]['CNAME']   = $trans['transaction']['CNAME'];
                    $arrList[$ctrTrans]['SEX']     = $trans['transaction']['SEX'];
                } else {
                    $arrList[$ctrTrans]['CNAME']   = $trans['customer']['CNAME'];
                    $arrList[$ctrTrans]['SEX']     = $trans['customer']['SEX'];
                }
                //==========================================================================================
            }// End if ( $TransCode != $arrData[$ctrTrans]['TRANSCODE'])
            //=======================================================================================================
            // Set transaction Details
            //-------------------------------------------------------------------------------------------------------
            $arrList[$ctrTrans]['details'][$ctrDtl]['STAFFNAME']      = $trans['staff']['STAFFNAME'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['TRANSCODE']      = $trans['details']['TRANSCODE'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['ROWNO']          = $trans['details']['ROWNO'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['GCODE']          = $trans['details']['GCODE'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['STAFFCODE']      = $trans['details']['STAFFCODE'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['STAFFCODESIMEI'] = $trans['details']['STAFFCODESIMEI'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['ZEIKUBUN']       = $trans['details']['ZEIKUBUN'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['CLAIMED']        = $trans['details']['CLAIMED'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['POINTKASAN1']    = $trans['details']['KASANPOINT1'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['POINTKASAN2']    = $trans['details']['KASANPOINT2'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['POINTKASAN3']    = $trans['details']['KASANPOINT3'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['TRANTYPE']       = $trans['details']['TRANTYPE'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['KEYCODE']        = $trans['service']['KEYCODE'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['STARTTIME']      = $trans['details']['STARTTIME'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['ENDTIME']        = $trans['details']['ENDTIME'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['TAX']            = $trans['details']['TAX'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['QUANTITY']       = $trans['details']['QUANTITY'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['PRICE']          = $trans['details']['PRICE'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['UNITPRICE']      = $trans['details']['UNITPRICE'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['GDCODE']         = $trans['services']['GDCODE'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['BUNRUINAME']     = $trans['services']['BUNRUINAME'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['SYSCODE']        = $trans['services']['SYSCODE'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['TOTALTAX']       = $trans[0]['TOTALTAX'];
            $arrList[$ctrTrans]['details'][$ctrDtl]['PRICETAXINC']    = $trans[0]['PRICETAXINC'];
            //==========================================================================================
            // Set transaction Details MenuName
            //------------------------------------------------------------------------------------------
            if ((int)$trans['details']['TRANTYPE'] === 1) {
                $arrList[$ctrTrans]['details'][$ctrDtl]['MENUNAME']   = $trans['service']['MENUNAME'];
                $arrList[$ctrTrans]['details'][$ctrDtl]['YOYAKUMARK'] = $trans['service']['YOYAKUMARK'];
            } else {
                $arrList[$ctrTrans]['details'][$ctrDtl]['MENUNAME']   = $trans['product']['PRODUCTNAME'];
            }//end if else
            if (($ctrArr == count($arrData) - 1) ||
                ($arrData[$ctrArr]['transaction']['TRANSCODE'] != $arrData[$ctrArr + 1]['transaction']['TRANSCODE'])) {
                $ctrDtl = 0;
                $ctrTrans++;
            }else {
                $ctrDtl++;
            }
            $ctrArr++;
            //-------------------------------------------------------------------------------------------------------
        }// End foreach ($arrData as $transd_data)
        //===========================================================================================================
        return $arrList;
        //===========================================================================================================
    }// End Function

    /**
     *
     * @param <array> $TransDetails
     * @param <int> $TotalPriceTaxExcluded (Total Price of Tax Excluded Items)
     * @param <int> $TotalTax (Total Tax of Tax Excluded Items)
     * @param <int> $ctrTransTaxExc (Counter for Tax Excluded Items)
     * @return <array> $TransDetails
     */
    function SetTransDetailPriceTaxIncluded (&$TransDetails, $TotalPriceTaxExcluded, $TotalTax, $ctrTransTaxExc) {
        //-----------------------------------------------------------------------------------------------------------
        $ctr = 0;
        $TaxRate = 0;
        $HoldRemainTotalTax = $TotalTax;
        $tmpCtrTransTaxExc = 0;
        $RetVal = null;
        //-----------------------------------------------------------------------------------------------------------
        if ((int)$TotalPriceTaxExcluded != 0) {
            //-------------------------------------------------------------------------------------------------------
            // Since Converted Database dont have TaxRate in each Transaction
            // Compute TaxRate based on Total Tax and TotalPriceTaxIncluded of TaxExcluded Items
            //-------------------------------------------------------------------------------------------------------
            settype($TaxRate, "double");
            $TaxRate = $TotalTax / $TotalPriceTaxExcluded;
        }// End if ( (int)$TotalPriceTaxIncluded == 0) {
        //-----------------------------------------------------------------------------------------------------------
        if ($TransDetails != null and count($TransDetails) > 0) {
            //-------------------------------------------------------------------------------------------------------
            foreach ($TransDetails as $TDetails) {
                //-------------------------------------------------------------------------------------------------------
                if ((int)$TDetails['ZEIKUBUN'] === 0) {
                    //---------------------------------------------------------------------------------------------------
                    // For TaxExcludedItem, Set each Tax per item
                    // Total Price = Price (total price) + tax (per item) * quantity
                    // Check the Remaining Tax if - then set PriceTaxInc to previous HoldRemainTax
                    //---------------------------------------------------------------------------------------------------
                    if ($tmpCtrTransTaxExc < $ctrTransTaxExc) {
                        $TransDetails[$ctr]['TAX'] = (int)floor($TDetails['UNITPRICE'] * $TaxRate);
                        $TransDetails[$ctr]['PRICETAXINC'] = (int)floor($TDetails['PRICE'] + ($TransDetails[$ctr]['TAX'] * $TDetails['QUANTITY']));
                        $HoldRemainTotalTax -= $TransDetails[$ctr]['TAX'] * $TDetails['QUANTITY'];
                        $tmpCtrTransTaxExc++;
                    } else {
                        $TransDetails[$ctr]['TAX'] = (int)floor($HoldRemainTotalTax / $TDetails['QUANTITY']);
                        $TransDetails[$ctr]['PRICETAXINC'] = (int)($TDetails['PRICE'] + $HoldRemainTotalTax);
                    }
                    //---------------------------------------------------------------------------------------------------
                }else {
                    //---------------------------------------------------------------------------------------------------
                    // For Tax Included Items, No Need to Compute tax since it is already computed in Tenpo
                    // And Tax in TransactionTable is only for tax excluded items.
                    //---------------------------------------------------------------------------------------------------
                    $TransDetails[$ctr]['PRICETAXINC'] = (int)$TDetails['PRICE'] + $TDetails['TAX'] * $TDetails['QUANTITY'];
                }//End  if ((int)$TDetails['ZEIKUBUN'] === 0)
                $ctr++;
            }//End  foreach ($TransDetails as $TDetails)
            $RetVal = $TransDetails;
            //-------------------------------------------------------------------------------------------------------
        }//End  if ($TransDetails != null and count($TransDetails) > 0)
        //===========================================================================================================
        return $RetVal;
        //===========================================================================================================
    }// End Function


    /**
     * TRANSCODEを発生させます
     * Generates TRANSCODE
     *
     * @param array $param
     * @return $transcode
     */
    function GenerateTranscode($param) {

    	$storecode     = $param['storecode'];
        $idno          = $param['idno'];
        $date          = $param['date'];

        // 0000001 00 20100101 000001
        $transcode = sprintf("%07s%02s%08s%06s", $storecode, $zero, $date, $idno);

        return $transcode;
    }


    /**
     * 他のトランザクションと対立すれば点検トランザクション
     * Checks transaction if it will conflict with other transactions
     *
     * @param controller &$controller
     * @param array $param
     * @return $ret
     */
    function CheckTransactionConflict(&$controller, $param) {

        $staff     = $controller->wsSearchAvailableStaff($sessionid, $param);
        $arrData   = $controller->wsSearchStoreTransaction($sessionid, $param);
        $rows      = $staff['records'][0]['ROWS'];
        $phonerows = $staff['records'][0]['PHONEROWS'];
        $ctr       = -1;
        $transcode = "";

        if ($param['PRIORITYTYPE'] == 1) {
            $col = $rows;
        } else {
            $col = $phonerows;
        }

        //-- JAPANESE ENCODING HERE (Checks if this transaction will add a column)
        $starttime = $param['STARTTIME'];
        $starttime_c = strtotime($starttime);
        $endtime = $param['ENDTIME'];
        $endtime_c = strtotime($endtime);
        $staffcode = $param['STAFFCODE'];
        $priority = 1;
        $prioritytype = $param['PRIORITYTYPE'];

        $checked_times = $arrData['checked_times'];

        $position_confirmed = false;
        while (!$position_confirmed) {
            $position_confirmed = true;
            foreach ($checked_times[$staffcode][$prioritytype][$priority] as $entry) {
                if (($starttime_c  > $entry["starttime"] && $starttime_c <  $entry["endtime"]) ||
                    ($endtime_c    > $entry["starttime"] && $endtime_c   <  $entry["endtime"]) ||
                    ($starttime_c <= $entry["starttime"] && $endtime_c   >= $entry["endtime"]) &&
                    ($param['PRIORITYTYPE'] == $entry["prioritytype"]) ) {
                    $position_confirmed = false;
                    $priority++;
                    break;
                }
            }
        }

        $ret = array();
        if ($priority > $col) {
            $ret['response'] = 'CONFLICT';
            if ($param['PRIORITYTYPE'] == 1) {
                $ret['Row']      = $priority;
                $ret['PhoneRow'] = $phonerows;
            } else {
                $ret['Row']      = $rows;
                $ret['PhoneRow'] = $priority;
            }
        } else {
            $ret['response'] = 'GOOD';
        }

        return $ret;
    }


    /**
     * 満期のケータイセッションを削除します
     * Deletes expired keitai sessions
     *
     * @param controller &$controller
     * @return boolean
     */
    function DeleteKeitaiSession(&$controller) {

        $controller->loadModel('LogSessionKeitai');

        $now = strtotime(date('Y-m-d H:i:s'));
        $datetime = $now - (SESSION_EXPIRATION_MIN * 60);
        $datetime =  date('Y-m-d H:i:s', $datetime);

        $condition = array('last_activity < ' => $datetime);

        $controller->LogSessionKeitai->deleteAll($condition);

        return true;
    }

    /**
     * メール用の店舗情報を取得する
     *
     * @param AppController $controller コントローラ
     * @param array $storeinfo $storeinfo 変数
     * @return array 店舗情報
     */
    function GetStoreForMail(&$controller, $storeinfo) {
        $controller->Store->set_company_database($storeinfo["dbname"], $controller->Store);
        $conditions = array("STORECODE = {$storeinfo["storecode"]}");
        $fields = array("STORENAME", "TEL", "FAX", "ZIP", "ADDRESS1", "ADDRESS2", "PC_HOMEPAGE", "mail");
        $recursive = 0;
        $stores = $controller->Store->find("all", compact("conditions", "fields", "recursive"));
        if (!$stores) return false;
        $store = $stores[0]["Store"];
        $store["storecode"] = $storeinfo["storecode"];
        $store["companyid"] = $storeinfo["companyid"];
        return $store;
    }

    /**
     * メール項目の配列を取得する
     *
     * @param AppController $controller コントローラ
     * @param array $storeinfo $storeinfo 変数
     * @return array メール項目の配列
     */
    function GetMailItems(&$controller, $storeinfo) {
        $store = $this->GetStoreForMail($controller, $storeinfo);
        if (!$store) return false;
        $items = array();
        $items["notice"] = $this->GetMailNotice();
        $items["noticesecond"] = $this->GetMailNoticeSecond($store);
        $items["modifying"] = $this->GetMailModifying();
        $items["follow"] = $this->GetMailFollow($store);
        $items["signature"] = $this->GetMailSignature($store);
        return $items;
    }

    /**
     * お知らせメール1を取得する
     *
     * @return string お知らせメール1
     */
    function GetMailNotice() {
        $item = "";
        $item .= "いつもご来店ありがとうございます。\n";
        $item .= "予約日が近づいてきましたので、ご予約詳細をお送りします。\n";
        $item .= "ご来店心よりお待ちしております。";
        return $item;
    }

    /**
     * お知らせメール2を取得する
     *
     * @param array $store 店舗情報
     * @return string お知らせメール2
     */
    function GetMailNoticeSecond($store) {
        $item = "";
        $item .= "まもなくご予約のお時間です。\n";
        $item .= "本日ご来店心よりお待ちしております。\n";
        $item .= "\n";
        $item .= "予約時間、日時変更等ございましたらこちらまでお気軽にご相談してください♪\n";

        if (isset($store["TEL"]) && $store["TEL"] !== "") {
            $item .= "{$store["TEL"]}";
        }

        return $item;
    }

    /**
     * 予約時間変更メールを取得する
     *
     * @return string 予約時間変更メール
     */
    function GetMailModifying() {
        $item = "";
        $item .= "ご予約のお時間が変更になりました。\n";
        $item .= "ご来店心よりお待ちしております。";
        return $item;
    }

    /**
     * フォローメールを取得する
     *
     * @param array $store 店舗情報
     * @return string フォローメール
     */
    function GetMailFollow($store) {
        $item = "";
        $item .= "ご予約のお時間が過ぎました。\n";

        if (isset($store["TEL"]) && $store["TEL"] !== "") {
            $item .= "\n";
            $item .= "ご予約日付、お時間変更をこちらまでお気軽にご相談してください♪\n";
            $item .= "{$store["TEL"]}\n";
        }

        $item .= "\n";
        $item .= "携帯からご予約のお客様はこちらから\n";

        if (isset($store["PC_HOMEPAGE"]) && $store["PC_HOMEPAGE"] !== "") {
            $item .= "{$store["PC_HOMEPAGE"]}";
        } else {
            $item .= MAIN_PATH . "yk/login/{$store["companyid"]}/{$store["storecode"]}";
        }

        return $item;
    }

    /**
     * デフォルトの署名を取得する
     *
     * @param array $store 店舗情報
     * @return string デフォルトの署名
     */
    function GetMailSignature($store) {
        $item = "";

        if (isset($store["STORENAME"]) && $store["STORENAME"] !== "") {
            $item .= "{$store["STORENAME"]}\n";
        }

        if (isset($store["ZIP"]) && $$store["ZIP"] !== "") {
            $item .= "〒{$store["ZIP"]}\n";
        }

        if (isset($store["ADDRESS1"]) && $store["ADDRESS1"] !== "") {
            $item .= "{$store["ADDRESS1"]}";

            if (isset($store["ADDRESS2"]) && $store["ADDRESS2"] !== "") {
                $item .= " {$store["ADDRESS2"]}";
            }

            $item .= "\n";
        }

        if (isset($store["TEL"]) && $store["TEL"] !== "") {
            $item .= "TEL {$store["TEL"]}";

            if (isset($store["FAX"]) && $store["FAX"] !== "") {
                $item .= "  FAX {$store["FAX"]}";
            }

            $item .= "\n";
        }

        if (isset($store["mail"]) && $store["mail"] !== "") {
            $item .= "Mail {$store["mail"]}\n";
        }

        if (isset($store["PC_HOMEPAGE"]) && $store["PC_HOMEPAGE"] !== "") {
            $item .= "URL {$store["PC_HOMEPAGE"]}\n";
        } else {
            $item .= "URL " . MAIN_PATH . "yk/login/{$store["companyid"]}/{$store["storecode"]}\n";
        }

        $lineLength = 0;

        foreach (explode("\n", $item) as $line) {
            $lineLength = max($lineLength, strlen(bin2hex($line)) / 2);
        }

        $itemLine = str_repeat("-", $lineLength + 10);
        $item = "{$itemLine}\n{$item}{$itemLine}";
        return $item;
    }


    //<editor-fold defaultstate="collapsed" desc="GetReturningCustomerCountAll">
    /**
     * @author MCUNANAN :mcunanan@think-ahead.jp
     * Date: 2015-12-05 14:34
     * @uses Get Mail Domain
     * @param type $sessionid
     * @param type $companyid
     * @param type $storecode
     */
    function GetReturningCustomerCountAll(&$controller, $dbname) {

        //-------------------------------------------------------------------------------------------
        $storeinfo = $controller->YoyakuSession->Check($controller);
        //-------------------------------------------------------------------------------------------
        if ($storeinfo == false) {
            $controller->_soap_server->fault(1, '', INVALID_SESSION);
            return;
        }//end if
        #-------------------------------------------------------------------
        # ADDED BY: MARVINC - 2015-12-28 16:34
        #-------------------------------------------------------------------
        $Sql = "SELECT
                IFNULL((SELECT returningcustomercountall
                FROM sipssbeauty_server.company
                WHERE company.dbname = '{$storeinfo['dbname']}'
                LIMIT 1),0) as returningcustomercountall";
        $data = $controller->Store->query($Sql);
        $arrReturn = $data[0][0]["returningcustomercountall"];
        #-------------------------------------------------------------------
        //===================================================================================
        return $arrReturn;
        //===================================================================================
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="sksort">
    /**
     * @author MCUNANAN :mcunanan@think-ahead.jp
     * Date: 2016-07-22
     * @uses Sort Array According to key
     * @param $array|type array()
     * @param $subkey|type string
     * @param $sort_ascending|type boolean
     */
    function sortBy($array, $field, $direction = 'asc')
    {
        usort($array, create_function('$a, $b', '
		$a = $a["' . $field . '"];
		$b = $b["' . $field . '"];

		if ($a == $b)
		{
			return 0;
		}

		return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
	'));

        return $array;
    }
    //</editor-fold>


    //<editor-fold defaultstate="collapsed" desc="CheckConflict">
    /**
     * @author MCUNANAN :mcunanan@think-ahead.jp
     * Date: 2016-07-25
     * @uses Check if transaction has a coflict in schedule
     * @param $transactions|type array()
     * @param $transcode|type string
     * @param $starttime_s|type string
     * @param $endtime_s|type string
     * @param $priority|type string
     * @param $sort_ascending|type boolean
     */
    public function CheckConflict($transactions, $transcode, $starttime_s, $endtime_s, $priority){

        foreach ($transactions as $key => $trans){
            $endtime = $trans["ADJUSTED_ENDTIME"];
            $startime = $transactions[$key]["YOYAKUTIME"];
            $prioritytypecur = $trans["PRIORITYTYPE"];
            $transcodecur = $trans["TRANSCODE"];
            if( $transcode !== $transcodecur &&
                    ($endtime > $starttime_s && $endtime_s > $startime)
                        && $prioritytypecur == $priority){
                return true;
            }
        }

        return false;
    }
    //</editor-fold>


}
?>