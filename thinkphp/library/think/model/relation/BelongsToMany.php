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

namespace think\model\relation;

<<<<<<< HEAD
use Closure;
use think\Collection;
=======
use think\Collection;
use think\Db;
>>>>>>> main
use think\db\Query;
use think\Exception;
use think\Loader;
use think\Model;
use think\model\Pivot;
use think\model\Relation;
use think\Paginator;

class BelongsToMany extends Relation
{
    // 中间表表名
    protected $middle;
    // 中间表模型名称
    protected $pivotName;
<<<<<<< HEAD
    // 中间表数据名称
    protected $pivotDataName = 'pivot';
    // 中间表模型对象
    protected $pivot;

    /**
     * 架构函数
     * @access public
     * @param  Model  $parent     上级模型对象
     * @param  string $model      模型名
     * @param  string $table      中间表名
     * @param  string $foreignKey 关联模型外键
     * @param  string $localKey   当前模型关联键
=======
    // 中间表模型对象
    protected $pivot;
    // 中间表数据名称
    protected $pivotDataName = 'pivot';

    /**
     * 构造函数
     * @access public
     * @param Model  $parent     上级模型对象
     * @param string $model      模型名
     * @param string $table      中间表名
     * @param string $foreignKey 关联模型外键
     * @param string $localKey   当前模型关联键
>>>>>>> main
     */
    public function __construct(Model $parent, $model, $table, $foreignKey, $localKey)
    {
        $this->parent     = $parent;
        $this->model      = $model;
        $this->foreignKey = $foreignKey;
        $this->localKey   = $localKey;
<<<<<<< HEAD

=======
>>>>>>> main
        if (false !== strpos($table, '\\')) {
            $this->pivotName = $table;
            $this->middle    = basename(str_replace('\\', '/', $table));
        } else {
            $this->middle = $table;
        }
<<<<<<< HEAD

        $this->query = (new $model)->db();
        $this->pivot = $this->newPivot();
=======
        $this->query = (new $model)->db();
        $this->pivot = $this->newPivot();

        if ('think\model\Pivot' == get_class($this->pivot)) {
            $this->pivot->name($this->middle);
        }
>>>>>>> main
    }

    /**
     * 设置中间表模型
<<<<<<< HEAD
     * @access public
     * @param  $pivot
=======
     * @param $pivot
>>>>>>> main
     * @return $this
     */
    public function pivot($pivot)
    {
        $this->pivotName = $pivot;
        return $this;
    }

    /**
     * 设置中间表数据名称
     * @access public
     * @param  string $name
     * @return $this
     */
    public function pivotDataName($name)
    {
        $this->pivotDataName = $name;
        return $this;
    }

    /**
     * 获取中间表更新条件
     * @param $data
     * @return array
     */
    protected function getUpdateWhere($data)
    {
        return [
            $this->localKey   => $data[$this->localKey],
            $this->foreignKey => $data[$this->foreignKey],
        ];
    }

    /**
     * 实例化中间表模型
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @param  array    $data
     * @param  bool     $isUpdate
     * @return Pivot
     * @throws Exception
     */
    protected function newPivot($data = [], $isUpdate = false)
    {
        $class = $this->pivotName ?: '\\think\\model\\Pivot';
        $pivot = new $class($data, $this->parent, $this->middle);
<<<<<<< HEAD

        if ($pivot instanceof Pivot) {
            return $isUpdate ? $pivot->isUpdate(true, $this->getUpdateWhere($data)) : $pivot;
        }

        throw new Exception('pivot model must extends: \think\model\Pivot');
=======
        if ($pivot instanceof Pivot) {
            return $isUpdate ? $pivot->isUpdate(true, $this->getUpdateWhere($data)) : $pivot;
        } else {
            throw new Exception('pivot model must extends: \think\model\Pivot');
        }
>>>>>>> main
    }

