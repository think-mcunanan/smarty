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
 * ======================================================================================
 * Update By Alberto S. Baguio
 * Reference to Redmine 1864 (revised from gdcode -->> orderby, gdcode, keycode, gcode)
 * Date Nov. 04, 2016       
 * ======================================================================================
 */

class StoreService extends AppModel
{
	var $name       = 'StoreService';
    var $useTable   = 'store_services';
    var $primaryKey	= 'orderby, gdcode, keycode, gcode';

    var $database_set = false;
    var $useDbConfig = 'database_schema';

    var $hasOne     = array('Store' => array(
                                    'foreignKey' => false,
                                    'conditions' => array('StoreService.STORECODE = Store.STORECODE'))
                                     );

}
?>