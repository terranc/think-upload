<?php
namespace Qsnh\think\Upload\Driver;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiniuUpload extends UploadDriverInterface
{

	protected $token = '';

	public function __construct($config)
	{
		$auth = new Auth($config['access_key'], $config['secret_key']);

		$this->token = $auth->uploadToken($config['bucket']);
	}

	public function upload(SplFileInfo $file)
	{
		$filename = $file->getFilename();

		$filepath = $file->getPath() . DIRECTORY_SEPARATOR . $filename;

		$uploadMgr = new UploadManager();

	    list($ret, $err) = $uploadMgr->putFile($this->token, $filename, $filepath);

	    if ($err !== null) {
	    	$this->setError($err);

	    	return false;
	    }

	    return $ret;
	}

}