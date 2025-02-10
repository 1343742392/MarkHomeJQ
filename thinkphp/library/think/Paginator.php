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
use Traversable;

abstract class Paginator implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
<<<<<<< HEAD
    /**
     * 是否简洁模式
     * @var bool
     */
    protected $simple = false;

    /**
     * 数据集
     * @var Collection
     */
    protected $items;

    /**
     * 当前页
     * @var integer
     */
    protected $currentPage;

    /**
     * 最后一页
     * @var integer
     */
    protected $lastPage;

    /**
     * 数据总数
     * @var integer|null
     */
    protected $total;

    /**
     * 每页数量
     * @var integer
     */
    protected $listRows;

    /**
     * 是否有下一页
     * @var bool
     */
    protected $hasMore;

    /**
     * 分页配置
     * @var array
     */
=======
    /** @var bool 是否为简洁模式 */
    protected $simple = false;

    /** @var Collection 数据集 */
    protected $items;

    /** @var integer 当前页 */
    protected $currentPage;

    /** @var  integer 最后一页 */
    protected $lastPage;

    /** @var integer|null 数据总数 */
    protected $total;

    /** @var  integer 每页的数量 */
    protected $listRows;

    /** @var bool 是否有下一页 */
    protected $hasMore;

    /** @var array 一些配置 */
>>>>>>> main
    protected $options = [
        'var_page' => 'page',
        'path'     => '/',
        'query'    => [],
        'fragment' => '',
    ];

<<<<<<< HEAD
=======
    /** @var mixed simple模式下的下个元素 */
    protected $nextItem;

