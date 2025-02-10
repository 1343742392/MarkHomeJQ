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

namespace think\db;

use PDO;
use think\Exception;

abstract class Builder
{
    // connection对象实例
    protected $connection;
<<<<<<< HEAD

    // 查询表达式映射
    protected $exp = ['EQ' => '=', 'NEQ' => '<>', 'GT' => '>', 'EGT' => '>=', 'LT' => '<', 'ELT' => '<=', 'NOTLIKE' => 'NOT LIKE', 'NOTIN' => 'NOT IN', 'NOTBETWEEN' => 'NOT BETWEEN', 'NOTEXISTS' => 'NOT EXISTS', 'NOTNULL' => 'NOT NULL', 'NOTBETWEEN TIME' => 'NOT BETWEEN TIME'];

    // 查询表达式解析
    protected $parser = [
        'parseCompare'     => ['=', '<>', '>', '>=', '<', '<='],
        'parseLike'        => ['LIKE', 'NOT LIKE'],
        'parseBetween'     => ['NOT BETWEEN', 'BETWEEN'],
        'parseIn'          => ['NOT IN', 'IN'],
        'parseExp'         => ['EXP'],
        'parseNull'        => ['NOT NULL', 'NULL'],
        'parseBetweenTime' => ['BETWEEN TIME', 'NOT BETWEEN TIME'],
        'parseTime'        => ['< TIME', '> TIME', '<= TIME', '>= TIME'],
        'parseExists'      => ['NOT EXISTS', 'EXISTS'],
        'parseColumn'      => ['COLUMN'],
    ];

    // SQL表达式
    protected $selectSql = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%FORCE%%JOIN%%WHERE%%GROUP%%HAVING%%UNION%%ORDER%%LIMIT% %LOCK%%COMMENT%';

    protected $insertSql = '%INSERT% INTO %TABLE% (%FIELD%) VALUES (%DATA%) %COMMENT%';

    protected $insertAllSql = '%INSERT% INTO %TABLE% (%FIELD%) %DATA% %COMMENT%';

    protected $updateSql = 'UPDATE %TABLE% SET %SET%%JOIN%%WHERE%%ORDER%%LIMIT% %LOCK%%COMMENT%';

    protected $deleteSql = 'DELETE FROM %TABLE%%USING%%JOIN%%WHERE%%ORDER%%LIMIT% %LOCK%%COMMENT%';

    /**
     * 架构函数
     * @access public
     * @param  Connection    $connection 数据库连接对象实例
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
=======
    // 查询对象实例
    protected $query;

    // 数据库表达式
    protected $exp = ['eq' => '=', 'neq' => '<>', 'gt' => '>', 'egt' => '>=', 'lt' => '<', 'elt' => '<=', 'notlike' => 'NOT LIKE', 'not like' => 'NOT LIKE', 'like' => 'LIKE', 'in' => 'IN', 'exp' => 'EXP', 'notin' => 'NOT IN', 'not in' => 'NOT IN', 'between' => 'BETWEEN', 'not between' => 'NOT BETWEEN', 'notbetween' => 'NOT BETWEEN', 'exists' => 'EXISTS', 'notexists' => 'NOT EXISTS', 'not exists' => 'NOT EXISTS', 'null' => 'NULL', 'notnull' => 'NOT NULL', 'not null' => 'NOT NULL', '> time' => '> TIME', '< time' => '< TIME', '>= time' => '>= TIME', '<= time' => '<= TIME', 'between time' => 'BETWEEN TIME', 'not between time' => 'NOT BETWEEN TIME', 'notbetween time' => 'NOT BETWEEN TIME'];

    // SQL表达式
    protected $selectSql    = 'SELECT%DISTINCT% %FIELD% FROM %TABLE%%FORCE%%JOIN%%WHERE%%GROUP%%HAVING%%UNION%%ORDER%%LIMIT%%LOCK%%COMMENT%';
    protected $insertSql    = '%INSERT% INTO %TABLE% (%FIELD%) VALUES (%DATA%) %COMMENT%';
    protected $insertAllSql = '%INSERT% INTO %TABLE% (%FIELD%) %DATA% %COMMENT%';
    protected $updateSql    = 'UPDATE %TABLE% SET %SET% %JOIN% %WHERE% %ORDER%%LIMIT% %LOCK%%COMMENT%';
    protected $deleteSql    = 'DELETE FROM %TABLE% %USING% %JOIN% %WHERE% %ORDER%%LIMIT% %LOCK%%COMMENT%';

    /**
     * 构造函数
     * @access public
     * @param Connection    $connection 数据库连接对象实例
     * @param Query         $query      数据库查询对象实例
     */
    public function __construct(Connection $connection, Query $query)
    {
        $this->connection = $connection;
        $this->query      = $query;
>>>>>>> main
    }

