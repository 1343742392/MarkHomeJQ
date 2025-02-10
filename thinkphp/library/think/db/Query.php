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
<<<<<<< HEAD
use think\Collection;
use think\Container;
=======
use think\App;
use think\Cache;
use think\Collection;
use think\Config;
>>>>>>> main
use think\Db;
use think\db\exception\BindParamException;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\exception\PDOException;
use think\Loader;
use think\Model;
<<<<<<< HEAD
use think\model\Collection as ModelCollection;
=======
>>>>>>> main
use think\model\Relation;
use think\model\relation\OneToOne;
use think\Paginator;

class Query
{
<<<<<<< HEAD
    /**
     * 当前数据库连接对象
     * @var Connection
     */
    protected $connection;

    /**
     * 当前模型对象
     * @var Model
     */
    protected $model;

    /**
     * 当前数据表名称（不含前缀）
     * @var string
     */
    protected $name = '';

    /**
     * 当前数据表主键
     * @var string|array
     */
    protected $pk;

    /**
     * 当前数据表前缀
     * @var string
     */
    protected $prefix = '';

    /**
     * 当前查询参数
     * @var array
     */
    protected $options = [];

    /**
     * 当前参数绑定
     * @var array
     */
    protected $bind = [];

    /**
     * 事件回调
     * @var array
     */
    private static $event = [];

    /**
     * 扩展查询方法
     * @var array
     */
    private static $extend = [];

    /**
     * 读取主库的表
     * @var array
     */
    protected static $readMaster = [];

    /**
     * 日期查询表达式
     * @var array
     */
    protected $timeRule = [
        'today'      => ['today', 'tomorrow -1second'],
        'yesterday'  => ['yesterday', 'today -1second'],
        'week'       => ['this week 00:00:00', 'next week 00:00:00 -1second'],
        'last week'  => ['last week 00:00:00', 'this week 00:00:00 -1second'],
        'month'      => ['first Day of this month 00:00:00', 'first Day of next month 00:00:00 -1second'],
        'last month' => ['first Day of last month 00:00:00', 'first Day of this month 00:00:00 -1second'],
        'year'       => ['this year 1/1', 'next year 1/1 -1second'],
        'last year'  => ['last year 1/1', 'this year 1/1 -1second'],
    ];

    /**
     * 日期查询快捷定义
     * @var array
     */
    protected $timeExp = ['d' => 'today', 'w' => 'week', 'm' => 'month', 'y' => 'year'];

    /**
     * 架构函数
     * @access public
     */
    public function __construct(Connection $connection = null)
    {
        if (is_null($connection)) {
            $this->connection = Db::connect();
        } else {
            $this->connection = $connection;
        }

        $this->prefix = $this->connection->getConfig('prefix');
    }

