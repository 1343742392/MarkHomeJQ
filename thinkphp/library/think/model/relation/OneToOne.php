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
=======
>>>>>>> main
use think\db\Query;
use think\Exception;
use think\Loader;
use think\Model;
use think\model\Relation;

/**
 * Class OneToOne
 * @package think\model\relation
 *
 */
abstract class OneToOne extends Relation
{
    // 预载入方式 0 -JOIN 1 -IN
    protected $eagerlyType = 1;
    // 当前关联的JOIN类型
    protected $joinType;
    // 要绑定的属性
    protected $bindAttr = [];
<<<<<<< HEAD
    // 关联名
=======
    // 关联方法名
>>>>>>> main
    protected $relation;

    /**
     * 设置join类型
     * @access public
<<<<<<< HEAD
     * @param  string $type JOIN类型
=======
     * @param string $type JOIN类型
>>>>>>> main
     * @return $this
     */
    public function joinType($type)
    {
        $this->joinType = $type;
        return $this;
    }

    /**
     * 预载入关联查询（JOIN方式）
     * @access public
<<<<<<< HEAD
     * @param  Query    $query       查询对象
     * @param  string   $relation    关联名
     * @param  mixed    $field       关联字段
     * @param  string   $joinType    JOIN方式
     * @param  \Closure $closure     闭包条件
     * @param  bool     $first
     * @return void
     */
    public function eagerly(Query $query, $relation, $field, $joinType, $closure, $first)
    {
        $name = Loader::parseName(basename(str_replace('\\', '/', get_class($this->parent))));
=======
     * @param Query    $query       查询对象
     * @param string   $relation    关联名
     * @param string   $subRelation 子关联
     * @param \Closure $closure     闭包条件
     * @param bool     $first
     * @return void
     */
    public function eagerly(Query $query, $relation, $subRelation, $closure, $first)
    {
        $name = Loader::parseName(basename(str_replace('\\', '/', get_class($query->getModel()))));
>>>>>>> main

        if ($first) {
            $table = $query->getTable();
            $query->table([$table => $name]);
<<<<<<< HEAD

            if ($query->getOptions('field')) {
                $masterField = $query->getOptions('field');
                $query->removeOption('field');
            } else {
                $masterField = true;
            }

            $query->field($masterField, false, $table, $name);
=======
            if ($query->getOptions('field')) {
                $field = $query->getOptions('field');
                $query->removeOption('field');
            } else {
                $field = true;
            }
            $query->field($field, false, $table, $name);
            $field = null;
>>>>>>> main
        }

        // 预载入封装
        $joinTable = $this->query->getTable();
        $joinAlias = $relation;
<<<<<<< HEAD
        $joinType  = $joinType ?: $this->joinType;

        $query->via($joinAlias);

        if ($this instanceof BelongsTo) {
            $joinOn = $name . '.' . $this->foreignKey . '=' . $joinAlias . '.' . $this->localKey;
        } else {
            $joinOn = $name . '.' . $this->localKey . '=' . $joinAlias . '.' . $this->foreignKey;
        }

        if ($closure instanceof Closure) {
            // 执行闭包查询
            $closure($query);
=======
        $query->via($joinAlias);

        if ($this instanceof BelongsTo) {
            $query->join([$joinTable => $joinAlias], $name . '.' . $this->foreignKey . '=' . $joinAlias . '.' . $this->localKey, $this->joinType);
        } else {
            $query->join([$joinTable => $joinAlias], $name . '.' . $this->localKey . '=' . $joinAlias . '.' . $this->foreignKey, $this->joinType);
        }

        if ($closure) {
            // 执行闭包查询
            call_user_func_array($closure, [ & $query]);
>>>>>>> main
            // 使用withField指定获取关联的字段，如
            // $query->where(['id'=>1])->withField('id,name');
            if ($query->getOptions('with_field')) {
                $field = $query->getOptions('with_field');
                $query->removeOption('with_field');
            }
<<<<<<< HEAD
        }

        $query->join([$joinTable => $joinAlias], $joinOn, $joinType)
            ->field($field, false, $joinTable, $joinAlias, $relation . '__');
=======
        } elseif (isset($this->option['field'])) {
            $field = $this->option['field'];
        }
        $query->field(isset($field) ? $field : true, false, $joinTable, $joinAlias, $relation . '__');
>>>>>>> main
    }

    /**
     *  预载入关联查询（数据集）
<<<<<<< HEAD
     * @access protected
     * @param  array    $resultSet
     * @param  string   $relation
     * @param  string   $subRelation
     * @param  \Closure $closure
=======
     * @param array    $resultSet
     * @param string   $relation
     * @param string   $subRelation
     * @param \Closure $closure
>>>>>>> main
     * @return mixed
     */
    abstract protected function eagerlySet(&$resultSet, $relation, $subRelation, $closure);

    /**
     * 预载入关联查询（数据）
<<<<<<< HEAD
     * @access protected
     * @param  Model    $result
     * @param  string   $relation
     * @param  string   $subRelation
     * @param  \Closure $closure
=======
     * @param Model    $result
     * @param string   $relation
     * @param string   $subRelation
     * @param \Closure $closure
>>>>>>> main
     * @return mixed
     */
    abstract protected function eagerlyOne(&$result, $relation, $subRelation, $closure);

    /**
     * 预载入关联查询（数据集）
     * @access public
<<<<<<< HEAD
     * @param  array    $resultSet   数据集
     * @param  string   $relation    当前关联名
     * @param  string   $subRelation 子关联名
     * @param  \Closure $closure     闭包
     * @param  bool     $join        是否为JOIN方式
     * @return void
     */
    public function eagerlyResultSet(&$resultSet, $relation, $subRelation, $closure, $join = false)
    {
        if ($join || 0 == $this->eagerlyType) {
            // 模型JOIN关联组装
            foreach ($resultSet as $result) {
                $this->match($this->model, $relation, $result);
            }
        } else {
            // IN查询
            $this->eagerlySet($resultSet, $relation, $subRelation, $closure);
=======
     * @param array    $resultSet   数据集
     * @param string   $relation    当前关联名
     * @param string   $subRelation 子关联名
     * @param \Closure $closure     闭包
     * @return void
     */
    public function eagerlyResultSet(&$resultSet, $relation, $subRelation, $closure)
    {
        if (1 == $this->eagerlyType) {
            // IN查询
            $this->eagerlySet($resultSet, $relation, $subRelation, $closure);
        } else {
            // 模型关联组装
            foreach ($resultSet as $result) {
                $this->match($this->model, $relation, $result);
            }
>>>>>>> main
        }
    }

    /**
     * 预载入关联查询（数据）
     * @access public
<<<<<<< HEAD
     * @param  Model    $result      数据对象
     * @param  string   $relation    当前关联名
     * @param  string   $subRelation 子关联名
     * @param  \Closure $closure     闭包
     * @param  bool     $join        是否为JOIN方式
     * @return void
     */
    public function eagerlyResult(&$result, $relation, $subRelation, $closure, $join = false)
    {
        if (0 == $this->eagerlyType || $join) {
            // 模型JOIN关联组装
            $this->match($this->model, $relation, $result);
        } else {
            // IN查询
            $this->eagerlyOne($result, $relation, $subRelation, $closure);
=======
     * @param Model    $result      数据对象
     * @param string   $relation    当前关联名
     * @param string   $subRelation 子关联名
     * @param \Closure $closure     闭包
     * @return void
     */
    public function eagerlyResult(&$result, $relation, $subRelation, $closure)
    {
        if (1 == $this->eagerlyType) {
            // IN查询
            $this->eagerlyOne($result, $relation, $subRelation, $closure);
        } else {
            // 模型关联组装
            $this->match($this->model, $relation, $result);
>>>>>>> main
        }
    }

    /**
     * 保存（新增）当前关联数据对象
     * @access public
<<<<<<< HEAD
     * @param  mixed $data 数据 可以使用数组 关联模型对象 和 关联对象的主键
=======
     * @param mixed $data 数据 可以使用数组 关联模型对象 和 关联对象的主键
>>>>>>> main
     * @return Model|false
     */
    public function save($data)
    {
        if ($data instanceof Model) {
            $data = $data->getData();
        }
<<<<<<< HEAD

        $model = new $this->model;
        // 保存关联表数据
        $data[$this->foreignKey] = $this->parent->{$this->localKey};

=======
        $model = new $this->model;
        // 保存关联表数据
        $data[$this->foreignKey] = $this->parent->{$this->localKey};
>>>>>>> main
        return $model->save($data) ? $model : false;
    }

    /**
     * 设置预载入方式
     * @access public
<<<<<<< HEAD
     * @param  integer $type 预载入方式 0 JOIN查询 1 IN查询
=======
     * @param integer $type 预载入方式 0 JOIN查询 1 IN查询
>>>>>>> main
     * @return $this
     */
    public function setEagerlyType($type)
    {
        $this->eagerlyType = $type;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 获取预载入方式
     * @access public
     * @return integer
     */
    public function getEagerlyType()
    {
        return $this->eagerlyType;
    }

    /**
     * 绑定关联表的属性到父模型属性
     * @access public
<<<<<<< HEAD
     * @param  mixed $attr 要绑定的属性列表
=======
     * @param mixed $attr 要绑定的属性列表
>>>>>>> main
     * @return $this
     */
    public function bind($attr)
    {
        if (is_string($attr)) {
            $attr = explode(',', $attr);
        }
        $this->bindAttr = $attr;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 获取绑定属性
     * @access public
     * @return array
     */
    public function getBindAttr()
    {
        return $this->bindAttr;
    }

    /**
<<<<<<< HEAD
     * 一对一 关联模型预查询拼装
     * @access public
     * @param  string $model    模型名称
     * @param  string $relation 关联名
     * @param  Model  $result   模型对象实例
=======
     * 关联统计
     * @access public
     * @param Model    $result  数据对象
     * @param \Closure $closure 闭包
     * @return integer
     */
    public function relationCount($result, $closure)
    {
    }

    /**
     * 一对一 关联模型预查询拼装
     * @access public
     * @param string $model    模型名称
     * @param string $relation 关联名
     * @param Model  $result   模型对象实例
>>>>>>> main
     * @return void
     */
    protected function match($model, $relation, &$result)
    {
        // 重新组装模型数据
        foreach ($result->getData() as $key => $val) {
            if (strpos($key, '__')) {
                list($name, $attr) = explode('__', $key, 2);
                if ($name == $relation) {
                    $list[$name][$attr] = $val;
                    unset($result->$key);
                }
            }
        }

        if (isset($list[$relation])) {
<<<<<<< HEAD
            $array = array_unique($list[$relation]);

            if (count($array) == 1 && null === current($array)) {
                $relationModel = null;
            } else {
                $relationModel = new $model($list[$relation]);
                $relationModel->setParent(clone $result);
                $relationModel->isUpdate(true);
            }
=======
            $relationModel = new $model($list[$relation]);
            $relationModel->setParent(clone $result);
            $relationModel->isUpdate(true);
>>>>>>> main

            if (!empty($this->bindAttr)) {
                $this->bindAttr($relationModel, $result, $this->bindAttr);
            }
        } else {
            $relationModel = null;
        }
<<<<<<< HEAD

=======
>>>>>>> main
        $result->setRelation(Loader::parseName($relation), $relationModel);
    }

    /**
     * 绑定关联属性到父模型
     * @access protected
<<<<<<< HEAD
     * @param  Model $result    关联模型对象
     * @param  Model $model   父模型对象
     * @return void
     * @throws Exception
     */
    protected function bindAttr($model, &$result)
    {
        foreach ($this->bindAttr as $key => $attr) {
            $key   = is_numeric($key) ? $attr : $key;
            $value = $result->getOrigin($key);

            if (!is_null($value)) {
                throw new Exception('bind attr has exists:' . $key);
            }

            $result->setAttr($key, $model ? $model->getAttr($attr) : null);
=======
     * @param Model $model    关联模型对象
     * @param Model $result   父模型对象
     * @param array $bindAttr 绑定属性
     * @return void
     * @throws Exception
     */
    protected function bindAttr($model, &$result, $bindAttr)
    {
        foreach ($bindAttr as $key => $attr) {
            $key = is_numeric($key) ? $attr : $key;
            if (isset($result->$key)) {
                throw new Exception('bind attr has exists:' . $key);
            } else {
                $result->setAttr($key, $model ? $model->$attr : null);
            }
>>>>>>> main
        }
    }

    /**
     * 一对一 关联模型预查询（IN方式）
     * @access public
<<<<<<< HEAD
     * @param  array         $where       关联预查询条件
     * @param  string        $key         关联键名
     * @param  string        $relation    关联名
     * @param  string        $subRelation 子关联
     * @param  \Closure      $closure
     * @return array
     */
    protected function eagerlyWhere($where, $key, $relation, $subRelation = '', $closure = null)
    {
        // 预载入关联查询 支持嵌套预载入
        if ($closure instanceof Closure) {
            $closure($this->query);

            if ($field = $this->query->getOptions('with_field')) {
                $this->query->field($field)->removeOption('with_field');
            }
        }

        $list = $this->query->where($where)->with($subRelation)->select();

        // 组装模型数据
        $data = [];

        foreach ($list as $set) {
            $data[$set->$key] = $set;
        }

        return $data;
    }

=======
     * @param object        $model       关联模型对象
     * @param array         $where       关联预查询条件
     * @param string        $key         关联键名
     * @param string        $relation    关联名
     * @param string        $subRelation 子关联
     * @param bool|\Closure $closure
     * @return array
     */
    protected function eagerlyWhere($model, $where, $key, $relation, $subRelation = '', $closure = false)
    {
        $this->baseQuery = true;

        // 预载入关联查询 支持嵌套预载入
        if ($closure) {
            call_user_func_array($closure, [ & $model]);
            if ($field = $model->getOptions('with_field')) {
                $model->field($field)->removeOption('with_field');
            }
        }
        $list = $model->where($where)->with($subRelation)->select();

        // 组装模型数据
        $data = [];
        foreach ($list as $set) {
            $data[$set->$key] = $set;
        }
        return $data;
    }

    /**
     * 创建关联统计子查询
     * @access public
     * @param \Closure $closure 闭包
     * @param string   $name    统计数据别名
     * @return string
     */
    public function getRelationCountQuery($closure, &$name = null)
    {
        throw new Exception('relation not support: withCount');
    }
>>>>>>> main
}