    /**
     * 获取当前的连接对象实例
     * @access public
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
<<<<<<< HEAD
     * 注册查询表达式解析
     * @access public
     * @param  string    $name   解析方法
     * @param  array     $parser 匹配表达式数据
     * @return $this
     */
    public function bindParser($name, $parser)
    {
        $this->parser[$name] = $parser;
        return $this;
=======
     * 获取当前的Query对象实例
     * @access public
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * 将SQL语句中的__TABLE_NAME__字符串替换成带前缀的表名（小写）
     * @access protected
     * @param string $sql sql语句
     * @return string
     */
    protected function parseSqlTable($sql)
    {
        return $this->query->parseSqlTable($sql);
>>>>>>> main
    }

    /**
     * 数据分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query     查询对象
     * @param  array     $data      数据
     * @param  array     $fields    字段信息
     * @param  array     $bind      参数绑定
     * @return array
     */
    protected function parseData(Query $query, $data = [], $fields = [], $bind = [])
=======
     * @param array     $data 数据
     * @param array     $options 查询参数
     * @return array
     * @throws Exception
     */
    protected function parseData($data, $options)
>>>>>>> main
    {
        if (empty($data)) {
            return [];
        }

<<<<<<< HEAD
        $options = $query->getOptions();

        // 获取绑定信息
        if (empty($bind)) {
            $bind = $this->connection->getFieldsBind($options['table']);
        }

        if (empty($fields)) {
            if ('*' == $options['field']) {
                $fields = array_keys($bind);
            } else {
                $fields = $options['field'];
            }
        }

        $result = [];

=======
        // 获取绑定信息
        $bind = $this->query->getFieldsBind($options['table']);
        if ('*' == $options['field']) {
            $fields = array_keys($bind);
        } else {
            $fields = $options['field'];
        }

        $result = [];
>>>>>>> main
        foreach ($data as $key => $val) {
            if ('*' != $options['field'] && !in_array($key, $fields, true)) {
                continue;
            }

<<<<<<< HEAD
            $item = $this->parseKey($query, $key, true);

            if ($val instanceof Expression) {
                $result[$item] = $val->getValue();
                continue;
            } elseif (!is_scalar($val) && (in_array($key, (array) $query->getOptions('json')) || 'json' == $this->connection->getFieldsType($options['table'], $key))) {
                $val = json_encode($val, JSON_UNESCAPED_UNICODE);
=======
            $item = $this->parseKey($key, $options, true);
            if ($val instanceof Expression) {
                $result[$item] = $val->getValue();
                continue;
>>>>>>> main
            } elseif (is_object($val) && method_exists($val, '__toString')) {
                // 对象数据写入
                $val = $val->__toString();
            }
<<<<<<< HEAD

            if (false !== strpos($key, '->')) {
                list($key, $name) = explode('->', $key);
                $item             = $this->parseKey($query, $key);
                $result[$item]    = 'json_set(' . $item . ', \'$.' . $name . '\', ' . $this->parseDataBind($query, $key, $val, $bind) . ')';
            } elseif ('*' == $options['field'] && false === strpos($key, '.') && !in_array($key, $fields, true)) {
=======
            if (false === strpos($key, '.') && !in_array($key, $fields, true)) {
>>>>>>> main
                if ($options['strict']) {
                    throw new Exception('fields not exists:[' . $key . ']');
                }
            } elseif (is_null($val)) {
                $result[$item] = 'NULL';
            } elseif (is_array($val) && !empty($val)) {
<<<<<<< HEAD
                switch (strtoupper($val[0])) {
                    case 'INC':
                        $result[$item] = $item . ' + ' . floatval($val[1]);
                        break;
                    case 'DEC':
                        $result[$item] = $item . ' - ' . floatval($val[1]);
                        break;
                    case 'EXP':
=======
                switch (strtolower($val[0])) {
                    case 'inc':
                        $result[$item] = $item . '+' . floatval($val[1]);
                        break;
                    case 'dec':
                        $result[$item] = $item . '-' . floatval($val[1]);
                        break;
                    case 'exp':
>>>>>>> main
                        throw new Exception('not support data:[' . $val[0] . ']');
                }
            } elseif (is_scalar($val)) {
                // 过滤非标量数据
<<<<<<< HEAD
                $result[$item] = $this->parseDataBind($query, $key, $val, $bind);
            }
        }

=======
                if (0 === strpos($val, ':') && $this->query->isBind(substr($val, 1))) {
                    $result[$item] = $val;
                } else {
                    $key = str_replace('.', '_', $key);
                    $this->query->bind('data__' . $key, $val, isset($bind[$key]) ? $bind[$key] : PDO::PARAM_STR);
                    $result[$item] = ':data__' . $key;
                }
            }
        }
>>>>>>> main
        return $result;
    }

    /**
<<<<<<< HEAD
     * 数据绑定处理
     * @access protected
     * @param  Query     $query     查询对象
     * @param  string    $key       字段名
     * @param  mixed     $data      数据
     * @param  array     $bind      绑定数据
     * @return string
     */
    protected function parseDataBind(Query $query, $key, $data, $bind = [])
    {
        if ($data instanceof Expression) {
            return $data->getValue();
        }

        $name = $query->bind($data, isset($bind[$key]) ? $bind[$key] : PDO::PARAM_STR);

        return ':' . $name;
    }

    /**
     * 字段名分析
     * @access public
     * @param  Query  $query    查询对象
     * @param  mixed  $key      字段名
     * @param  bool   $strict   严格检测
     * @return string
     */
    public function parseKey(Query $query, $key, $strict = false)
    {
        return $key instanceof Expression ? $key->getValue() : $key;
=======
     * 字段名分析
     * @access protected
     * @param string $key
     * @param array  $options
     * @return string
     */
    protected function parseKey($key, $options = [], $strict = false)
    {
        return $key;
    }

    /**
     * value分析
     * @access protected
     * @param mixed     $value
     * @param string    $field
     * @return string|array
     */
    protected function parseValue($value, $field = '')
    {
        if (is_string($value)) {
            $value = strpos($value, ':') === 0 && $this->query->isBind(substr($value, 1)) ? $value : $this->connection->quote($value);
        } elseif (is_array($value)) {
            $value = array_map([$this, 'parseValue'], $value);
        } elseif (is_bool($value)) {
            $value = $value ? '1' : '0';
        } elseif (is_null($value)) {
            $value = 'null';
        }
        return $value;
>>>>>>> main
    }

    /**
     * field分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query     查询对象
     * @param  mixed     $fields    字段名
     * @return string
     */
    protected function parseField(Query $query, $fields)
=======
     * @param mixed     $fields
     * @param array     $options
     * @return string
     */
    protected function parseField($fields, $options = [])
>>>>>>> main
    {
        if ('*' == $fields || empty($fields)) {
            $fieldsStr = '*';
        } elseif (is_array($fields)) {
            // 支持 'field1'=>'field2' 这样的字段别名定义
            $array = [];
<<<<<<< HEAD

            foreach ($fields as $key => $field) {
                if (!is_numeric($key)) {
                    $array[] = $this->parseKey($query, $key) . ' AS ' . $this->parseKey($query, $field, true);
                } else {
                    $array[] = $this->parseKey($query, $field);
                }
            }

            $fieldsStr = implode(',', $array);
        }

=======
            foreach ($fields as $key => $field) {
                if ($field instanceof Expression) {
                    $array[] = $field->getValue();
                } elseif (!is_numeric($key)) {
                    $array[] = $this->parseKey($key, $options) . ' AS ' . $this->parseKey($field, $options, true);
                } else {
                    $array[] = $this->parseKey($field, $options);
                }
            }
            $fieldsStr = implode(',', $array);
        }
>>>>>>> main
        return $fieldsStr;
    }

    /**
     * table分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query         查询对象
     * @param  mixed     $tables        表名
     * @return string
     */
    protected function parseTable(Query $query, $tables)
    {
        $item    = [];
        $options = $query->getOptions();

        foreach ((array) $tables as $key => $table) {
            if (!is_numeric($key)) {
                $key    = $this->connection->parseSqlTable($key);
                $item[] = $this->parseKey($query, $key) . ' ' . $this->parseKey($query, $table);
            } else {
                $table = $this->connection->parseSqlTable($table);

                if (isset($options['alias'][$table])) {
                    $item[] = $this->parseKey($query, $table) . ' ' . $this->parseKey($query, $options['alias'][$table]);
                } else {
                    $item[] = $this->parseKey($query, $table);
                }
            }
        }

=======
     * @param mixed $tables
     * @param array $options
     * @return string
     */
    protected function parseTable($tables, $options = [])
    {
        $item = [];
        foreach ((array) $tables as $key => $table) {
            if (!is_numeric($key)) {
                $key    = $this->parseSqlTable($key);
                $item[] = $this->parseKey($key) . ' ' . (isset($options['alias'][$table]) ? $this->parseKey($options['alias'][$table]) : $this->parseKey($table));
            } else {
                $table = $this->parseSqlTable($table);
                if (isset($options['alias'][$table])) {
                    $item[] = $this->parseKey($table) . ' ' . $this->parseKey($options['alias'][$table]);
                } else {
                    $item[] = $this->parseKey($table);
                }
            }
        }
>>>>>>> main
        return implode(',', $item);
    }

    /**
     * where分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query   查询对象
     * @param  mixed     $where   查询条件
     * @return string
     */
    protected function parseWhere(Query $query, $where)
    {
        $options  = $query->getOptions();
        $whereStr = $this->buildWhere($query, $where);

=======
     * @param mixed $where   查询条件
     * @param array $options 查询参数
     * @return string
     */
    protected function parseWhere($where, $options)
    {
        $whereStr = $this->buildWhere($where, $options);
>>>>>>> main
        if (!empty($options['soft_delete'])) {
            // 附加软删除条件
            list($field, $condition) = $options['soft_delete'];

<<<<<<< HEAD
            $binds    = $this->connection->getFieldsBind($options['table']);
            $whereStr = $whereStr ? '( ' . $whereStr . ' ) AND ' : '';
            $whereStr = $whereStr . $this->parseWhereItem($query, $field, $condition, '', $binds);
        }

=======
            $binds    = $this->query->getFieldsBind($options['table']);
            $whereStr = $whereStr ? '( ' . $whereStr . ' ) AND ' : '';
            $whereStr = $whereStr . $this->parseWhereItem($field, $condition, '', $options, $binds);
        }
>>>>>>> main
        return empty($whereStr) ? '' : ' WHERE ' . $whereStr;
    }

    /**
     * 生成查询条件SQL
     * @access public
<<<<<<< HEAD
     * @param  Query     $query     查询对象
     * @param  mixed     $where     查询条件
     * @return string
     */
    public function buildWhere(Query $query, $where)
=======
     * @param mixed     $where
     * @param array     $options
     * @return string
     */
    public function buildWhere($where, $options)
>>>>>>> main
    {
        if (empty($where)) {
            $where = [];
        }

<<<<<<< HEAD
        $whereStr = '';
        $binds    = $this->connection->getFieldsBind($query->getOptions('table'));

        foreach ($where as $logic => $val) {
            $str = [];

            foreach ($val as $value) {
                if ($value instanceof Expression) {
                    $str[] = ' ' . $logic . ' ( ' . $value->getValue() . ' )';
                    continue;
                }

                if (is_array($value)) {
                    if (key($value) !== 0) {
                        throw new Exception('where express error:' . var_export($value, true));
                    }
                    $field = array_shift($value);
                } elseif (!($value instanceof \Closure)) {
                    throw new Exception('where express error:' . var_export($value, true));
                }

                if ($value instanceof \Closure) {
                    // 使用闭包查询
                    $newQuery = $query->newQuery()->setConnection($this->connection);
                    $value($newQuery);
                    $whereClause = $this->buildWhere($newQuery, $newQuery->getOptions('where'));

                    if (!empty($whereClause)) {
                        $query->bind($newQuery->getBind(false));
                        $str[] = ' ' . $logic . ' ( ' . $whereClause . ' )';
                    }
                } elseif (is_array($field)) {
                    array_unshift($value, $field);
                    $str2 = [];
                    foreach ($value as $item) {
                        $str2[] = $this->parseWhereItem($query, array_shift($item), $item, $logic, $binds);
                    }

                    $str[] = ' ' . $logic . ' ( ' . implode(' AND ', $str2) . ' )';
=======
        if ($where instanceof Query) {
            return $this->buildWhere($where->getOptions('where'), $options);
        }

        $whereStr = '';
        $binds    = $this->query->getFieldsBind($options['table']);
        foreach ($where as $key => $val) {
            $str = [];
            foreach ($val as $field => $value) {
                if ($value instanceof Expression) {
                    $str[] = ' ' . $key . ' ( ' . $value->getValue() . ' )';
                } elseif ($value instanceof \Closure) {
                    // 使用闭包查询
                    $query = new Query($this->connection);
                    call_user_func_array($value, [ & $query]);
                    $whereClause = $this->buildWhere($query->getOptions('where'), $options);
                    if (!empty($whereClause)) {
                        $str[] = ' ' . $key . ' ( ' . $whereClause . ' )';
                    }
>>>>>>> main
                } elseif (strpos($field, '|')) {
                    // 不同字段使用相同查询条件（OR）
                    $array = explode('|', $field);
                    $item  = [];
<<<<<<< HEAD

                    foreach ($array as $k) {
                        $item[] = $this->parseWhereItem($query, $k, $value, '', $binds);
                    }

                    $str[] = ' ' . $logic . ' ( ' . implode(' OR ', $item) . ' )';
=======
                    foreach ($array as $k) {
                        $item[] = $this->parseWhereItem($k, $value, '', $options, $binds);
                    }
                    $str[] = ' ' . $key . ' ( ' . implode(' OR ', $item) . ' )';
>>>>>>> main
                } elseif (strpos($field, '&')) {
                    // 不同字段使用相同查询条件（AND）
                    $array = explode('&', $field);
                    $item  = [];
<<<<<<< HEAD

                    foreach ($array as $k) {
                        $item[] = $this->parseWhereItem($query, $k, $value, '', $binds);
                    }

                    $str[] = ' ' . $logic . ' ( ' . implode(' AND ', $item) . ' )';
                } else {
                    // 对字段使用表达式查询
                    $field = is_string($field) ? $field : '';
                    $str[] = ' ' . $logic . ' ' . $this->parseWhereItem($query, $field, $value, $logic, $binds);
                }
            }

            $whereStr .= empty($whereStr) ? substr(implode(' ', $str), strlen($logic) + 1) : implode(' ', $str);
=======
                    foreach ($array as $k) {
                        $item[] = $this->parseWhereItem($k, $value, '', $options, $binds);
                    }
                    $str[] = ' ' . $key . ' ( ' . implode(' AND ', $item) . ' )';
                } else {
                    // 对字段使用表达式查询
                    $field = is_string($field) ? $field : '';
                    $str[] = ' ' . $key . ' ' . $this->parseWhereItem($field, $value, $key, $options, $binds);
                }
            }

            $whereStr .= empty($whereStr) ? substr(implode(' ', $str), strlen($key) + 1) : implode(' ', $str);
>>>>>>> main
        }

        return $whereStr;
    }

    // where子单元分析
<<<<<<< HEAD
    protected function parseWhereItem(Query $query, $field, $val, $rule = '', $binds = [])
    {
        // 字段分析
        $key = $field ? $this->parseKey($query, $field, true) : '';

        // 查询规则和条件
        if (!is_array($val)) {
            $val = is_null($val) ? ['NULL', ''] : ['=', $val];
        }

=======
    protected function parseWhereItem($field, $val, $rule = '', $options = [], $binds = [], $bindName = null)
    {
        // 字段分析
        $key = $field ? $this->parseKey($field, $options, true) : '';

        // 查询规则和条件
        if (!is_array($val)) {
            $val = is_null($val) ? ['null', ''] : ['=', $val];
        }
>>>>>>> main
        list($exp, $value) = $val;

        // 对一个字段使用多个查询条件
        if (is_array($exp)) {
            $item = array_pop($val);
<<<<<<< HEAD

=======
>>>>>>> main
            // 传入 or 或者 and
            if (is_string($item) && in_array($item, ['AND', 'and', 'OR', 'or'])) {
                $rule = $item;
            } else {
                array_push($val, $item);
            }
<<<<<<< HEAD

            foreach ($val as $k => $item) {
                $str[] = $this->parseWhereItem($query, $field, $item, $rule, $binds);
            }

=======
            foreach ($val as $k => $item) {
                $bindName = 'where_' . str_replace('.', '_', $field) . '_' . $k;
                $str[]    = $this->parseWhereItem($field, $item, $rule, $options, $binds, $bindName);
            }
>>>>>>> main
            return '( ' . implode(' ' . $rule . ' ', $str) . ' )';
        }

        // 检测操作符
<<<<<<< HEAD
        $exp = strtoupper($exp);
        if (isset($this->exp[$exp])) {
            $exp = $this->exp[$exp];
=======
        if (!in_array($exp, $this->exp)) {
            $exp = strtolower($exp);
            if (isset($this->exp[$exp])) {
                $exp = $this->exp[$exp];
            } else {
                throw new Exception('where express error:' . $exp);
            }
        }
        $bindName = $bindName ?: 'where_' . $rule . '_' . str_replace(['.', '-'], '_', $field);
        if (preg_match('/\W/', $bindName)) {
            // 处理带非单词字符的字段名
            $bindName = md5($bindName);
>>>>>>> main
        }

        if ($value instanceof Expression) {

        } elseif (is_object($value) && method_exists($value, '__toString')) {
            // 对象数据写入
            $value = $value->__toString();
        }

<<<<<<< HEAD
        if (strpos($field, '->')) {
            $jsonType = $query->getJsonFieldType($field);
            $bindType = $this->connection->getFieldBindType($jsonType);
        } else {
            $bindType = isset($binds[$field]) && 'LIKE' != $exp ? $binds[$field] : PDO::PARAM_STR;
        }

        if (is_scalar($value) && !in_array($exp, ['EXP', 'NOT NULL', 'NULL', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN']) && strpos($exp, 'TIME') === false) {
            if (0 === strpos($value, ':') && $query->isBind(substr($value, 1))) {
            } else {
                $name  = $query->bind($value, $bindType);
                $value = ':' . $name;
            }
        }

        // 解析查询表达式
        foreach ($this->parser as $fun => $parse) {
            if (in_array($exp, $parse)) {
                $whereStr = $this->$fun($query, $key, $exp, $value, $field, $bindType, isset($val[2]) ? $val[2] : 'AND');
                break;
            }
        }

        if (!isset($whereStr)) {
            throw new Exception('where express error:' . $exp);
        }

        return $whereStr;
    }

    /**
     * 模糊查询
     * @access protected
     * @param  Query     $query        查询对象
     * @param  string    $key
     * @param  string    $exp
     * @param  mixed     $value
     * @param  string    $field
     * @param  integer   $bindType
     * @param  string    $logic
     * @return string
     */
    protected function parseLike(Query $query, $key, $exp, $value, $field, $bindType, $logic)
    {
        // 模糊匹配
        if (is_array($value)) {
            foreach ($value as $item) {
                $name    = $query->bind($item, PDO::PARAM_STR);
                $array[] = $key . ' ' . $exp . ' :' . $name;
            }

            $whereStr = '(' . implode(' ' . strtoupper($logic) . ' ', $array) . ')';
        } else {
            $whereStr = $key . ' ' . $exp . ' ' . $value;
        }

        return $whereStr;
    }

    /**
     * 表达式查询
     * @access protected
     * @param  Query        $query        查询对象
     * @param  string       $key
     * @param  string       $exp
     * @param  array        $value
     * @param  string       $field
     * @param  integer      $bindType
     * @return string
     */
    protected function parseColumn(Query $query, $key, $exp, array $value, $field, $bindType)
    {
        // 字段比较查询
        list($op, $field2) = $value;

        if (!in_array($op, ['=', '<>', '>', '>=', '<', '<='])) {
            throw new Exception('where express error:' . var_export($value, true));
        }

        return '( ' . $key . ' ' . $op . ' ' . $this->parseKey($query, $field2, true) . ' )';
    }

    /**
     * 表达式查询
     * @access protected
     * @param  Query        $query        查询对象
     * @param  string       $key
     * @param  string       $exp
     * @param  Expression   $value
     * @param  string       $field
     * @param  integer      $bindType
     * @return string
     */
    protected function parseExp(Query $query, $key, $exp, Expression $value, $field, $bindType)
    {
        // 表达式查询
        return '( ' . $key . ' ' . $value->getValue() . ' )';
    }

    /**
     * Null查询
     * @access protected
     * @param  Query     $query        查询对象
     * @param  string    $key
     * @param  string    $exp
     * @param  mixed     $value
     * @param  string    $field
     * @param  integer   $bindType
     * @return string
     */
    protected function parseNull(Query $query, $key, $exp, $value, $field, $bindType)
    {
        // NULL 查询
        return $key . ' IS ' . $exp;
    }

    /**
     * 范围查询
     * @access protected
     * @param  Query     $query        查询对象
     * @param  string    $key
     * @param  string    $exp
     * @param  mixed     $value
     * @param  string    $field
     * @param  integer   $bindType
     * @return string
     */
    protected function parseBetween(Query $query, $key, $exp, $value, $field, $bindType)
    {
        // BETWEEN 查询
        $data = is_array($value) ? $value : explode(',', $value);

        $min = $query->bind($data[0], $bindType);
        $max = $query->bind($data[1], $bindType);

        return $key . ' ' . $exp . ' :' . $min . ' AND :' . $max . ' ';
    }

    /**
     * Exists查询
     * @access protected
     * @param  Query     $query        查询对象
     * @param  string    $key
     * @param  string    $exp
     * @param  mixed     $value
     * @param  string    $field
     * @param  integer   $bindType
     * @return string
     */
    protected function parseExists(Query $query, $key, $exp, $value, $field, $bindType)
    {
        // EXISTS 查询
        if ($value instanceof \Closure) {
            $value = $this->parseClosure($query, $value, false);
        } elseif ($value instanceof Expression) {
            $value = $value->getValue();
        } else {
            throw new Exception('where express error:' . $value);
        }

        return $exp . ' (' . $value . ')';
    }

    /**
     * 时间比较查询
     * @access protected
     * @param  Query     $query        查询对象
     * @param  string    $key
     * @param  string    $exp
     * @param  mixed     $value
     * @param  string    $field
     * @param  integer   $bindType
     * @return string
     */
    protected function parseTime(Query $query, $key, $exp, $value, $field, $bindType)
    {
        return $key . ' ' . substr($exp, 0, 2) . ' ' . $this->parseDateTime($query, $value, $field, $bindType);
    }

    /**
     * 大小比较查询
     * @access protected
     * @param  Query     $query        查询对象
     * @param  string    $key
     * @param  string    $exp
     * @param  mixed     $value
     * @param  string    $field
     * @param  integer   $bindType
     * @return string
     */
    protected function parseCompare(Query $query, $key, $exp, $value, $field, $bindType)
    {
        if (is_array($value)) {
            throw new Exception('where express error:' . $exp . var_export($value, true));
        }

        // 比较运算
        if ($value instanceof \Closure) {
            $value = $this->parseClosure($query, $value);
        }

        if ('=' == $exp && is_null($value)) {
            return $key . ' IS NULL';
        }

        return $key . ' ' . $exp . ' ' . $value;
    }

    /**
     * 时间范围查询
     * @access protected
     * @param  Query     $query        查询对象
     * @param  string    $key
     * @param  string    $exp
     * @param  mixed     $value
     * @param  string    $field
     * @param  integer   $bindType
     * @return string
     */
    protected function parseBetweenTime(Query $query, $key, $exp, $value, $field, $bindType)
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        return $key . ' ' . substr($exp, 0, -4)
        . $this->parseDateTime($query, $value[0], $field, $bindType)
        . ' AND '
        . $this->parseDateTime($query, $value[1], $field, $bindType);

    }

    /**
     * IN查询
     * @access protected
     * @param  Query     $query        查询对象
     * @param  string    $key
     * @param  string    $exp
     * @param  mixed     $value
     * @param  string    $field
     * @param  integer   $bindType
     * @return string
     */
    protected function parseIn(Query $query, $key, $exp, $value, $field, $bindType)
    {
        // IN 查询
        if ($value instanceof \Closure) {
            $value = $this->parseClosure($query, $value, false);
        } elseif ($value instanceof Expression) {
            $value = $value->getValue();
        } else {
            $value = array_unique(is_array($value) ? $value : explode(',', $value));
            $array = [];

            foreach ($value as $k => $v) {
                $name    = $query->bind($v, $bindType);
                $array[] = ':' . $name;
            }

            if (count($array) == 1) {
                return $key . ('IN' == $exp ? ' = ' : ' <> ') . $array[0];
            } else {
                $zone  = implode(',', $array);
                $value = empty($zone) ? "''" : $zone;
            }
        }

        return $key . ' ' . $exp . ' (' . $value . ')';
    }

    /**
     * 闭包子查询
     * @access protected
     * @param  Query     $query        查询对象
     * @param  \Closure  $call
     * @param  bool      $show
     * @return string
     */
    protected function parseClosure(Query $query, $call, $show = true)
    {
        $newQuery = $query->newQuery()->removeOption();
        $call($newQuery);

        return $newQuery->buildSql($show);
=======
        $bindType = isset($binds[$field]) ? $binds[$field] : PDO::PARAM_STR;
        if (is_scalar($value) && array_key_exists($field, $binds) && !in_array($exp, ['EXP', 'NOT NULL', 'NULL', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN']) && strpos($exp, 'TIME') === false) {
            if (strpos($value, ':') !== 0 || !$this->query->isBind(substr($value, 1))) {
                if ($this->query->isBind($bindName)) {
                    $bindName .= '_' . str_replace('.', '_', uniqid('', true));
                }
                $this->query->bind($bindName, $value, $bindType);
                $value = ':' . $bindName;
            }
        }

        $whereStr = '';
        if (in_array($exp, ['=', '<>', '>', '>=', '<', '<='])) {
            // 比较运算
            if ($value instanceof \Closure) {
                $whereStr .= $key . ' ' . $exp . ' ' . $this->parseClosure($value);
            } else {
                $whereStr .= $key . ' ' . $exp . ' ' . $this->parseValue($value, $field);
            }
        } elseif ('LIKE' == $exp || 'NOT LIKE' == $exp) {
            // 模糊匹配
            if (is_array($value)) {
                foreach ($value as $item) {
                    $array[] = $key . ' ' . $exp . ' ' . $this->parseValue($item, $field);
                }
                $logic = isset($val[2]) ? $val[2] : 'AND';
                $whereStr .= '(' . implode($array, ' ' . strtoupper($logic) . ' ') . ')';
            } else {
                $whereStr .= $key . ' ' . $exp . ' ' . $this->parseValue($value, $field);
            }
        } elseif ('EXP' == $exp) {
            // 表达式查询
            if ($value instanceof Expression) {
                $whereStr .= '( ' . $key . ' ' . $value->getValue() . ' )';
            } else {
                throw new Exception('where express error:' . $exp);
            }
        } elseif (in_array($exp, ['NOT NULL', 'NULL'])) {
            // NULL 查询
            $whereStr .= $key . ' IS ' . $exp;
        } elseif (in_array($exp, ['NOT IN', 'IN'])) {
            // IN 查询
            if ($value instanceof \Closure) {
                $whereStr .= $key . ' ' . $exp . ' ' . $this->parseClosure($value);
            } else {
                $value = array_unique(is_array($value) ? $value : explode(',', $value));
                if (array_key_exists($field, $binds)) {
                    $bind  = [];
                    $array = [];
                    $i     = 0;
                    foreach ($value as $v) {
                        $i++;
                        if ($this->query->isBind($bindName . '_in_' . $i)) {
                            $bindKey = $bindName . '_in_' . uniqid() . '_' . $i;
                        } else {
                            $bindKey = $bindName . '_in_' . $i;
                        }
                        $bind[$bindKey] = [$v, $bindType];
                        $array[]        = ':' . $bindKey;
                    }
                    $this->query->bind($bind);
                    $zone = implode(',', $array);
                } else {
                    $zone = implode(',', $this->parseValue($value, $field));
                }
                $whereStr .= $key . ' ' . $exp . ' (' . (empty($zone) ? "''" : $zone) . ')';
            }
        } elseif (in_array($exp, ['NOT BETWEEN', 'BETWEEN'])) {
            // BETWEEN 查询
            $data = is_array($value) ? $value : explode(',', $value);
            if (array_key_exists($field, $binds)) {
                if ($this->query->isBind($bindName . '_between_1')) {
                    $bindKey1 = $bindName . '_between_1' . uniqid();
                    $bindKey2 = $bindName . '_between_2' . uniqid();
                } else {
                    $bindKey1 = $bindName . '_between_1';
                    $bindKey2 = $bindName . '_between_2';
                }
                $bind = [
                    $bindKey1 => [$data[0], $bindType],
                    $bindKey2 => [$data[1], $bindType],
                ];
                $this->query->bind($bind);
                $between = ':' . $bindKey1 . ' AND :' . $bindKey2;
            } else {
                $between = $this->parseValue($data[0], $field) . ' AND ' . $this->parseValue($data[1], $field);
            }
            $whereStr .= $key . ' ' . $exp . ' ' . $between;
        } elseif (in_array($exp, ['NOT EXISTS', 'EXISTS'])) {
            // EXISTS 查询
            if ($value instanceof \Closure) {
                $whereStr .= $exp . ' ' . $this->parseClosure($value);
            } else {
                $whereStr .= $exp . ' (' . $value . ')';
            }
        } elseif (in_array($exp, ['< TIME', '> TIME', '<= TIME', '>= TIME'])) {
            $whereStr .= $key . ' ' . substr($exp, 0, 2) . ' ' . $this->parseDateTime($value, $field, $options, $bindName, $bindType);
        } elseif (in_array($exp, ['BETWEEN TIME', 'NOT BETWEEN TIME'])) {
            if (is_string($value)) {
                $value = explode(',', $value);
            }

            $whereStr .= $key . ' ' . substr($exp, 0, -4) . $this->parseDateTime($value[0], $field, $options, $bindName . '_between_1', $bindType) . ' AND ' . $this->parseDateTime($value[1], $field, $options, $bindName . '_between_2', $bindType);
        }
        return $whereStr;
    }

    // 执行闭包子查询
    protected function parseClosure($call, $show = true)
    {
        $query = new Query($this->connection);
        call_user_func_array($call, [ & $query]);
        return $query->buildSql($show);
>>>>>>> main
    }

    /**
     * 日期时间条件解析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @param  string    $value
     * @param  string    $key
     * @param  integer   $bindType
     * @return string
     */
    protected function parseDateTime(Query $query, $value, $key, $bindType = null)
    {
        $options = $query->getOptions();

        // 获取时间字段类型
        if (strpos($key, '.')) {
            list($table, $key) = explode('.', $key);

=======
     * @param string    $value
     * @param string    $key
     * @param array     $options
     * @param string    $bindName
     * @param integer   $bindType
     * @return string
     */
    protected function parseDateTime($value, $key, $options = [], $bindName = null, $bindType = null)
    {
        // 获取时间字段类型
        if (strpos($key, '.')) {
            list($table, $key) = explode('.', $key);
>>>>>>> main
            if (isset($options['alias']) && $pos = array_search($table, $options['alias'])) {
                $table = $pos;
            }
        } else {
            $table = $options['table'];
        }
<<<<<<< HEAD

        $type = $this->connection->getTableInfo($table, 'type');

        if (isset($type[$key])) {
            $info = $type[$key];
        }

=======
        $type = $this->query->getTableInfo($table, 'type');
        if (isset($type[$key])) {
            $info = $type[$key];
        }
>>>>>>> main
        if (isset($info)) {
            if (is_string($value)) {
                $value = strtotime($value) ?: $value;
            }

            if (preg_match('/(datetime|timestamp)/is', $info)) {
                // 日期及时间戳类型
                $value = date('Y-m-d H:i:s', $value);
            } elseif (preg_match('/(date)/is', $info)) {
                // 日期及时间戳类型
                $value = date('Y-m-d', $value);
            }
        }
<<<<<<< HEAD

        $name = $query->bind($value, $bindType);

        return ':' . $name;
=======
        $bindName = $bindName ?: $key;

        if ($this->query->isBind($bindName)) {
            $bindName .= '_' . str_replace('.', '_', uniqid('', true));
        }

        $this->query->bind($bindName, $value, $bindType);
        return ':' . $bindName;
>>>>>>> main
    }

    /**
     * limit分析
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
        return (!empty($limit) && false === strpos($limit, '(')) ? ' LIMIT ' . $limit . ' ' : '';
    }

    /**
     * join分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @param  array     $join
     * @return string
     */
    protected function parseJoin(Query $query, $join)
    {
        $joinStr = '';

        if (!empty($join)) {
            foreach ($join as $item) {
                list($table, $type, $on) = $item;

                $condition = [];

=======
     * @param array $join
     * @param array $options 查询条件
     * @return string
     */
    protected function parseJoin($join, $options = [])
    {
        $joinStr = '';
        if (!empty($join)) {
            foreach ($join as $item) {
                list($table, $type, $on) = $item;
                $condition               = [];
>>>>>>> main
                foreach ((array) $on as $val) {
                    if ($val instanceof Expression) {
                        $condition[] = $val->getValue();
                    } elseif (strpos($val, '=')) {
                        list($val1, $val2) = explode('=', $val, 2);
<<<<<<< HEAD

                        $condition[] = $this->parseKey($query, $val1) . '=' . $this->parseKey($query, $val2);
=======
                        $condition[]       = $this->parseKey($val1, $options) . '=' . $this->parseKey($val2, $options);
>>>>>>> main
                    } else {
                        $condition[] = $val;
                    }
                }

<<<<<<< HEAD
                $table = $this->parseTable($query, $table);

                $joinStr .= ' ' . $type . ' JOIN ' . $table . ' ON ' . implode(' AND ', $condition);
            }
        }

=======
                $table = $this->parseTable($table, $options);
                $joinStr .= ' ' . $type . ' JOIN ' . $table . ' ON ' . implode(' AND ', $condition);
            }
        }
>>>>>>> main
        return $joinStr;
    }

    /**
     * order分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @param  mixed     $order
     * @return string
     */
    protected function parseOrder(Query $query, $order)
    {
        foreach ($order as $key => $val) {
            if ($val instanceof Expression) {
                $array[] = $val->getValue();
            } elseif (is_array($val) && preg_match('/^[\w\.]+$/', $key)) {
                $array[] = $this->parseOrderField($query, $key, $val);
            } elseif ('[rand]' == $val) {
                $array[] = $this->parseRand($query);
            } elseif (is_string($val)) {
=======
     * @param mixed $order
     * @param array $options 查询条件
     * @return string
     */
    protected function parseOrder($order, $options = [])
    {
        if (empty($order)) {
            return '';
        }

        $array = [];
        foreach ($order as $key => $val) {
            if ($val instanceof Expression) {
                $array[] = $val->getValue();
            } elseif ('[rand]' == $val) {
                $array[] = $this->parseRand();
            } else {
>>>>>>> main
                if (is_numeric($key)) {
                    list($key, $sort) = explode(' ', strpos($val, ' ') ? $val : $val . ' ');
                } else {
                    $sort = $val;
                }
<<<<<<< HEAD

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
    }

    /**
     * orderField分析
     * @access protected
     * @param  Query     $query        查询对象
     * @param  mixed     $key
     * @param  array     $val
     * @return string
     */
    protected function parseOrderField($query, $key, $val)
    {
        if (isset($val['sort'])) {
            $sort = $val['sort'];
            unset($val['sort']);
        } else {
            $sort = '';
        }

        $sort = strtoupper($sort);
        $sort = in_array($sort, ['ASC', 'DESC'], true) ? ' ' . $sort : '';

        $options = $query->getOptions();
        $bind    = $this->connection->getFieldsBind($options['table']);

        foreach ($val as $k => $item) {
            $val[$k] = $this->parseDataBind($query, $key, $item, $bind);
        }

        return 'field(' . $this->parseKey($query, $key, true) . ',' . implode(',', $val) . ')' . $sort;
=======
                $sort    = strtoupper($sort);
                $sort    = in_array($sort, ['ASC', 'DESC'], true) ? ' ' . $sort : '';
                $array[] = $this->parseKey($key, $options, true) . $sort;
            }
        }
        $order = implode(',', $array);

        return !empty($order) ? ' ORDER BY ' . $order : '';
>>>>>>> main
    }

    /**
     * group分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @param  mixed     $group
     * @return string
     */
    protected function parseGroup(Query $query, $group)
    {
        if (empty($group)) {
            return '';
        }

        if (is_string($group)) {
            $group = explode(',', $group);
        }

        foreach ($group as $key) {
            $val[] = $this->parseKey($query, $key);
        }

        return ' GROUP BY ' . implode(',', $val);
=======
     * @param mixed $group
     * @return string
     */
    protected function parseGroup($group)
    {
        return !empty($group) ? ' GROUP BY ' . $this->parseKey($group) : '';
>>>>>>> main
    }

    /**
     * having分析
     * @access protected
<<<<<<< HEAD
     * @param  Query  $query        查询对象
     * @param  string $having
     * @return string
     */
    protected function parseHaving(Query $query, $having)
=======
     * @param string $having
     * @return string
     */
    protected function parseHaving($having)
>>>>>>> main
    {
        return !empty($having) ? ' HAVING ' . $having : '';
    }

    /**
     * comment分析
     * @access protected
<<<<<<< HEAD
     * @param  Query  $query        查询对象
     * @param  string $comment
     * @return string
     */
    protected function parseComment(Query $query, $comment)
=======
     * @param string $comment
     * @return string
     */
    protected function parseComment($comment)
>>>>>>> main
    {
        if (false !== strpos($comment, '*/')) {
            $comment = strstr($comment, '*/', true);
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return !empty($comment) ? ' /* ' . $comment . ' */' : '';
    }

    /**
     * distinct分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @param  mixed     $distinct
     * @return string
     */
    protected function parseDistinct(Query $query, $distinct)
=======
     * @param mixed $distinct
     * @return string
     */
    protected function parseDistinct($distinct)
>>>>>>> main
    {
        return !empty($distinct) ? ' DISTINCT ' : '';
    }

    /**
     * union分析
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @param  mixed     $union
     * @return string
     */
    protected function parseUnion(Query $query, $union)
=======
     * @param mixed $union
     * @return string
     */
    protected function parseUnion($union)
>>>>>>> main
    {
        if (empty($union)) {
            return '';
        }
<<<<<<< HEAD

        $type = $union['type'];
        unset($union['type']);

        foreach ($union as $u) {
            if ($u instanceof \Closure) {
                $sql[] = $type . ' ' . $this->parseClosure($query, $u);
            } elseif (is_string($u)) {
                $sql[] = $type . ' ( ' . $this->connection->parseSqlTable($u) . ' )';
            }
        }

=======
        $type = $union['type'];
        unset($union['type']);
        foreach ($union as $u) {
            if ($u instanceof \Closure) {
                $sql[] = $type . ' ' . $this->parseClosure($u);
            } elseif (is_string($u)) {
                $sql[] = $type . ' ( ' . $this->parseSqlTable($u) . ' )';
            }
        }
>>>>>>> main
        return ' ' . implode(' ', $sql);
    }

    /**
     * index分析，可在操作链中指定需要强制使用的索引
     * @access protected
<<<<<<< HEAD
     * @param  Query     $query        查询对象
     * @param  mixed     $index
     * @return string
     */
    protected function parseForce(Query $query, $index)
=======
     * @param mixed $index
     * @return string
     */
    protected function parseForce($index)
>>>>>>> main
    {
        if (empty($index)) {
            return '';
        }

        return sprintf(" FORCE INDEX ( %s ) ", is_array($index) ? implode(',', $index) : $index);
    }

    /**
     * 设置锁机制
     * @access protected
<<<<<<< HEAD
     * @param  Query         $query        查询对象
     * @param  bool|string   $lock
     * @return string
     */
    protected function parseLock(Query $query, $lock = false)
    {
        if (is_bool($lock)) {
            return $lock ? ' FOR UPDATE ' : '';
        } elseif (is_string($lock) && !empty($lock)) {
=======
     * @param bool|string $lock
     * @return string
     */
    protected function parseLock($lock = false)
    {
        if (is_bool($lock)) {
            return $lock ? ' FOR UPDATE ' : '';
        } elseif (is_string($lock)) {
>>>>>>> main
            return ' ' . trim($lock) . ' ';
        }
    }

    /**
     * 生成查询SQL
     * @access public
<<<<<<< HEAD
     * @param  Query  $query  查询对象
     * @return string
     */
    public function select(Query $query)
    {
        $options = $query->getOptions();

        return str_replace(
            ['%TABLE%', '%DISTINCT%', '%FIELD%', '%JOIN%', '%WHERE%', '%GROUP%', '%HAVING%', '%ORDER%', '%LIMIT%', '%UNION%', '%LOCK%', '%COMMENT%', '%FORCE%'],
            [
                $this->parseTable($query, $options['table']),
                $this->parseDistinct($query, $options['distinct']),
                $this->parseField($query, $options['field']),
                $this->parseJoin($query, $options['join']),
                $this->parseWhere($query, $options['where']),
                $this->parseGroup($query, $options['group']),
                $this->parseHaving($query, $options['having']),
                $this->parseOrder($query, $options['order']),
                $this->parseLimit($query, $options['limit']),
                $this->parseUnion($query, $options['union']),
                $this->parseLock($query, $options['lock']),
                $this->parseComment($query, $options['comment']),
                $this->parseForce($query, $options['force']),
            ],
            $this->selectSql);
    }

    /**
     * 生成Insert SQL
     * @access public
     * @param  Query     $query   查询对象
     * @param  bool      $replace 是否replace
     * @return string
     */
    public function insert(Query $query, $replace = false)
    {
        $options = $query->getOptions();

        // 分析并处理数据
        $data = $this->parseData($query, $options['data']);
        if (empty($data)) {
            return '';
        }

        $fields = array_keys($data);
        $values = array_values($data);

        return str_replace(
            ['%INSERT%', '%TABLE%', '%FIELD%', '%DATA%', '%COMMENT%'],
            [
                $replace ? 'REPLACE' : 'INSERT',
                $this->parseTable($query, $options['table']),
                implode(' , ', $fields),
                implode(' , ', $values),
                $this->parseComment($query, $options['comment']),
            ],
            $this->insertSql);
=======
     * @param array $options 表达式
     * @return string
     */
    public function select($options = [])
    {
        $sql = str_replace(
            ['%TABLE%', '%DISTINCT%', '%FIELD%', '%JOIN%', '%WHERE%', '%GROUP%', '%HAVING%', '%ORDER%', '%LIMIT%', '%UNION%', '%LOCK%', '%COMMENT%', '%FORCE%'],
            [
                $this->parseTable($options['table'], $options),
                $this->parseDistinct($options['distinct']),
                $this->parseField($options['field'], $options),
                $this->parseJoin($options['join'], $options),
                $this->parseWhere($options['where'], $options),
                $this->parseGroup($options['group']),
                $this->parseHaving($options['having']),
                $this->parseOrder($options['order'], $options),
                $this->parseLimit($options['limit']),
                $this->parseUnion($options['union']),
                $this->parseLock($options['lock']),
                $this->parseComment($options['comment']),
                $this->parseForce($options['force']),
            ], $this->selectSql);
        return $sql;
    }

    /**
     * 生成insert SQL
     * @access public
     * @param array     $data 数据
     * @param array     $options 表达式
     * @param bool      $replace 是否replace
     * @return string
     */
    public function insert(array $data, $options = [], $replace = false)
    {
        // 分析并处理数据
        $data = $this->parseData($data, $options);
        if (empty($data)) {
            return 0;
        }
        $fields = array_keys($data);
        $values = array_values($data);

        $sql = str_replace(
            ['%INSERT%', '%TABLE%', '%FIELD%', '%DATA%', '%COMMENT%'],
            [
                $replace ? 'REPLACE' : 'INSERT',
                $this->parseTable($options['table'], $options),
                implode(' , ', $fields),
                implode(' , ', $values),
                $this->parseComment($options['comment']),
            ], $this->insertSql);

        return $sql;
>>>>>>> main
    }

    /**
     * 生成insertall SQL
     * @access public
<<<<<<< HEAD
     * @param  Query     $query   查询对象
     * @param  array     $dataSet 数据集
     * @param  bool      $replace 是否replace
     * @return string
     */
    public function insertAll(Query $query, $dataSet, $replace = false)
    {
        $options = $query->getOptions();

        // 获取合法的字段
        if ('*' == $options['field']) {
            $allowFields = $this->connection->getTableFields($options['table']);
        } else {
            $allowFields = $options['field'];
        }

        // 获取绑定信息
        $bind = $this->connection->getFieldsBind($options['table']);

        foreach ($dataSet as $data) {
            $data = $this->parseData($query, $data, $allowFields, $bind);

            $values[] = 'SELECT ' . implode(',', array_values($data));
=======
     * @param array     $dataSet 数据集
     * @param array     $options 表达式
     * @param bool      $replace 是否replace
     * @return string
     * @throws Exception
     */
    public function insertAll($dataSet, $options = [], $replace = false)
    {
        // 获取合法的字段
        if ('*' == $options['field']) {
            $fields = array_keys($this->query->getFieldsType($options['table']));
        } else {
            $fields = $options['field'];
        }

        foreach ($dataSet as $data) {
            foreach ($data as $key => $val) {
                if (!in_array($key, $fields, true)) {
                    if ($options['strict']) {
                        throw new Exception('fields not exists:[' . $key . ']');
                    }
                    unset($data[$key]);
                } elseif (is_null($val)) {
                    $data[$key] = 'NULL';
                } elseif (is_scalar($val)) {
                    $data[$key] = $this->parseValue($val, $key);
                } elseif (is_object($val) && method_exists($val, '__toString')) {
                    // 对象数据写入
                    $data[$key] = $val->__toString();
                } else {
                    // 过滤掉非标量数据
                    unset($data[$key]);
                }
            }
            $value    = array_values($data);
            $values[] = 'SELECT ' . implode(',', $value);
>>>>>>> main

            if (!isset($insertFields)) {
                $insertFields = array_keys($data);
            }
        }

<<<<<<< HEAD
        $fields = [];

        foreach ($insertFields as $field) {
            $fields[] = $this->parseKey($query, $field);
=======
        foreach ($insertFields as $field) {
            $fields[] = $this->parseKey($field, $options, true);
>>>>>>> main
        }

        return str_replace(
            ['%INSERT%', '%TABLE%', '%FIELD%', '%DATA%', '%COMMENT%'],
            [
                $replace ? 'REPLACE' : 'INSERT',
<<<<<<< HEAD
                $this->parseTable($query, $options['table']),
                implode(' , ', $fields),
                implode(' UNION ALL ', $values),
                $this->parseComment($query, $options['comment']),
            ],
            $this->insertAllSql);
    }

    /**
     * 生成slect insert SQL
     * @access public
     * @param  Query     $query  查询对象
     * @param  array     $fields 数据
     * @param  string    $table  数据表
     * @return string
     */
    public function selectInsert(Query $query, $fields, $table)
=======
                $this->parseTable($options['table'], $options),
                implode(' , ', $insertFields),
                implode(' UNION ALL ', $values),
                $this->parseComment($options['comment']),
            ], $this->insertAllSql);
    }

    /**
     * 生成select insert SQL
     * @access public
     * @param array     $fields 数据
     * @param string    $table 数据表
     * @param array     $options 表达式
     * @return string
     */
    public function selectInsert($fields, $table, $options)
>>>>>>> main
    {
        if (is_string($fields)) {
            $fields = explode(',', $fields);
        }

<<<<<<< HEAD
        foreach ($fields as &$field) {
            $field = $this->parseKey($query, $field, true);
        }

        return 'INSERT INTO ' . $this->parseTable($query, $table) . ' (' . implode(',', $fields) . ') ' . $this->select($query);
=======
        $fields = array_map([$this, 'parseKey'], $fields);
        $sql    = 'INSERT INTO ' . $this->parseTable($table, $options) . ' (' . implode(',', $fields) . ') ' . $this->select($options);
        return $sql;
>>>>>>> main
    }

    /**
     * 生成update SQL
     * @access public
<<<<<<< HEAD
     * @param  Query     $query  查询对象
     * @return string
     */
    public function update(Query $query)
    {
        $options = $query->getOptions();

        $data = $this->parseData($query, $options['data']);

        if (empty($data)) {
            return '';
        }

        foreach ($data as $key => $val) {
            $set[] = $key . ' = ' . $val;
        }

        return str_replace(
            ['%TABLE%', '%SET%', '%JOIN%', '%WHERE%', '%ORDER%', '%LIMIT%', '%LOCK%', '%COMMENT%'],
            [
                $this->parseTable($query, $options['table']),
                implode(' , ', $set),
                $this->parseJoin($query, $options['join']),
                $this->parseWhere($query, $options['where']),
                $this->parseOrder($query, $options['order']),
                $this->parseLimit($query, $options['limit']),
                $this->parseLock($query, $options['lock']),
                $this->parseComment($query, $options['comment']),
            ],
            $this->updateSql);
=======
     * @param array     $data 数据
     * @param array     $options 表达式
     * @return string
     */
    public function update($data, $options)
    {
        $table = $this->parseTable($options['table'], $options);
        $data  = $this->parseData($data, $options);
        if (empty($data)) {
            return '';
        }
        foreach ($data as $key => $val) {
            $set[] = $key . '=' . $val;
        }

        $sql = str_replace(
            ['%TABLE%', '%SET%', '%JOIN%', '%WHERE%', '%ORDER%', '%LIMIT%', '%LOCK%', '%COMMENT%'],
            [
                $this->parseTable($options['table'], $options),
                implode(',', $set),
                $this->parseJoin($options['join'], $options),
                $this->parseWhere($options['where'], $options),
                $this->parseOrder($options['order'], $options),
                $this->parseLimit($options['limit']),
                $this->parseLock($options['lock']),
                $this->parseComment($options['comment']),
            ], $this->updateSql);

        return $sql;
>>>>>>> main
    }

    /**
     * 生成delete SQL
     * @access public
<<<<<<< HEAD
     * @param  Query  $query  查询对象
     * @return string
     */
    public function delete(Query $query)
    {
        $options = $query->getOptions();

        return str_replace(
            ['%TABLE%', '%USING%', '%JOIN%', '%WHERE%', '%ORDER%', '%LIMIT%', '%LOCK%', '%COMMENT%'],
            [
                $this->parseTable($query, $options['table']),
                !empty($options['using']) ? ' USING ' . $this->parseTable($query, $options['using']) . ' ' : '',
                $this->parseJoin($query, $options['join']),
                $this->parseWhere($query, $options['where']),
                $this->parseOrder($query, $options['order']),
                $this->parseLimit($query, $options['limit']),
                $this->parseLock($query, $options['lock']),
                $this->parseComment($query, $options['comment']),
            ],
            $this->deleteSql);
=======
     * @param array $options 表达式
     * @return string
     */
    public function delete($options)
    {
        $sql = str_replace(
            ['%TABLE%', '%USING%', '%JOIN%', '%WHERE%', '%ORDER%', '%LIMIT%', '%LOCK%', '%COMMENT%'],
            [
                $this->parseTable($options['table'], $options),
                !empty($options['using']) ? ' USING ' . $this->parseTable($options['using'], $options) . ' ' : '',
                $this->parseJoin($options['join'], $options),
                $this->parseWhere($options['where'], $options),
                $this->parseOrder($options['order'], $options),
                $this->parseLimit($options['limit']),
                $this->parseLock($options['lock']),
                $this->parseComment($options['comment']),
            ], $this->deleteSql);

        return $sql;
>>>>>>> main
    }
}
