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

class customersns extends AppModel
{
    var $name           = 'customersns';
    var $useTable       = 'customer_sns';
    var $primaryKey     = NULL;

    var $database_set   = false;
    var $useDbConfig    = 'database_schema';
    
}
?>