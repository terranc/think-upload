<?php
namespace Qsnh\think\Upload\Driver;

use JohnLui\AliyunOSS\AliyunOSS;
use Aliyun\OSS\Exceptions\OssException;

class AliyunUpload extends UploadDriverInterface
{

	protected $app = null;

	public function __construct($config)
	{
		$this->app = AliyunOSS::boot(
			$config['oss_server'],
			$config['access_key_id'],
			$config['access_key_secret']
		);

		$this->app->setBucket($config['bucket']);
	}

	public function upload(\SplFileInfo $file)
	{
		$filename = $file->getFilename();

		$filepath = $file->getPath() . DIRECTORY_SEPARATOR . $filename;

		try{
        	$result = $this->app->uploadFile($filename, $filepath);
	    } catch(OssException $e) {
	        $this->setError($e->getMessage());

	        return false;
	    }

		return $this->app->getUrl($filename, new \DateTime("+1 day"));
	}

}