    /**
     * 合成中间表模型
<<<<<<< HEAD
     * @access protected
     * @param  array|Collection|Paginator $models
=======
     * @param array|Collection|Paginator $models
>>>>>>> main
     */
    protected function hydratePivot($models)
    {
        foreach ($models as $model) {
            $pivot = [];
<<<<<<< HEAD

            foreach ($model->getData() as $key => $val) {
                if (strpos($key, '__')) {
                    list($name, $attr) = explode('__', $key, 2);

=======
            foreach ($model->getData() as $key => $val) {
                if (strpos($key, '__')) {
                    list($name, $attr) = explode('__', $key, 2);
>>>>>>> main
                    if ('pivot' == $name) {
                        $pivot[$attr] = $val;
                        unset($model->$key);
                    }
                }
            }
<<<<<<< HEAD

=======
>>>>>>> main
            $model->setRelation($this->pivotDataName, $this->newPivot($pivot, true));
        }
    }

    /**
     * 创建关联查询Query对象
<<<<<<< HEAD
     * @access protected
=======
>>>>>>> main
     * @return Query
     */
    protected function buildQuery()
    {
        $foreignKey = $this->foreignKey;
        $localKey   = $this->localKey;
<<<<<<< HEAD

        // 关联查询
        $pk = $this->parent->getPk();

        $condition[] = ['pivot.' . $localKey, '=', $this->parent->$pk];

=======
        $pk         = $this->parent->getPk();
        // 关联查询
        $condition['pivot.' . $localKey] = $this->parent->$pk;
>>>>>>> main
        return $this->belongsToManyQuery($foreignKey, $localKey, $condition);
    }

    /**
     * 延迟获取关联数据
<<<<<<< HEAD
     * @access public
     * @param  string   $subRelation 子关联名
     * @param  \Closure $closure     闭包查询条件
     * @return Collection
     */
    public function getRelation($subRelation = '', $closure = null)
    {
        if ($closure instanceof Closure) {
            $closure($this->query);
        }

        $result = $this->buildQuery()->relation($subRelation)->select();
        $this->hydratePivot($result);

=======
     * @param string   $subRelation 子关联名
     * @param \Closure $closure     闭包查询条件
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getRelation($subRelation = '', $closure = null)
    {
        if ($closure) {
            call_user_func_array($closure, [ & $this->query]);
        }
        $result = $this->buildQuery()->relation($subRelation)->select();
        $this->hydratePivot($result);
>>>>>>> main
        return $result;
    }

    /**
     * 重载select方法
<<<<<<< HEAD
     * @access public
     * @param  mixed $data
     * @return Collection
=======
     * @param null $data
     * @return false|\PDOStatement|string|Collection
>>>>>>> main
     */
    public function select($data = null)
    {
        $result = $this->buildQuery()->select($data);
        $this->hydratePivot($result);
<<<<<<< HEAD

=======
>>>>>>> main
        return $result;
    }

    /**
     * 重载paginate方法
<<<<<<< HEAD
     * @access public
     * @param  null  $listRows
     * @param  bool  $simple
     * @param  array $config
=======
     * @param null  $listRows
     * @param bool  $simple
     * @param array $config
>>>>>>> main
     * @return Paginator
     */
    public function paginate($listRows = null, $simple = false, $config = [])
    {
        $result = $this->buildQuery()->paginate($listRows, $simple, $config);
        $this->hydratePivot($result);
<<<<<<< HEAD

=======
>>>>>>> main
        return $result;
    }

    /**
     * 重载find方法
<<<<<<< HEAD
     * @access public
     * @param  mixed $data
     * @return Model
=======
     * @param null $data
     * @return array|false|\PDOStatement|string|Model
>>>>>>> main
     */
    public function find($data = null)
    {
        $result = $this->buildQuery()->find($data);
        if ($result) {
            $this->hydratePivot([$result]);
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $result;
    }

    /**
     * 查找多条记录 如果不存在则抛出异常
     * @access public
<<<<<<< HEAD
     * @param  array|string|Query|\Closure $data
     * @return Collection
=======
     * @param array|string|Query|\Closure $data
     * @return array|\PDOStatement|string|Model
>>>>>>> main
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
     * @return Model
=======
     * @param array|string|Query|\Closure $data
     * @return array|\PDOStatement|string|Model
>>>>>>> main
     */
    public function findOrFail($data = null)
    {
        return $this->failException(true)->find($data);
    }

    /**
     * 根据关联条件查询当前模型
     * @access public
<<<<<<< HEAD
     * @param  string  $operator 比较操作符
     * @param  integer $count    个数
     * @param  string  $id       关联表的统计字段
     * @param  string  $joinType JOIN类型
=======
     * @param string  $operator 比较操作符
     * @param integer $count    个数
     * @param string  $id       关联表的统计字段
     * @param string  $joinType JOIN类型
>>>>>>> main
     * @return Query
     */
    public function has($operator = '>=', $count = 1, $id = '*', $joinType = 'INNER')
    {
        return $this->parent;
    }

    /**
     * 根据关联条件查询当前模型
     * @access public
<<<<<<< HEAD
     * @param  mixed     $where 查询条件（数组或者闭包）
     * @param  mixed     $fields 字段
=======
     * @param  mixed  $where 查询条件（数组或者闭包）
     * @param  mixed  $fields   字段
>>>>>>> main
     * @return Query
     * @throws Exception
     */
    public function hasWhere($where = [], $fields = null)
    {
        throw new Exception('relation not support: hasWhere');
    }

    /**
     * 设置中间表的查询条件
<<<<<<< HEAD
     * @access public
     * @param  string    $field
     * @param  string    $op
     * @param  mixed     $condition
=======
     * @param      $field
     * @param null $op
     * @param null $condition
>>>>>>> main
     * @return $this
     */
    public function wherePivot($field, $op = null, $condition = null)
    {
<<<<<<< HEAD
        $this->query->where('pivot.' . $field, $op, $condition);
=======
        $field = 'pivot.' . $field;
        $this->query->where($field, $op, $condition);
>>>>>>> main
        return $this;
    }

    /**
     * 预载入关联查询（数据集）
     * @access public
<<<<<<< HEAD
     * @param  array    $resultSet   数据集
     * @param  string   $relation    当前关联名
     * @param  string   $subRelation 子关联名
     * @param  \Closure $closure     闭包
=======
     * @param array    $resultSet   数据集
     * @param string   $relation    当前关联名
     * @param string   $subRelation 子关联名
     * @param \Closure $closure     闭包
>>>>>>> main
     * @return void
     */
    public function eagerlyResultSet(&$resultSet, $relation, $subRelation, $closure)
    {
        $localKey   = $this->localKey;
        $foreignKey = $this->foreignKey;

        $pk    = $resultSet[0]->getPk();
        $range = [];
        foreach ($resultSet as $result) {
            // 获取关联外键列表
            if (isset($result->$pk)) {
                $range[] = $result->$pk;
            }
        }

        if (!empty($range)) {
            // 查询关联数据
            $data = $this->eagerlyManyToMany([
<<<<<<< HEAD
                ['pivot.' . $localKey, 'in', $range],
            ], $relation, $subRelation, $closure);

            // 关联属性名
            $attr = Loader::parseName($relation);

=======
                'pivot.' . $localKey => [
                    'in',
                    $range,
                ],
            ], $relation, $subRelation);
            // 关联属性名
            $attr = Loader::parseName($relation);
>>>>>>> main
            // 关联数据封装
            foreach ($resultSet as $result) {
                if (!isset($data[$result->$pk])) {
                    $data[$result->$pk] = [];
                }

                $result->setRelation($attr, $this->resultSetBuild($data[$result->$pk]));
            }
        }
    }

    /**
     * 预载入关联查询（单个数据）
     * @access public
<<<<<<< HEAD
     * @param  Model    $result      数据对象
     * @param  string   $relation    当前关联名
     * @param  string   $subRelation 子关联名
     * @param  \Closure $closure     闭包
=======
     * @param Model    $result      数据对象
     * @param string   $relation    当前关联名
     * @param string   $subRelation 子关联名
     * @param \Closure $closure     闭包
>>>>>>> main
     * @return void
     */
    public function eagerlyResult(&$result, $relation, $subRelation, $closure)
    {
        $pk = $result->getPk();
<<<<<<< HEAD

        if (isset($result->$pk)) {
            $pk = $result->$pk;
            // 查询管理数据
            $data = $this->eagerlyManyToMany([
                ['pivot.' . $this->localKey, '=', $pk],
            ], $relation, $subRelation, $closure);
=======
        if (isset($result->$pk)) {
            $pk = $result->$pk;
            // 查询管理数据
            $data = $this->eagerlyManyToMany(['pivot.' . $this->localKey => $pk], $relation, $subRelation);
>>>>>>> main

            // 关联数据封装
            if (!isset($data[$pk])) {
                $data[$pk] = [];
            }
<<<<<<< HEAD

=======
>>>>>>> main
            $result->setRelation(Loader::parseName($relation), $this->resultSetBuild($data[$pk]));
        }
    }

    /**
     * 关联统计
     * @access public
<<<<<<< HEAD
     * @param  Model    $result  数据对象
     * @param  \Closure $closure 闭包
     * @param  string   $aggregate 聚合查询方法
     * @param  string   $field 字段
     * @param  string   $name 统计字段别名
     * @return integer
     */
    public function relationCount($result, $closure, $aggregate = 'count', $field = '*', &$name = '')
    {
        $pk = $result->getPk();

        if (!isset($result->$pk)) {
            return 0;
        }

        $pk = $result->$pk;

        if ($closure instanceof Closure) {
            $return = $closure($this->query);

=======
     * @param Model    $result  数据对象
     * @param \Closure $closure 闭包
     * @return integer
     */
    public function relationCount($result, $closure)
    {
        $pk    = $result->getPk();
        $count = 0;
        if (isset($result->$pk)) {
            $pk    = $result->$pk;
            $count = $this->belongsToManyQuery($this->foreignKey, $this->localKey, ['pivot.' . $this->localKey => $pk])->count();
        }
        return $count;
    }

    /**
     * 获取关联统计子查询
     * @access public
     * @param \Closure $closure 闭包
     * @param string   $name    统计数据别名
     * @return string
     */
    public function getRelationCountQuery($closure, &$name = null)
    {
        if ($closure) {
            $return = call_user_func_array($closure, [ & $this->query]);
>>>>>>> main
            if ($return && is_string($return)) {
                $name = $return;
            }
        }

        return $this->belongsToManyQuery($this->foreignKey, $this->localKey, [
<<<<<<< HEAD
            ['pivot.' . $this->localKey, '=', $pk],
        ])->$aggregate($field);
    }

    /**
     * 获取关联统计子查询
     * @access public
     * @param  \Closure $closure 闭包
     * @param  string   $aggregate 聚合查询方法
     * @param  string   $field 字段
     * @param  string   $aggregateAlias 聚合字段别名
     * @return array
     */
    public function getRelationCountQuery($closure, $aggregate = 'count', $field = '*', &$aggregateAlias = '')
    {
        if ($closure instanceof Closure) {
            $return = $closure($this->query);

            if ($return && is_string($return)) {
                $aggregateAlias = $return;
            }
        }

        return $this->belongsToManyQuery($this->foreignKey, $this->localKey, [
            [
                'pivot.' . $this->localKey, 'exp', $this->query->raw('=' . $this->parent->getTable() . '.' . $this->parent->getPk()),
            ],
        ])->fetchSql()->$aggregate($field);
=======
            'pivot.' . $this->localKey => [
                'exp',
                Db::raw('=' . $this->parent->getTable() . '.' . $this->parent->getPk()),
            ],
        ])->fetchSql()->count();
>>>>>>> main
    }

    /**
     * 多对多 关联模型预查询
<<<<<<< HEAD
     * @access protected
     * @param  array    $where       关联预查询条件
     * @param  string   $relation    关联名
     * @param  string   $subRelation 子关联
     * @param  \Closure $closure     闭包
     * @return array
     */
    protected function eagerlyManyToMany($where, $relation, $subRelation = '', $closure = null)
    {
        // 预载入关联查询 支持嵌套预载入
        if ($closure instanceof Closure) {
            $closure($this->query);
        }

        $list = $this->belongsToManyQuery($this->foreignKey, $this->localKey, $where)
            ->with($subRelation)
            ->select();
=======
     * @access public
     * @param array  $where       关联预查询条件
     * @param string $relation    关联名
     * @param string $subRelation 子关联
     * @return array
     */
    protected function eagerlyManyToMany($where, $relation, $subRelation = '')
    {
        // 预载入关联查询 支持嵌套预载入
        $list = $this->belongsToManyQuery($this->foreignKey, $this->localKey, $where)->with($subRelation)->select();
>>>>>>> main

        // 组装模型数据
        $data = [];
        foreach ($list as $set) {
            $pivot = [];
            foreach ($set->getData() as $key => $val) {
                if (strpos($key, '__')) {
                    list($name, $attr) = explode('__', $key, 2);
                    if ('pivot' == $name) {
                        $pivot[$attr] = $val;
                        unset($set->$key);
                    }
                }
            }
<<<<<<< HEAD

            $set->setRelation($this->pivotDataName, $this->newPivot($pivot, true));

            $data[$pivot[$this->localKey]][] = $set;
        }

=======
            $set->setRelation($this->pivotDataName, $this->newPivot($pivot, true));
            $data[$pivot[$this->localKey]][] = $set;
        }
>>>>>>> main
        return $data;
    }

    /**
     * BELONGS TO MANY 关联查询
<<<<<<< HEAD
     * @access protected
     * @param  string   $foreignKey 关联模型关联键
     * @param  string   $localKey   当前模型关联键
     * @param  array    $condition  关联查询条件
=======
     * @access public
     * @param string $foreignKey 关联模型关联键
     * @param string $localKey   当前模型关联键
     * @param array  $condition  关联查询条件
>>>>>>> main
     * @return Query
     */
    protected function belongsToManyQuery($foreignKey, $localKey, $condition = [])
    {
        // 关联查询封装
        $tableName = $this->query->getTable();
        $table     = $this->pivot->getTable();
        $fields    = $this->getQueryFields($tableName);

<<<<<<< HEAD
        $query = $this->query
            ->field($fields)
=======
        $query = $this->query->field($fields)
>>>>>>> main
            ->field(true, false, $table, 'pivot', 'pivot__');

        if (empty($this->baseQuery)) {
            $relationFk = $this->query->getPk();
            $query->join([$table => 'pivot'], 'pivot.' . $foreignKey . '=' . $tableName . '.' . $relationFk)
                ->where($condition);
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $query;
    }

    /**
     * 保存（新增）当前关联数据对象
     * @access public
<<<<<<< HEAD
     * @param  mixed $data  数据 可以使用数组 关联模型对象 和 关联对象的主键
     * @param  array $pivot 中间表额外数据
     * @return array|Pivot
=======
     * @param mixed $data  数据 可以使用数组 关联模型对象 和 关联对象的主键
     * @param array $pivot 中间表额外数据
     * @return integer
>>>>>>> main
     */
    public function save($data, array $pivot = [])
    {
        // 保存关联表/中间表数据
        return $this->attach($data, $pivot);
    }

    /**
     * 批量保存当前关联数据对象
     * @access public
<<<<<<< HEAD
     * @param  array $dataSet   数据集
     * @param  array $pivot     中间表额外数据
     * @param  bool  $samePivot 额外数据是否相同
     * @return array|false
     */
    public function saveAll(array $dataSet, array $pivot = [], $samePivot = false)
    {
        $result = [];

=======
     * @param array $dataSet   数据集
     * @param array $pivot     中间表额外数据
     * @param bool  $samePivot 额外数据是否相同
     * @return integer
     */
    public function saveAll(array $dataSet, array $pivot = [], $samePivot = false)
    {
        $result = false;
>>>>>>> main
        foreach ($dataSet as $key => $data) {
            if (!$samePivot) {
                $pivotData = isset($pivot[$key]) ? $pivot[$key] : [];
            } else {
                $pivotData = $pivot;
            }
<<<<<<< HEAD

            $result[] = $this->attach($data, $pivotData);
        }

        return empty($result) ? false : $result;
=======
            $result = $this->attach($data, $pivotData);
        }
        return $result;
>>>>>>> main
    }

    /**
     * 附加关联的一个中间表数据
     * @access public
<<<<<<< HEAD
     * @param  mixed $data  数据 可以使用数组、关联模型对象 或者 关联对象的主键
     * @param  array $pivot 中间表额外数据
=======
     * @param mixed $data  数据 可以使用数组、关联模型对象 或者 关联对象的主键
     * @param array $pivot 中间表额外数据
>>>>>>> main
     * @return array|Pivot
     * @throws Exception
     */
    public function attach($data, $pivot = [])
    {
        if (is_array($data)) {
            if (key($data) === 0) {
                $id = $data;
            } else {
                // 保存关联表数据
                $model = new $this->model;
<<<<<<< HEAD
                $id    = $model->insertGetId($data);
=======
                $model->save($data);
                $id = $model->getLastInsID();
>>>>>>> main
            }
        } elseif (is_numeric($data) || is_string($data)) {
            // 根据关联表主键直接写入中间表
            $id = $data;
        } elseif ($data instanceof Model) {
            // 根据关联表主键直接写入中间表
            $relationFk = $data->getPk();
            $id         = $data->$relationFk;
        }

        if ($id) {
            // 保存中间表数据
            $pk                     = $this->parent->getPk();
            $pivot[$this->localKey] = $this->parent->$pk;
            $ids                    = (array) $id;
<<<<<<< HEAD

            foreach ($ids as $id) {
                $pivot[$this->foreignKey] = $id;
                $this->pivot->replace()
                    ->exists(false)
                    ->data([])
                    ->save($pivot);
                $result[] = $this->newPivot($pivot, true);
            }

=======
            foreach ($ids as $id) {
                $pivot[$this->foreignKey] = $id;
                $this->pivot->insert($pivot, true);
                $result[] = $this->newPivot($pivot, true);
            }
>>>>>>> main
            if (count($result) == 1) {
                // 返回中间表模型对象
                $result = $result[0];
            }
<<<<<<< HEAD

=======
>>>>>>> main
            return $result;
        } else {
            throw new Exception('miss relation data');
        }
    }

    /**
     * 判断是否存在关联数据
     * @access public
     * @param  mixed $data  数据 可以使用关联模型对象 或者 关联对象的主键
<<<<<<< HEAD
     * @return Pivot|false
=======
     * @return Pivot
>>>>>>> main
     * @throws Exception
     */
    public function attached($data)
    {
        if ($data instanceof Model) {
<<<<<<< HEAD
            $id = $data->getKey();
=======
            $relationFk = $data->getPk();
            $id         = $data->$relationFk;
>>>>>>> main
        } else {
            $id = $data;
        }

<<<<<<< HEAD
        $pivot = $this->pivot
            ->where($this->localKey, $this->parent->getKey())
            ->where($this->foreignKey, $id)
            ->find();
=======
        $pk = $this->parent->getPk();

        $pivot = $this->pivot->where($this->localKey, $this->parent->$pk)->where($this->foreignKey, $id)->find();
>>>>>>> main

        return $pivot ?: false;
    }

    /**
     * 解除关联的一个中间表数据
     * @access public
<<<<<<< HEAD
     * @param  integer|array $data        数据 可以使用关联对象的主键
     * @param  bool          $relationDel 是否同时删除关联表数据
=======
     * @param integer|array $data        数据 可以使用关联对象的主键
     * @param bool          $relationDel 是否同时删除关联表数据
>>>>>>> main
     * @return integer
     */
    public function detach($data = null, $relationDel = false)
    {
        if (is_array($data)) {
            $id = $data;
        } elseif (is_numeric($data) || is_string($data)) {
            // 根据关联表主键直接写入中间表
            $id = $data;
        } elseif ($data instanceof Model) {
            // 根据关联表主键直接写入中间表
            $relationFk = $data->getPk();
            $id         = $data->$relationFk;
        }
<<<<<<< HEAD

        // 删除中间表数据
        $pk      = $this->parent->getPk();
        $pivot[] = [$this->localKey, '=', $this->parent->$pk];

        if (isset($id)) {
            $pivot[] = [$this->foreignKey, is_array($id) ? 'in' : '=', $id];
        }

        $result = $this->pivot->where($pivot)->delete();

=======
        // 删除中间表数据
        $pk                     = $this->parent->getPk();
        $pivot[$this->localKey] = $this->parent->$pk;
        if (isset($id)) {
            $pivot[$this->foreignKey] = is_array($id) ? ['in', $id] : $id;
        }
        $this->pivot->where($pivot)->delete();
>>>>>>> main
        // 删除关联表数据
        if (isset($id) && $relationDel) {
            $model = $this->model;
            $model::destroy($id);
        }
<<<<<<< HEAD

        return $result;
=======
>>>>>>> main
    }

    /**
     * 数据同步
<<<<<<< HEAD
     * @access public
     * @param  array $ids
     * @param  bool  $detaching
=======
     * @param array $ids
     * @param bool  $detaching
>>>>>>> main
     * @return array
     */
    public function sync($ids, $detaching = true)
    {
        $changes = [
            'attached' => [],
            'detached' => [],
            'updated'  => [],
        ];
<<<<<<< HEAD

        $pk = $this->parent->getPk();

        $current = $this->pivot
            ->where($this->localKey, $this->parent->$pk)
            ->column($this->foreignKey);

=======
        $pk      = $this->parent->getPk();
        $current = $this->pivot->where($this->localKey, $this->parent->$pk)
            ->column($this->foreignKey);
>>>>>>> main
        $records = [];

        foreach ($ids as $key => $value) {
            if (!is_array($value)) {
                $records[$value] = [];
            } else {
                $records[$key] = $value;
            }
        }

        $detach = array_diff($current, array_keys($records));

        if ($detaching && count($detach) > 0) {
            $this->detach($detach);
<<<<<<< HEAD
=======

>>>>>>> main
            $changes['detached'] = $detach;
        }

        foreach ($records as $id => $attributes) {
            if (!in_array($id, $current)) {
                $this->attach($id, $attributes);
                $changes['attached'][] = $id;
<<<<<<< HEAD
            } elseif (count($attributes) > 0 && $this->attach($id, $attributes)) {
=======
            } elseif (count($attributes) > 0 &&
                $this->attach($id, $attributes)
            ) {
>>>>>>> main
                $changes['updated'][] = $id;
            }
        }

        return $changes;
<<<<<<< HEAD
    }

    /**
     * 执行基础查询（仅执行一次）
=======

    }

    /**
     * 执行基础查询（进执行一次）
>>>>>>> main
     * @access protected
     * @return void
     */
    protected function baseQuery()
    {
        if (empty($this->baseQuery) && $this->parent->getData()) {
            $pk    = $this->parent->getPk();
            $table = $this->pivot->getTable();
<<<<<<< HEAD

            $this->query
                ->join([$table => 'pivot'], 'pivot.' . $this->foreignKey . '=' . $this->query->getTable() . '.' . $this->query->getPk())
                ->where('pivot.' . $this->localKey, $this->parent->$pk);
=======
            $this->query->join([$table => 'pivot'], 'pivot.' . $this->foreignKey . '=' . $this->query->getTable() . '.' . $this->query->getPk())->where('pivot.' . $this->localKey, $this->parent->$pk);
>>>>>>> main
            $this->baseQuery = true;
        }
    }

}
