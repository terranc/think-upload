<?php
namespace Qsnh\think\Upload\Driver;

use JohnLui\AliyunOSS\AliyunOSS;

class AliyunUpload extends UploadDriverInterface
{

	protected $app = null;

	public function __construct($config)
	{
		$this->app = AliyunOSS::boot(
			$config['oss_server'],
			$config['access_key_id'],,
			$config['access_key_secret']
		);
	}

	public function upload(SplFileInfo $file)
	{
		$filename = $file->getFilename();

		$filepath = $file->getPath() . DIRECTORY_SEPARATOR . $filename;

		$result = $this->app->upload($filename, $filepath);

		return $result;
	}

}