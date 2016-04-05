<?php
include dirname(__FILE__) . "/FluentPDO/FluentPDO.php";
class Db {

    private static $link = null;

    function __construct()
    {
        $this->dbconn = self::linkDb($this->dbname);
    }

    /**
     * @param  string mysql database name
     * @return [type]
     */
    public static function linkDb($dbname = "_default") {
        if (isset(self::$link[$dbname])) {
            return self::$link[$dbname];
        }

        $config     = Yaf_Registry::get('config');
        if(!$config->database->get($dbname)){
            return false;
        }
        $host       = $config->database->get($dbname)->host;
        $port       = $config->database->get($dbname)->port;
        $user       = $config->database->get($dbname)->username;
        $password   = $config->database->get($dbname)->password;
        $name       = $config->database->get($dbname)->dbname;
        $charset    = isset($config->database->get($dbname)->charset)?$config->database->get($dbname)->charset:"UTF8";

        try{
            $link_temp = new PDO(
                'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $name . ';charset=' . $charset,
                $user,
                $password,
                $arrayName = array(
                    PDO::ATTR_PERSISTENT => false
                )
            );
            $link_temp->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);        //PHP5.3.6以下，需禁用仿真预处理，防止注入
            $link_temp->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            self::$link[$dbname] = new FluentPDO($link_temp);
        }catch (Exception $e){
            self::$link[$dbname] = null;
        }

        if (!self::$link[$dbname]) 
        {
            return false;
        }
        return self::$link[$dbname];
    }

}
