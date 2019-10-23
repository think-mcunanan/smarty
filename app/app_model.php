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

class AppModel extends Model {

    /**
     * 会社データベースを設定するモデルを更新する
     * Sets the company database and updates the model
     *
     * @param string $dbname
     * @param model $model
     * @param string $con master|slave
     * @return boolean
     */
    function set_company_database($dbname, $model, $con = null) {
        // Copy default config values, usefull if dynamic config differs only in one-two params
        $config = $this->getDataSource()->config;

        // Set new database name
        $config['database'] = $dbname;

        // Set Server Connection SLAVE/MASTER
        if($con === null){
            $config['con'] = ConnectionServer::MASTER;
        }else{
            $config['con'] = ConnectionServer::SLAVE;
        }
        if(DBMasterConnectionOnly === true){
            $config['con'] = ConnectionServer::MASTER;
        }

        // Create New Connection Intance
        ConnectionManager::getInstance()->create('companydb'.$config['con'], $config);

        // Point model to new config
        $this->useDbConfig = 'companydb'.$config['con'];

        //Recreates the model
        $model->setSource($model->useTable);

        $this->database_set = true;

        return true;
    }

}
?>