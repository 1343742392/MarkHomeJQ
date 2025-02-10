<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zhangyajun <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
<<<<<<< HEAD
     * 数据集数据
     * @var array
     */
    protected $items = [];

=======
     * @var array 数据
     */
    protected $items = [];

    /**
     * Collection constructor.
     * @access public
     * @param  array $items 数据
     */
>>>>>>> main
    public function __construct($items = [])
    {
        $this->items = $this->convertToArray($items);
    }

<<<<<<< HEAD
=======
    /**
     * 创建 Collection 实例
     * @access public
     * @param  array $items 数据
     * @return static
     */
>>>>>>> main
    public static function make($items = [])
    {
        return new static($items);
    }

    /**
<<<<<<< HEAD
     * 是否为空
=======
     * 判断数据是否为空
>>>>>>> main
     * @access public
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

<<<<<<< HEAD
    public function toArray()
    {
        return array_map(function ($value) {
            return ($value instanceof Model || $value instanceof self) ? $value->toArray() : $value;
        }, $this->items);
    }

=======
    /**
     * 将数据转成数组
     * @access public
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return ($value instanceof Model || $value instanceof self) ?
                $value->toArray() :
                $value;
        }, $this->items);
    }

    /**
     * 获取全部的数据
     * @access public
     * @return array
     */
>>>>>>> main
    public function all()
    {
        return $this->items;
    }

    /**
<<<<<<< HEAD
     * 合并数组
     *
     * @access public
     * @param  mixed $items
     * @return static
     */
    public function merge($items)
    {
        return new static(array_merge($this->items, $this->convertToArray($items)));
    }

    /**
     * 交换数组中的键和值
     *
=======
     * 交换数组中的键和值
>>>>>>> main
     * @access public
     * @return static
     */
    public function flip()
    {
        return new static(array_flip($this->items));
    }

    /**
<<<<<<< HEAD
     * 按指定键整理数据
     *
     * @access public
     * @param  mixed    $items      数据
     * @param  string   $indexKey   键名
     * @return array
     */
    public function dictionary($items = null, &$indexKey = null)
    {
        if ($items instanceof self || $items instanceof Paginator) {
            $items = $items->all();
        }

        $items = is_null($items) ? $this->items : $items;

        if ($items && empty($indexKey)) {
            $indexKey = is_array($items[0]) ? 'id' : $items[0]->getPk();
        }

        if (isset($indexKey) && is_string($indexKey)) {
            return array_column($items, null, $indexKey);
        }

        return $items;
    }

    /**
     * 比较数组，返回差集
     *
     * @access public
     * @param  mixed    $items      数据
     * @param  string   $indexKey   指定比较的键名
     * @return static
     */
    public function diff($items, $indexKey = null)
    {
        if ($this->isEmpty() || is_scalar($this->items[0])) {
            return new static(array_diff($this->items, $this->convertToArray($items)));
        }

        $diff       = [];
        $dictionary = $this->dictionary($items, $indexKey);

        if (is_string($indexKey)) {
            foreach ($this->items as $item) {
                if (!isset($dictionary[$item[$indexKey]])) {
                    $diff[] = $item;
                }
            }
        }

        return new static($diff);
    }

    /**
     * 比较数组，返回交集
     *
     * @access public
     * @param  mixed    $items      数据
     * @param  string   $indexKey   指定比较的键名
     * @return static
     */
    public function intersect($items, $indexKey = null)
    {
        if ($this->isEmpty() || is_scalar($this->items[0])) {
            return new static(array_diff($this->items, $this->convertToArray($items)));
        }

        $intersect  = [];
        $dictionary = $this->dictionary($items, $indexKey);

        if (is_string($indexKey)) {
            foreach ($this->items as $item) {
                if (isset($dictionary[$item[$indexKey]])) {
                    $intersect[] = $item;
                }
            }
        }

        return new static($intersect);
    }

    /**
     * 返回数组中所有的键名
     *
     * @access public
     * @return array
     */
    public function keys()
    {
        $current = current($this->items);

        if (is_scalar($current)) {
            $array = $this->items;
        } elseif (is_array($current)) {
            $array = $current;
        } else {
            $array = $current->toArray();
        }

        return array_keys($array);
    }

    /**
     * 删除数组的最后一个元素（出栈）
     *
=======
     * 返回数组中所有的键名组成的新 Collection 实例
     * @access public
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->items));
    }

    /**
     * 返回数组中所有的值组成的新 Collection 实例
     * @access public
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->items));
    }

    /**
     * 合并数组并返回一个新的 Collection 实例
     * @access public
     * @param  mixed $items 新的数据
     * @return static
     */
    public function merge($items)
    {
        return new static(array_merge($this->items, $this->convertToArray($items)));
    }

    /**
     * 比较数组，返回差集生成的新 Collection 实例
     * @access public
     * @param  mixed $items 做比较的数据
     * @return static
     */
    public function diff($items)
    {
        return new static(array_diff($this->items, $this->convertToArray($items)));
    }

    /**
     * 比较数组，返回交集组成的 Collection 新实例
     * @access public
     * @param  mixed $items 比较数据
     * @return static
     */
    public function intersect($items)
    {
        return new static(array_intersect($this->items, $this->convertToArray($items)));
    }

    /**
     * 返回并删除数据中的的最后一个元素（出栈）
>>>>>>> main
     * @access public
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
<<<<<<< HEAD
     * 通过使用用户自定义函数，以字符串返回数组
     *
     * @access public
     * @param  callable $callback
     * @param  mixed    $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    /**
     * 以相反的顺序返回数组。
     *
     * @access public
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->items));
    }

    /**
     * 删除数组中首个元素，并返回被删除元素的值
     *
=======
     * 返回并删除数据中首个元素
>>>>>>> main
     * @access public
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
<<<<<<< HEAD
     * 在数组结尾插入一个元素
     * @access public
     * @param  mixed  $value
     * @param  mixed  $key
=======
     * 在数组开头插入一个元素
     * @access public
     * @param mixed $value 值
     * @param mixed $key   键名
     * @return void
     */
    public function unshift($value, $key = null)
    {
        if (is_null($key)) {
            array_unshift($this->items, $value);
        } else {
            $this->items = [$key => $value] + $this->items;
        }
    }

    /**
     * 在数组结尾插入一个元素
     * @access public
     * @param  mixed $value 值
     * @param  mixed $key   键名
>>>>>>> main
     * @return void
     */
    public function push($value, $key = null)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
