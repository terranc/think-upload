<?php
namespace Qsnh\think\Upload\Driver;

abstract UploadDriverInterface
{

	/**
	 * 错误信息
	 * @var string
	 */
	protected $err = '';

	/**
	 * 上传方法
	 * @param SplFileInfo $file
	 * @return string 文件的绝对地址
	 */
	public abstract function upload(SplFileInfo $file);

	public function setError($error)
	{
		$this->error = $error;
	}

	public function getError()
	{
		return $this->error;
	}

}