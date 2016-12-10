<?php
namespace Qsnh\think\Upload\Driver;

use OSS\OssClient;
use OSS\Core\OssException;

class AliyunUpload extends UploadDriverInterface
{

	protected $app = null;

	protected $bucket = '';

	protected $remote_url = '';

	public function __construct($config)
	{
		$this->app = new OssClient(
			$config['access_key_id'],
			$config['access_key_secret'],
			$config['oss_server']
		);

		$this->bucket = $config['bucket'];

		$this->remote_url = $config['remote_url'];

		if (substr($this->remote_url, -1, 1) != '/') {
			$this->remote_url .= '/';
		}
	}

	public function upload(\SplFileInfo $file)
	{
		$filename = $file->getFilename();

		$filepath = $file->getPath() . DIRECTORY_SEPARATOR . $filename;

		try{
        	$result = $this->app->uploadFile($this->bucket, $filename, $filepath);
	    } catch(OssException $e) {
	        $this->setError($e->getMessage());

	        return false;
	    }

	    @unlink($file->getPathname());

		return $this->remote_url . $filename;
	}

}