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

class Staff extends AppModel {
    var $name         = 'Staff';
    var $useTable     = 'staff';
    var $primaryKey   = 'STAFFCODE';
    var $database_set = false;
    var $useDbConfig  = 'database_schema';
    var $hasOne       = array(
        "StaffAssignToStore" => array(
            "className"  => "StaffAssignToStore",
            "foreignKey" => "STAFFCODE"
        )
    );
    var $belongsTo    = array(
        "Sublevel" => array(
            "className"  => "Sublevel",
            "foreignKey" => "SUBLEVELCODE"
        ),
        "Position" => array(
            "className"  => "Position",
            "foreignKey" => "POSITIONCODE"
        )
    );
}
?>
