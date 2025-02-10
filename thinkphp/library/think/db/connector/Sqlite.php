<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think\db\connector;

use PDO;
use think\db\Connection;

/**
 * Sqlite数据库驱动
 */
class Sqlite extends Connection
{

    protected $builder = '\\think\\db\\builder\\Sqlite';

    /**
     * 解析pdo连接的dsn信息
     * @access protected
<<<<<<< HEAD
     * @param  array $config 连接信息
=======
     * @param array $config 连接信息
>>>>>>> main
     * @return string
     */
    protected function parseDsn($config)
    {
        $dsn = 'sqlite:' . $config['database'];
<<<<<<< HEAD

=======
>>>>>>> main
        return $dsn;
    }

    /**
     * 取得数据表的字段信息
     * @access public
<<<<<<< HEAD
     * @param  string $tableName
=======
     * @param string $tableName
>>>>>>> main
     * @return array
     */
    public function getFields($tableName)
    {
        list($tableName) = explode(' ', $tableName);
        $sql             = 'PRAGMA table_info( ' . $tableName . ' )';

        $pdo    = $this->query($sql, [], false, true);
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
<<<<<<< HEAD

=======
>>>>>>> main
        if ($result) {
            foreach ($result as $key => $val) {
                $val                = array_change_key_case($val);
                $info[$val['name']] = [
                    'name'    => $val['name'],
                    'type'    => $val['type'],
                    'notnull' => 1 === $val['notnull'],
                    'default' => $val['dflt_value'],
                    'primary' => '1' == $val['pk'],
                    'autoinc' => '1' == $val['pk'],
                ];
            }
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this->fieldCase($info);
    }

    /**
     * 取得数据库的表信息
     * @access public
<<<<<<< HEAD
     * @param  string $dbName
=======
     * @param string $dbName
>>>>>>> main
     * @return array
     */
    public function getTables($dbName = '')
    {
<<<<<<< HEAD
=======

>>>>>>> main
        $sql = "SELECT name FROM sqlite_master WHERE type='table' "
            . "UNION ALL SELECT name FROM sqlite_temp_master "
            . "WHERE type='table' ORDER BY name";

        $pdo    = $this->query($sql, [], false, true);
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
<<<<<<< HEAD

        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }

=======
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
>>>>>>> main
        return $info;
    }

    /**
     * SQL性能分析
     * @access protected
<<<<<<< HEAD
     * @param  string $sql
=======
     * @param string $sql
>>>>>>> main
     * @return array
     */
    protected function getExplain($sql)
    {
        return [];
    }

    protected function supportSavepoint()
    {
        return true;
    }
}
