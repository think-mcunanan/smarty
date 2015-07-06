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

class StaffAssignToStore extends AppModel {
    var $name         = "StaffAssignToStore";
    var $useTable     = "staff_assign_to_store";
    var $primaryKey   = "STAFFCODE";
    var $database_set = false;
    var $useDbConfig  = "database_schema";
    var $hasOne       = array(
        "Staff" => array(
            "className"  => "Staff",
            "foreignKey" => "STAFFCODE"
        ),
        "Store" => array(
            "className"  => "Store",
            "foreignKey" => "STORECODE"
        ),
        "Stafftype" => array(
            "className"  => "Stafftype",
            "foreignKey" => "STAFFCODE"
        )
    );
}
?>
