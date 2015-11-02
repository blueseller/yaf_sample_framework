<?php
/**
 * Created by JetBrains PhpStorm.
 * Date: 13-10-8
 * Time: 下午5:53
 * To change this template use File | Settings | File Templates.
 */
class RedisFactory {

    private static $instance = null;
    private $redisConfs = array();
    private $links = array();

    private function __construct() {}

    public static function getInstatnce() {
        if (self::$instance === null) {
            self::$instance = new RedisFactory();
        }
        return self::$instance;
    }

    /**
     * 使用redis原生扩展
     */
    public function getConnectionV2($redisName, $database = null) {
        $redisName = $this->getRedisName($redisName);
        //echo $redisName."\n";
        if (isset($this->links[$redisName])) return $this->links[$redisName];
        $database = isset($database) ? $database : Yaf_Registry::get('config')->redis->$redisName->database;
        if(!isset($database)){
            $database = 0;
        }

        $this->redisConfs[$redisName] = array(
            "host"               => Yaf_Registry::get('config')->redis->$redisName->host,
            "port"               => Yaf_Registry::get('config')->redis->$redisName->port,
            "database"           => $database,
            "connection_timeout" => Yaf_Registry::get('config')->redis->$redisName->connection_timeout,
            "read_write_timeout" => Yaf_Registry::get('config')->redis->$redisName->read_write_timeout,
        );
        //var_dump($this->redisConfs[$redisName]);

        if (!empty(Yaf_Application::app()->getConfig()->redis->$redisName->password)) {
            $this->redisConfs[$redisName]['password'] = Yaf_Registry::get('config')->redis->$redisName->password;
        }

        try {
            $newlink = new Redis();
            $newlink->connect(
                    $this->redisConfs[$redisName]['host'],
                    $this->redisConfs[$redisName]['port'],
                    $this->redisConfs[$redisName]['connection_timeout']
            );
            if(!empty($this->redisConfs[$redisName]['password'])){
                $newlink->auth($this->redisConfs[$redisName]['password']);
            }
            $newlink->select($this->redisConfs[$redisName]['database']);
            $this->links[$redisName] = $newlink;

            return $this->links[$redisName];
        } catch (Exception $e) {
            //重新连接redis
            try{
                $newlink = new Redis();
                $newlink->connect(
                        $this->redisConfs[$redisName]['host'],
                        $this->redisConfs[$redisName]['port'],
                        $this->redisConfs[$redisName]['connection_timeout']
                );
                if(!empty($this->redisConfs[$redisName]['password'])){
                    $newlink->auth($this->redisConfs[$redisName]['password']);
                }
                $newlink->select($this->redisConfs[$redisName]['database']);
                $this->links[$redisName] = $newlink;
                
                return $this->links[$redisName];
            }catch (Exception $e){
                return false;
            }
        }
    }

}
