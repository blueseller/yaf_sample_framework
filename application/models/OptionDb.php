<?php
/**************************************************************************
 File Name: Optiondb.php
 Author: liukai
 mail: liukai-ps@360.cn
 Created Time: Tue 05 Apr 2016 05:59:56 PM CST
 test url: https://github.com/blueseller/fluentpdo/tree/master/tests
**************************************************************************/
class OptionDbModel extends DB
{

    protected $dbname = 'default';
    protected $table  = 'table_test';
    protected $dbconn = null; 

    public function seleclOption()
    {
        $result = $this->dbconn->from($this->table)->where(array("id" => 1))->fetchAll();
        return $result;
    }

    public function delOption()
    {
        $result = $this->dbconn->deleteFrom($this->table)->where(array("id" => 2))->execute();
        return $result;
    }

    public function insertOption()
    {
        $result = $this->dbconn->insertInto($this->table,
            array(
                "name" => "360搜索",
                "url"  => "www.so.com"
            ))->execute();
        return $result;
    }

    public function updateOption()
    {
        $result = $this->dbconn->update($this->table)->set(array("name" => "360so"))->where(array("id" => 1))->execute();
        return $result;
    }
}