>>>>>>> main
    public function __construct($items, $listRows, $currentPage = null, $total = null, $simple = false, $options = [])
    {
        $this->options = array_merge($this->options, $options);

        $this->options['path'] = '/' != $this->options['path'] ? rtrim($this->options['path'], '/') : $this->options['path'];

        $this->simple   = $simple;
        $this->listRows = $listRows;

        if (!$items instanceof Collection) {
            $items = Collection::make($items);
        }

        if ($simple) {
            $this->currentPage = $this->setCurrentPage($currentPage);
            $this->hasMore     = count($items) > ($this->listRows);
<<<<<<< HEAD
            $items             = $items->slice(0, $this->listRows);
=======
            if ($this->hasMore) {
                $this->nextItem = $items->slice($this->listRows, 1);
            }
            $items = $items->slice(0, $this->listRows);
>>>>>>> main
        } else {
            $this->total       = $total;
            $this->lastPage    = (int) ceil($total / $listRows);
            $this->currentPage = $this->setCurrentPage($currentPage);
            $this->hasMore     = $this->currentPage < $this->lastPage;
        }
        $this->items = $items;
    }

    /**
<<<<<<< HEAD
     * @access public
     * @param       $items
     * @param       $listRows
     * @param null  $currentPage
     * @param null  $total
     * @param bool  $simple
=======
     * @param       $items
     * @param       $listRows
     * @param null  $currentPage
     * @param bool  $simple
     * @param null  $total
>>>>>>> main
     * @param array $options
     * @return Paginator
     */
    public static function make($items, $listRows, $currentPage = null, $total = null, $simple = false, $options = [])
    {
        return new static($items, $listRows, $currentPage, $total, $simple, $options);
    }

    protected function setCurrentPage($currentPage)
    {
        if (!$this->simple && $currentPage > $this->lastPage) {
            return $this->lastPage > 0 ? $this->lastPage : 1;
        }

        return $currentPage;
    }

    /**
     * 获取页码对应的链接
     *
<<<<<<< HEAD
     * @access protected
     * @param  $page
=======
     * @param $page
>>>>>>> main
     * @return string
     */
    protected function url($page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        if (strpos($this->options['path'], '[PAGE]') === false) {
            $parameters = [$this->options['var_page'] => $page];
            $path       = $this->options['path'];
        } else {
            $parameters = [];
            $path       = str_replace('[PAGE]', $page, $this->options['path']);
        }
<<<<<<< HEAD

        if (count($this->options['query']) > 0) {
            $parameters = array_merge($this->options['query'], $parameters);
        }

=======
        if (count($this->options['query']) > 0) {
            $parameters = array_merge($this->options['query'], $parameters);
        }
>>>>>>> main
        $url = $path;
        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters, null, '&');
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $url . $this->buildFragment();
    }

    /**
     * 自动获取当前页码
<<<<<<< HEAD
     * @access public
     * @param  string $varPage
     * @param  int    $default
=======
     * @param string $varPage
     * @param int    $default
>>>>>>> main
     * @return int
     */
    public static function getCurrentPage($varPage = 'page', $default = 1)
    {
<<<<<<< HEAD
        $page = Container::get('request')->param($varPage);

        if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
=======
        $page = (int) Request::instance()->param($varPage);

        if (filter_var($page, FILTER_VALIDATE_INT) !== false && $page >= 1) {
>>>>>>> main
            return $page;
        }

        return $default;
    }

    /**
     * 自动获取当前的path
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @return string
     */
    public static function getCurrentPath()
    {
<<<<<<< HEAD
        return Container::get('request')->baseUrl();
=======
        return Request::instance()->baseUrl();
>>>>>>> main
    }

    public function total()
    {
        if ($this->simple) {
            throw new \DomainException('not support total');
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this->total;
    }

    public function listRows()
    {
        return $this->listRows;
    }

    public function currentPage()
    {
        return $this->currentPage;
    }

    public function lastPage()
    {
        if ($this->simple) {
            throw new \DomainException('not support last');
        }
<<<<<<< HEAD

=======
>>>>>>> main
        return $this->lastPage;
    }

    /**
     * 数据是否足够分页
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @return boolean
     */
    public function hasPages()
    {
        return !(1 == $this->currentPage && !$this->hasMore);
    }

    /**
     * 创建一组分页链接
     *
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @param  int $start
     * @param  int $end
     * @return array
     */
    public function getUrlRange($start, $end)
    {
        $urls = [];

        for ($page = $start; $page <= $end; $page++) {
            $urls[$page] = $this->url($page);
        }

        return $urls;
    }

    /**
     * 设置URL锚点
     *
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @param  string|null $fragment
     * @return $this
     */
    public function fragment($fragment)
    {
        $this->options['fragment'] = $fragment;
<<<<<<< HEAD

=======
>>>>>>> main
        return $this;
    }

    /**
     * 添加URL参数
     *
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @param  array|string $key
     * @param  string|null  $value
     * @return $this
     */
    public function appends($key, $value = null)
    {
        if (!is_array($key)) {
            $queries = [$key => $value];
        } else {
            $queries = $key;
        }

        foreach ($queries as $k => $v) {
            if ($k !== $this->options['var_page']) {
                $this->options['query'][$k] = $v;
            }
        }

        return $this;
    }

    /**
     * 构造锚点字符串
     *
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @return string
     */
    protected function buildFragment()
    {
        return $this->options['fragment'] ? '#' . $this->options['fragment'] : '';
    }

    /**
     * 渲染分页html
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @return mixed
     */
    abstract public function render();

    public function items()
    {
        return $this->items->all();
    }

    public function getCollection()
    {
        return $this->items;
    }

    public function isEmpty()
    {
        return $this->items->isEmpty();
    }

    /**
     * 给每个元素执行个回调
     *
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @param  callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            $result = $callback($item, $key);
<<<<<<< HEAD

=======
>>>>>>> main
            if (false === $result) {
                break;
            } elseif (!is_object($item)) {
                $this->items[$key] = $result;
            }
        }

        return $this;
    }

    /**
     * Retrieve an external iterator
<<<<<<< HEAD
     * @access public
=======
>>>>>>> main
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items->all());
    }

    /**
     * Whether a offset exists
<<<<<<< HEAD
     * @access public
     * @param  mixed $offset
=======
     * @param mixed $offset
>>>>>>> main
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->items->offsetExists($offset);
    }

    /**
     * Offset to retrieve
<<<<<<< HEAD
     * @access public
     * @param  mixed $offset
=======
     * @param mixed $offset
>>>>>>> main
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items->offsetGet($offset);
    }

    /**
     * Offset to set
<<<<<<< HEAD
     * @access public
     * @param  mixed $offset
     * @param  mixed $value
=======
     * @param mixed $offset
     * @param mixed $value
>>>>>>> main
     */
    public function offsetSet($offset, $value)
    {
        $this->items->offsetSet($offset, $value);
    }

    /**
     * Offset to unset
<<<<<<< HEAD
     * @access public
     * @param  mixed $offset
     * @return void
     * @since  5.0.0
=======
     * @param mixed $offset
     * @return void
     * @since 5.0.0
>>>>>>> main
     */
    public function offsetUnset($offset)
    {
        $this->items->offsetUnset($offset);
    }

    /**
     * Count elements of an object
     */
    public function count()
    {
        return $this->items->count();
    }

    public function __toString()
    {
        return (string) $this->render();
    }

    public function toArray()
    {
<<<<<<< HEAD
        try {
            $total = $this->total();
        } catch (\DomainException $e) {
            $total = null;
        }

        return [
            'total'        => $total,
            'per_page'     => $this->listRows(),
            'current_page' => $this->currentPage(),
            'last_page'    => $this->lastPage,
            'data'         => $this->items->toArray(),
        ];
=======
        if ($this->simple) {
            return [
                'per_page'     => $this->listRows,
                'current_page' => $this->currentPage,
                'has_more'     => $this->hasMore,
                'next_item'    => $this->nextItem,
                'data'         => $this->items->toArray(),
            ];
        } else {
            return [
                'total'        => $this->total,
                'per_page'     => $this->listRows,
                'current_page' => $this->currentPage,
                'last_page'    => $this->lastPage,
                'data'         => $this->items->toArray(),
            ];
        }

>>>>>>> main
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function __call($name, $arguments)
    {
        $collection = $this->getCollection();

        $result = call_user_func_array([$collection, $name], $arguments);

        if ($result === $collection) {
            return $this;
        }

        return $result;
    }

}
