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
     * @return boolean
     */
    function set_company_database($dbname, $model) {
        // Copy default config values, usefull if dynamic config differs only in one-two params
        $config = $this->getDataSource()->config;
        // Set new database name
        $config['database'] = $dbname;
        // Add new config to connections manager
        ConnectionManager::getInstance()->create('companydb', $config);
        // Point model to new config
        $this->useDbConfig = 'companydb';
        //Recreates the model
        $model->setSource($model->useTable);

        $this->database_set = true;

        return true;
    }

}
?>