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

class StoreAccount extends AppModel
{
	var $name       = 'StoreAccount';
    var $useTable   = 'sipss_store_accounts';
    var $primaryKey	= 'storecode';

    var $hasOne     = array('WebyanAccount' => array(
                                    'foreignKey' => false,
                                    'conditions' => array('WebyanAccount.storecode = StoreAccount.storecode',
                                                          'WebyanAccount.companyid = StoreAccount.companyid')),
                            'Company' => array(
                                    'foreignKey' => false,
                                    'conditions' => array('Company.companyid = StoreAccount.companyid'))
                                     );

}
?>