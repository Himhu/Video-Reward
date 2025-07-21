<?php

// +----------------------------------------------------------------------
// | 基础模型类 - 新架构版本
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建基础模型类 - 系统重构
// | 功能说明：提供动态表前缀、统一查询接口、数据验证等基础功能
// | 新特性：支持{prefix}动态表前缀、统一异常处理、查询优化
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\common\model;

use think\facade\Config;
use think\facade\Db;
use think\Model;
use think\Exception;

/**
 * 基础模型类
 * 
 * 提供动态表前缀支持、统一的查询接口和数据验证功能
 * 所有业务模型都应继承此类以获得统一的基础功能
 * 
 * @package app\common\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class BaseModel extends TimeModel
{
    /**
     * 数据表前缀
     * 支持动态配置，从数据库配置中读取
     * @var string
     */
    protected $prefix = '';

    /**
     * 是否自动获取表前缀
     * @var bool
     */
    protected $autoPrefix = true;

    /**
     * 模型初始化
     * 自动设置表前缀和基础配置
     */
    protected static function init()
    {
        // 初始化动态表前缀配置
        self::initTablePrefix();
    }

    /**
     * 模型构造函数
     * 
     * @param array $data 初始数据
     */
    public function __construct(array $data = [])
    {
        // 设置动态表前缀
        if ($this->autoPrefix) {
            $this->setDynamicPrefix();
        }
        
        parent::__construct($data);
    }

    /**
     * 设置动态表前缀
     * 从数据库配置中读取前缀设置
     */
    protected function setDynamicPrefix()
    {
        try {
            // 从数据库配置中获取表前缀
            $dbConfig = Config::get('database.connections.mysql');
            $prefix = $dbConfig['prefix'] ?? '';
            
            // 如果配置了前缀，则使用配置的前缀
            if (!empty($prefix)) {
                $this->prefix = $prefix;
            }
            
            // 设置表名（如果未手动设置）
            if (empty($this->table) && !empty($this->name)) {
                $this->table = $this->prefix . $this->name;
            }
        } catch (Exception $e) {
            // 如果获取配置失败，使用默认前缀
            $this->prefix = 'ds_';
        }
    }

    /**
     * 静态方法初始化表前缀
     * 用于模型初始化时设置全局前缀配置
     */
    protected static function initTablePrefix()
    {
        try {
            $dbConfig = Config::get('database.connections.mysql');
            $prefix = $dbConfig['prefix'] ?? 'ds_';

            // 这里可以添加全局前缀设置逻辑
        } catch (Exception $e) {
            // 配置获取失败时的处理
        }
    }

    /**
     * 获取完整的表名
     * 
     * @return string 完整表名
     */
    public function getFullTableName()
    {
        return $this->prefix . $this->name;
    }

    /**
     * 获取表前缀
     * 
     * @return string 表前缀
     */
    public function getTablePrefix()
    {
        return $this->prefix;
    }

    /**
     * 设置动态表前缀
     *
     * @param string $prefix 表前缀
     * @return $this
     */
    public function setDynamicTablePrefix($prefix)
    {
        $this->prefix = $prefix;

        // 更新表名
        if (!empty($this->name)) {
            $this->table = $this->prefix . $this->name;
        }

        return $this;
    }

    /**
     * 安全的数据保存
     * 包含数据验证和异常处理
     * 
     * @param array $data 要保存的数据
     * @param array $allowField 允许的字段
     * @return bool 保存结果
     * @throws Exception
     */
    public function safeSave(array $data = [], array $allowField = [])
    {
        try {
            // 设置允许的字段
            if (!empty($allowField)) {
                $this->allowField($allowField);
            }
            
            // 数据验证（子类可重写此方法）
            $this->validateData($data);
            
            // 保存数据
            return $this->save($data);
            
        } catch (Exception $e) {
            // 记录错误日志
            $this->logError('数据保存失败', $e->getMessage(), $data);
            throw $e;
        }
    }

    /**
     * 数据验证方法
     * 子类可重写此方法实现自定义验证
     * 
     * @param array $data 要验证的数据
     * @return bool 验证结果
     * @throws Exception
     */
    protected function validateData(array $data)
    {
        // 基础验证逻辑
        // 子类可重写此方法添加具体的验证规则
        return true;
    }

    /**
     * 记录错误日志
     * 
     * @param string $title 错误标题
     * @param string $message 错误信息
     * @param array $data 相关数据
     */
    protected function logError($title, $message, $data = [])
    {
        try {
            // 构建日志信息
            $logData = [
                'model' => static::class,
                'table' => $this->getFullTableName(),
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'time' => date('Y-m-d H:i:s'),
            ];
            
            // 写入日志（这里可以根据需要选择日志方式）
            trace($logData, 'error');
            
        } catch (Exception $e) {
            // 日志记录失败时的处理
        }
    }

    /**
     * 获取分页数据
     * 统一的分页查询方法
     * 
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @return array 分页数据
     */
    public function getPageList($where = [], $page = 1, $limit = 15, $order = 'id desc')
    {
        try {
            $query = $this->where($where);
            
            // 添加排序
            if (!empty($order)) {
                $query->order($order);
            }
            
            // 执行分页查询
            $result = $query->paginate([
                'list_rows' => $limit,
                'page' => $page,
            ]);
            
            return [
                'data' => $result->items(),
                'total' => $result->total(),
                'page' => $page,
                'limit' => $limit,
                'pages' => $result->lastPage(),
            ];
            
        } catch (Exception $e) {
            $this->logError('分页查询失败', $e->getMessage(), $where);
            throw $e;
        }
    }

    /**
     * 批量插入数据
     * 
     * @param array $dataList 数据列表
     * @param bool $replace 是否使用replace模式
     * @return int 插入的记录数
     * @throws Exception
     */
    public function batchInsert(array $dataList, $replace = false)
    {
        if (empty($dataList)) {
            return 0;
        }
        
        try {
            $tableName = $this->getFullTableName();
            
            if ($replace) {
                return Db::name($this->name)->insertAll($dataList, true);
            } else {
                return Db::name($this->name)->insertAll($dataList);
            }
            
        } catch (Exception $e) {
            $this->logError('批量插入失败', $e->getMessage(), $dataList);
            throw $e;
        }
    }

    /**
     * 获取字段列表
     * 
     * @return array 字段列表
     */
    public function getTableFields()
    {
        try {
            return Db::getFields($this->getFullTableName());
        } catch (Exception $e) {
            $this->logError('获取字段列表失败', $e->getMessage());
            return [];
        }
    }

    /**
     * 检查表是否存在
     * 
     * @return bool 表是否存在
     */
    public function tableExists()
    {
        try {
            $tables = Db::getTables();
            return in_array($this->getFullTableName(), $tables);
        } catch (Exception $e) {
            $this->logError('检查表存在性失败', $e->getMessage());
            return false;
        }
    }
}
