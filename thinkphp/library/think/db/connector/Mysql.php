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
<<<<<<< HEAD
use think\db\Query;
=======
use think\Log;
>>>>>>> main

/**
 * mysql数据库驱动
 */
class Mysql extends Connection
{

    protected $builder = '\\think\\db\\builder\\Mysql';

    /**
<<<<<<< HEAD
     * 初始化
     * @access protected
     * @return void
     */
    protected function initialize()
    {
        // Point类型支持
        Query::extend('point', function ($query, $field, $value = null, $fun = 'GeomFromText', $type = 'POINT') {
            if (!is_null($value)) {
                $query->data($field, ['point', $value, $fun, $type]);
            } else {
                if (is_string($field)) {
                    $field = explode(',', $field);
                }
                $query->setOption('point', $field);
            }

            return $query;
        });
    }

    /**
     * 解析pdo连接的dsn信息
     * @access protected
     * @param  array $config 连接信息
=======
     * 解析pdo连接的dsn信息
     * @access protected
     * @param array $config 连接信息
>>>>>>> main
     * @return string
     */
    protected function parseDsn($config)
    {
        if (!empty($config['socket'])) {
            $dsn = 'mysql:unix_socket=' . $config['socket'];
        } elseif (!empty($config['hostport'])) {
            $dsn = 'mysql:host=' . $config['hostname'] . ';port=' . $config['hostport'];
        } else {
            $dsn = 'mysql:host=' . $config['hostname'];
        }
        $dsn .= ';dbname=' . $config['database'];

        if (!empty($config['charset'])) {
            $dsn .= ';charset=' . $config['charset'];
        }
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
<<<<<<< HEAD

=======
>>>>>>> main
        if (false === strpos($tableName, '`')) {
            if (strpos($tableName, '.')) {
                $tableName = str_replace('.', '`.`', $tableName);
            }
            $tableName = '`' . $tableName . '`';
        }
<<<<<<< HEAD

=======
>>>>>>> main
        $sql    = 'SHOW COLUMNS FROM ' . $tableName;
        $pdo    = $this->query($sql, [], false, true);
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
<<<<<<< HEAD

=======
>>>>>>> main
        if ($result) {
            foreach ($result as $key => $val) {
                $val                 = array_change_key_case($val);
                $info[$val['field']] = [
                    'name'    => $val['field'],
                    'type'    => $val['type'],
<<<<<<< HEAD
                    'notnull' => 'NO' == $val['null'],
                    'default' => $val['default'],
                    'primary' => strtolower($val['key']) == 'pri',
                    'autoinc' => strtolower($val['extra']) == 'auto_increment',
                ];
            }
        }

=======
                    'notnull' => (bool) ('' === $val['null']), // not null is empty, null is yes
                    'default' => $val['default'],
                    'primary' => (strtolower($val['key']) == 'pri'),
                    'autoinc' => (strtolower($val['extra']) == 'auto_increment'),
                ];
            }
        }
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
        $sql    = !empty($dbName) ? 'SHOW TABLES FROM ' . $dbName : 'SHOW TABLES ';
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
<<<<<<< HEAD
        $pdo = $this->linkID->prepare("EXPLAIN " . $this->queryStr);

        foreach ($this->bind as $key => $val) {
            // 占位符
            $param = is_int($key) ? $key + 1 : ':' . $key;

            if (is_array($val)) {
                if (PDO::PARAM_INT == $val[1] && '' === $val[0]) {
                    $val[0] = 0;
                } elseif (self::PARAM_FLOAT == $val[1]) {
                    $val[0] = is_string($val[0]) ? (float) $val[0] : $val[0];
                    $val[1] = PDO::PARAM_STR;
                }

                $result = $pdo->bindValue($param, $val[0], $val[1]);
            } else {
                $result = $pdo->bindValue($param, $val);
            }
        }

        $pdo->execute();
        $result = $pdo->fetch(PDO::FETCH_ASSOC);
        $result = array_change_key_case($result);

        if (isset($result['extra'])) {
            if (strpos($result['extra'], 'filesort') || strpos($result['extra'], 'temporary')) {
                $this->log('SQL:' . $this->queryStr . '[' . $result['extra'] . ']', 'warn');
            }
        }

=======
        $pdo    = $this->linkID->query("EXPLAIN " . $sql);
        $result = $pdo->fetch(PDO::FETCH_ASSOC);
        $result = array_change_key_case($result);
        if (isset($result['extra'])) {
            if (strpos($result['extra'], 'filesort') || strpos($result['extra'], 'temporary')) {
                Log::record('SQL:' . $this->queryStr . '[' . $result['extra'] . ']', 'warn');
            }
        }
>>>>>>> main
        return $result;
    }

    protected function supportSavepoint()
    {
        return true;
    }

<<<<<<< HEAD
    /**
     * 启动XA事务
     * @access public
     * @param  string $xid XA事务id
     * @return void
     */
    public function startTransXa($xid)
    {
        $this->initConnect(true);
        if (!$this->linkID) {
            return false;
        }

        $this->linkID->exec("XA START '$xid'");
    }

    /**
     * 预编译XA事务
     * @access public
     * @param  string $xid XA事务id
     * @return void
     */
    public function prepareXa($xid)
    {
        $this->initConnect(true);
        $this->linkID->exec("XA END '$xid'");
        $this->linkID->exec("XA PREPARE '$xid'");
    }

    /**
     * 提交XA事务
     * @access public
     * @param  string $xid XA事务id
     * @return void
     */
    public function commitXa($xid)
    {
        $this->initConnect(true);
        $this->linkID->exec("XA COMMIT '$xid'");
    }

    /**
     * 回滚XA事务
     * @access public
     * @param  string $xid XA事务id
     * @return void
     */
    public function rollbackXa($xid)
    {
        $this->initConnect(true);
        $this->linkID->exec("XA ROLLBACK '$xid'");
    }
=======
>>>>>>> main
}
