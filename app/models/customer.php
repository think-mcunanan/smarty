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

class Customer extends AppModel {
    var $name = "Customer";
    var $useTable = "customer";
    var $primaryKey = "CCODE";
    var $database_set = false;
    var $useDbConfig = "database_schema";
    var $hasOne = array(
        "CustomerTotal" => array(
            "foreignKey" => false,
            "conditions" => array(
                "CustomerTotal.CCODE = Customer.CCODE"
            )
        )
    );
    var $belongsTo = array(
        "Store" => array(
            "className"  => "Store",
            "foreignKey" => "CSTORECODE"
        )
    );
}
?>
