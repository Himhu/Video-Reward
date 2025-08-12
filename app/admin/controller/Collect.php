<?php

namespace app\admin\controller;

use app\admin\model\Stock;
use app\admin\model\Category;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\facade\Log;

/**
 * @ControllerAnnotation(title="采集管理")
 */
class Collect extends AdminController
{
    
    /**
     * @NodeAnotation(title="采集管理")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $page = $this->request->param('page', 1);
            $limit = $this->request->param('limit', 15);
            
            // 获取最近采集的数据
            $list = Stock::with(['category'])
                ->order('create_time desc')
                ->paginate([
                    'list_rows' => $limit,
                    'page' => $page,
                ]);
            
            $result = [
                'code' => 0,
                'msg' => '',
                'count' => $list->total(),
                'data' => $list->items()
            ];
            
            return json($result);
        }
        
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="采集配置")
     */
    public function config()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            
            // 保存采集配置到配置文件或数据库
            $config = [
                'api_url' => $data['api_url'] ?? '',
                'api_key' => $data['api_key'] ?? '',
                'page_limit' => $data['page_limit'] ?? 50,
                'auto_save' => $data['auto_save'] ?? 0,
                'default_category' => $data['default_category'] ?? 0,
            ];
            
            // 这里可以保存到配置文件或数据库
            // 暂时返回成功
            return $this->success('配置保存成功');
        }
        
        // 获取分类列表
        $categories = Category::where('status', 1)->select();
        $this->assign('categories', $categories);
        
        return $this->fetch();
    }
    
    /**
     * @NodeAnotation(title="执行采集")
     */
    public function execute()
    {
        if ($this->request->isPost()) {
            $page = $this->request->param('page', 1);
            $limit = $this->request->param('limit', 10);
            $auto_save = $this->request->param('auto_save', 0);
            
            try {
                // 调用采集方法
                $result = $this->collectData($page, $limit, $auto_save);
                
                if ($result['success']) {
                    return $this->success('采集成功', '', $result['data']);
                } else {
                    return $this->error($result['message']);
                }
                
            } catch (\Exception $e) {
                Log::error('采集失败: ' . $e->getMessage());
                return $this->error('采集失败: ' . $e->getMessage());
            }
        }
        
        return $this->fetch();
    }
    
    /**
     * 采集数据方法
     */
    private function collectData($page = 1, $limit = 10, $autoSave = 0)
    {
        // API配置
        $apiUrl = 'http://czcz11.ltcdrc.cn/api/resource/getList';
        $apiKey = 'BFTWo000o9U3VRPeLkoo00o4nX0xxy7mqmFZ6BQWF3tvnRrez1pdtbNT6IjE4a2ZKwNqFvmeZNN9Mg3pqNYCATCshqWIOg';
        
        $url = $apiUrl . '?ldk=' . $apiKey . '&page=' . $page . '&limit=' . $limit . '&encode=1&cid=0&key=&payed=0';
        
        // 发起HTTP请求
        $response = $this->getCurl($url);
        
        if (empty($response)) {
            return ['success' => false, 'message' => 'API请求失败'];
        }
        
        $data = json_decode($response, true);
        
        if (!$data || $data['code'] != 1) {
            return ['success' => false, 'message' => 'API返回错误'];
        }
        
        // 解码数据
        $list = json_decode(base64_decode($data['data']['list']), true);
        
        if (empty($list)) {
            return ['success' => false, 'message' => '没有获取到数据'];
        }
        
        $collectData = [];
        $savedCount = 0;
        
        foreach ($list as $item) {
            $itemData = [
                'title' => $item['title'] ?? '',
                'video_url' => $item['video_url'] ?? '',
                'img' => $item['img'] ?? '',
                'category_name' => $item['sort']['name'] ?? '其他',
            ];
            
            $collectData[] = $itemData;
            
            // 如果启用自动保存
            if ($autoSave) {
                $cid = $this->mapCategory($itemData['category_name']);
                
                $stockData = [
                    'title' => $itemData['title'],
                    'url' => $itemData['video_url'],
                    'image' => $itemData['img'],
                    'cid' => $cid,
                    'is_dsp' => 0,
                    'create_time' => time(),
                ];
                
                // 检查是否已存在
                $exists = Stock::where('url', $stockData['url'])->find();
                if (!$exists) {
                    Stock::create($stockData);
                    $savedCount++;
                }
            }
        }
        
        return [
            'success' => true,
            'data' => [
                'list' => $collectData,
                'total' => count($collectData),
                'saved_count' => $savedCount,
                'page' => $page,
                'limit' => $limit
            ]
        ];
    }
    
    /**
     * 分类映射
     */
    private function mapCategory($categoryName)
    {
        $categoryMap = [
            "人妻" => 16,
            "国产" => 11,
            "日韩" => 14,
            "黑丝" => 22,
            "自拍" => 17,
            "乱伦" => 25,
            "强歼" => 12,
            "人兽" => 23,
            "高清" => 13,
            "国产熟女" => 16,
        ];
        
        return $categoryMap[$categoryName] ?? 24; // 默认分类
    }
    
    /**
     * HTTP请求方法
     */
    private function getCurl($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $ua = 0, $nobody = 0, $ip = 0, $split = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $httpheader[] = "Accept:*/*";
        $httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
        $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
        $httpheader[] = "Connection:close";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        
        if ($header) {
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
        }
        
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        
        if ($referer) {
            if ($referer == 1) {
                curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
            } else {
                curl_setopt($ch, CURLOPT_REFERER, $referer);
            }
        }
        
        if ($ip) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip, 'CLIENT-IP:' . $ip));
        }
        
        if ($ua) {
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        } else {
            curl_setopt($ch, CURLOPT_USERAGENT, 'Stream/1.0.3 (iPhone; iOS 12.4; Scale/2.00)');
        }
        
        if ($nobody) {
            curl_setopt($ch, CURLOPT_NOBODY, 1);
        }
        
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        
        if ($split) {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($ret, 0, $headerSize);
            $body = substr($ret, $headerSize);
            $ret = array();
            $ret['header'] = $header;
            $ret['body'] = $body;
        }
        
        curl_close($ch);
        return $ret;
    }
}