    /**
     * 创建一个新的查询对象
     * @access public
     * @return Query
     */
    public function newQuery()
    {
        $query = new static($this->connection);

        if ($this->model) {
            $query->model($this->model);
        }

        if (isset($this->options['table'])) {
            $query->table($this->options['table']);
        } else {
            $query->name($this->name);
        }

        if (isset($this->options['json'])) {
            $query->json($this->options['json'], $this->options['json_assoc']);
        }

        if (isset($this->options['field_type'])) {
            $query->setJsonFieldType($this->options['field_type']);
        }

        return $query;
=======
    // 数据库Connection对象实例
    protected $connection;
    // 数据库Builder对象实例
    protected $builder;
    // 当前模型类名称
    protected $model;
    // 当前数据表名称（含前缀）
    protected $table = '';
    // 当前数据表名称（不含前缀）
    protected $name = '';
    // 当前数据表主键
    protected $pk;
    // 当前数据表前缀
    protected $prefix = '';
    // 查询参数
    protected $options = [];
    // 参数绑定
    protected $bind = [];
    // 数据表信息
    protected static $info = [];
    // 回调事件
    private static $event = [];
    // 读取主库
    protected static $readMaster = [];

    /**
     * 构造函数
     * @access public
     * @param Connection $connection 数据库对象实例
     * @param Model      $model      模型对象
     */
    public function __construct(Connection $connection = null, $model = null)
    {
        $this->connection = $connection ?: Db::connect([], true);
        $this->prefix     = $this->connection->getConfig('prefix');
        $this->model      = $model;
        // 设置当前连接的Builder对象
        $this->setBuilder();
>>>>>>> main
    }

    /**
     * 利用__call方法实现一些特殊的Model方法
     * @access public
<<<<<<< HEAD
     * @param  string $method 方法名称
     * @param  array  $args   调用参数
=======
     * @param string $method 方法名称
     * @param array  $args   调用参数
>>>>>>> main
     * @return mixed
     * @throws DbException
     * @throws Exception
     */
    public function __call($method, $args)
    {
<<<<<<< HEAD
        if (isset(self::$extend[strtolower($method)])) {
            // 调用扩展查询方法
            array_unshift($args, $this);

            return Container::getInstance()
                ->invoke(self::$extend[strtolower($method)], $args);
        } elseif (strtolower(substr($method, 0, 5)) == 'getby') {
            // 根据某个字段获取记录
            $field = Loader::parseName(substr($method, 5));
            return $this->where($field, '=', $args[0])->find();
        } elseif (strtolower(substr($method, 0, 10)) == 'getfieldby') {
            // 根据某个字段获取记录的某个值
            $name = Loader::parseName(substr($method, 10));
            return $this->where($name, '=', $args[0])->value($args[1]);
        } elseif (strtolower(substr($method, 0, 7)) == 'whereor') {
            $name = Loader::parseName(substr($method, 7));
            array_unshift($args, $name);
            return call_user_func_array([$this, 'whereOr'], $args);
        } elseif (strtolower(substr($method, 0, 5)) == 'where') {
            $name = Loader::parseName(substr($method, 5));
            array_unshift($args, $name);
            return call_user_func_array([$this, 'where'], $args);
=======
        if (strtolower(substr($method, 0, 5)) == 'getby') {
            // 根据某个字段获取记录
            $field         = Loader::parseName(substr($method, 5));
            $where[$field] = $args[0];
            return $this->where($where)->find();
        } elseif (strtolower(substr($method, 0, 10)) == 'getfieldby') {
            // 根据某个字段获取记录的某个值
            $name         = Loader::parseName(substr($method, 10));
            $where[$name] = $args[0];
            return $this->where($where)->value($args[1]);
>>>>>>> main
        } elseif ($this->model && method_exists($this->model, 'scope' . $method)) {
            // 动态调用命名范围
            $method = 'scope' . $method;
            array_unshift($args, $this);

            call_user_func_array([$this->model, $method], $args);
            return $this;
        } else {
<<<<<<< HEAD
            throw new Exception('method not exist:' . ($this->model ? get_class($this->model) : static::class) . '->' . $method);
=======
            throw new Exception('method not exist:' . __CLASS__ . '->' . $method);
>>>>>>> main
        }
    }

    /**
<<<<<<< HEAD
     * 扩展查询方法
     * @access public
     * @param  string|array  $method     查询方法名
     * @param  callable      $callback
     * @return void
     */
    public static function extend($method, $callback = null)
    {
        if (is_array($method)) {
            foreach ($method as $key => $val) {
                self::$extend[strtolower($key)] = $val;
            }
        } else {
            self::$extend[strtolower($method)] = $callback;
        }
    }

    /**
     * 设置当前的数据库Connection对象
     * @access public
     * @param  Connection      $connection
     * @return $this
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
        $this->prefix     = $this->connection->getConfig('prefix');

        return $this;
    }

    /**
=======
>>>>>>> main
     * 获取当前的数据库Connection对象
     * @access public
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
<<<<<<< HEAD
     * 指定模型
     * @access public
     * @param  Model $model 模型对象实例
     * @return $this
     */
    public function model(Model $model)
    {
        $this->model = $model;
=======
     * 切换当前的数据库连接
     * @access public
     * @param mixed $config
     * @return $this
     */
    public function connect($config)
    {
        $this->connection = Db::connect($config);
        $this->setBuilder();
        $this->prefix = $this->connection->getConfig('prefix');
>>>>>>> main
        return $this;
    }

    /**
<<<<<<< HEAD
     * 获取当前的模型对象
=======
     * 设置当前的数据库Builder对象
     * @access protected
     * @return void
     */
    protected function setBuilder()
    {
        $class         = $this->connection->getBuilder();
        $this->builder = new $class($this->connection, $this);
    }

    /**
     * 获取当前的模型对象实例
>>>>>>> main
     * @access public
     * @return Model|null
     */
    public function getModel()
    {
<<<<<<< HEAD
        return $this->model ? $this->model->setQuery($this) : null;
    }

    /**
     * 设置从主库读取数据
     * @access public
     * @param  bool $all 是否所有表有效
     * @return $this
     */
    public function readMaster($all = false)
    {
        $table = $all ? '*' : $this->getTable();
=======
        return $this->model;
    }

    /**
     * 设置后续从主库读取数据
     * @access public
     * @param  bool $allTable
     * @return void
     */
    public function readMaster($allTable = false)
    {
        if ($allTable) {
            $table = '*';
        } else {
            $table = isset($this->options['table']) ? $this->options['table'] : $this->getTable();
        }
>>>>>>> main

        static::$readMaster[$table] = true;

        return $this;
    }

    /**
<<<<<<< HEAD
     * 指定当前数据表名（不含前缀）
     * @access public
     * @param  string $name
=======
     * 获取当前的builder实例对象
     * @access public
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * 指定默认的数据表名（不含前缀）
     * @access public
     * @param string $name
>>>>>>> main
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
<<<<<<< HEAD
     * 获取当前的数据表名称
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->name ?: $this->model->getName();
=======
     * 指定默认数据表名（含前缀）
     * @access public
     * @param string $table 表名
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
>>>>>>> main
    }

    /**
     * 得到当前或者指定名称的数据表
     * @access public
<<<<<<< HEAD
     * @param  string $name
=======
     * @param string $name
>>>>>>> main
     * @return string
     */
    public function getTable($name = '')
    {
<<<<<<< HEAD
        if (empty($name) && isset($this->options['table'])) {
            return $this->options['table'];
        }

        $name = $name ?: $this->name;

        return $this->prefix . Loader::parseName($name);
=======
        if ($name || empty($this->table)) {
            $name      = $name ?: $this->name;
            $tableName = $this->prefix;
            if ($name) {
                $tableName .= Loader::parseName($name);
            }
        } else {
            $tableName = $this->table;
        }
        return $tableName;
    }

    /**
     * 将SQL语句中的__TABLE_NAME__字符串替换成带前缀的表名（小写）
     * @access public
     * @param string $sql sql语句
     * @return string
     */
    public function parseSqlTable($sql)
    {
        if (false !== strpos($sql, '__')) {
            $prefix = $this->prefix;
            $sql    = preg_replace_callback("/__([A-Z0-9_-]+)__/sU", function ($match) use ($prefix) {
                return $prefix . strtolower($match[1]);
            }, $sql);
        }
        return $sql;
>>>>>>> main
    }

    /**
     * 执行查询 返回数据集
     * @access public
<<<<<<< HEAD
     * @param  string      $sql    sql指令
     * @param  array       $bind   参数绑定
     * @param  boolean     $master 是否在主服务器读操作
     * @param  bool        $pdo    是否返回PDO对象
=======
     * @param string      $sql    sql指令
     * @param array       $bind   参数绑定
     * @param boolean     $master 是否在主服务器读操作
     * @param bool|string $class  指定返回的数据集对象
>>>>>>> main
     * @return mixed
     * @throws BindParamException
     * @throws PDOException
     */
<<<<<<< HEAD
    public function query($sql, $bind = [], $master = false, $pdo = false)
    {
        return $this->connection->query($sql, $bind, $master, $pdo);
=======
    public function query($sql, $bind = [], $master = false, $class = false)
    {
        return $this->connection->query($sql, $bind, $master, $class);
>>>>>>> main
    }

    /**
     * 执行语句
     * @access public
<<<<<<< HEAD
     * @param  string $sql  sql指令
     * @param  array  $bind 参数绑定
=======
     * @param string $sql  sql指令
     * @param array  $bind 参数绑定
>>>>>>> main
     * @return int
     * @throws BindParamException
     * @throws PDOException
     */
    public function execute($sql, $bind = [])
    {
        return $this->connection->execute($sql, $bind, $this);
    }

    /**
<<<<<<< HEAD
     * 监听SQL执行
     * @access public
     * @param  callable $callback 回调方法
     * @return void
     */
    public function listen($callback)
    {
        $this->connection->listen($callback);
    }

    /**
     * 获取最近插入的ID
     * @access public
     * @param  string $sequence 自增序列名
=======
     * 获取最近插入的ID
     * @access public
     * @param string $sequence 自增序列名
>>>>>>> main
     * @return string
     */
    public function getLastInsID($sequence = null)
    {
        return $this->connection->getLastInsID($sequence);
    }

    /**
<<<<<<< HEAD
     * 获取返回或者影响的记录数
     * @access public
     * @return integer
     */
    public function getNumRows()
    {
        return $this->connection->getNumRows();
    }

    /**
=======
>>>>>>> main
     * 获取最近一次查询的sql语句
     * @access public
     * @return string
     */
    public function getLastSql()
    {
        return $this->connection->getLastSql();
    }

    /**
<<<<<<< HEAD
     * 执行数据库Xa事务
     * @access public
     * @param  callable $callback 数据操作方法回调
     * @param  array    $dbs      多个查询对象或者连接对象
     * @return mixed
     * @throws PDOException
     * @throws \Exception
     * @throws \Throwable
     */
    public function transactionXa($callback, array $dbs = [])
    {
        $xid = uniqid('xa');

        if (empty($dbs)) {
            $dbs[] = $this->getConnection();
        }

        foreach ($dbs as $key => $db) {
            if ($db instanceof Query) {
                $db = $db->getConnection();

                $dbs[$key] = $db;
            }

            $db->startTransXa($xid);
        }

        try {
            $result = null;
            if (is_callable($callback)) {
                $result = call_user_func_array($callback, [$this]);
            }

            foreach ($dbs as $db) {
                $db->prepareXa($xid);
            }

            foreach ($dbs as $db) {
                $db->commitXa($xid);
            }

            return $result;
        } catch (\Exception $e) {
            foreach ($dbs as $db) {
                $db->rollbackXa($xid);
            }
            throw $e;
        } catch (\Throwable $e) {
            foreach ($dbs as $db) {
                $db->rollbackXa($xid);
            }
            throw $e;
        }
    }

    /**
     * 执行数据库事务
     * @access public
     * @param  callable $callback 数据操作方法回调
=======
     * 执行数据库事务
     * @access public
     * @param callable $callback 数据操作方法回调
>>>>>>> main
     * @return mixed
     */
    public function transaction($callback)
    {
        return $this->connection->transaction($callback);
    }

    /**
     * 启动事务
     * @access public
     * @return void
     */
    public function startTrans()
    {
        $this->connection->startTrans();
    }

    /**
     * 用于非自动提交状态下面的查询提交
     * @access public
     * @return void
     * @throws PDOException
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * 事务回滚
     * @access public
     * @return void
     * @throws PDOException
     */
    public function rollback()
    {
        $this->connection->rollback();
    }

    /**
     * 批处理执行SQL语句
     * 批处理的指令都认为是execute操作
     * @access public
<<<<<<< HEAD
     * @param  array $sql SQL批处理指令
     * @return boolean
     */
    public function batchQuery($sql = [])
    {
        return $this->connection->batchQuery($sql);
=======
     * @param array $sql SQL批处理指令
     * @return boolean
     */
    public function batchQuery($sql = [], $bind = [])
    {
        return $this->connection->batchQuery($sql, $bind);
>>>>>>> main
    }

    /**
     * 获取数据库的配置参数
     * @access public
<<<<<<< HEAD
     * @param  string $name 参数名称
     * @return mixed
=======
     * @param string $name 参数名称
     * @return boolean
>>>>>>> main
     */
    public function getConfig($name = '')
    {
        return $this->connection->getConfig($name);
    }

    /**
<<<<<<< HEAD
     * 获取数据表字段信息
     * @access public
     * @param  string $tableName 数据表名
     * @return array
     */
    public function getTableFields($tableName = '')
    {
        if ('' == $tableName) {
            $tableName = isset($this->options['table']) ? $this->options['table'] : $this->getTable();
        }

        return $this->connection->getTableFields($tableName);
    }

    /**
     * 获取数据表字段类型
     * @access public
     * @param  string $tableName 数据表名
     * @param  string $field    字段名
     * @return array|string
     */
    public function getFieldsType($tableName = '', $field = null)
    {
        if ('' == $tableName) {
            $tableName = isset($this->options['table']) ? $this->options['table'] : $this->getTable();
        }

        return $this->connection->getFieldsType($tableName, $field);
    }

    /**
     * 得到分表的的数据表名
     * @access public
     * @param  array  $data  操作的数据
     * @param  string $field 分表依据的字段
     * @param  array  $rule  分表规则
     * @return array
=======
     * 得到分表的的数据表名
     * @access public
     * @param array  $data  操作的数据
     * @param string $field 分表依据的字段
     * @param array  $rule  分表规则
     * @return string
>>>>>>> main
     */
    public function getPartitionTableName($data, $field, $rule = [])
    {
        // 对数据表进行分区
        if ($field && isset($data[$field])) {
            $value = $data[$field];
            $type  = $rule['type'];
            switch ($type) {
                case 'id':
                    // 按照id范围分表
                    $step = $rule['expr'];
                    $seq  = floor($value / $step) + 1;
                    break;
                case 'year':
                    // 按照年份分表
                    if (!is_numeric($value)) {
                        $value = strtotime($value);
                    }
                    $seq = date('Y', $value) - $rule['expr'] + 1;
                    break;
                case 'mod':
                    // 按照id的模数分表
                    $seq = ($value % $rule['num']) + 1;
                    break;
                case 'md5':
                    // 按照md5的序列分表
                    $seq = (ord(substr(md5($value), 0, 1)) % $rule['num']) + 1;
                    break;
                default:
                    if (function_exists($type)) {
                        // 支持指定函数哈希
<<<<<<< HEAD
                        $value = $type($value);
                    }

                    $seq = (ord(substr($value, 0, 1)) % $rule['num']) + 1;
            }

            return $this->getTable() . '_' . $seq;
        }
        // 当设置的分表字段不在查询条件或者数据中
        // 进行联合查询，必须设定 partition['num']
        $tableName = [];
        for ($i = 0; $i < $rule['num']; $i++) {
            $tableName[] = 'SELECT * FROM ' . $this->getTable() . '_' . ($i + 1);
        }

        return ['( ' . implode(" UNION ", $tableName) . ' )' => $this->name];
=======
                        $seq = (ord(substr($type($value), 0, 1)) % $rule['num']) + 1;
                    } else {
                        // 按照字段的首字母的值分表
                        $seq = (ord($value[0]) % $rule['num']) + 1;
                    }
            }
            return $this->getTable() . '_' . $seq;
        } else {
            // 当设置的分表字段不在查询条件或者数据中
            // 进行联合查询，必须设定 partition['num']
            $tableName = [];
            for ($i = 0; $i < $rule['num']; $i++) {
                $tableName[] = 'SELECT * FROM ' . $this->getTable() . '_' . ($i + 1);
            }

            $tableName = '( ' . implode(" UNION ", $tableName) . ') AS ' . $this->name;
            return $tableName;
        }
>>>>>>> main
    }

    /**
     * 得到某个字段的值
     * @access public
<<<<<<< HEAD
     * @param  string $field   字段名
     * @param  mixed  $default 默认值
     * @return mixed
     */
    public function value($field, $default = null)
    {
        $this->parseOptions();

        return $this->connection->value($this, $field, $default);
=======
     * @param string $field   字段名
     * @param mixed  $default 默认值
     * @param bool   $force   强制转为数字类型
     * @return mixed
     */
    public function value($field, $default = null, $force = false)
    {
        $result = false;
        if (empty($this->options['fetch_sql']) && !empty($this->options['cache'])) {
            // 判断查询缓存
            $cache = $this->options['cache'];
            if (empty($this->options['table'])) {
                $this->options['table'] = $this->getTable();
            }
            $key    = is_string($cache['key']) ? $cache['key'] : md5($this->connection->getConfig('database') . '.' . $field . serialize($this->options) . serialize($this->bind));
            $result = Cache::get($key);
        }
        if (false === $result) {
            if (isset($this->options['field'])) {
                unset($this->options['field']);
            }
            $pdo = $this->field($field)->limit(1)->getPdo();
            if (is_string($pdo)) {
                // 返回SQL语句
                return $pdo;
            }

            $result = $pdo->fetchColumn();
            if ($force) {
                $result = (float) $result;
            }

            if (isset($cache) && false !== $result) {
                // 缓存数据
                $this->cacheData($key, $result, $cache);
            }
        } else {
            // 清空查询条件
            $this->options = [];
        }
        return false !== $result ? $result : $default;
>>>>>>> main
    }

    /**
     * 得到某个列的数组
     * @access public
<<<<<<< HEAD
     * @param  string $field 字段名 多个字段用逗号分隔
     * @param  string $key   索引
=======
     * @param string $field 字段名 多个字段用逗号分隔
     * @param string $key   索引
>>>>>>> main
     * @return array
     */
    public function column($field, $key = '')
    {
<<<<<<< HEAD
        $this->parseOptions();

        return $this->connection->column($this, $field, $key);
    }

    /**
     * 聚合查询
     * @access public
     * @param  string               $aggregate    聚合方法
     * @param  string|Expression    $field        字段名
     * @param  bool                 $force        强制转为数字类型
     * @return mixed
     */
    public function aggregate($aggregate, $field, $force = false)
    {
        $this->parseOptions();

        $result = $this->connection->aggregate($this, $aggregate, $field);

        if (!empty($this->options['fetch_sql'])) {
            return $result;
        } elseif ($force) {
            $result = (float) $result;
        }

=======
        $result = false;
        if (empty($this->options['fetch_sql']) && !empty($this->options['cache'])) {
            // 判断查询缓存
            $cache = $this->options['cache'];
            if (empty($this->options['table'])) {
                $this->options['table'] = $this->getTable();
            }
            $guid   = is_string($cache['key']) ? $cache['key'] : md5($this->connection->getConfig('database') . '.' . $field . serialize($this->options) . serialize($this->bind));
            $result = Cache::get($guid);
        }
        if (false === $result) {
            if (isset($this->options['field'])) {
                unset($this->options['field']);
            }
            if (is_null($field)) {
                $field = '*';
            } elseif ($key && '*' != $field) {
                $field = $key . ',' . $field;
            }
            $pdo = $this->field($field)->getPdo();
            if (is_string($pdo)) {
                // 返回SQL语句
                return $pdo;
            }
            if (1 == $pdo->columnCount()) {
                $result = $pdo->fetchAll(PDO::FETCH_COLUMN);
            } else {
                $resultSet = $pdo->fetchAll(PDO::FETCH_ASSOC);
                if ($resultSet) {
                    $fields = array_keys($resultSet[0]);
                    $count  = count($fields);
                    $key1   = array_shift($fields);
                    $key2   = $fields ? array_shift($fields) : '';
                    $key    = $key ?: $key1;
                    if (strpos($key, '.')) {
                        list($alias, $key) = explode('.', $key);
                    }
                    foreach ($resultSet as $val) {
                        if ($count > 2) {
                            $result[$val[$key]] = $val;
                        } elseif (2 == $count) {
                            $result[$val[$key]] = $val[$key2];
                        } elseif (1 == $count) {
                            $result[$val[$key]] = $val[$key1];
                        }
                    }
                } else {
                    $result = [];
                }
            }
            if (isset($cache) && isset($guid)) {
                // 缓存数据
                $this->cacheData($guid, $result, $cache);
            }
        } else {
            // 清空查询条件
            $this->options = [];
        }
>>>>>>> main
        return $result;
    }

    /**
     * COUNT查询
     * @access public
<<<<<<< HEAD
     * @param  string|Expression $field 字段名
     * @return float|string
     */
    public function count($field = '*')
    {
        if (!empty($this->options['group'])) {
            // 支持GROUP
            $options = $this->getOptions();
            $subSql  = $this->options($options)
                ->field('count(' . $field . ') AS think_count')
                ->bind($this->bind)
                ->buildSql();

            $query = $this->newQuery()->table([$subSql => '_group_count_']);

            if (!empty($options['fetch_sql'])) {
                $query->fetchSql(true);
            }

            $count = $query->aggregate('COUNT', '*', true);
=======
     * @param string $field 字段名
     * @return integer|string
     */
    public function count($field = '*')
    {
        if (isset($this->options['group'])) {
            if (!preg_match('/^[\w\.\*]+$/', $field)) {
                throw new Exception('not support data:' . $field);
            }
            // 支持GROUP
            $options = $this->getOptions();
            $subSql  = $this->options($options)->field('count(' . $field . ')')->bind($this->bind)->buildSql();

            $count = $this->table([$subSql => '_group_count_'])->value('COUNT(*) AS tp_count', 0, true);
>>>>>>> main
        } else {
            $count = $this->aggregate('COUNT', $field, true);
        }

        return is_string($count) ? $count : (int) $count;
<<<<<<< HEAD
=======

    }

    /**
     * 聚合查询
     * @access public
     * @param  string $aggregate    聚合方法
     * @param  string $field        字段名
     * @param  bool   $force        强制转为数字类型
     * @return mixed
     */
    public function aggregate($aggregate, $field, $force = false)
    {
        if (0 === stripos($field, 'DISTINCT ')) {
            list($distinct, $field) = explode(' ', $field);
        }

        if (!preg_match('/^[\w\.\+\-\*]+$/', $field)) {
            throw new Exception('not support data:' . $field);
        }

        $result = $this->value($aggregate . '(' . (!empty($distinct) ? 'DISTINCT ' : '') . $field . ') AS tp_' . strtolower($aggregate), 0, $force);

        return $result;
>>>>>>> main
    }

    /**
     * SUM查询
     * @access public
<<<<<<< HEAD
     * @param  string|Expression $field 字段名
     * @return float
=======
     * @param string $field 字段名
     * @return float|int
>>>>>>> main
     */
    public function sum($field)
    {
        return $this->aggregate('SUM', $field, true);
    }

    /**
     * MIN查询
     * @access public
<<<<<<< HEAD
     * @param  string|Expression $field    字段名
     * @param  bool              $force    强制转为数字类型
=======
     * @param string $field 字段名
     * @param bool   $force   强制转为数字类型
>>>>>>> main
     * @return mixed
     */
    public function min($field, $force = true)
    {
        return $this->aggregate('MIN', $field, $force);
    }

    /**
     * MAX查询
     * @access public
<<<<<<< HEAD
     * @param  string|Expression $field    字段名
     * @param  bool              $force    强制转为数字类型
=======
     * @param string $field 字段名
     * @param bool   $force   强制转为数字类型
>>>>>>> main
     * @return mixed
     */
    public function max($field, $force = true)
    {
        return $this->aggregate('MAX', $field, $force);
    }

    /**
     * AVG查询
     * @access public
<<<<<<< HEAD
     * @param  string|Expression $field 字段名
     * @return float
=======
     * @param string $field 字段名
     * @return float|int
>>>>>>> main
     */
    public function avg($field)
    {
        return $this->aggregate('AVG', $field, true);
    }

    /**
     * 设置记录的某个字段值
     * 支持使用数据库字段和方法
     * @access public
<<<<<<< HEAD
     * @param  string|array $field 字段名
     * @param  mixed        $value 字段值
=======
     * @param string|array $field 字段名
     * @param mixed        $value 字段值
>>>>>>> main
     * @return integer
     */
    public function setField($field, $value = '')
    {
        if (is_array($field)) {
            $data = $field;
        } else {
            $data[$field] = $value;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this->update($data);
    }

    /**
     * 字段值(延迟)增长
     * @access public
<<<<<<< HEAD
     * @param  string  $field    字段名
     * @param  integer $step     增长值
     * @param  integer $lazyTime 延时时间(s)
=======
     * @param string  $field    字段名
     * @param integer $step     增长值
     * @param integer $lazyTime 延时时间(s)
>>>>>>> main
     * @return integer|true
     * @throws Exception
     */
    public function setInc($field, $step = 1, $lazyTime = 0)
    {
        $condition = !empty($this->options['where']) ? $this->options['where'] : [];
<<<<<<< HEAD

=======
>>>>>>> main
        if (empty($condition)) {
            // 没有条件不做任何更新
            throw new Exception('no data to update');
        }
<<<<<<< HEAD

        if ($lazyTime > 0) {
            // 延迟写入
            $guid = md5($this->getTable() . '_' . $field . '_' . serialize($condition));
            $step = $this->lazyWrite('inc', $guid, $step, $lazyTime);

=======
        if ($lazyTime > 0) {
            // 延迟写入
            $guid = md5($this->getTable() . '_' . $field . '_' . serialize($condition) . serialize($this->bind));
            $step = $this->lazyWrite('inc', $guid, $step, $lazyTime);
>>>>>>> main
            if (false === $step) {
                // 清空查询条件
                $this->options = [];
                return true;
            }
        }
<<<<<<< HEAD

        return $this->setField($field, ['INC', $step]);
=======
        return $this->setField($field, ['inc', $step]);
>>>>>>> main
    }

    /**
     * 字段值（延迟）减少
     * @access public
<<<<<<< HEAD
     * @param  string  $field    字段名
     * @param  integer $step     减少值
     * @param  integer $lazyTime 延时时间(s)
=======
     * @param string  $field    字段名
     * @param integer $step     减少值
     * @param integer $lazyTime 延时时间(s)
>>>>>>> main
     * @return integer|true
     * @throws Exception
     */
    public function setDec($field, $step = 1, $lazyTime = 0)
    {
        $condition = !empty($this->options['where']) ? $this->options['where'] : [];
<<<<<<< HEAD

=======
>>>>>>> main
        if (empty($condition)) {
            // 没有条件不做任何更新
            throw new Exception('no data to update');
        }
<<<<<<< HEAD

        if ($lazyTime > 0) {
            // 延迟写入
            $guid = md5($this->getTable() . '_' . $field . '_' . serialize($condition));
            $step = $this->lazyWrite('dec', $guid, $step, $lazyTime);

=======
        if ($lazyTime > 0) {
            // 延迟写入
            $guid = md5($this->getTable() . '_' . $field . '_' . serialize($condition) . serialize($this->bind));
            $step = $this->lazyWrite('dec', $guid, $step, $lazyTime);
>>>>>>> main
            if (false === $step) {
                // 清空查询条件
                $this->options = [];
                return true;
            }
<<<<<<< HEAD

            $value = ['INC', $step];
        } else {
            $value = ['DEC', $step];
        }

        return $this->setField($field, $value);
=======
            return $this->setField($field, ['inc', $step]);
        }
        return $this->setField($field, ['dec', $step]);
>>>>>>> main
    }

    /**
     * 延时更新检查 返回false表示需要延时
     * 否则返回实际写入的数值
     * @access protected
<<<<<<< HEAD
     * @param  string  $type     自增或者自减
     * @param  string  $guid     写入标识
     * @param  integer $step     写入步进值
     * @param  integer $lazyTime 延时时间(s)
=======
     * @param string  $type     自增或者自减
     * @param string  $guid     写入标识
     * @param integer $step     写入步进值
     * @param integer $lazyTime 延时时间(s)
>>>>>>> main
     * @return false|integer
     */
    protected function lazyWrite($type, $guid, $step, $lazyTime)
    {
<<<<<<< HEAD
        $cache = Container::get('cache');

        if (!$cache->has($guid . '_time')) {
            // 计时开始
            $cache->set($guid . '_time', time(), 0);
            $cache->$type($guid, $step);
        } elseif (time() > $cache->get($guid . '_time') + $lazyTime) {
            // 删除缓存
            $value = $cache->$type($guid, $step);
            $cache->rm($guid);
            $cache->rm($guid . '_time');
            return 0 === $value ? false : $value;
        } else {
            // 更新缓存
            $cache->$type($guid, $step);
        }

=======
        if (!Cache::has($guid . '_time')) {
            // 计时开始
            Cache::set($guid . '_time', $_SERVER['REQUEST_TIME'], 0);
            Cache::$type($guid, $step);
        } elseif ($_SERVER['REQUEST_TIME'] > Cache::get($guid . '_time') + $lazyTime) {
            // 删除缓存
            $value = Cache::$type($guid, $step);
            Cache::rm($guid);
            Cache::rm($guid . '_time');
            return 0 === $value ? false : $value;
        } else {
            // 更新缓存
            Cache::$type($guid, $step);
        }
>>>>>>> main
        return false;
    }

    /**
     * 查询SQL组装 join
     * @access public
<<<<<<< HEAD
     * @param  mixed  $join      关联的表名
     * @param  mixed  $condition 条件
     * @param  string $type      JOIN类型
     * @param  array  $bind      参数绑定
     * @return $this
     */
    public function join($join, $condition = null, $type = 'INNER', $bind = [])
=======
     * @param mixed  $join      关联的表名
     * @param mixed  $condition 条件
     * @param string $type      JOIN类型
     * @return $this
     */
    public function join($join, $condition = null, $type = 'INNER')
>>>>>>> main
    {
        if (empty($condition)) {
            // 如果为组数，则循环调用join
            foreach ($join as $key => $value) {
                if (is_array($value) && 2 <= count($value)) {
                    $this->join($value[0], $value[1], isset($value[2]) ? $value[2] : $type);
                }
            }
        } else {
            $table = $this->getJoinTable($join);
<<<<<<< HEAD
            if ($bind) {
                $this->bindParams($condition, $bind);
            }
            $this->options['join'][] = [$table, strtoupper($type), $condition];
        }

=======

            $this->options['join'][] = [$table, strtoupper($type), $condition];
        }
>>>>>>> main
        return $this;
    }

    /**
<<<<<<< HEAD
     * LEFT JOIN
     * @access public
     * @param  mixed  $join      关联的表名
     * @param  mixed  $condition 条件
     * @param  array  $bind      参数绑定
     * @return $this
     */
    public function leftJoin($join, $condition = null, $bind = [])
    {
        return $this->join($join, $condition, 'LEFT');
    }

    /**
     * RIGHT JOIN
     * @access public
     * @param  mixed  $join      关联的表名
     * @param  mixed  $condition 条件
     * @param  array  $bind      参数绑定
     * @return $this
     */
    public function rightJoin($join, $condition = null, $bind = [])
    {
        return $this->join($join, $condition, 'RIGHT');
    }

    /**
     * FULL JOIN
     * @access public
     * @param  mixed  $join      关联的表名
     * @param  mixed  $condition 条件
     * @param  array  $bind      参数绑定
     * @return $this
     */
    public function fullJoin($join, $condition = null, $bind = [])
    {
        return $this->join($join, $condition, 'FULL');
    }

    /**
     * 获取Join表名及别名 支持
     * ['prefix_table或者子查询'=>'alias'] 'table alias'
     * @access protected
     * @param  array|string $join
     * @param  string       $alias
     * @return string
     */
    protected function getJoinTable($join, &$alias = null)
    {
=======
     * 获取Join表名及别名 支持
     * ['prefix_table或者子查询'=>'alias'] 'prefix_table alias' 'table alias'
     * @access public
     * @param array|string $join
     * @return array|string
     */
    protected function getJoinTable($join, &$alias = null)
    {
        // 传入的表名为数组
>>>>>>> main
        if (is_array($join)) {
            $table = $join;
            $alias = array_shift($join);
        } else {
            $join = trim($join);
<<<<<<< HEAD

=======
>>>>>>> main
            if (false !== strpos($join, '(')) {
                // 使用子查询
                $table = $join;
            } else {
                $prefix = $this->prefix;
                if (strpos($join, ' ')) {
                    // 使用别名
                    list($table, $alias) = explode(' ', $join);
                } else {
                    $table = $join;
                    if (false === strpos($join, '.') && 0 !== strpos($join, '__')) {
                        $alias = $join;
                    }
                }
<<<<<<< HEAD

=======
>>>>>>> main
                if ($prefix && false === strpos($table, '.') && 0 !== strpos($table, $prefix) && 0 !== strpos($table, '__')) {
                    $table = $this->getTable($table);
                }
            }
<<<<<<< HEAD

=======
>>>>>>> main
            if (isset($alias) && $table != $alias) {
                $table = [$table => $alias];
            }
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $table;
    }

    /**
     * 查询SQL组装 union
     * @access public
<<<<<<< HEAD
     * @param  mixed   $union
     * @param  boolean $all
=======
     * @param mixed   $union
     * @param boolean $all
>>>>>>> main
     * @return $this
     */
    public function union($union, $all = false)
    {
<<<<<<< HEAD
        if (empty($union)) {
            return $this;
        }

=======
>>>>>>> main
        $this->options['union']['type'] = $all ? 'UNION ALL' : 'UNION';

        if (is_array($union)) {
            $this->options['union'] = array_merge($this->options['union'], $union);
        } else {
            $this->options['union'][] = $union;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
<<<<<<< HEAD
     * 查询SQL组装 union all
     * @access public
     * @param  mixed   $union
     * @return $this
     */
    public function unionAll($union)
    {
        return $this->union($union, true);
    }

    /**
     * 指定查询字段 支持字段排除和指定数据表
     * @access public
     * @param  mixed   $field
     * @param  boolean $except    是否排除
     * @param  string  $tableName 数据表名
     * @param  string  $prefix    字段前缀
     * @param  string  $alias     别名前缀
=======
     * 指定查询字段 支持字段排除和指定数据表
     * @access public
     * @param mixed   $field
     * @param boolean $except    是否排除
     * @param string  $tableName 数据表名
     * @param string  $prefix    字段前缀
     * @param string  $alias     别名前缀
>>>>>>> main
     * @return $this
     */
    public function field($field, $except = false, $tableName = '', $prefix = '', $alias = '')
    {
        if (empty($field)) {
            return $this;
        } elseif ($field instanceof Expression) {
            $this->options['field'][] = $field;
            return $this;
        }

        if (is_string($field)) {
            if (preg_match('/[\<\'\"\(]/', $field)) {
                return $this->fieldRaw($field);
            }
<<<<<<< HEAD

            $field = array_map('trim', explode(',', $field));
        }

        if (true === $field) {
            // 获取全部字段
            $fields = $this->getTableFields($tableName);
            $field  = $fields ?: ['*'];
        } elseif ($except) {
            // 字段排除
            $fields = $this->getTableFields($tableName);
            $field  = $fields ? array_diff($fields, $field) : $field;
        }

        if ($tableName) {
            // 添加统一的前缀
            $prefix = $prefix ?: $tableName;
            foreach ($field as $key => &$val) {
                if (is_numeric($key) && $alias) {
                    $field[$prefix . '.' . $val] = $alias . $val;
                    unset($field[$key]);
                } elseif (is_numeric($key)) {
                    $val = $prefix . '.' . $val;
                }
=======
            $field = array_map('trim', explode(',', $field));
        }
        if (true === $field) {
            // 获取全部字段
            $fields = $this->getTableInfo($tableName ?: (isset($this->options['table']) ? $this->options['table'] : ''), 'fields');
            $field  = $fields ?: ['*'];
        } elseif ($except) {
            // 字段排除
            $fields = $this->getTableInfo($tableName ?: (isset($this->options['table']) ? $this->options['table'] : ''), 'fields');
            $field  = $fields ? array_diff($fields, $field) : $field;
        }
        if ($tableName) {
            // 添加统一的前缀
            $prefix = $prefix ?: $tableName;
            foreach ($field as $key => $val) {
                if (is_numeric($key)) {
                    $val = $prefix . '.' . $val . ($alias ? ' AS ' . $alias . $val : '');
                }
                $field[$key] = $val;
>>>>>>> main
            }
        }

        if (isset($this->options['field'])) {
            $field = array_merge((array) $this->options['field'], $field);
        }
<<<<<<< HEAD

        $this->options['field'] = array_unique($field);

=======
        $this->options['field'] = array_unique($field);
>>>>>>> main
        return $this;
    }

    /**
     * 表达式方式指定查询字段
     * @access public
     * @param  string $field    字段名
<<<<<<< HEAD
     * @return $this
     */
    public function fieldRaw($field)
    {
        $this->options['field'][] = $this->raw($field);

=======
     * @param  array  $bind     参数绑定
     * @return $this
     */
    public function fieldRaw($field, array $bind = [])
    {
        $this->options['field'][] = $this->raw($field);

        if ($bind) {
            $this->bind($bind);
        }

>>>>>>> main
        return $this;
    }

    /**
     * 设置数据
     * @access public
<<<<<<< HEAD
     * @param  mixed $field 字段名或者数据
     * @param  mixed $value 字段值
=======
     * @param mixed $field 字段名或者数据
     * @param mixed $value 字段值
>>>>>>> main
     * @return $this
     */
    public function data($field, $value = null)
    {
        if (is_array($field)) {
            $this->options['data'] = isset($this->options['data']) ? array_merge($this->options['data'], $field) : $field;
        } else {
            $this->options['data'][$field] = $value;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 字段值增长
     * @access public
<<<<<<< HEAD
     * @param  string|array $field 字段名
     * @param  integer      $step  增长值
     * @return $this
     */
    public function inc($field, $step = 1, $op = 'INC')
    {
        $fields = is_string($field) ? explode(',', $field) : $field;

        foreach ($fields as $field => $val) {
            if (is_numeric($field)) {
                $field = $val;
            } else {
                $step = $val;
            }

            $this->data($field, [$op, $step]);
        }

=======
     * @param string|array $field 字段名
     * @param integer      $step  增长值
     * @return $this
     */
    public function inc($field, $step = 1)
    {
        $fields = is_string($field) ? explode(',', $field) : $field;
        foreach ($fields as $field) {
            $this->data($field, ['inc', $step]);
        }
>>>>>>> main
        return $this;
    }

    /**
     * 字段值减少
     * @access public
<<<<<<< HEAD
     * @param  string|array $field 字段名
     * @param  integer      $step  增长值
=======
     * @param string|array $field 字段名
     * @param integer      $step  增长值
>>>>>>> main
     * @return $this
     */
    public function dec($field, $step = 1)
    {
<<<<<<< HEAD
        return $this->inc($field, $step, 'DEC');
=======
        $fields = is_string($field) ? explode(',', $field) : $field;
        foreach ($fields as $field) {
            $this->data($field, ['dec', $step]);
        }
        return $this;
>>>>>>> main
    }

    /**
     * 使用表达式设置数据
     * @access public
<<<<<<< HEAD
     * @param  string $field 字段名
     * @param  string $value 字段值
=======
     * @param string $field 字段名
     * @param string $value 字段值
>>>>>>> main
     * @return $this
     */
    public function exp($field, $value)
    {
        $this->data($field, $this->raw($value));
        return $this;
    }

    /**
     * 使用表达式设置数据
     * @access public
     * @param  mixed $value 表达式
     * @return Expression
     */
    public function raw($value)
    {
        return new Expression($value);
    }

    /**
     * 指定JOIN查询字段
     * @access public
<<<<<<< HEAD
     * @param  string|array $table 数据表
     * @param  string|array $field 查询字段
     * @param  mixed        $on    JOIN条件
     * @param  string       $type  JOIN类型
=======
     * @param string|array $table 数据表
     * @param string|array $field 查询字段
     * @param mixed        $on    JOIN条件
     * @param string       $type  JOIN类型
>>>>>>> main
     * @return $this
     */
    public function view($join, $field = true, $on = null, $type = 'INNER')
    {
        $this->options['view'] = true;
<<<<<<< HEAD

=======
>>>>>>> main
        if (is_array($join) && key($join) === 0) {
            foreach ($join as $key => $val) {
                $this->view($val[0], $val[1], isset($val[2]) ? $val[2] : null, isset($val[3]) ? $val[3] : 'INNER');
            }
        } else {
            $fields = [];
            $table  = $this->getJoinTable($join, $alias);

            if (true === $field) {
                $fields = $alias . '.*';
            } else {
                if (is_string($field)) {
                    $field = explode(',', $field);
                }
<<<<<<< HEAD

                foreach ($field as $key => $val) {
                    if (is_numeric($key)) {
                        $fields[] = $alias . '.' . $val;

=======
                foreach ($field as $key => $val) {
                    if (is_numeric($key)) {
                        $fields[]                   = $alias . '.' . $val;
>>>>>>> main
                        $this->options['map'][$val] = $alias . '.' . $val;
                    } else {
                        if (preg_match('/[,=\.\'\"\(\s]/', $key)) {
                            $name = $key;
                        } else {
                            $name = $alias . '.' . $key;
                        }
<<<<<<< HEAD

                        $fields[] = $name . ' AS ' . $val;

=======
                        $fields[$name]              = $val;
>>>>>>> main
                        $this->options['map'][$val] = $name;
                    }
                }
            }
<<<<<<< HEAD

            $this->field($fields);

=======
            $this->field($fields);
>>>>>>> main
            if ($on) {
                $this->join($table, $on, $type);
            } else {
                $this->table($table);
            }
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 设置分表规则
     * @access public
<<<<<<< HEAD
     * @param  array  $data  操作的数据
     * @param  string $field 分表依据的字段
     * @param  array  $rule  分表规则
=======
     * @param array  $data  操作的数据
     * @param string $field 分表依据的字段
     * @param array  $rule  分表规则
>>>>>>> main
     * @return $this
     */
    public function partition($data, $field, $rule = [])
    {
        $this->options['table'] = $this->getPartitionTableName($data, $field, $rule);
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 指定AND查询条件
     * @access public
<<<<<<< HEAD
     * @param  mixed $field     查询字段
     * @param  mixed $op        查询表达式
     * @param  mixed $condition 查询条件
=======
     * @param mixed $field     查询字段
     * @param mixed $op        查询表达式
     * @param mixed $condition 查询条件
>>>>>>> main
     * @return $this
     */
    public function where($field, $op = null, $condition = null)
    {
        $param = func_get_args();
        array_shift($param);
<<<<<<< HEAD
        return $this->parseWhereExp('AND', $field, $op, $condition, $param);
=======
        $this->parseWhereExp('AND', $field, $op, $condition, $param);
        return $this;
>>>>>>> main
    }

    /**
     * 指定OR查询条件
     * @access public
<<<<<<< HEAD
     * @param  mixed $field     查询字段
     * @param  mixed $op        查询表达式
     * @param  mixed $condition 查询条件
=======
     * @param mixed $field     查询字段
     * @param mixed $op        查询表达式
     * @param mixed $condition 查询条件
>>>>>>> main
     * @return $this
     */
    public function whereOr($field, $op = null, $condition = null)
    {
        $param = func_get_args();
        array_shift($param);
<<<<<<< HEAD
        return $this->parseWhereExp('OR', $field, $op, $condition, $param);
=======
        $this->parseWhereExp('OR', $field, $op, $condition, $param);
        return $this;
>>>>>>> main
    }

    /**
     * 指定XOR查询条件
     * @access public
<<<<<<< HEAD
     * @param  mixed $field     查询字段
     * @param  mixed $op        查询表达式
     * @param  mixed $condition 查询条件
=======
     * @param mixed $field     查询字段
     * @param mixed $op        查询表达式
     * @param mixed $condition 查询条件
>>>>>>> main
     * @return $this
     */
    public function whereXor($field, $op = null, $condition = null)
    {
        $param = func_get_args();
        array_shift($param);
<<<<<<< HEAD
        return $this->parseWhereExp('XOR', $field, $op, $condition, $param);
    }

    /**
     * 指定Null查询条件
     * @access public
     * @param  mixed  $field 查询字段
     * @param  string $logic 查询逻辑 and or xor
     * @return $this
     */
    public function whereNull($field, $logic = 'AND')
    {
        return $this->parseWhereExp($logic, $field, 'NULL', null, [], true);
    }

    /**
     * 指定NotNull查询条件
     * @access public
     * @param  mixed  $field 查询字段
     * @param  string $logic 查询逻辑 and or xor
     * @return $this
     */
    public function whereNotNull($field, $logic = 'AND')
    {
        return $this->parseWhereExp($logic, $field, 'NOTNULL', null, [], true);
    }

    /**
     * 指定Exists查询条件
     * @access public
     * @param  mixed  $condition 查询条件
     * @param  string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereExists($condition, $logic = 'AND')
    {
        if (is_string($condition)) {
            $condition = $this->raw($condition);
        }

        $this->options['where'][strtoupper($logic)][] = ['', 'EXISTS', $condition];
        return $this;
    }

    /**
     * 指定NotExists查询条件
     * @access public
     * @param  mixed  $condition 查询条件
     * @param  string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereNotExists($condition, $logic = 'AND')
    {
        if (is_string($condition)) {
            $condition = $this->raw($condition);
        }

        $this->options['where'][strtoupper($logic)][] = ['', 'NOT EXISTS', $condition];
        return $this;
    }

    /**
     * 指定In查询条件
     * @access public
     * @param  mixed  $field     查询字段
     * @param  mixed  $condition 查询条件
     * @param  string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereIn($field, $condition, $logic = 'AND')
    {
        return $this->parseWhereExp($logic, $field, 'IN', $condition, [], true);
    }

    /**
     * 指定NotIn查询条件
     * @access public
     * @param  mixed  $field     查询字段
     * @param  mixed  $condition 查询条件
     * @param  string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereNotIn($field, $condition, $logic = 'AND')
    {
        return $this->parseWhereExp($logic, $field, 'NOT IN', $condition, [], true);
    }

    /**
     * 指定Like查询条件
     * @access public
     * @param  mixed  $field     查询字段
     * @param  mixed  $condition 查询条件
     * @param  string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereLike($field, $condition, $logic = 'AND')
    {
        return $this->parseWhereExp($logic, $field, 'LIKE', $condition, [], true);
    }

    /**
     * 指定NotLike查询条件
     * @access public
     * @param  mixed  $field     查询字段
     * @param  mixed  $condition 查询条件
     * @param  string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereNotLike($field, $condition, $logic = 'AND')
    {
        return $this->parseWhereExp($logic, $field, 'NOT LIKE', $condition, [], true);
    }

    /**
     * 指定Between查询条件
     * @access public
     * @param  mixed  $field     查询字段
     * @param  mixed  $condition 查询条件
     * @param  string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereBetween($field, $condition, $logic = 'AND')
    {
        return $this->parseWhereExp($logic, $field, 'BETWEEN', $condition, [], true);
    }

    /**
     * 指定NotBetween查询条件
     * @access public
     * @param  mixed  $field     查询字段
     * @param  mixed  $condition 查询条件
     * @param  string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereNotBetween($field, $condition, $logic = 'AND')
    {
        return $this->parseWhereExp($logic, $field, 'NOT BETWEEN', $condition, [], true);
    }

    /**
     * 比较两个字段
     * @access public
     * @param  string|array $field1     查询字段
     * @param  string       $operator   比较操作符
     * @param  string       $field2     比较字段
     * @param  string       $logic      查询逻辑 and or xor
     * @return $this
     */
    public function whereColumn($field1, $operator = null, $field2 = null, $logic = 'AND')
    {
        if (is_array($field1)) {
            foreach ($field1 as $item) {
                $this->whereColumn($item[0], $item[1], isset($item[2]) ? $item[2] : null);
            }
            return $this;
        }

        if (is_null($field2)) {
            $field2   = $operator;
            $operator = '=';
        }

        return $this->parseWhereExp($logic, $field1, 'COLUMN', [$operator, $field2], [], true);
    }

    /**
     * 设置软删除字段及条件
     * @access public
     * @param  false|string  $field     查询字段
     * @param  mixed         $condition 查询条件
     * @return $this
     */
    public function useSoftDelete($field, $condition = null)
    {
        if ($field) {
            $this->options['soft_delete'] = [$field, $condition];
        }

        return $this;
    }

    /**
     * 指定Exp查询条件
     * @access public
     * @param  mixed  $field     查询字段
     * @param  string $where     查询条件
     * @param  array  $bind      参数绑定
     * @param  string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereExp($field, $where, $bind = [], $logic = 'AND')
    {
        if ($bind) {
            $this->bindParams($where, $bind);
        }

        $this->options['where'][$logic][] = [$field, 'EXP', $this->raw($where)];

=======
        $this->parseWhereExp('XOR', $field, $op, $condition, $param);
>>>>>>> main
        return $this;
    }

    /**
     * 指定表达式查询条件
     * @access public
     * @param  string $where  查询条件
     * @param  array  $bind   参数绑定
     * @param  string $logic  查询逻辑 and or xor
     * @return $this
     */
    public function whereRaw($where, $bind = [], $logic = 'AND')
    {
<<<<<<< HEAD
        if ($bind) {
            $this->bindParams($where, $bind);
        }

        $this->options['where'][$logic][] = $this->raw($where);

        return $this;
    }

    /**
     * 参数绑定
     * @access public
     * @param  string $sql    绑定的sql表达式
     * @param  array  $bind   参数绑定
     * @return void
     */
    protected function bindParams(&$sql, array $bind = [])
    {
        foreach ($bind as $key => $value) {
            if (is_array($value)) {
                $name = $this->bind($value[0], $value[1], isset($value[2]) ? $value[2] : null);
            } else {
                $name = $this->bind($value);
            }

            if (is_numeric($key)) {
                $sql = substr_replace($sql, ':' . $name, strpos($sql, '?'), 1);
            } else {
                $sql = str_replace(':' . $key, ':' . $name, $sql);
            }
        }
=======
        $this->options['where'][$logic][] = $this->raw($where);

        if ($bind) {
            $this->bind($bind);
        }

        return $this;
>>>>>>> main
    }

    /**
     * 指定表达式查询条件 OR
     * @access public
     * @param  string $where  查询条件
     * @param  array  $bind   参数绑定
     * @return $this
     */
    public function whereOrRaw($where, $bind = [])
    {
        return $this->whereRaw($where, $bind, 'OR');
    }

    /**
<<<<<<< HEAD
     * 分析查询表达式
     * @access protected
     * @param  string   $logic     查询逻辑 and or xor
     * @param  mixed    $field     查询字段
     * @param  mixed    $op        查询表达式
     * @param  mixed    $condition 查询条件
     * @param  array    $param     查询参数
     * @param  bool     $strict    严格模式
     * @return $this
     */
    protected function parseWhereExp($logic, $field, $op, $condition, array $param = [], $strict = false)
    {
        if ($field instanceof $this) {
            $this->options['where'] = $field->getOptions('where');
            $this->bind($field->getBind(false));
            return $this;
        }

        $logic = strtoupper($logic);

        if ($field instanceof Where) {
            $this->options['where'][$logic] = $field->parse();
            return $this;
        }

        if (is_string($field) && !empty($this->options['via']) && false === strpos($field, '.')) {
=======
     * 指定Null查询条件
     * @access public
     * @param mixed  $field 查询字段
     * @param string $logic 查询逻辑 and or xor
     * @return $this
     */
    public function whereNull($field, $logic = 'AND')
    {
        $this->parseWhereExp($logic, $field, 'null', null, [], true);
        return $this;
    }

    /**
     * 指定NotNull查询条件
     * @access public
     * @param mixed  $field 查询字段
     * @param string $logic 查询逻辑 and or xor
     * @return $this
     */
    public function whereNotNull($field, $logic = 'AND')
    {
        $this->parseWhereExp($logic, $field, 'notnull', null, [], true);
        return $this;
    }

    /**
     * 指定Exists查询条件
     * @access public
     * @param mixed  $condition 查询条件
     * @param string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereExists($condition, $logic = 'AND')
    {
        $this->options['where'][strtoupper($logic)][] = ['exists', $condition];
        return $this;
    }

    /**
     * 指定NotExists查询条件
     * @access public
     * @param mixed  $condition 查询条件
     * @param string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereNotExists($condition, $logic = 'AND')
    {
        $this->options['where'][strtoupper($logic)][] = ['not exists', $condition];
        return $this;
    }

    /**
     * 指定In查询条件
     * @access public
     * @param mixed  $field     查询字段
     * @param mixed  $condition 查询条件
     * @param string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereIn($field, $condition, $logic = 'AND')
    {
        $this->parseWhereExp($logic, $field, 'in', $condition, [], true);
        return $this;
    }

    /**
     * 指定NotIn查询条件
     * @access public
     * @param mixed  $field     查询字段
     * @param mixed  $condition 查询条件
     * @param string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereNotIn($field, $condition, $logic = 'AND')
    {
        $this->parseWhereExp($logic, $field, 'not in', $condition, [], true);
        return $this;
    }

    /**
     * 指定Like查询条件
     * @access public
     * @param mixed  $field     查询字段
     * @param mixed  $condition 查询条件
     * @param string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereLike($field, $condition, $logic = 'AND')
    {
        $this->parseWhereExp($logic, $field, 'like', $condition, [], true);
        return $this;
    }

    /**
     * 指定NotLike查询条件
     * @access public
     * @param mixed  $field     查询字段
     * @param mixed  $condition 查询条件
     * @param string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereNotLike($field, $condition, $logic = 'AND')
    {
        $this->parseWhereExp($logic, $field, 'not like', $condition, [], true);
        return $this;
    }

    /**
     * 指定Between查询条件
     * @access public
     * @param mixed  $field     查询字段
     * @param mixed  $condition 查询条件
     * @param string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereBetween($field, $condition, $logic = 'AND')
    {
        $this->parseWhereExp($logic, $field, 'between', $condition, [], true);
        return $this;
    }

    /**
     * 指定NotBetween查询条件
     * @access public
     * @param mixed  $field     查询字段
     * @param mixed  $condition 查询条件
     * @param string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereNotBetween($field, $condition, $logic = 'AND')
    {
        $this->parseWhereExp($logic, $field, 'not between', $condition, [], true);
        return $this;
    }

    /**
     * 指定Exp查询条件
     * @access public
     * @param mixed  $field     查询字段
     * @param mixed  $condition 查询条件
     * @param string $logic     查询逻辑 and or xor
     * @return $this
     */
    public function whereExp($field, $condition, $logic = 'AND')
    {
        $this->parseWhereExp($logic, $field, 'exp', $this->raw($condition), [], true);
        return $this;
    }

    /**
     * 设置软删除字段及条件
     * @access public
     * @param false|string  $field     查询字段
     * @param mixed         $condition 查询条件
     * @return $this
     */
    public function useSoftDelete($field, $condition = null)
    {
        if ($field) {
            $this->options['soft_delete'] = [$field, $condition ?: ['null', '']];
        }
        return $this;
    }

    /**
     * 分析查询表达式
     * @access public
     * @param string                $logic     查询逻辑 and or xor
     * @param string|array|\Closure $field     查询字段
     * @param mixed                 $op        查询表达式
     * @param mixed                 $condition 查询条件
     * @param array                 $param     查询参数
     * @param  bool                 $strict    严格模式
     * @return void
     */
    protected function parseWhereExp($logic, $field, $op, $condition, $param = [], $strict = false)
    {
        $logic = strtoupper($logic);
        if ($field instanceof \Closure) {
            $this->options['where'][$logic][] = is_string($op) ? [$op, $field] : $field;
            return;
        }

        if (is_string($field) && !empty($this->options['via']) && !strpos($field, '.')) {
>>>>>>> main
            $field = $this->options['via'] . '.' . $field;
        }

        if ($field instanceof Expression) {
<<<<<<< HEAD
            return $this->whereRaw($field, is_array($op) ? $op : [], $logic);
        } elseif ($strict) {
            // 使用严格模式查询
            $where = [$field, $op, $condition, $logic];
        } elseif (is_array($field)) {
            // 解析数组批量查询
            return $this->parseArrayWhereItems($field, $logic);
        } elseif ($field instanceof \Closure) {
            $where = $field;
        } elseif (is_string($field)) {
            if (preg_match('/[,=\<\'\"\(\s]/', $field)) {
                return $this->whereRaw($field, $op, $logic);
            } elseif (is_string($op) && strtolower($op) == 'exp') {
                $bind = isset($param[2]) && is_array($param[2]) ? $param[2] : null;
                return $this->whereExp($field, $condition, $bind, $logic);
            }

            $where = $this->parseWhereItem($logic, $field, $op, $condition, $param);
        }

        if (!empty($where)) {
            $this->options['where'][$logic][] = $where;
        }

        return $this;
    }

    /**
     * 分析查询表达式
     * @access protected
     * @param  string   $logic     查询逻辑 and or xor
     * @param  mixed    $field     查询字段
     * @param  mixed    $op        查询表达式
     * @param  mixed    $condition 查询条件
     * @param  array    $param     查询参数
     * @return mixed
     */
    protected function parseWhereItem($logic, $field, $op, $condition, $param = [])
    {
        if (is_array($op)) {
            // 同一字段多条件查询
            array_unshift($param, $field);
            $where = $param;
        } elseif ($field && is_null($condition)) {
            if (in_array(strtoupper($op), ['NULL', 'NOTNULL', 'NOT NULL'], true)) {
                // null查询
                $where = [$field, $op, ''];
            } elseif (in_array($op, ['=', 'eq', 'EQ', null], true)) {
                $where = [$field, 'NULL', ''];
            } elseif (in_array($op, ['<>', 'neq', 'NEQ'], true)) {
                $where = [$field, 'NOTNULL', ''];
            } else {
                // 字段相等查询
                $where = [$field, '=', $op];
            }
        } elseif (in_array(strtoupper($op), ['EXISTS', 'NOT EXISTS', 'NOTEXISTS'], true)) {
            $where = [$field, $op, is_string($condition) ? $this->raw($condition) : $condition];
        } else {
            $where = $field ? [$field, $op, $condition, isset($param[2]) ? $param[2] : null] : null;
        }

        return $where;
    }

    /**
     * 数组批量查询
     * @access protected
     * @param  array    $field     批量查询
     * @param  string   $logic     查询逻辑 and or xor
     * @return $this
     */
    protected function parseArrayWhereItems($field, $logic)
    {
        if (key($field) !== 0) {
            $where = [];
            foreach ($field as $key => $val) {
                if ($val instanceof Expression) {
                    $where[] = [$key, 'exp', $val];
                } elseif (is_null($val)) {
                    $where[] = [$key, 'NULL', ''];
                } else {
                    $where[] = [$key, is_array($val) ? 'IN' : '=', $val];
                }
            }
        } else {
            // 数组批量查询
            $where = $field;
        }

        if (!empty($where)) {
            $this->options['where'][$logic] = isset($this->options['where'][$logic]) ? array_merge($this->options['where'][$logic], $where) : $where;
        }

        return $this;
=======
            return $this->whereRaw($field, is_array($op) ? $op : []);
        } elseif ($strict) {
            // 使用严格模式查询
            $where[$field] = [$op, $condition];

            // 记录一个字段多次查询条件
            $this->options['multi'][$logic][$field][] = $where[$field];
        } elseif (is_string($field) && preg_match('/[,=\>\<\'\"\(\s]/', $field)) {
            $where[] = ['exp', $this->raw($field)];
            if (is_array($op)) {
                // 参数绑定
                $this->bind($op);
            }
        } elseif (is_null($op) && is_null($condition)) {
            if (is_array($field)) {
                // 数组批量查询
                $where = $field;
                foreach ($where as $k => $val) {
                    $this->options['multi'][$logic][$k][] = $val;
                }
            } elseif ($field && is_string($field)) {
                // 字符串查询
                $where[$field]                            = ['null', ''];
                $this->options['multi'][$logic][$field][] = $where[$field];
            }
        } elseif (is_array($op)) {
            $where[$field] = $param;
        } elseif (in_array(strtolower($op), ['null', 'notnull', 'not null'])) {
            // null查询
            $where[$field] = [$op, ''];

            $this->options['multi'][$logic][$field][] = $where[$field];
        } elseif (is_null($condition)) {
            // 字段相等查询
            $where[$field] = ['eq', $op];

            $this->options['multi'][$logic][$field][] = $where[$field];
        } else {
            if ('exp' == strtolower($op)) {
                $where[$field] = ['exp', $this->raw($condition)];
                // 参数绑定
                if (isset($param[2]) && is_array($param[2])) {
                    $this->bind($param[2]);
                }
            } else {
                $where[$field] = [$op, $condition];
            }
            // 记录一个字段多次查询条件
            $this->options['multi'][$logic][$field][] = $where[$field];
        }

        if (!empty($where)) {
            if (!isset($this->options['where'][$logic])) {
                $this->options['where'][$logic] = [];
            }
            if (is_string($field) && $this->checkMultiField($field, $logic)) {
                $where[$field] = $this->options['multi'][$logic][$field];
            } elseif (is_array($field)) {
                foreach ($field as $key => $val) {
                    if ($this->checkMultiField($key, $logic)) {
                        $where[$key] = $this->options['multi'][$logic][$key];
                    }
                }
            }
            $this->options['where'][$logic] = array_merge($this->options['where'][$logic], $where);
        }
    }

    /**
     * 检查是否存在一个字段多次查询条件
     * @access public
     * @param string $field 查询字段
     * @param string $logic 查询逻辑 and or xor
     * @return bool
     */
    private function checkMultiField($field, $logic)
    {
        return isset($this->options['multi'][$logic][$field]) && count($this->options['multi'][$logic][$field]) > 1;
>>>>>>> main
    }

    /**
     * 去除某个查询条件
     * @access public
<<<<<<< HEAD
     * @param  string $field 查询字段
     * @param  string $logic 查询逻辑 and or xor
=======
     * @param string $field 查询字段
     * @param string $logic 查询逻辑 and or xor
>>>>>>> main
     * @return $this
     */
    public function removeWhereField($field, $logic = 'AND')
    {
        $logic = strtoupper($logic);
<<<<<<< HEAD

        if (isset($this->options['where'][$logic])) {
            foreach ($this->options['where'][$logic] as $key => $val) {
                if (is_array($val) && $val[0] == $field) {
                    unset($this->options['where'][$logic][$key]);
                }
            }
        }

=======
        if (isset($this->options['where'][$logic][$field])) {
            unset($this->options['where'][$logic][$field]);
            unset($this->options['multi'][$logic][$field]);
        }
>>>>>>> main
        return $this;
    }

    /**
     * 去除查询参数
     * @access public
<<<<<<< HEAD
     * @param  string|bool $option 参数名 true 表示去除所有参数
=======
     * @param string|bool $option 参数名 true 表示去除所有参数
>>>>>>> main
     * @return $this
     */
    public function removeOption($option = true)
    {
        if (true === $option) {
            $this->options = [];
<<<<<<< HEAD
            $this->bind    = [];
        } elseif (is_string($option) && isset($this->options[$option])) {
            unset($this->options[$option]);
        }

        return $this;
    }

    /**
     * 条件查询
     * @access public
     * @param  mixed             $condition  满足条件（支持闭包）
     * @param  \Closure|array    $query      满足条件后执行的查询表达式（闭包或数组）
     * @param  \Closure|array    $otherwise  不满足条件后执行
     * @return $this
     */
    public function when($condition, $query, $otherwise = null)
    {
        if ($condition instanceof \Closure) {
            $condition = $condition($this);
        }

        if ($condition) {
            if ($query instanceof \Closure) {
                $query($this, $condition);
            } elseif (is_array($query)) {
                $this->where($query);
            }
        } elseif ($otherwise) {
            if ($otherwise instanceof \Closure) {
                $otherwise($this, $condition);
            } elseif (is_array($otherwise)) {
                $this->where($otherwise);
            }
        }

=======
        } elseif (is_string($option) && isset($this->options[$option])) {
            unset($this->options[$option]);
        }
>>>>>>> main
        return $this;
    }

    /**
     * 指定查询数量
     * @access public
<<<<<<< HEAD
     * @param  mixed $offset 起始位置
     * @param  mixed $length 查询数量
=======
     * @param mixed $offset 起始位置
     * @param mixed $length 查询数量
>>>>>>> main
     * @return $this
     */
    public function limit($offset, $length = null)
    {
        if (is_null($length) && strpos($offset, ',')) {
            list($offset, $length) = explode(',', $offset);
        }
<<<<<<< HEAD

        $this->options['limit'] = intval($offset) . ($length ? ',' . intval($length) : '');

=======
        $this->options['limit'] = intval($offset) . ($length ? ',' . intval($length) : '');
>>>>>>> main
        return $this;
    }

    /**
     * 指定分页
     * @access public
<<<<<<< HEAD
     * @param  mixed $page     页数
     * @param  mixed $listRows 每页数量
=======
     * @param mixed $page     页数
     * @param mixed $listRows 每页数量
>>>>>>> main
     * @return $this
     */
    public function page($page, $listRows = null)
    {
        if (is_null($listRows) && strpos($page, ',')) {
            list($page, $listRows) = explode(',', $page);
        }
<<<<<<< HEAD

        $this->options['page'] = [intval($page), intval($listRows)];

=======
        $this->options['page'] = [intval($page), intval($listRows)];
>>>>>>> main
        return $this;
    }

    /**
     * 分页查询
<<<<<<< HEAD
     * @access public
     * @param  int|array $listRows 每页数量 数组表示配置参数
     * @param  int|bool  $simple   是否简洁模式或者总记录数
     * @param  array     $config   配置参数
=======
     * @param int|array $listRows 每页数量 数组表示配置参数
     * @param int|bool  $simple   是否简洁模式或者总记录数
     * @param array     $config   配置参数
>>>>>>> main
     *                            page:当前页,
     *                            path:url路径,
     *                            query:url额外参数,
     *                            fragment:url锚点,
     *                            var_page:分页变量,
     *                            list_rows:每页数量
     *                            type:分页类名
<<<<<<< HEAD
     * @return $this[]|\think\Paginator
=======
     * @return \think\Paginator
>>>>>>> main
     * @throws DbException
     */
    public function paginate($listRows = null, $simple = false, $config = [])
    {
        if (is_int($simple)) {
            $total  = $simple;
            $simple = false;
        }
<<<<<<< HEAD

        $paginate = Container::get('config')->pull('paginate');

        if (is_array($listRows)) {
            $config   = array_merge($paginate, $listRows);
            $listRows = $config['list_rows'];
        } else {
            $config   = array_merge($paginate, $config);
=======
        if (is_array($listRows)) {
            $config   = array_merge(Config::get('paginate'), $listRows);
            $listRows = $config['list_rows'];
        } else {
            $config   = array_merge(Config::get('paginate'), $config);
>>>>>>> main
            $listRows = $listRows ?: $config['list_rows'];
        }

        /** @var Paginator $class */
        $class = false !== strpos($config['type'], '\\') ? $config['type'] : '\\think\\paginator\\driver\\' . ucwords($config['type']);
        $page  = isset($config['page']) ? (int) $config['page'] : call_user_func([
            $class,
            'getCurrentPage',
        ], $config['var_page']);

        $page = $page < 1 ? 1 : $page;

        $config['path'] = isset($config['path']) ? $config['path'] : call_user_func([$class, 'getCurrentPath']);

        if (!isset($total) && !$simple) {
            $options = $this->getOptions();

            unset($this->options['order'], $this->options['limit'], $this->options['page'], $this->options['field']);

            $bind    = $this->bind;
            $total   = $this->count();
            $results = $this->options($options)->bind($bind)->page($page, $listRows)->select();
        } elseif ($simple) {
            $results = $this->limit(($page - 1) * $listRows, $listRows + 1)->select();
            $total   = null;
        } else {
            $results = $this->page($page, $listRows)->select();
        }
<<<<<<< HEAD

        $this->removeOption('limit');
        $this->removeOption('page');

=======
>>>>>>> main
        return $class::make($results, $listRows, $page, $total, $simple, $config);
    }

    /**
     * 指定当前操作的数据表
     * @access public
<<<<<<< HEAD
     * @param  mixed $table 表名
=======
     * @param mixed $table 表名
>>>>>>> main
     * @return $this
     */
    public function table($table)
    {
        if (is_string($table)) {
            if (strpos($table, ')')) {
                // 子查询
            } elseif (strpos($table, ',')) {
                $tables = explode(',', $table);
                $table  = [];
<<<<<<< HEAD

=======
>>>>>>> main
                foreach ($tables as $item) {
                    list($item, $alias) = explode(' ', trim($item));
                    if ($alias) {
                        $this->alias([$item => $alias]);
                        $table[$item] = $alias;
                    } else {
                        $table[] = $item;
                    }
                }
            } elseif (strpos($table, ' ')) {
                list($table, $alias) = explode(' ', $table);

                $table = [$table => $alias];
                $this->alias($table);
            }
        } else {
            $tables = $table;
            $table  = [];
<<<<<<< HEAD

=======
>>>>>>> main
            foreach ($tables as $key => $val) {
                if (is_numeric($key)) {
                    $table[] = $val;
                } else {
                    $this->alias([$key => $val]);
                    $table[$key] = $val;
                }
            }
        }
<<<<<<< HEAD

        $this->options['table'] = $table;

=======
        $this->options['table'] = $table;
>>>>>>> main
        return $this;
    }

    /**
     * USING支持 用于多表删除
     * @access public
<<<<<<< HEAD
     * @param  mixed $using
=======
     * @param mixed $using
>>>>>>> main
     * @return $this
     */
    public function using($using)
    {
        $this->options['using'] = $using;
        return $this;
    }

    /**
     * 指定排序 order('id','desc') 或者 order(['id'=>'desc','create_time'=>'desc'])
     * @access public
<<<<<<< HEAD
     * @param  string|array $field 排序字段
     * @param  string       $order 排序
=======
     * @param string|array $field 排序字段
     * @param string       $order 排序
>>>>>>> main
     * @return $this
     */
    public function order($field, $order = null)
    {
        if (empty($field)) {
            return $this;
        } elseif ($field instanceof Expression) {
            $this->options['order'][] = $field;
            return $this;
        }

        if (is_string($field)) {
            if (!empty($this->options['via'])) {
                $field = $this->options['via'] . '.' . $field;
            }
<<<<<<< HEAD

=======
>>>>>>> main
            if (strpos($field, ',')) {
                $field = array_map('trim', explode(',', $field));
            } else {
                $field = empty($order) ? $field : [$field => $order];
            }
        } elseif (!empty($this->options['via'])) {
            foreach ($field as $key => $val) {
                if (is_numeric($key)) {
                    $field[$key] = $this->options['via'] . '.' . $val;
                } else {
                    $field[$this->options['via'] . '.' . $key] = $val;
                    unset($field[$key]);
                }
            }
        }
<<<<<<< HEAD

        if (!isset($this->options['order'])) {
            $this->options['order'] = [];
        }

=======
        if (!isset($this->options['order'])) {
            $this->options['order'] = [];
        }
>>>>>>> main
        if (is_array($field)) {
            $this->options['order'] = array_merge($this->options['order'], $field);
        } else {
            $this->options['order'][] = $field;
        }

        return $this;
    }

    /**
     * 表达式方式指定Field排序
     * @access public
     * @param  string $field 排序字段
     * @param  array  $bind  参数绑定
     * @return $this
     */
<<<<<<< HEAD
    public function orderRaw($field, $bind = [])
    {
        if ($bind) {
            $this->bindParams($field, $bind);
        }

        $this->options['order'][] = $this->raw($field);

        return $this;
    }

    /**
     * 指定Field排序 order('id',[1,2,3],'desc')
     * @access public
     * @param  string|array $field 排序字段
     * @param  array        $values 排序值
     * @param  string       $order
     * @return $this
     */
    public function orderField($field, array $values, $order = '')
    {
        if (!empty($values)) {
            $values['sort'] = $order;

            $this->options['order'][$field] = $values;
=======
    public function orderRaw($field, array $bind = [])
    {
        $this->options['order'][] = $this->raw($field);

        if ($bind) {
            $this->bind($bind);
>>>>>>> main
        }

        return $this;
    }

    /**
<<<<<<< HEAD
     * 随机排序
     * @access public
     * @return $this
     */
    public function orderRand()
    {
        $this->options['order'][] = '[rand]';
        return $this;
    }

    /**
     * 查询缓存
     * @access public
     * @param  mixed             $key    缓存key
     * @param  integer|\DateTime $expire 缓存有效期
     * @param  string            $tag    缓存标签
=======
     * 查询缓存
     * @access public
     * @param mixed             $key    缓存key
     * @param integer|\DateTime $expire 缓存有效期
     * @param string            $tag    缓存标签
>>>>>>> main
     * @return $this
     */
    public function cache($key = true, $expire = null, $tag = null)
    {
        // 增加快捷调用方式 cache(10) 等同于 cache(true, 10)
        if ($key instanceof \DateTime || (is_numeric($key) && is_null($expire))) {
            $expire = $key;
            $key    = true;
        }

        if (false !== $key) {
            $this->options['cache'] = ['key' => $key, 'expire' => $expire, 'tag' => $tag];
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 指定group查询
     * @access public
<<<<<<< HEAD
     * @param  string|array $group GROUP
=======
     * @param string $group GROUP
>>>>>>> main
     * @return $this
     */
    public function group($group)
    {
        $this->options['group'] = $group;
        return $this;
    }

    /**
     * 指定having查询
     * @access public
<<<<<<< HEAD
     * @param  string $having having
=======
     * @param string $having having
>>>>>>> main
     * @return $this
     */
    public function having($having)
    {
        $this->options['having'] = $having;
        return $this;
    }

    /**
     * 指定查询lock
     * @access public
<<<<<<< HEAD
     * @param  bool|string $lock 是否lock
=======
     * @param bool|string $lock 是否lock
>>>>>>> main
     * @return $this
     */
    public function lock($lock = false)
    {
        $this->options['lock']   = $lock;
        $this->options['master'] = true;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 指定distinct查询
     * @access public
<<<<<<< HEAD
     * @param  string $distinct 是否唯一
=======
     * @param string $distinct 是否唯一
>>>>>>> main
     * @return $this
     */
    public function distinct($distinct)
    {
        $this->options['distinct'] = $distinct;
        return $this;
    }

    /**
     * 指定数据表别名
     * @access public
<<<<<<< HEAD
     * @param  array|string $alias 数据表别名
=======
     * @param mixed $alias 数据表别名
>>>>>>> main
     * @return $this
     */
    public function alias($alias)
    {
        if (is_array($alias)) {
            foreach ($alias as $key => $val) {
                if (false !== strpos($key, '__')) {
<<<<<<< HEAD
                    $table = $this->connection->parseSqlTable($key);
=======
                    $table = $this->parseSqlTable($key);
>>>>>>> main
                } else {
                    $table = $key;
                }
                $this->options['alias'][$table] = $val;
            }
        } else {
            if (isset($this->options['table'])) {
                $table = is_array($this->options['table']) ? key($this->options['table']) : $this->options['table'];
                if (false !== strpos($table, '__')) {
<<<<<<< HEAD
                    $table = $this->connection->parseSqlTable($table);
=======
                    $table = $this->parseSqlTable($table);
>>>>>>> main
                }
            } else {
                $table = $this->getTable();
            }

            $this->options['alias'][$table] = $alias;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 指定强制索引
     * @access public
<<<<<<< HEAD
     * @param  string $force 索引名称
=======
     * @param string $force 索引名称
>>>>>>> main
     * @return $this
     */
    public function force($force)
    {
        $this->options['force'] = $force;
        return $this;
    }

    /**
     * 查询注释
     * @access public
<<<<<<< HEAD
     * @param  string $comment 注释
=======
     * @param string $comment 注释
>>>>>>> main
     * @return $this
     */
    public function comment($comment)
    {
        $this->options['comment'] = $comment;
        return $this;
    }

    /**
     * 获取执行的SQL语句
     * @access public
<<<<<<< HEAD
     * @param  boolean $fetch 是否返回sql
=======
     * @param boolean $fetch 是否返回sql
>>>>>>> main
     * @return $this
     */
    public function fetchSql($fetch = true)
    {
        $this->options['fetch_sql'] = $fetch;
        return $this;
    }

    /**
     * 不主动获取数据集
     * @access public
<<<<<<< HEAD
     * @param  bool $pdo 是否返回 PDOStatement 对象
=======
     * @param bool $pdo 是否返回 PDOStatement 对象
>>>>>>> main
     * @return $this
     */
    public function fetchPdo($pdo = true)
    {
        $this->options['fetch_pdo'] = $pdo;
        return $this;
    }

    /**
<<<<<<< HEAD
     * 设置是否返回数据集对象（支持设置数据集对象类名）
     * @access public
     * @param  bool|string  $collection  是否返回数据集对象
     * @return $this
     */
    public function fetchCollection($collection = true)
    {
        $this->options['collection'] = $collection;

        return $this;
    }

    /**
=======
>>>>>>> main
     * 设置从主服务器读取数据
     * @access public
     * @return $this
     */
    public function master()
    {
        $this->options['master'] = true;
        return $this;
    }

    /**
     * 设置是否严格检查字段名
     * @access public
<<<<<<< HEAD
     * @param  bool $strict 是否严格检查字段
=======
     * @param bool $strict 是否严格检查字段
>>>>>>> main
     * @return $this
     */
    public function strict($strict = true)
    {
        $this->options['strict'] = $strict;
        return $this;
    }

    /**
     * 设置查询数据不存在是否抛出异常
     * @access public
<<<<<<< HEAD
     * @param  bool $fail 数据不存在是否抛出异常
=======
     * @param bool $fail 数据不存在是否抛出异常
>>>>>>> main
     * @return $this
     */
    public function failException($fail = true)
    {
        $this->options['fail'] = $fail;
        return $this;
    }

    /**
     * 设置自增序列名
     * @access public
<<<<<<< HEAD
     * @param  string $sequence 自增序列名
=======
     * @param string $sequence 自增序列名
>>>>>>> main
     * @return $this
     */
    public function sequence($sequence = null)
    {
        $this->options['sequence'] = $sequence;
        return $this;
    }

    /**
<<<<<<< HEAD
     * 设置需要隐藏的输出属性
     * @access public
     * @param  mixed $hidden 需要隐藏的字段名
     * @return $this
     */
    public function hidden($hidden)
    {
        if ($this->model) {
            $this->options['hidden'] = $hidden;
            return $this;
        }

        return $this->field($hidden, true);
    }

    /**
     * 设置需要输出的属性
     * @access public
     * @param  array $visible 需要输出的属性
     * @return $this
     */
    public function visible(array $visible)
    {
        $this->options['visible'] = $visible;
        return $this;
    }

    /**
     * 设置需要附加的输出属性
     * @access public
     * @param  array $append   属性列表
     * @return $this
     */
    public function append(array $append = [])
    {
        $this->options['append'] = $append;
        return $this;
    }

    /**
     * 设置数据字段获取器
     * @access public
     * @param  string|array $name       字段名
     * @param  callable     $callback   闭包获取器
     * @return $this
     */
    public function withAttr($name, $callback = null)
    {
        if (is_array($name)) {
            $this->options['with_attr'] = $name;
        } else {
            $this->options['with_attr'][$name] = $callback;
        }

        return $this;
    }

    /**
     * 设置JSON字段信息
     * @access public
     * @param  array $json JSON字段
     * @param  bool  $assoc 是否取出数组
     * @return $this
     */
    public function json(array $json = [], $assoc = false)
    {
        $this->options['json']       = $json;
        $this->options['json_assoc'] = $assoc;
        return $this;
    }

    /**
     * 设置字段类型信息
     * @access public
     * @param  array $type 字段类型信息
     * @return $this
     */
    public function setJsonFieldType(array $type)
    {
        $this->options['field_type'] = $type;
        return $this;
    }

    /**
     * 获取字段类型信息
     * @access public
     * @param  string $field 字段名
     * @return string|null
     */
    public function getJsonFieldType($field)
    {
        return isset($this->options['field_type'][$field]) ? $this->options['field_type'][$field] : null;
    }

    /**
     * 是否允许返回空数据（或空模型）
     * @access public
     * @param  bool $allowEmpty 是否允许为空
     * @return $this
     */
    public function allowEmpty($allowEmpty = true)
    {
        $this->options['allow_empty'] = $allowEmpty;
        return $this;
    }

    /**
     * 添加查询范围
     * @access public
     * @param  array|string|\Closure   $scope 查询范围定义
     * @param  array                   $args  参数
     * @return $this
     */
    public function scope($scope, ...$args)
    {
        // 查询范围的第一个参数始终是当前查询对象
        array_unshift($args, $this);

        if ($scope instanceof \Closure) {
            call_user_func_array($scope, $args);
            return $this;
        }

        if (is_string($scope)) {
            $scope = explode(',', $scope);
        }

        if ($this->model) {
            // 检查模型类的查询范围方法
            foreach ($scope as $name) {
                $method = 'scope' . trim($name);

                if (method_exists($this->model, $method)) {
                    call_user_func_array([$this->model, $method], $args);
                }
            }
        }

        return $this;
    }

    /**
     * 使用搜索器条件搜索字段
     * @access public
     * @param  array    $fields     搜索字段
     * @param  array    $data       搜索数据
     * @param  string   $prefix     字段前缀标识
     * @return $this
     */
    public function withSearch(array $fields, array $data = [], $prefix = '')
    {
        foreach ($fields as $key => $field) {
            if ($field instanceof \Closure) {
                $field($this, isset($data[$key]) ? $data[$key] : null, $data, $prefix);
            } elseif ($this->model) {
                // 检测搜索器
                $fieldName = is_numeric($key) ? $field : $key;
                $method    = 'search' . Loader::parseName($fieldName, 1) . 'Attr';

                if (method_exists($this->model, $method)) {
                    $this->model->$method($this, isset($data[$field]) ? $data[$field] : null, $data, $prefix);
                }
            }
        }

        return $this;
    }

    /**
     * 指定数据表主键
     * @access public
     * @param  string $pk 主键
=======
     * 指定数据表主键
     * @access public
     * @param string $pk 主键
>>>>>>> main
     * @return $this
     */
    public function pk($pk)
    {
        $this->pk = $pk;
        return $this;
    }

    /**
     * 查询日期或者时间
     * @access public
<<<<<<< HEAD
     * @param  string       $name  时间表达式
     * @param  string|array $rule  时间范围
     * @return $this
     */
    public function timeRule($name, $rule)
    {
        $this->timeRule[$name] = $rule;
        return $this;
    }

    /**
     * 查询日期或者时间
     * @access public
     * @param  string       $field 日期字段名
     * @param  string|array $op    比较运算符或者表达式
     * @param  string|array $range 比较范围
     * @param  string       $logic AND OR
     * @return $this
     */
    public function whereTime($field, $op, $range = null, $logic = 'AND')
=======
     * @param string       $field 日期字段名
     * @param string|array $op    比较运算符或者表达式
     * @param string|array $range 比较范围
     * @return $this
     */
    public function whereTime($field, $op, $range = null)
>>>>>>> main
    {
        if (is_null($range)) {
            if (is_array($op)) {
                $range = $op;
            } else {
<<<<<<< HEAD
                if (isset($this->timeExp[strtolower($op)])) {
                    $op = $this->timeExp[strtolower($op)];
                }

                if (isset($this->timeRule[strtolower($op)])) {
                    $range = $this->timeRule[strtolower($op)];
                } else {
                    $range = $op;
                }
            }

            $op = is_array($range) ? 'between' : '>=';
        }

        return $this->parseWhereExp($logic, $field, strtolower($op) . ' time', $range, [], true);
    }

    /**
     * 查询当前时间在两个时间字段范围
     * @access public
     * @param  string    $startField    开始时间字段
     * @param  string    $endField 结束时间字段
     * @return $this
     */
    public function whereBetweenTimeField($startField, $endField)
    {
        return $this->whereTime($startField, '<=', time())
            ->whereTime($endField, '>=', time());
    }

    /**
     * 查询当前时间不在两个时间字段范围
     * @access public
     * @param  string    $startField    开始时间字段
     * @param  string    $endField 结束时间字段
     * @return $this
     */
    public function whereNotBetweenTimeField($startField, $endField)
    {
        return $this->whereTime($startField, '>', time())
            ->whereTime($endField, '<', time(), 'OR');
    }

    /**
     * 查询日期或者时间范围
     * @access public
     * @param  string    $field 日期字段名
     * @param  string    $startTime    开始时间
     * @param  string    $endTime 结束时间
     * @param  string    $logic AND OR
     * @return $this
     */
    public function whereBetweenTime($field, $startTime, $endTime = null, $logic = 'AND')
    {
        if (is_null($endTime)) {
            $time    = is_string($startTime) ? strtotime($startTime) : $startTime;
            $endTime = strtotime('+1 day', $time);
        }

        return $this->parseWhereExp($logic, $field, 'between time', [$startTime, $endTime], [], true);
=======
                // 使用日期表达式
                switch (strtolower($op)) {
                    case 'today':
                    case 'd':
                        $range = ['today', 'tomorrow'];
                        break;
                    case 'week':
                    case 'w':
                        $range = ['this week 00:00:00', 'next week 00:00:00'];
                        break;
                    case 'month':
                    case 'm':
                        $range = ['first Day of this month 00:00:00', 'first Day of next month 00:00:00'];
                        break;
                    case 'year':
                    case 'y':
                        $range = ['this year 1/1', 'next year 1/1'];
                        break;
                    case 'yesterday':
                        $range = ['yesterday', 'today'];
                        break;
                    case 'last week':
                        $range = ['last week 00:00:00', 'this week 00:00:00'];
                        break;
                    case 'last month':
                        $range = ['first Day of last month 00:00:00', 'first Day of this month 00:00:00'];
                        break;
                    case 'last year':
                        $range = ['last year 1/1', 'this year 1/1'];
                        break;
                    default:
                        $range = $op;
                }
            }
            $op = is_array($range) ? 'between' : '>';
        }
        $this->where($field, strtolower($op) . ' time', $range);
        return $this;
    }

    /**
     * 获取数据表信息
     * @access public
     * @param mixed  $tableName 数据表名 留空自动获取
     * @param string $fetch     获取信息类型 包括 fields type bind pk
     * @return mixed
     */
    public function getTableInfo($tableName = '', $fetch = '')
    {
        if (!$tableName) {
            $tableName = $this->getTable();
        }
        if (is_array($tableName)) {
            $tableName = key($tableName) ?: current($tableName);
        }

        if (strpos($tableName, ',')) {
            // 多表不获取字段信息
            return false;
        } else {
            $tableName = $this->parseSqlTable($tableName);
        }

        // 修正子查询作为表名的问题
        if (strpos($tableName, ')')) {
            return [];
        }

        list($guid) = explode(' ', $tableName);
        $db         = $this->getConfig('database');
        if (!isset(self::$info[$db . '.' . $guid])) {
            if (!strpos($guid, '.')) {
                $schema = $db . '.' . $guid;
            } else {
                $schema = $guid;
            }
            // 读取缓存
            if (!App::$debug && is_file(RUNTIME_PATH . 'schema/' . $schema . '.php')) {
                $info = include RUNTIME_PATH . 'schema/' . $schema . '.php';
            } else {
                $info = $this->connection->getFields($guid);
            }
            $fields = array_keys($info);
            $bind   = $type   = [];
            foreach ($info as $key => $val) {
                // 记录字段类型
                $type[$key] = $val['type'];
                $bind[$key] = $this->getFieldBindType($val['type']);
                if (!empty($val['primary'])) {
                    $pk[] = $key;
                }
            }
            if (isset($pk)) {
                // 设置主键
                $pk = count($pk) > 1 ? $pk : $pk[0];
            } else {
                $pk = null;
            }
            self::$info[$db . '.' . $guid] = ['fields' => $fields, 'type' => $type, 'bind' => $bind, 'pk' => $pk];
        }
        return $fetch ? self::$info[$db . '.' . $guid][$fetch] : self::$info[$db . '.' . $guid];
>>>>>>> main
    }

    /**
     * 获取当前数据表的主键
     * @access public
<<<<<<< HEAD
     * @param  string|array $options 数据表名或者查询参数
=======
     * @param string|array $options 数据表名或者查询参数
>>>>>>> main
     * @return string|array
     */
    public function getPk($options = '')
    {
        if (!empty($this->pk)) {
            $pk = $this->pk;
        } else {
<<<<<<< HEAD
            $pk = $this->connection->getPk(is_array($options) && isset($options['table']) ? $options['table'] : $this->getTable());
        }

        return $pk;
    }

    /**
     * 参数绑定
     * @access public
     * @param  mixed   $value 绑定变量值
     * @param  integer $type  绑定类型
     * @param  string  $name  绑定名称
     * @return $this|string
     */
    public function bind($value, $type = PDO::PARAM_STR, $name = null)
    {
        if (is_array($value)) {
            $this->bind = array_merge($this->bind, $value);
        } else {
            $name = $name ?: 'ThinkBind_' . (count($this->bind) + 1) . '_' . mt_rand() . '_';

            $this->bind[$name] = [$value, $type];
            return $name;
        }

=======
            $pk = $this->getTableInfo(is_array($options) ? $options['table'] : $options, 'pk');
        }
        return $pk;
    }

    // 获取当前数据表字段信息
    public function getTableFields($table = '')
    {
        return $this->getTableInfo($table ?: $this->getOptions('table'), 'fields');
    }

    // 获取当前数据表字段类型
    public function getFieldsType($table = '')
    {
        return $this->getTableInfo($table ?: $this->getOptions('table'), 'type');
    }

    // 获取当前数据表绑定信息
    public function getFieldsBind($table = '')
    {
        $types = $this->getFieldsType($table);
        $bind  = [];
        if ($types) {
            foreach ($types as $key => $type) {
                $bind[$key] = $this->getFieldBindType($type);
            }
        }
        return $bind;
    }

    /**
     * 获取字段绑定类型
     * @access public
     * @param string $type 字段类型
     * @return integer
     */
    protected function getFieldBindType($type)
    {
        if (0 === strpos($type, 'set') || 0 === strpos($type, 'enum')) {
            $bind = PDO::PARAM_STR;
        } elseif (preg_match('/(int|double|float|decimal|real|numeric|serial|bit)/is', $type)) {
            $bind = PDO::PARAM_INT;
        } elseif (preg_match('/bool/is', $type)) {
            $bind = PDO::PARAM_BOOL;
        } else {
            $bind = PDO::PARAM_STR;
        }
        return $bind;
    }

    /**
     * 参数绑定
     * @access public
     * @param mixed   $key   参数名
     * @param mixed   $value 绑定变量值
     * @param integer $type  绑定类型
     * @return $this
     */
    public function bind($key, $value = false, $type = PDO::PARAM_STR)
    {
        if (is_array($key)) {
            $this->bind = array_merge($this->bind, $key);
        } else {
            $this->bind[$key] = [$value, $type];
        }
>>>>>>> main
        return $this;
    }

    /**
     * 检测参数是否已经绑定
     * @access public
<<<<<<< HEAD
     * @param  string $key 参数名
=======
     * @param string $key 参数名
>>>>>>> main
     * @return bool
     */
    public function isBind($key)
    {
        return isset($this->bind[$key]);
    }

    /**
     * 查询参数赋值
<<<<<<< HEAD
     * @access public
     * @param  string $name     参数名
     * @param  mixed  $value    值
     * @return $this
     */
    public function option($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * 查询参数赋值
     * @access protected
     * @param  array $options 表达式参数
=======
     * @access protected
     * @param array $options 表达式参数
>>>>>>> main
     * @return $this
     */
    protected function options(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * 获取当前的查询参数
     * @access public
<<<<<<< HEAD
     * @param  string $name 参数名
=======
     * @param string $name 参数名
>>>>>>> main
     * @return mixed
     */
    public function getOptions($name = '')
    {
        if ('' === $name) {
            return $this->options;
<<<<<<< HEAD
        }
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    /**
     * 设置当前的查询参数
     * @access public
     * @param  string $option 参数名
     * @param  mixed  $value  参数值
     * @return $this
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
        return $this;
=======
        } else {
            return isset($this->options[$name]) ? $this->options[$name] : null;
        }
>>>>>>> main
    }

    /**
     * 设置关联查询JOIN预查询
     * @access public
<<<<<<< HEAD
     * @param  string|array $with 关联方法名称
=======
     * @param string|array $with 关联方法名称
>>>>>>> main
     * @return $this
     */
    public function with($with)
    {
        if (empty($with)) {
            return $this;
        }

        if (is_string($with)) {
            $with = explode(',', $with);
        }

        $first = true;

        /** @var Model $class */
        $class = $this->model;
        foreach ($with as $key => $relation) {
<<<<<<< HEAD
            $closure = null;

            if ($relation instanceof \Closure) {
                // 支持闭包查询过滤关联条件
                $closure  = $relation;
                $relation = $key;
            } elseif (is_array($relation)) {
                $relation = $key;
            } elseif (is_string($relation) && strpos($relation, '.')) {
=======
            $subRelation = '';
            $closure     = false;
            if ($relation instanceof \Closure) {
                // 支持闭包查询过滤关联条件
                $closure    = $relation;
                $relation   = $key;
                $with[$key] = $key;
            } elseif (is_array($relation)) {
                $subRelation = $relation;
                $relation    = $key;
            } elseif (is_string($relation) && strpos($relation, '.')) {
                $with[$key]                   = $relation;
>>>>>>> main
                list($relation, $subRelation) = explode('.', $relation, 2);
            }

            /** @var Relation $model */
            $relation = Loader::parseName($relation, 1, false);
            $model    = $class->$relation();
<<<<<<< HEAD

            if ($model instanceof OneToOne && 0 == $model->getEagerlyType()) {
                $table = $model->getTable();
                $model->removeOption()
                    ->table($table)
                    ->eagerly($this, $relation, true, '', $closure, $first);
                $first = false;
            }
        }

        $this->via();

        $this->options['with'] = $with;

        return $this;
    }

    /**
     * 关联预载入 JOIN方式（不支持嵌套）
     * @access protected
     * @param  string|array $with 关联方法名
     * @param  string       $joinType JOIN方式
     * @return $this
     */
    public function withJoin($with, $joinType = '')
    {
        if (empty($with)) {
            return $this;
        }

        if (is_string($with)) {
            $with = explode(',', $with);
        }

        $first = true;

        /** @var Model $class */
        $class = $this->model;
        foreach ($with as $key => $relation) {
            $closure = null;
            $field   = true;

            if ($relation instanceof \Closure) {
                // 支持闭包查询过滤关联条件
                $closure  = $relation;
                $relation = $key;
            } elseif (is_array($relation)) {
                $field    = $relation;
                $relation = $key;
            } elseif (is_string($relation) && strpos($relation, '.')) {
                list($relation, $subRelation) = explode('.', $relation, 2);
            }

            /** @var Relation $model */
            $relation = Loader::parseName($relation, 1, false);
            $model    = $class->$relation();

            if ($model instanceof OneToOne) {
                $model->eagerly($this, $relation, $field, $joinType, $closure, $first);
                $first = false;
            } else {
                // 不支持其它关联
                unset($with[$key]);
            }
        }

        $this->via();

        $this->options['with_join'] = $with;

        return $this;
    }

    /**
     * 关联统计
     * @access protected
     * @param  string|array $relation 关联方法名
     * @param  string       $aggregate 聚合查询方法
     * @param  string       $field 字段
     * @param  bool         $subQuery 是否使用子查询
     * @return $this
     */
    protected function withAggregate($relation, $aggregate = 'count', $field = '*', $subQuery = true)
    {
        $relations = is_string($relation) ? explode(',', $relation) : $relation;

        if (!$subQuery) {
            $this->options['with_count'][] = [$relations, $aggregate, $field];
        } else {
            if (!isset($this->options['field'])) {
                $this->field('*');
            }

            foreach ($relations as $key => $relation) {
                $closure = $aggregateField = null;

                if ($relation instanceof \Closure) {
                    $closure  = $relation;
                    $relation = $key;
                } elseif (!is_int($key)) {
                    $aggregateField = $relation;
                    $relation       = $key;
                }

                $relation = Loader::parseName($relation, 1, false);

                $count = $this->model->$relation()->getRelationCountQuery($closure, $aggregate, $field, $aggregateField);

                if (empty($aggregateField)) {
                    $aggregateField = Loader::parseName($relation) . '_' . $aggregate;
                }

                $this->field(['(' . $count . ')' => $aggregateField]);
            }
        }

=======
            if ($model instanceof OneToOne && 0 == $model->getEagerlyType()) {
                $model->eagerly($this, $relation, $subRelation, $closure, $first);
                $first = false;
            } elseif ($closure) {
                $with[$key] = $closure;
            }
        }
        $this->via();
        if (isset($this->options['with'])) {
            $this->options['with'] = array_merge($this->options['with'], $with);
        } else {
            $this->options['with'] = $with;
        }
>>>>>>> main
        return $this;
    }

    /**
     * 关联统计
     * @access public
<<<<<<< HEAD
     * @param  string|array $relation 关联方法名
     * @param  bool         $subQuery 是否使用子查询
=======
     * @param string|array $relation 关联方法名
     * @param bool         $subQuery 是否使用子查询
>>>>>>> main
     * @return $this
     */
    public function withCount($relation, $subQuery = true)
    {
<<<<<<< HEAD
        return $this->withAggregate($relation, 'count', '*', $subQuery);
    }

    /**
     * 关联统计Sum
     * @access public
     * @param  string|array $relation 关联方法名
     * @param  string       $field 字段
     * @param  bool         $subQuery 是否使用子查询
     * @return $this
     */
    public function withSum($relation, $field, $subQuery = true)
    {
        return $this->withAggregate($relation, 'sum', $field, $subQuery);
    }

    /**
     * 关联统计Max
     * @access public
     * @param  string|array $relation 关联方法名
     * @param  string       $field 字段
     * @param  bool         $subQuery 是否使用子查询
     * @return $this
     */
    public function withMax($relation, $field, $subQuery = true)
    {
        return $this->withAggregate($relation, 'max', $field, $subQuery);
    }

    /**
     * 关联统计Min
     * @access public
     * @param  string|array $relation 关联方法名
     * @param  string       $field 字段
     * @param  bool         $subQuery 是否使用子查询
     * @return $this
     */
    public function withMin($relation, $field, $subQuery = true)
    {
        return $this->withAggregate($relation, 'min', $field, $subQuery);
    }

    /**
     * 关联统计Avg
     * @access public
     * @param  string|array $relation 关联方法名
     * @param  string       $field 字段
     * @param  bool         $subQuery 是否使用子查询
     * @return $this
     */
    public function withAvg($relation, $field, $subQuery = true)
    {
        return $this->withAggregate($relation, 'avg', $field, $subQuery);
=======
        if (!$subQuery) {
            $this->options['with_count'] = $relation;
        } else {
            $relations = is_string($relation) ? explode(',', $relation) : $relation;
            if (!isset($this->options['field'])) {
                $this->field('*');
            }
            foreach ($relations as $key => $relation) {
                $closure = $name = null;
                if ($relation instanceof \Closure) {
                    $closure  = $relation;
                    $relation = $key;
                } elseif (!is_int($key)) {
                    $name     = $relation;
                    $relation = $key;
                }
                $relation = Loader::parseName($relation, 1, false);

                $count = '(' . $this->model->$relation()->getRelationCountQuery($closure, $name) . ')';

                if (empty($name)) {
                    $name = Loader::parseName($relation) . '_count';
                }

                $this->field([$count => $name]);
            }
        }
        return $this;
>>>>>>> main
    }

    /**
     * 关联预加载中 获取关联指定字段值
     * example:
     * Model::with(['relation' => function($query){
     *     $query->withField("id,name");
     * }])
     *
<<<<<<< HEAD
     * @access public
     * @param  string | array $field 指定获取的字段
=======
     * @param string | array $field 指定获取的字段
>>>>>>> main
     * @return $this
     */
    public function withField($field)
    {
        $this->options['with_field'] = $field;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 设置当前字段添加的表别名
     * @access public
<<<<<<< HEAD
     * @param  string $via
=======
     * @param string $via
>>>>>>> main
     * @return $this
     */
    public function via($via = '')
    {
        $this->options['via'] = $via;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 设置关联查询
     * @access public
<<<<<<< HEAD
     * @param  string|array $relation 关联名称
=======
     * @param string|array $relation 关联名称
>>>>>>> main
     * @return $this
     */
    public function relation($relation)
    {
        if (empty($relation)) {
            return $this;
        }
<<<<<<< HEAD

        if (is_string($relation)) {
            $relation = explode(',', $relation);
        }

=======
        if (is_string($relation)) {
            $relation = explode(',', $relation);
        }
>>>>>>> main
        if (isset($this->options['relation'])) {
            $this->options['relation'] = array_merge($this->options['relation'], $relation);
        } else {
            $this->options['relation'] = $relation;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
<<<<<<< HEAD
     * 插入记录
     * @access public
     * @param  array   $data         数据
     * @param  boolean $replace      是否replace
     * @param  boolean $getLastInsID 返回自增主键
     * @param  string  $sequence     自增序列名
=======
     * 把主键值转换为查询条件 支持复合主键
     * @access public
     * @param array|string $data    主键数据
     * @param mixed        $options 表达式参数
     * @return void
     * @throws Exception
     */
    protected function parsePkWhere($data, &$options)
    {
        $pk = $this->getPk($options);
        // 获取当前数据表
        $table = is_array($options['table']) ? key($options['table']) : $options['table'];
        if (!empty($options['alias'][$table])) {
            $alias = $options['alias'][$table];
        }
        if (is_string($pk)) {
            $key = isset($alias) ? $alias . '.' . $pk : $pk;
            // 根据主键查询
            if (is_array($data)) {
                $where[$key] = isset($data[$pk]) ? $data[$pk] : ['in', $data];
            } else {
                $where[$key] = strpos($data, ',') ? ['IN', $data] : $data;
            }
        } elseif (is_array($pk) && is_array($data) && !empty($data)) {
            // 根据复合主键查询
            foreach ($pk as $key) {
                if (isset($data[$key])) {
                    $attr         = isset($alias) ? $alias . '.' . $key : $key;
                    $where[$attr] = $data[$key];
                } else {
                    throw new Exception('miss complex primary data');
                }
            }
        }

        if (!empty($where)) {
            if (isset($options['where']['AND'])) {
                $options['where']['AND'] = array_merge($options['where']['AND'], $where);
            } else {
                $options['where']['AND'] = $where;
            }
        }
        return;
    }

    /**
     * 插入记录
     * @access public
     * @param mixed   $data         数据
     * @param boolean $replace      是否replace
     * @param boolean $getLastInsID 返回自增主键
     * @param string  $sequence     自增序列名
>>>>>>> main
     * @return integer|string
     */
    public function insert(array $data = [], $replace = false, $getLastInsID = false, $sequence = null)
    {
<<<<<<< HEAD
        $this->parseOptions();

        $this->options['data'] = array_merge($this->options['data'], $data);

        return $this->connection->insert($this, $replace, $getLastInsID, $sequence);
=======
        // 分析查询表达式
        $options = $this->parseExpress();
        $data    = array_merge($options['data'], $data);
        // 生成SQL语句
        $sql = $this->builder->insert($data, $options, $replace);
        // 获取参数绑定
        $bind = $this->getBind();
        if ($options['fetch_sql']) {
            // 获取实际执行的SQL语句
            return $this->connection->getRealSql($sql, $bind);
        }

        // 执行操作
        $result = 0 === $sql ? 0 : $this->execute($sql, $bind, $this);
        if ($result) {
            $sequence  = $sequence ?: (isset($options['sequence']) ? $options['sequence'] : null);
            $lastInsId = $this->getLastInsID($sequence);
            if ($lastInsId) {
                $pk = $this->getPk($options);
                if (is_string($pk)) {
                    $data[$pk] = $lastInsId;
                }
            }
            $options['data'] = $data;
            $this->trigger('after_insert', $options);

            if ($getLastInsID) {
                return $lastInsId;
            }
        }
        return $result;
>>>>>>> main
    }

    /**
     * 插入记录并获取自增ID
     * @access public
<<<<<<< HEAD
     * @param  array   $data     数据
     * @param  boolean $replace  是否replace
     * @param  string  $sequence 自增序列名
=======
     * @param mixed   $data     数据
     * @param boolean $replace  是否replace
     * @param string  $sequence 自增序列名
>>>>>>> main
     * @return integer|string
     */
    public function insertGetId(array $data, $replace = false, $sequence = null)
    {
        return $this->insert($data, $replace, true, $sequence);
    }

    /**
     * 批量插入记录
     * @access public
<<<<<<< HEAD
     * @param  array     $dataSet 数据集
     * @param  boolean   $replace 是否replace
     * @param  integer   $limit   每次写入数据限制
     * @return integer|string
     */
    public function insertAll(array $dataSet = [], $replace = false, $limit = null)
    {
        $this->parseOptions();

        if (empty($dataSet)) {
            $dataSet = $this->options['data'];
        }

        if (empty($limit) && !empty($this->options['limit'])) {
            $limit = $this->options['limit'];
        }

        return $this->connection->insertAll($this, $dataSet, $replace, $limit);
=======
     * @param mixed     $dataSet 数据集
     * @param boolean   $replace  是否replace
     * @param integer   $limit   每次写入数据限制
     * @return integer|string
     */
    public function insertAll(array $dataSet, $replace = false, $limit = null)
    {
        // 分析查询表达式
        $options = $this->parseExpress();
        if (!is_array(reset($dataSet))) {
            return false;
        }

        // 生成SQL语句
        if (is_null($limit)) {
            $sql = $this->builder->insertAll($dataSet, $options, $replace);
        } else {
            $array = array_chunk($dataSet, $limit, true);
            foreach ($array as $item) {
                $sql[] = $this->builder->insertAll($item, $options, $replace);
            }
        }

        // 获取参数绑定
        $bind = $this->getBind();
        if ($options['fetch_sql']) {
            // 获取实际执行的SQL语句
            return $this->connection->getRealSql($sql, $bind);
        } elseif (is_array($sql)) {
            // 执行操作
            return $this->batchQuery($sql, $bind, $this);
        } else {
            // 执行操作
            return $this->execute($sql, $bind, $this);
        }
>>>>>>> main
    }

    /**
     * 通过Select方式插入记录
     * @access public
<<<<<<< HEAD
     * @param  string $fields 要插入的数据表字段名
     * @param  string $table  要插入的数据表名
=======
     * @param string $fields 要插入的数据表字段名
     * @param string $table  要插入的数据表名
>>>>>>> main
     * @return integer|string
     * @throws PDOException
     */
    public function selectInsert($fields, $table)
    {
<<<<<<< HEAD
        $this->parseOptions();

        return $this->connection->selectInsert($this, $fields, $table);
=======
        // 分析查询表达式
        $options = $this->parseExpress();
        // 生成SQL语句
        $table = $this->parseSqlTable($table);
        $sql   = $this->builder->selectInsert($fields, $table, $options);
        // 获取参数绑定
        $bind = $this->getBind();
        if ($options['fetch_sql']) {
            // 获取实际执行的SQL语句
            return $this->connection->getRealSql($sql, $bind);
        } else {
            // 执行操作
            return $this->execute($sql, $bind, $this);
        }
>>>>>>> main
    }

    /**
     * 更新记录
     * @access public
<<<<<<< HEAD
     * @param  mixed $data 数据
=======
     * @param mixed $data 数据
>>>>>>> main
     * @return integer|string
     * @throws Exception
     * @throws PDOException
     */
    public function update(array $data = [])
    {
<<<<<<< HEAD
        $this->parseOptions();

        $this->options['data'] = array_merge($this->options['data'], $data);

        return $this->connection->update($this);
    }

    /**
     * 删除记录
     * @access public
     * @param  mixed $data 表达式 true 表示强制删除
     * @return int
     * @throws Exception
     * @throws PDOException
     */
    public function delete($data = null)
    {
        $this->parseOptions();

        if (!is_null($data) && true !== $data) {
            // AR模式分析主键条件
            $this->parsePkWhere($data);
        }

        if (!empty($this->options['soft_delete'])) {
            // 软删除
            list($field, $condition) = $this->options['soft_delete'];
            if ($condition) {
                unset($this->options['soft_delete']);
                $this->options['data'] = [$field => $condition];

                return $this->connection->update($this);
            }
        }

        $this->options['data'] = $data;

        return $this->connection->delete($this);
=======
        $options = $this->parseExpress();
        $data    = array_merge($options['data'], $data);
        $pk      = $this->getPk($options);
        if (isset($options['cache']) && is_string($options['cache']['key'])) {
            $key = $options['cache']['key'];
        }

        if (empty($options['where'])) {
            // 如果存在主键数据 则自动作为更新条件
            if (is_string($pk) && isset($data[$pk])) {
                $where[$pk] = $data[$pk];
                if (!isset($key)) {
                    $key = 'think:' . $options['table'] . '|' . $data[$pk];
                }
                unset($data[$pk]);
            } elseif (is_array($pk)) {
                // 增加复合主键支持
                foreach ($pk as $field) {
                    if (isset($data[$field])) {
                        $where[$field] = $data[$field];
                    } else {
                        // 如果缺少复合主键数据则不执行
                        throw new Exception('miss complex primary data');
                    }
                    unset($data[$field]);
                }
            }
            if (!isset($where)) {
                // 如果没有任何更新条件则不执行
                throw new Exception('miss update condition');
            } else {
                $options['where']['AND'] = $where;
            }
        } elseif (!isset($key) && is_string($pk) && isset($options['where']['AND'][$pk])) {
            $key = $this->getCacheKey($options['where']['AND'][$pk], $options, $this->bind);
        }

        // 生成UPDATE SQL语句
        $sql = $this->builder->update($data, $options);
        // 获取参数绑定
        $bind = $this->getBind();
        if ($options['fetch_sql']) {
            // 获取实际执行的SQL语句
            return $this->connection->getRealSql($sql, $bind);
        } else {
            // 检测缓存
            if (isset($key) && Cache::get($key)) {
                // 删除缓存
                Cache::rm($key);
            } elseif (!empty($options['cache']['tag'])) {
                Cache::clear($options['cache']['tag']);
            }
            // 执行操作
            $result = '' == $sql ? 0 : $this->execute($sql, $bind, $this);
            if ($result) {
                if (is_string($pk) && isset($where[$pk])) {
                    $data[$pk] = $where[$pk];
                } elseif (is_string($pk) && isset($key) && strpos($key, '|')) {
                    list($a, $val) = explode('|', $key);
                    $data[$pk]     = $val;
                }
                $options['data'] = $data;
                $this->trigger('after_update', $options);
            }
            return $result;
        }
>>>>>>> main
    }

    /**
     * 执行查询但只返回PDOStatement对象
     * @access public
     * @return \PDOStatement|string
     */
    public function getPdo()
    {
<<<<<<< HEAD
        $this->parseOptions();

        return $this->connection->pdo($this);
    }

    /**
     * 使用游标查找记录
     * @access public
     * @param  array|string|Query|\Closure $data
     * @return \Generator
     */
    public function cursor($data = null)
    {
        if ($data instanceof \Closure) {
            $data($this);
            $data = null;
        }

        $this->parseOptions();

        if (!is_null($data)) {
            // 主键条件分析
            $this->parsePkWhere($data);
        }

        $this->options['data'] = $data;

        $connection = clone $this->connection;

        return $connection->cursor($this);
=======
        // 分析查询表达式
        $options = $this->parseExpress();
        // 生成查询SQL
        $sql = $this->builder->select($options);
        // 获取参数绑定
        $bind = $this->getBind();
        if ($options['fetch_sql']) {
            // 获取实际执行的SQL语句
            return $this->connection->getRealSql($sql, $bind);
        }
        // 执行查询操作
        return $this->query($sql, $bind, $options['master'], true);
>>>>>>> main
    }

    /**
     * 查找记录
     * @access public
<<<<<<< HEAD
     * @param  array|string|Query|\Closure $data
     * @return Collection|array|\PDOStatement|string
=======
     * @param array|string|Query|\Closure $data
     * @return Collection|false|\PDOStatement|string
>>>>>>> main
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function select($data = null)
    {
        if ($data instanceof Query) {
            return $data->select();
        } elseif ($data instanceof \Closure) {
<<<<<<< HEAD
            $data($this);
            $data = null;
        }

        $this->parseOptions();

        if (false === $data) {
            // 用于子查询 不查询只返回SQL
            $this->options['fetch_sql'] = true;
        } elseif (!is_null($data)) {
            // 主键条件分析
            $this->parsePkWhere($data);
        }

        $this->options['data'] = $data;

        $resultSet = $this->connection->select($this);

        if ($this->options['fetch_sql']) {
            return $resultSet;
        }

        // 返回结果处理
        if (!empty($this->options['fail']) && count($resultSet) == 0) {
            $this->throwNotFound($this->options);
=======
            call_user_func_array($data, [ & $this]);
            $data = null;
        }
        // 分析查询表达式
        $options = $this->parseExpress();

        if (false === $data) {
            // 用于子查询 不查询只返回SQL
            $options['fetch_sql'] = true;
        } elseif (!is_null($data)) {
            // 主键条件分析
            $this->parsePkWhere($data, $options);
        }

        $resultSet = false;
        if (empty($options['fetch_sql']) && !empty($options['cache'])) {
            // 判断查询缓存
            $cache = $options['cache'];
            unset($options['cache']);
            $key       = is_string($cache['key']) ? $cache['key'] : md5($this->connection->getConfig('database') . '.' . serialize($options) . serialize($this->bind));
            $resultSet = Cache::get($key);
        }
        if (false === $resultSet) {
            // 生成查询SQL
            $sql = $this->builder->select($options);
            // 获取参数绑定
            $bind = $this->getBind();
            if ($options['fetch_sql']) {
                // 获取实际执行的SQL语句
                return $this->connection->getRealSql($sql, $bind);
            }

            $options['data'] = $data;
            if ($resultSet = $this->trigger('before_select', $options)) {
            } else {
                // 执行查询操作
                $resultSet = $this->query($sql, $bind, $options['master'], $options['fetch_pdo']);

                if ($resultSet instanceof \PDOStatement) {
                    // 返回PDOStatement对象
                    return $resultSet;
                }
            }

            if (isset($cache) && false !== $resultSet) {
                // 缓存数据集
                $this->cacheData($key, $resultSet, $cache);
            }
>>>>>>> main
        }

        // 数据列表读取后的处理
        if (!empty($this->model)) {
            // 生成模型对象
<<<<<<< HEAD
            $resultSet = $this->resultSetToModelCollection($resultSet);
        } else {
            $this->resultSet($resultSet);
        }

=======
            if (count($resultSet) > 0) {
                foreach ($resultSet as $key => $result) {
                    /** @var Model $model */
                    $model = $this->model->newInstance($result);
                    $model->isUpdate(true);

                    // 关联查询
                    if (!empty($options['relation'])) {
                        $model->relationQuery($options['relation']);
                    }
                    // 关联统计
                    if (!empty($options['with_count'])) {
                        $model->relationCount($model, $options['with_count']);
                    }
                    $resultSet[$key] = $model;
                }
                if (!empty($options['with'])) {
                    // 预载入
                    $model->eagerlyResultSet($resultSet, $options['with']);
                }
                // 模型数据集转换
                $resultSet = $model->toCollection($resultSet);
            } else {
                $resultSet = $this->model->toCollection($resultSet);
            }
        } elseif ('collection' == $this->connection->getConfig('resultset_type')) {
            // 返回Collection对象
            $resultSet = new Collection($resultSet);
        }
        // 返回结果处理
        if (!empty($options['fail']) && count($resultSet) == 0) {
            $this->throwNotFound($options);
        }
>>>>>>> main
        return $resultSet;
    }

    /**
<<<<<<< HEAD
     * 查询数据转换为模型数据集对象
     * @access protected
     * @param  array  $resultSet         数据集
     * @return ModelCollection
     */
    protected function resultSetToModelCollection(array $resultSet)
    {
        if (!empty($this->options['collection']) && is_string($this->options['collection'])) {
            $collection = $this->options['collection'];
        }

        if (empty($resultSet)) {
            return $this->model->toCollection([], isset($collection) ? $collection : null);
        }

        // 检查动态获取器
        if (!empty($this->options['with_attr'])) {
            foreach ($this->options['with_attr'] as $name => $val) {
                if (strpos($name, '.')) {
                    list($relation, $field) = explode('.', $name);

                    $withRelationAttr[$relation][$field] = $val;
                    unset($this->options['with_attr'][$name]);
                }
            }
        }

        $withRelationAttr = isset($withRelationAttr) ? $withRelationAttr : [];

        foreach ($resultSet as $key => &$result) {
            // 数据转换为模型对象
            $this->resultToModel($result, $this->options, true, $withRelationAttr);
        }

        if (!empty($this->options['with'])) {
            // 预载入
            $result->eagerlyResultSet($resultSet, $this->options['with'], $withRelationAttr);
        }

        if (!empty($this->options['with_join'])) {
            // JOIN预载入
            $result->eagerlyResultSet($resultSet, $this->options['with_join'], $withRelationAttr, true);
        }

        // 模型数据集转换
        return $result->toCollection($resultSet, isset($collection) ? $collection : null);
    }

    /**
     * 处理数据集
     * @access public
     * @param  array $resultSet
     * @return void
     */
    protected function resultSet(&$resultSet)
    {
        if (!empty($this->options['json'])) {
            foreach ($resultSet as &$result) {
                $this->jsonResult($result, $this->options['json'], true);
            }
        }

        if (!empty($this->options['with_attr'])) {
            foreach ($resultSet as &$result) {
                $this->getResultAttr($result, $this->options['with_attr']);
            }
        }

        if (!empty($this->options['collection']) || 'collection' == $this->connection->getConfig('resultset_type')) {
            // 返回Collection对象
            $resultSet = new Collection($resultSet);
=======
     * 缓存数据
     * @access public
     * @param string    $key    缓存标识
     * @param mixed     $data   缓存数据
     * @param array     $config 缓存参数
     */
    protected function cacheData($key, $data, $config = [])
    {
        if (isset($config['tag'])) {
            Cache::tag($config['tag'])->set($key, $data, $config['expire']);
        } else {
            Cache::set($key, $data, $config['expire']);
        }
    }

    /**
     * 生成缓存标识
     * @access public
     * @param mixed     $value   缓存数据
     * @param array     $options 缓存参数
     * @param array     $bind    绑定参数
     * @return string
     */
    protected function getCacheKey($value, $options, $bind = [])
    {
        if (is_scalar($value)) {
            $data = $value;
        } elseif (is_array($value) && is_string($value[0]) && 'eq' == strtolower($value[0])) {
            $data = $value[1];
        }
        $prefix = $this->connection->getConfig('database') . '.';

        if (isset($data)) {
            return 'think:' . $prefix . (is_array($options['table']) ? key($options['table']) : $options['table']) . '|' . $data;
        }

        try {
            return md5($prefix . serialize($options) . serialize($bind));
        } catch (\Exception $e) {
            throw new Exception('closure not support cache(true)');
>>>>>>> main
        }
    }

    /**
     * 查找单条记录
     * @access public
<<<<<<< HEAD
     * @param  array|string|Query|\Closure $data
     * @return array|null|\PDOStatement|string|Model
=======
     * @param array|string|Query|\Closure $data
     * @return array|false|\PDOStatement|string|Model
>>>>>>> main
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function find($data = null)
    {
        if ($data instanceof Query) {
            return $data->find();
        } elseif ($data instanceof \Closure) {
<<<<<<< HEAD
            $data($this);
            $data = null;
        }

        $this->parseOptions();

        if (!is_null($data)) {
            // AR模式分析主键条件
            $this->parsePkWhere($data);
        }

        $this->options['data'] = $data;

        $result = $this->connection->find($this);

        if ($this->options['fetch_sql']) {
            return $result;
        }

        // 数据处理
        if (empty($result)) {
            return $this->resultToEmpty();
        }

        if (!empty($this->model)) {
            // 返回模型对象
            $this->resultToModel($result, $this->options);
        } else {
            $this->result($result);
        }

=======
            call_user_func_array($data, [ & $this]);
            $data = null;
        }
        // 分析查询表达式
        $options = $this->parseExpress();
        $pk      = $this->getPk($options);
        if (!is_null($data)) {
            // AR模式分析主键条件
            $this->parsePkWhere($data, $options);
        } elseif (!empty($options['cache']) && true === $options['cache']['key'] && is_string($pk) && isset($options['where']['AND'][$pk])) {
            $key = $this->getCacheKey($options['where']['AND'][$pk], $options, $this->bind);
        }

        $options['limit'] = 1;
        $result           = false;
        if (empty($options['fetch_sql']) && !empty($options['cache'])) {
            // 判断查询缓存
            $cache = $options['cache'];
            if (true === $cache['key'] && !is_null($data) && !is_array($data)) {
                $key = 'think:' . $this->connection->getConfig('database') . '.' . (is_array($options['table']) ? key($options['table']) : $options['table']) . '|' . $data;
            } elseif (is_string($cache['key'])) {
                $key = $cache['key'];
            } elseif (!isset($key)) {
                $key = md5($this->connection->getConfig('database') . '.' . serialize($options) . serialize($this->bind));
            }
            $result = Cache::get($key);
        }
        if (false === $result) {
            // 生成查询SQL
            $sql = $this->builder->select($options);
            // 获取参数绑定
            $bind = $this->getBind();
            if ($options['fetch_sql']) {
                // 获取实际执行的SQL语句
                return $this->connection->getRealSql($sql, $bind);
            }
            if (is_string($pk)) {
                if (!is_array($data)) {
                    if (isset($key) && strpos($key, '|')) {
                        list($a, $val) = explode('|', $key);
                        $item[$pk]     = $val;
                    } else {
                        $item[$pk] = $data;
                    }
                    $data = $item;
                }
            }
            $options['data'] = $data;
            // 事件回调
            if ($result = $this->trigger('before_find', $options)) {
            } else {
                // 执行查询
                $resultSet = $this->query($sql, $bind, $options['master'], $options['fetch_pdo']);

                if ($resultSet instanceof \PDOStatement) {
                    // 返回PDOStatement对象
                    return $resultSet;
                }
                $result = isset($resultSet[0]) ? $resultSet[0] : null;
            }

            if (isset($cache) && $result) {
                // 缓存数据
                $this->cacheData($key, $result, $cache);
            }
        }

        // 数据处理
        if (!empty($result)) {
            if (!empty($this->model)) {
                // 返回模型对象
                $result = $this->model->newInstance($result);
                $result->isUpdate(true, isset($options['where']['AND']) ? $options['where']['AND'] : null);
                // 关联查询
                if (!empty($options['relation'])) {
                    $result->relationQuery($options['relation']);
                }
                // 预载入查询
                if (!empty($options['with'])) {
                    $result->eagerlyResult($result, $options['with']);
                }
                // 关联统计
                if (!empty($options['with_count'])) {
                    $result->relationCount($result, $options['with_count']);
                }
            }
        } elseif (!empty($options['fail'])) {
            $this->throwNotFound($options);
        }
>>>>>>> main
        return $result;
    }

    /**
<<<<<<< HEAD
     * 处理空数据
     * @access protected
     * @return array|Model|null
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    protected function resultToEmpty()
    {
        if (!empty($this->options['allow_empty'])) {
            return !empty($this->model) ? $this->model->newInstance([], $this->getModelUpdateCondition($this->options)) : [];
        } elseif (!empty($this->options['fail'])) {
            $this->throwNotFound($this->options);
        }
    }

    /**
     * 查找单条记录
     * @access public
     * @param  mixed     $data  主键值或者查询条件（闭包）
     * @param  mixed     $with  关联预查询
     * @param  bool      $cache 是否缓存
     * @param  bool      $failException 是否抛出异常
     * @return static|null
     * @throws exception\DbException
     */
    public function get($data, $with = [], $cache = false, $failException = false)
    {
        if (is_null($data)) {
            return;
        }

        if (true === $with || is_int($with)) {
            $cache = $with;
            $with  = [];
        }

        return $this->parseQuery($data, $with, $cache)
            ->failException($failException)
            ->find($data);
    }

    /**
     * 查找单条记录 如果不存在直接抛出异常
     * @access public
     * @param  mixed     $data  主键值或者查询条件（闭包）
     * @param  mixed     $with  关联预查询
     * @param  bool      $cache 是否缓存
     * @return static|null
     * @throws exception\DbException
     */
    public function getOrFail($data, $with = [], $cache = false)
    {
        return $this->get($data, $with, $cache, true);
    }

    /**
     * 查找所有记录
     * @access public
     * @param  mixed        $data  主键列表或者查询条件（闭包）
     * @param  array|string $with  关联预查询
     * @param  bool         $cache 是否缓存
     * @return static[]|false
     * @throws exception\DbException
     */
    public function all($data = null, $with = [], $cache = false)
    {
        if (true === $with || is_int($with)) {
            $cache = $with;
            $with  = [];
        }

        return $this->parseQuery($data, $with, $cache)->select($data);
    }

    /**
     * 分析查询表达式
     * @access public
     * @param  mixed  $data  主键列表或者查询条件（闭包）
     * @param  string $with  关联预查询
     * @param  bool   $cache 是否缓存
     * @return Query
     */
    protected function parseQuery(&$data, $with, $cache)
    {
        $result = $this->with($with)->cache($cache);

        if ((is_array($data) && key($data) !== 0) || $data instanceof Where) {
            $result = $result->where($data);
            $data   = null;
        } elseif ($data instanceof \Closure) {
            $data($result);
            $data = null;
        } elseif ($data instanceof Query) {
            $result = $data->with($with)->cache($cache);
            $data   = null;
        }

        return $result;
    }

    /**
     * 处理数据
     * @access protected
     * @param  array $result     查询数据
     * @return void
     */
    protected function result(&$result)
    {
        if (!empty($this->options['json'])) {
            $this->jsonResult($result, $this->options['json'], true);
        }

        if (!empty($this->options['with_attr'])) {
            $this->getResultAttr($result, $this->options['with_attr']);
        }
    }

    /**
     * 使用获取器处理数据
     * @access protected
     * @param  array $result     查询数据
     * @param  array $withAttr   字段获取器
     * @return void
     */
    protected function getResultAttr(&$result, $withAttr = [])
    {
        foreach ($withAttr as $name => $closure) {
            $name = Loader::parseName($name);

            if (strpos($name, '.')) {
                // 支持JSON字段 获取器定义
                list($key, $field) = explode('.', $name);

                if (isset($result[$key])) {
                    $result[$key][$field] = $closure(isset($result[$key][$field]) ? $result[$key][$field] : null, $result[$key]);
                }
            } else {
                $result[$name] = $closure(isset($result[$name]) ? $result[$name] : null, $result);
            }
        }
    }

    /**
     * JSON字段数据转换
     * @access protected
     * @param  array $result            查询数据
     * @param  array $json              JSON字段
     * @param  bool  $assoc             是否转换为数组
     * @param  array $withRelationAttr  关联获取器
     * @return void
     */
    protected function jsonResult(&$result, $json = [], $assoc = false, $withRelationAttr = [])
    {
        foreach ($json as $name) {
            if (isset($result[$name])) {
                $result[$name] = json_decode($result[$name], $assoc);

                if (isset($withRelationAttr[$name])) {
                    foreach ($withRelationAttr[$name] as $key => $closure) {
                        $data                = get_object_vars($result[$name]);
                        $result[$name]->$key = $closure(isset($result[$name]->$key) ? $result[$name]->$key : null, $data);
                    }
                }
            }
        }
    }

    /**
     * 查询数据转换为模型对象
     * @access protected
     * @param  array $result            查询数据
     * @param  array $options           查询参数
     * @param  bool  $resultSet         是否为数据集查询
     * @param  array $withRelationAttr  关联字段获取器
     * @return void
     */
    protected function resultToModel(&$result, $options = [], $resultSet = false, $withRelationAttr = [])
    {
        // 动态获取器
        if (!empty($options['with_attr']) && empty($withRelationAttr)) {
            foreach ($options['with_attr'] as $name => $val) {
                if (strpos($name, '.')) {
                    list($relation, $field) = explode('.', $name);

                    $withRelationAttr[$relation][$field] = $val;
                    unset($options['with_attr'][$name]);
                }
            }
        }

        // JSON 数据处理
        if (!empty($options['json'])) {
            $this->jsonResult($result, $options['json'], $options['json_assoc'], $withRelationAttr);
        }

        $result = $this->model->newInstance($result, $resultSet ? null : $this->getModelUpdateCondition($options));

        // 动态获取器
        if (!empty($options['with_attr'])) {
            $result->withAttribute($options['with_attr']);
        }

        // 输出属性控制
        if (!empty($options['visible'])) {
            $result->visible($options['visible'], true);
        } elseif (!empty($options['hidden'])) {
            $result->hidden($options['hidden'], true);
        }

        if (!empty($options['append'])) {
            $result->append($options['append'], true);
        }

        // 关联查询
        if (!empty($options['relation'])) {
            $result->relationQuery($options['relation'], $withRelationAttr);
        }

        // 预载入查询
        if (!$resultSet && !empty($options['with'])) {
            $result->eagerlyResult($result, $options['with'], $withRelationAttr);
        }

        // JOIN预载入查询
        if (!$resultSet && !empty($options['with_join'])) {
            $result->eagerlyResult($result, $options['with_join'], $withRelationAttr, true);
        }

        // 关联统计
        if (!empty($options['with_count'])) {
            foreach ($options['with_count'] as $val) {
                $result->relationCount($result, $val[0], $val[1], $val[2]);
            }
        }
    }

    /**
     * 获取模型的更新条件
     * @access protected
     * @param  array $options 查询参数
     */
    protected function getModelUpdateCondition(array $options)
    {
        return isset($options['where']['AND']) ? $options['where']['AND'] : null;
    }

    /**
     * 查询失败 抛出异常
     * @access protected
     * @param  array $options 查询参数
=======
     * 查询失败 抛出异常
     * @access public
     * @param array $options 查询参数
>>>>>>> main
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    protected function throwNotFound($options = [])
    {
        if (!empty($this->model)) {
            $class = get_class($this->model);
            throw new ModelNotFoundException('model data Not Found:' . $class, $class, $options);
<<<<<<< HEAD
        }
        $table = is_array($options['table']) ? key($options['table']) : $options['table'];
        throw new DataNotFoundException('table data not Found:' . $table, $table, $options);
=======
        } else {
            $table = is_array($options['table']) ? key($options['table']) : $options['table'];
            throw new DataNotFoundException('table data not Found:' . $table, $table, $options);
        }
>>>>>>> main
    }

    /**
     * 查找多条记录 如果不存在则抛出异常
     * @access public
<<<<<<< HEAD
     * @param  array|string|Query|\Closure $data
=======
     * @param array|string|Query|\Closure $data
>>>>>>> main
     * @return array|\PDOStatement|string|Model
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function selectOrFail($data = null)
    {
        return $this->failException(true)->select($data);
    }

    /**
     * 查找单条记录 如果不存在则抛出异常
     * @access public
<<<<<<< HEAD
     * @param  array|string|Query|\Closure $data
=======
     * @param array|string|Query|\Closure $data
>>>>>>> main
     * @return array|\PDOStatement|string|Model
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function findOrFail($data = null)
    {
        return $this->failException(true)->find($data);
    }

    /**
<<<<<<< HEAD
     * 查找单条记录 不存在则返回空模型
     * @access public
     * @param  array|string|Query|\Closure $data
     * @return array|\PDOStatement|string|Model
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     */
    public function findOrEmpty($data = null)
    {
        return $this->allowEmpty(true)->find($data);
    }

    /**
     * 分批数据返回处理
     * @access public
     * @param  integer      $count    每次处理的数据数量
     * @param  callable     $callback 处理回调方法
     * @param  string|array $column   分批处理的字段名
     * @param  string       $order    字段排序
     * @return boolean
     * @throws DbException
=======
     * 分批数据返回处理
     * @access public
     * @param integer  $count    每次处理的数据数量
     * @param callable $callback 处理回调方法
     * @param string   $column   分批处理的字段名
     * @param string   $order    排序规则
     * @return boolean
     * @throws \LogicException
>>>>>>> main
     */
    public function chunk($count, $callback, $column = null, $order = 'asc')
    {
        $options = $this->getOptions();
<<<<<<< HEAD
        $column  = $column ?: $this->getPk($options);

        if (isset($options['order'])) {
            if (Container::get('app')->isDebug()) {
                throw new DbException('chunk not support call order');
            }
            unset($options['order']);
        }

        $bind = $this->bind;

=======
        if (empty($options['table'])) {
            $options['table'] = $this->getTable();
        }
        $column = $column ?: $this->getPk($options);

        if (isset($options['order'])) {
            if (App::$debug) {
                throw new \LogicException('chunk not support call order');
            }
            unset($options['order']);
        }
        $bind = $this->bind;
>>>>>>> main
        if (is_array($column)) {
            $times = 1;
            $query = $this->options($options)->page($times, $count);
        } else {
<<<<<<< HEAD
            $query = $this->options($options)->limit($count);

=======
>>>>>>> main
            if (strpos($column, '.')) {
                list($alias, $key) = explode('.', $column);
            } else {
                $key = $column;
            }
<<<<<<< HEAD
        }

=======
            $query = $this->options($options)->limit($count);
        }
>>>>>>> main
        $resultSet = $query->order($column, $order)->select();

        while (count($resultSet) > 0) {
            if ($resultSet instanceof Collection) {
                $resultSet = $resultSet->all();
            }

            if (false === call_user_func($callback, $resultSet)) {
                return false;
            }

<<<<<<< HEAD
            if (isset($times)) {
=======
            if (is_array($column)) {
>>>>>>> main
                $times++;
                $query = $this->options($options)->page($times, $count);
            } else {
                $end    = end($resultSet);
                $lastId = is_array($end) ? $end[$key] : $end->getData($key);
<<<<<<< HEAD

                $query = $this->options($options)
=======
                $query  = $this->options($options)
>>>>>>> main
                    ->limit($count)
                    ->where($column, 'asc' == strtolower($order) ? '>' : '<', $lastId);
            }

            $resultSet = $query->bind($bind)->order($column, $order)->select();
        }

        return true;
    }

    /**
     * 获取绑定的参数 并清空
     * @access public
<<<<<<< HEAD
     * @param  bool $clear
     * @return array
     */
    public function getBind($clear = true)
    {
        $bind = $this->bind;
        if ($clear) {
            $this->bind = [];
        }

=======
     * @return array
     */
    public function getBind()
    {
        $bind       = $this->bind;
        $this->bind = [];
>>>>>>> main
        return $bind;
    }

    /**
     * 创建子查询SQL
     * @access public
<<<<<<< HEAD
     * @param  bool $sub
=======
     * @param bool $sub
>>>>>>> main
     * @return string
     * @throws DbException
     */
    public function buildSql($sub = true)
    {
        return $sub ? '( ' . $this->select(false) . ' )' : $this->select(false);
    }

    /**
<<<<<<< HEAD
     * 视图查询处理
     * @access protected
     * @param  array   $options    查询参数
     * @return void
     */
    protected function parseView(&$options)
    {
        if (!isset($options['map'])) {
            return;
        }

        foreach (['AND', 'OR'] as $logic) {
            if (isset($options['where'][$logic])) {
                foreach ($options['where'][$logic] as $key => $val) {
                    if (array_key_exists($key, $options['map'])) {
                        array_shift($val);
                        array_unshift($val, $options['map'][$key]);
                        $options['where'][$logic][$options['map'][$key]] = $val;
                        unset($options['where'][$logic][$key]);
                    }
                }
            }
        }

        if (isset($options['order'])) {
            // 视图查询排序处理
            if (is_string($options['order'])) {
                $options['order'] = explode(',', $options['order']);
            }
            foreach ($options['order'] as $key => $val) {
                if (is_numeric($key) && is_string($val)) {
                    if (strpos($val, ' ')) {
                        list($field, $sort) = explode(' ', $val);
                        if (array_key_exists($field, $options['map'])) {
                            $options['order'][$options['map'][$field]] = $sort;
                            unset($options['order'][$key]);
                        }
                    } elseif (array_key_exists($val, $options['map'])) {
                        $options['order'][$options['map'][$val]] = 'asc';
                        unset($options['order'][$key]);
                    }
                } elseif (array_key_exists($key, $options['map'])) {
                    $options['order'][$options['map'][$key]] = $val;
                    unset($options['order'][$key]);
                }
            }
        }
    }

    /**
     * 把主键值转换为查询条件 支持复合主键
     * @access public
     * @param  array|string $data    主键数据
     * @return void
     * @throws Exception
     */
    public function parsePkWhere($data)
    {
        $pk = $this->getPk($this->options);
        // 获取当前数据表
        $table = is_array($this->options['table']) ? key($this->options['table']) : $this->options['table'];

        if (!empty($this->options['alias'][$table])) {
            $alias = $this->options['alias'][$table];
        }

        if (is_string($pk)) {
            $key = isset($alias) ? $alias . '.' . $pk : $pk;
            // 根据主键查询
            if (is_array($data)) {
                $where[$pk] = isset($data[$pk]) ? [$key, '=', $data[$pk]] : [$key, 'in', $data];
            } else {
                $where[$pk] = strpos($data, ',') ? [$key, 'IN', $data] : [$key, '=', $data];
            }
        } elseif (is_array($pk) && is_array($data) && !empty($data)) {
            // 根据复合主键查询
            foreach ($pk as $key) {
                if (isset($data[$key])) {
                    $attr        = isset($alias) ? $alias . '.' . $key : $key;
                    $where[$key] = [$attr, '=', $data[$key]];
                } else {
                    throw new Exception('miss complex primary data');
                }
            }
        }

        if (!empty($where)) {
            if (isset($this->options['where']['AND'])) {
                $this->options['where']['AND'] = array_merge($this->options['where']['AND'], $where);
            } else {
                $this->options['where']['AND'] = $where;
            }
        }

        return;
=======
     * 删除记录
     * @access public
     * @param mixed $data 表达式 true 表示强制删除
     * @return int
     * @throws Exception
     * @throws PDOException
     */
    public function delete($data = null)
    {
        // 分析查询表达式
        $options = $this->parseExpress();
        $pk      = $this->getPk($options);
        if (isset($options['cache']) && is_string($options['cache']['key'])) {
            $key = $options['cache']['key'];
        }

        if (!is_null($data) && true !== $data) {
            if (!isset($key) && !is_array($data)) {
                // 缓存标识
                $key = 'think:' . $options['table'] . '|' . $data;
            }
            // AR模式分析主键条件
            $this->parsePkWhere($data, $options);
        } elseif (!isset($key) && is_string($pk) && isset($options['where']['AND'][$pk])) {
            $key = $this->getCacheKey($options['where']['AND'][$pk], $options, $this->bind);
        }

        if (true !== $data && empty($options['where'])) {
            // 如果条件为空 不进行删除操作 除非设置 1=1
            throw new Exception('delete without condition');
        }
        // 生成删除SQL语句
        $sql = $this->builder->delete($options);
        // 获取参数绑定
        $bind = $this->getBind();
        if ($options['fetch_sql']) {
            // 获取实际执行的SQL语句
            return $this->connection->getRealSql($sql, $bind);
        }

        // 检测缓存
        if (isset($key) && Cache::get($key)) {
            // 删除缓存
            Cache::rm($key);
        } elseif (!empty($options['cache']['tag'])) {
            Cache::clear($options['cache']['tag']);
        }
        // 执行操作
        $result = $this->execute($sql, $bind, $this);
        if ($result) {
            if (!is_array($data) && is_string($pk) && isset($key) && strpos($key, '|')) {
                list($a, $val) = explode('|', $key);
                $item[$pk]     = $val;
                $data          = $item;
            }
            $options['data'] = $data;
            $this->trigger('after_delete', $options);
        }
        return $result;
>>>>>>> main
    }

    /**
     * 分析表达式（可用于查询或者写入操作）
     * @access protected
     * @return array
     */
<<<<<<< HEAD
    protected function parseOptions()
    {
        $options = $this->getOptions();
=======
    protected function parseExpress()
    {
        $options = $this->options;
>>>>>>> main

        // 获取数据表
        if (empty($options['table'])) {
            $options['table'] = $this->getTable();
        }

        if (!isset($options['where'])) {
            $options['where'] = [];
        } elseif (isset($options['view'])) {
            // 视图查询条件处理
<<<<<<< HEAD
            $this->parseView($options);
=======
            foreach (['AND', 'OR'] as $logic) {
                if (isset($options['where'][$logic])) {
                    foreach ($options['where'][$logic] as $key => $val) {
                        if (array_key_exists($key, $options['map'])) {
                            $options['where'][$logic][$options['map'][$key]] = $val;
                            unset($options['where'][$logic][$key]);
                        }
                    }
                }
            }

            if (isset($options['order'])) {
                // 视图查询排序处理
                if (is_string($options['order'])) {
                    $options['order'] = explode(',', $options['order']);
                }
                foreach ($options['order'] as $key => $val) {
                    if (is_numeric($key)) {
                        if (strpos($val, ' ')) {
                            list($field, $sort) = explode(' ', $val);
                            if (array_key_exists($field, $options['map'])) {
                                $options['order'][$options['map'][$field]] = $sort;
                                unset($options['order'][$key]);
                            }
                        } elseif (array_key_exists($val, $options['map'])) {
                            $options['order'][$options['map'][$val]] = 'asc';
                            unset($options['order'][$key]);
                        }
                    } elseif (array_key_exists($key, $options['map'])) {
                        $options['order'][$options['map'][$key]] = $val;
                        unset($options['order'][$key]);
                    }
                }
            }
>>>>>>> main
        }

        if (!isset($options['field'])) {
            $options['field'] = '*';
        }

<<<<<<< HEAD
        foreach (['data', 'order', 'join', 'union'] as $name) {
            if (!isset($options[$name])) {
                $options[$name] = [];
            }
=======
        if (!isset($options['data'])) {
            $options['data'] = [];
>>>>>>> main
        }

        if (!isset($options['strict'])) {
            $options['strict'] = $this->getConfig('fields_strict');
        }

        foreach (['master', 'lock', 'fetch_pdo', 'fetch_sql', 'distinct'] as $name) {
            if (!isset($options[$name])) {
                $options[$name] = false;
            }
        }

        if (isset(static::$readMaster['*']) || (is_string($options['table']) && isset(static::$readMaster[$options['table']]))) {
            $options['master'] = true;
        }

<<<<<<< HEAD
        foreach (['group', 'having', 'limit', 'force', 'comment'] as $name) {
=======
        foreach (['join', 'union', 'group', 'having', 'limit', 'order', 'force', 'comment'] as $name) {
>>>>>>> main
            if (!isset($options[$name])) {
                $options[$name] = '';
            }
        }

        if (isset($options['page'])) {
            // 根据页数计算limit
            list($page, $listRows) = $options['page'];
            $page                  = $page > 0 ? $page : 1;
            $listRows              = $listRows > 0 ? $listRows : (is_numeric($options['limit']) ? $options['limit'] : 20);
            $offset                = $listRows * ($page - 1);
            $options['limit']      = $offset . ',' . $listRows;
        }

<<<<<<< HEAD
        $this->options = $options;

=======
        $this->options = [];
>>>>>>> main
        return $options;
    }

    /**
     * 注册回调方法
     * @access public
<<<<<<< HEAD
     * @param  string   $event    事件名
     * @param  callable $callback 回调方法
=======
     * @param string   $event    事件名
     * @param callable $callback 回调方法
>>>>>>> main
     * @return void
     */
    public static function event($event, $callback)
    {
        self::$event[$event] = $callback;
    }

    /**
     * 触发事件
<<<<<<< HEAD
     * @access public
     * @param  string $event   事件名
     * @return bool
     */
    public function trigger($event)
    {
        $result = false;

        if (isset(self::$event[$event])) {
            $result = Container::getInstance()->invoke(self::$event[$event], [$this]);
        }

        return $result;
    }

=======
     * @access protected
     * @param string $event   事件名
     * @param mixed  $params  额外参数
     * @return bool
     */
    protected function trigger($event, $params = [])
    {
        $result = false;
        if (isset(self::$event[$event])) {
            $callback = self::$event[$event];
            $result   = call_user_func_array($callback, [$params, $this]);
        }
        return $result;
    }
>>>>>>> main
}
