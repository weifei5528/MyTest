<?php
namespace app\common\service;
use GuzzleHttp\Client;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27
 * Time: 21:42
 */
abstract class Base
{
    protected $token;
    protected $client;
    protected $pageSize=10;
    protected $httpsOption = false;
    public function __construct()
    {
        $this->client = new Client(['verify' => $this->httpsOption]);

    }

    /**
     * 获取授权的token
     * @return mixed
     *
     */
    public abstract function auth();

    /**
     * 获取公共的图片 不需要授权
     * @return mixed
     */
    public abstract function getPublicImageList($page=1, $params=[]);

    /**
     * 获取
     */
    public abstract function getMyImageList();
    /**
     * 设置每页的数量
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }
    /**
     * 设置 https验证
     */
    public function setHttpsOption($default = false)
    {
        $this->client->setDefaultOption('verify', $default);
    }
    /**
     * 下载添加图片
     */
    abstract public function downThumbFinger($from, $data=[]);
}