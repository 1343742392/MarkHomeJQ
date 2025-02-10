<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think\db\builder;

use think\db\Builder;
use think\db\Expression;
<<<<<<< HEAD
use think\db\Query;
use think\Exception;
=======
>>>>>>> main

/**
 * Sqlsrv数据库驱动
 */
class Sqlsrv extends Builder
{
    protected $selectSql       = 'SELECT T1.* FROM (SELECT thinkphp.*, ROW_NUMBER() OVER (%ORDER%) AS ROW_NUMBER FROM (SELECT %DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%) AS thinkphp) AS T1 %LIMIT%%COMMENT%';
    protected $selectInsertSql = 'SELECT %DISTINCT% %FIELD% FROM %TABLE%%JOIN%%WHERE%%GROUP%%HAVING%';
    protected $updateSql       = 'UPDATE %TABLE% SET %SET% FROM %TABLE% %JOIN% %WHERE% %LIMIT% %LOCK%%COMMENT%';
<<<<<<< HEAD
    protected $deleteSql       = 'DELETE FROM %TABLE% %USING% FROM %TABLE% %JOIN% %WHERE% %LIMIT% %LOCK%%COMMENT%';
=======
    protected $deleteSql       = 'DELETE FROM %TABLE%  %USING% FROM %TABLE%  %JOIN% %WHERE% %LIMIT% %LOCK%%COMMENT%';
>>>>>>> main
    protected $insertSql       = 'INSERT INTO %TABLE% (%FIELD%) VALUES (%DATA%) %COMMENT%';
    protected $insertAllSql    = 'INSERT INTO %TABLE% (%FIELD%) %DATA% %COMMENT%';

    /**
     * order分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @param  mixed     $order
     * @return string
     */
    protected function parseOrder(Query $query, $order)
=======
     * @param mixed $order
     * @param array $options
     * @return string
     */
    protected function parseOrder($order, $options = [])
>>>>>>> main
    {
        if (empty($order)) {
            return ' ORDER BY rand()';
        }

<<<<<<< HEAD
        foreach ($order as $key => $val) {
            if ($val instanceof Expression) {
                $array[] = $val->getValue();
            } elseif ('[rand]' == $val) {
                $array[] = $this->parseRand($query);
            } else {
                if (is_numeric($key)) {
                    list($key, $sort) = explode(' ', strpos($val, ' ') ? $val : $val . ' ');
                } else {
                    $sort = $val;
                }

                if (preg_match('/^[\w\.]+$/', $key)) {
                    $sort    = strtoupper($sort);
                    $sort    = in_array($sort, ['ASC', 'DESC'], true) ? ' ' . $sort : '';
                    $array[] = $this->parseKey($query, $key, true) . $sort;
                } else {
                    throw new Exception('order express error:' . $key);
                }
            }
        }

        return empty($array) ? '' : ' ORDER BY ' . implode(',', $array);
=======
        $array = [];
        foreach ($order as $key => $val) {
            if ($val instanceof Expression) {
                $array[] = $val->getValue();
            } elseif (is_numeric($key)) {
                if (false === strpos($val, '(')) {
                    $array[] = $this->parseKey($val, $options);
                } elseif ('[rand]' == $val) {
                    $array[] = $this->parseRand();
                } else {
                    $array[] = $val;
                }
            } else {
                $sort    = in_array(strtolower(trim($val)), ['asc', 'desc'], true) ? ' ' . $val : '';
                $array[] = $this->parseKey($key, $options, true) . ' ' . $sort;
            }
        }

        return ' ORDER BY ' . implode(',', $array);
>>>>>>> main
    }

    /**
     * 随机排序
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @return string
     */
    protected function parseRand(Query $query)
=======
     * @return string
     */
    protected function parseRand()
>>>>>>> main
    {
        return 'rand()';
    }

    /**
     * 字段和表名处理
<<<<<<< HEAD
     * @access public
     * @param  Query     $query     查询对象
     * @param  mixed     $key       字段名
     * @param  bool      $strict   严格检测
     * @return string
     */
    public function parseKey(Query $query, $key, $strict = false)
=======
     * @access protected
     * @param mixed  $key
     * @param array  $options
     * @return string
     */
    protected function parseKey($key, $options = [], $strict = false)
>>>>>>> main
    {
        if (is_numeric($key)) {
            return $key;
        } elseif ($key instanceof Expression) {
            return $key->getValue();
        }
<<<<<<< HEAD

        $key = trim($key);

        if (strpos($key, '.') && !preg_match('/[,\'\"\(\)\[\s]/', $key)) {
            list($table, $key) = explode('.', $key, 2);

            $alias = $query->getOptions('alias');

            if ('__TABLE__' == $table) {
                $table = $query->getOptions('table');
                $table = is_array($table) ? array_shift($table) : $table;
            }

            if (isset($alias[$table])) {
                $table = $alias[$table];
=======
        $key = trim($key);
        if (strpos($key, '.') && !preg_match('/[,\'\"\(\)\[\s]/', $key)) {
            list($table, $key) = explode('.', $key, 2);
            if ('__TABLE__' == $table) {
                $table = $this->query->getTable();
            }
            if (isset($options['alias'][$table])) {
                $table = $options['alias'][$table];
>>>>>>> main
            }
        }

        if ($strict && !preg_match('/^[\w\.\*]+$/', $key)) {
            throw new Exception('not support data:' . $key);
        }
<<<<<<< HEAD

        if ('*' != $key && !preg_match('/[,\'\"\*\(\)\[.\s]/', $key)) {
            $key = '[' . $key . ']';
        }

        if (isset($table)) {
            $key = '[' . $table . '].' . $key;
        }

=======
        if ('*' != $key && ($strict || !preg_match('/[,\'\"\*\(\)\[.\s]/', $key))) {
            $key = '[' . $key . ']';
        }
        if (isset($table)) {
            $key = '[' . $table . '].' . $key;
        }
>>>>>>> main
        return $key;
    }

    /**
     * limit
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @param  mixed     $limit
     * @return string
     */
    protected function parseLimit(Query $query, $limit)
=======
     * @param mixed $limit
     * @return string
     */
    protected function parseLimit($limit)
>>>>>>> main
    {
        if (empty($limit)) {
            return '';
        }

        $limit = explode(',', $limit);
<<<<<<< HEAD

=======
>>>>>>> main
        if (count($limit) > 1) {
            $limitStr = '(T1.ROW_NUMBER BETWEEN ' . $limit[0] . ' + 1 AND ' . $limit[0] . ' + ' . $limit[1] . ')';
        } else {
            $limitStr = '(T1.ROW_NUMBER BETWEEN 1 AND ' . $limit[0] . ")";
        }
<<<<<<< HEAD

        return 'WHERE ' . $limitStr;
    }

    public function selectInsert(Query $query, $fields, $table)
    {
        $this->selectSql = $this->selectInsertSql;

        return parent::selectInsert($query, $fields, $table);
=======
        return 'WHERE ' . $limitStr;
    }

    public function selectInsert($fields, $table, $options)
    {
        $this->selectSql = $this->selectInsertSql;
        return parent::selectInsert($fields, $table, $options);
>>>>>>> main
    }

}
