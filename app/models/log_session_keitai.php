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

class LogSessionKeitai extends AppModel
{
	var $name       = 'LogSessionKeitai';
    var $useTable   = 'logsession_keitai';
    var $primaryKey	= 'session_no';

    var $hasOne = array('Company' => array(
                              'foreignKey' => false,
                              'conditions' => array('LogSessionKeitai.companyid = Company.companyid')),
                       /* 'Customer' => array(
                              'foreignKey' => false,
                              'conditions' => array('LogSessionKeitai.companyid = Company.companyid')),*/


                                     );

}
?>