<<<<<<< HEAD
     * 把一个数组分割为新的数组块.
     *
     * @access public
     * @param  int  $size
     * @param  bool $preserveKeys
=======
     * 通过使用用户自定义函数，以字符串返回数组
     * @access public
     * @param  callable $callback 回调函数
     * @param  mixed    $initial  初始值
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    /**
     * 以相反的顺序创建一个新的 Collection 实例
     * @access public
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->items));
    }

    /**
     * 把数据分割为新的数组块
     * @access public
     * @param  int  $size         分隔长度
     * @param  bool $preserveKeys 是否保持原数据索引
>>>>>>> main
     * @return static
     */
    public function chunk($size, $preserveKeys = false)
    {
        $chunks = [];

        foreach (array_chunk($this->items, $size, $preserveKeys) as $chunk) {
            $chunks[] = new static($chunk);
        }

        return new static($chunks);
    }

    /**
<<<<<<< HEAD
     * 在数组开头插入一个元素
     * @access public
     * @param mixed  $value
     * @param mixed  $key
     * @return void
     */
    public function unshift($value, $key = null)
    {
        if (is_null($key)) {
            array_unshift($this->items, $value);
        } else {
            $this->items = [$key => $value] + $this->items;
        }
    }

    /**
     * 给每个元素执行个回调
     *
     * @access public
     * @param  callable $callback
=======
     * 给数据中的每个元素执行回调
     * @access public
     * @param  callable $callback 回调函数
>>>>>>> main
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            $result = $callback($item, $key);

            if (false === $result) {
                break;
<<<<<<< HEAD
            } elseif (!is_object($item)) {
=======
            }

            if (!is_object($item)) {
>>>>>>> main
                $this->items[$key] = $result;
            }
        }

        return $this;
    }

    /**
<<<<<<< HEAD
     * 用回调函数处理数组中的元素
     * @access public
     * @param  callable|null $callback
     * @return static
     */
    public function map(callable $callback)
    {
        return new static(array_map($callback, $this->items));
    }

    /**
     * 用回调函数过滤数组中的元素
     * @access public
     * @param  callable|null $callback
=======
     * 用回调函数过滤数据中的元素
     * @access public
     * @param callable|null $callback 回调函数
>>>>>>> main
     * @return static
     */
    public function filter(callable $callback = null)
    {
<<<<<<< HEAD
        if ($callback) {
            return new static(array_filter($this->items, $callback));
        }

        return new static(array_filter($this->items));
    }

    /**
     * 根据字段条件过滤数组中的元素
     * @access public
     * @param  string   $field 字段名
     * @param  mixed    $operator 操作符
     * @param  mixed    $value 数据
     * @return static
     */
    public function where($field, $operator, $value = null)
    {
        if (is_null($value)) {
            $value    = $operator;
            $operator = '=';
        }

        return $this->filter(function ($data) use ($field, $operator, $value) {
            if (strpos($field, '.')) {
                list($field, $relation) = explode('.', $field);

                $result = isset($data[$field][$relation]) ? $data[$field][$relation] : null;
            } else {
                $result = isset($data[$field]) ? $data[$field] : null;
            }

            switch (strtolower($operator)) {
                case '===':
                    return $result === $value;
                case '!==':
                    return $result !== $value;
                case '!=':
                case '<>':
                    return $result != $value;
                case '>':
                    return $result > $value;
                case '>=':
                    return $result >= $value;
                case '<':
                    return $result < $value;
                case '<=':
                    return $result <= $value;
                case 'like':
                    return is_string($result) && false !== strpos($result, $value);
                case 'not like':
                    return is_string($result) && false === strpos($result, $value);
                case 'in':
                    return is_scalar($result) && in_array($result, $value, true);
                case 'not in':
                    return is_scalar($result) && !in_array($result, $value, true);
                case 'between':
                    list($min, $max) = is_string($value) ? explode(',', $value) : $value;
                    return is_scalar($result) && $result >= $min && $result <= $max;
                case 'not between':
                    list($min, $max) = is_string($value) ? explode(',', $value) : $value;
                    return is_scalar($result) && $result > $max || $result < $min;
                case '==':
                case '=':
                default:
                    return $result == $value;
            }
        });
=======
        return new static(array_filter($this->items, $callback ?: null));
>>>>>>> main
    }

    /**
     * 返回数据中指定的一列
     * @access public
     * @param mixed $columnKey 键名
<<<<<<< HEAD
     * @param mixed $indexKey  作为索引值的列
=======
     * @param null  $indexKey  作为索引值的列
>>>>>>> main
     * @return array
     */
    public function column($columnKey, $indexKey = null)
    {
<<<<<<< HEAD
        return array_column($this->toArray(), $columnKey, $indexKey);
    }

    /**
     * 对数组排序
     *
     * @access public
     * @param  callable|null $callback
=======
        if (function_exists('array_column')) {
            return array_column($this->items, $columnKey, $indexKey);
        }

        $result = [];
        foreach ($this->items as $row) {
            $key    = $value = null;
            $keySet = $valueSet = false;

            if (null !== $indexKey && array_key_exists($indexKey, $row)) {
                $key    = (string) $row[$indexKey];
                $keySet = true;
            }

            if (null === $columnKey) {
                $valueSet = true;
                $value    = $row;
            } elseif (is_array($row) && array_key_exists($columnKey, $row)) {
                $valueSet = true;
                $value    = $row[$columnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $result[$key] = $value;
                } else {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * 对数据排序，并返回排序后的数据组成的新 Collection 实例
     * @access public
     * @param  callable|null $callback 回调函数
>>>>>>> main
     * @return static
     */
    public function sort(callable $callback = null)
    {
<<<<<<< HEAD
        $items = $this->items;

        $callback = $callback ?: function ($a, $b) {
            return $a == $b ? 0 : (($a < $b) ? -1 : 1);

        };

        uasort($items, $callback);

=======
        $items    = $this->items;
        $callback = $callback ?: function ($a, $b) {
            return $a == $b ? 0 : (($a < $b) ? -1 : 1);
        };

        uasort($items, $callback);
>>>>>>> main
        return new static($items);
    }

    /**
<<<<<<< HEAD
     * 指定字段排序
     * @access public
     * @param  string       $field 排序字段
     * @param  string       $order 排序
     * @param  bool         $intSort 是否为数字排序
     * @return $this
     */
    public function order($field, $order = null, $intSort = true)
    {
        return $this->sort(function ($a, $b) use ($field, $order, $intSort) {
            $fieldA = isset($a[$field]) ? $a[$field] : null;
            $fieldB = isset($b[$field]) ? $b[$field] : null;

            if ($intSort) {
                return 'desc' == strtolower($order) ? $fieldB >= $fieldA : $fieldA >= $fieldB;
            } else {
                return 'desc' == strtolower($order) ? strcmp($fieldB, $fieldA) : strcmp($fieldA, $fieldB);
            }
        });
    }

    /**
     * 将数组打乱
     *
=======
     * 将数据打乱后组成新的 Collection 实例
>>>>>>> main
     * @access public
     * @return static
     */
    public function shuffle()
    {
        $items = $this->items;

        shuffle($items);
<<<<<<< HEAD

=======
>>>>>>> main
        return new static($items);
    }

    /**
<<<<<<< HEAD
     * 截取数组
     *
     * @access public
     * @param  int  $offset
     * @param  int  $length
     * @param  bool $preserveKeys
=======
     * 截取数据并返回新的 Collection 实例
     * @access public
     * @param  int  $offset       起始位置
     * @param  int  $length       截取长度
     * @param  bool $preserveKeys 是否保持原先的键名
>>>>>>> main
     * @return static
     */
    public function slice($offset, $length = null, $preserveKeys = false)
    {
        return new static(array_slice($this->items, $offset, $length, $preserveKeys));
    }

