<?php
namespace Qsnh\think\Upload\Driver;

abstract class UploadDriverInterface
{

	/**
	 * 错误信息
	 * @var string
	 */
	protected $error = '';

	/**
	 * 上传方法
	 * @param SplFileInfo $file
	 * @return string 文件的绝对地址
	 */
	public abstract function upload(\SplFileInfo $file);

	/**
   	 * 设置错误信息
   	 * @param string $error 错误信息
	 */
	public function setError($error)
	{
		$this->error = $error;
	}

	/**
     * 获取当前错误信息
     * @return string 错误信息
	 */
	public function getError()
	{
		return $this->error;
	}

}