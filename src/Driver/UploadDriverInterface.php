<?php
namespace Qsnh\think\Upload\Driver;

interface UploadDriverInterface
{

	/**
	 * 上传方法
	 * @param SplFileInfo $file
	 * @return string 文件的绝对地址
	 */
	public function upload(SplFileInfo $file);

}