<<<<<<< HEAD
    // ArrayAccess
=======
    /**
     * 指定的键是否存在
     * @access public
     * @param  mixed $offset 键名
     * @return bool
     */
>>>>>>> main
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

<<<<<<< HEAD
=======
    /**
     * 获取指定键对应的值
     * @access public
     * @param  mixed $offset 键名
     * @return mixed
     */
>>>>>>> main
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

<<<<<<< HEAD
=======
    /**
     * 设置键值
     * @access public
     * @param  mixed $offset 键名
     * @param  mixed $value  值
     * @return void
     */
>>>>>>> main
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

<<<<<<< HEAD
=======
    /**
     * 删除指定键值
     * @access public
     * @param  mixed $offset 键名
     * @return void
     */
>>>>>>> main
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

<<<<<<< HEAD
    //Countable
=======
    /**
     * 统计数据的个数
     * @access public
     * @return int
     */
>>>>>>> main
    public function count()
    {
        return count($this->items);
    }

<<<<<<< HEAD
    //IteratorAggregate
=======
    /**
     * 获取数据的迭代器
     * @access public
     * @return ArrayIterator
     */
>>>>>>> main
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

<<<<<<< HEAD
    //JsonSerializable
=======
    /**
     * 将数据反序列化成数组
     * @access public
     * @return array
     */
>>>>>>> main
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
<<<<<<< HEAD
     * 转换当前数据集为JSON字符串
     * @access public
     * @param  integer $options json参数
=======
     * 转换当前数据集为 JSON 字符串
     * @access public
     * @param  integer $options json 参数
>>>>>>> main
     * @return string
     */
    public function toJson($options = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->toArray(), $options);
    }

<<<<<<< HEAD
=======
    /**
     * 将数据转换成字符串
     * @access public
     * @return string
     */
>>>>>>> main
    public function __toString()
    {
        return $this->toJson();
    }

    /**
<<<<<<< HEAD
     * 转换成数组
     *
     * @access public
     * @param  mixed $items
=======
     * 将数据转换成数组
     * @access protected
     * @param  mixed $items 数据
>>>>>>> main
     * @return array
     */
    protected function convertToArray($items)
    {
<<<<<<< HEAD
        if ($items instanceof self) {
            return $items->all();
        }

        return (array) $items;
=======
        return $items instanceof self ? $items->all() : (array) $items;
>>>>>>> main
    }
}
