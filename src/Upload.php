<?php
namespace Qsnh\think\Upload;

use Qsnh\think\Upload\Driver\UpyunUpload;
use think\File;
use think\Request;
use Qsnh\think\Upload\Driver\QiniuUpload;
use Qsnh\think\Upload\Driver\AliyunUpload;

class Upload
{

	/**
	 * 错误信息
	 * @var string
	 */
	protected $errors = '';

	/**
	 * 图片上传的配置信息
	 * @var array
	 */
	protected $config = [];

	/**
	 * SplFileInfo
	 * @var null
	 */
	public $file = null;

	/**
	 * Create Upload Instance
	 * @param array $config 配置
	 */
	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * 上传图片
	 * @param string $filed 上传的字段名
	 * @return mixed
	 */
	public function upload($prefix = '', $name = null, $filed = 'file')
	{
		$request = Request::instance();

		$file = $request->file($filed);

		if (is_null($file)) {
			$this->setError('请选择上传文件.');

			return false;
		}

		$checkData = [];
		/** 大小验证 */
		if ($this->config['size'] > 0) {
			$checkData['size'] = $this->config['size'];
		}
		/** 文件后缀验证 */
		if ($this->config['ext']) {
			$checkData['ext'] = $this->config['ext'];
		}
		if ($this->config['type']) {
			$checkData['type'] = $this->config['type'];
		}

		/** 验证 */
		if (!$file->validate($checkData)) {
			$this->setError($file->getError());

			return false;
		}

		/** 先上传到服务器 */
		if (!$this->byThinkUpload($name ?: true, $file)) {
			return false;
		}

		/** Dispatch */
		return $this->dispatch($prefix, $name, $this->file);
	}

	/**
	 * 通过TP自带的上传
	 * @param  think\File   $file 上传文件对象
	 * @return mixed       
	 */
	protected function byThinkUpload($name = true, File $file)
	{
		$result = $file->move($this->config['path'], $name);

		if (!$result) {
			
			$this->setError($file->getError());

			return false;
		}

		$this->file = $result;

		return true;
	}

	/**
	 * 上传分发
	 * @return string
	 */
	protected function dispatch($prefix = '', $name = null, \SplFileInfo $file)
	{
		switch ($this->config['driver']) {
            case 'qiniu':
                return $this->qiniuDriver($prefix, $name, $file);
                break;
            case 'upyun':
                return $this->upyunDriver($prefix, $name, $file);
                break;
			case 'aliyun':
				return $this->aliyunDriver($prefix, $name, $file);
				break;
			default:
				return $this->defaultDriver($prefix, $name, $file);
		}
	}

	/**
	 * 默认驱动
	 * @return mixed
	 */
	protected function defaultDriver(\SplFileInfo $file)
	{
		$url = $this->config['default']['remote_url'];

		if (substr($this->config['default']['remote_url'], -1, 1) != '/') {
			$url .= '/';
		}

		$pathname = $file->getPathname();
		if (substr($pathname, 0, 1) == '.') {
			$pathname = substr($pathname, 1);
		}
		if (substr($pathname, 0, 1) == '/') {
			$pathname = substr($pathname, 1);
		}

		$full = str_replace('\\', '/', $url . $pathname);

		return $full;
	}

	/**
	 * 七牛云上传
	 * @param  SplFileInfo $file 
	 * @return mixed
	 */
	protected function qiniuDriver($prefix = '', $name = null, \SplFileInfo $file)
	{
		$qiniu = new QiniuUpload($this->config['qiniu']);

		$result = $qiniu->upload($prefix, $name, $file);

		if (!$result) {
			$this->setError($qiniu->getError());

			return false;
		}

		return $result;
	}

    /**
     * 又拍云上传
     * @param  SplFileInfo $file
     * @return mixed
     */
    protected function upyunDriver($prefix = '', $name = null, \SplFileInfo $file)
    {
        $upyun = new UpyunUpload($this->config['upyun']);

        $result = $upyun->upload($prefix, $name, $file);

        if (!$result) {
            $this->setError($upyun->getError());

            return false;
        }

        return $result;
    }

	/**
	 * 阿里云上传驱动
	 * @param  SplFileInfo $file 
	 * @return mixed            
	 */
	protected function aliyunDriver($prefix = '', $name = null, \SplFileInfo $file)
	{
		$app = new AliyunUpload($this->config['aliyun']);

		$result = $app->upload($prefix, $name, $file);

		if (!$result) {
			$this->setError($app->getError());

			return false;
		}

		return $result;
	}

	/**
	 * 设置错误
	 * @param void $errors
	 */
	protected function setError($errors)
	{
		$this->errors = $errors;
	}

	/**
	 * 获取错误信息
	 * @return string 
	 */
	public function getError()
	{
		return $this->errors;
	}

}
