<?php
class Db {

    private static $link = null;

    private $statement;

    private $message = '';

    /**
     * @param  string mysql database name
     * @return [type]
     */
    private function linkDb($dbname = "_default") {
        if (isset(self::$link[$dbname])) {
            return self::$link[$dbname];
        }

        $config     = Yaf_Registry::get('config');
        if(!$config->database->get("params".$dbname)){
            return false;
        }
        $host       = $config->database->get("params".$dbname)->host;
        $port       = $config->database->get("params".$dbname)->port;
        $user       = $config->database->get("params".$dbname)->username;
        $password   = $config->database->get("params".$dbname)->password;
        $name     = $config->database->get("params".$dbname)->dbname;
        $charset    = isset($config->database->get("params".$dbname)->charset)?$config->database->get("params".$dbname)->charset:"UTF8";

        try{
            self::$link[$dbname] = new PDO(
                'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $name . ';charset=' . $charset,
                $user,
                $password,
                $arrayName = array(
                    PDO::ATTR_PERSISTENT => false
                )
            );
            self::$link[$dbname]->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);        //PHP5.3.6以下，需禁用仿真预处理，防止注入
            self::$link[$dbname]->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch (Exception $e){
            var_dump($e);
            self::$link[$dbname] = null;
        }

        if (!self::$link[$dbname]) 
        {
            return false;
        }
        return self::$link[$dbname];
    }

    /**
     * 检查database是否连接成功,如果未连接则进行连接
     * @param  string $dbname database name
     * @return [type]
     */
    private static function checkLink($dbname){
        if (!isset(self::$link[$dbname]) && !self::linkDb($dbname)) 
        {
            return false;
        }else{
            return true;
        }
    }

    /**
     * 执行sql语句，返回结果数组
     * 主要用于执行SELECT语句
     * @param string $sql
     * @param array $params
     * @param array $dbname
     * @param array $key 设定返回数组的key值字段
     * @return array/boolean $result
     */
    public static function getResult($sql, $params = null ,$dbname = "_default" , $key = '') {
        $result = array();
        if(!self::checkLink($dbname)){
            return $result;
        }
        try{
            $sth = self::$link[$dbname]->prepare($sql);
            $sth->execute($params);
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        }catch (Exception $e){
            $result = array();
        }
        
        //根据 key的值，把字段值设定为数据的key
        if(count($result) && !empty($key)){
            $tmpData = array();
            foreach($result as $item){
                $tempData[$item[$key]] = $item;
            }
            $result = $tempData;
        }
        return $result;
        
    }

    /**
     * 执行sql语句，返回结果数组
     * 主要用于执行SELECT语句
     * @param string $sql
     * @param array $params
     * @param array $dbname
     * @return array/boolean $result
     */
    public function getRow($sql, $params = null ,$dbname = "_default") {
        $result = array();
        if(!self::checkLink($dbname)){
            return array();
        }
        try{
            $sth = self::$link[$dbname]->prepare($sql);
            $sth->execute($params);
            $result = $sth->fetch(PDO::FETCH_ASSOC);
        }catch (Exception $e){
        }
        
        return $result;
    }

    /**
     * 执行sql语句，返回影响的行数
     * 主要用于执行UPDATE、DELETE等语句
     * @param string $sql
     * @param array $params
     * @param array $dbname
     * @return int/boolean $affectedRows
     */
    public function getAffectedRows($sql, $params = null ,$dbname = "_default") {
        $affectedRows = 0;
        if(!self::checkLink($dbname)){
            return false;
        }
        try{
            $sth = self::$link[$dbname]->prepare($sql);
            $sth->execute($params);
            $affectedRows = $sth->rowCount();
        }catch (Exception $e){
            var_dump($e);
        }
        return $affectedRows;
    }

    /**
     * 执行sql语句，返回最后插入的ID
     * 主要用于执行INSERT语句
     * @param string $sql
     * @param array $params
     * @param array $dbname
     * @return int $lastId
     */
    public function getLastInsertId($sql, $params = null ,$dbname = "_default") {
        $lastId = 0;
        if(!self::checkLink($dbname)){
            return false;
        }
        try{
            $sth = self::$link[$dbname]->prepare($sql);
            $sth->execute($params);
            $lastId = self::$link[$dbname]->lastInsertId();
        }catch (Exception $e){
        }
        
        return $lastId;
    }

    /**
     * 开启事务
     */
    public function beginTransaction($dbname = "_default") {
        self::$link[$dbname]->beginTransaction();
    }

    /**
     * 提交事务
     */
    public function commit($dbname = "_default") {
        self::$link[$dbname]->commit();
    }

    /**
     * 回滚事务
     */
    public function rollback($dbname = "_default") {
        self::$link[$dbname]->rollback();
    }
}
