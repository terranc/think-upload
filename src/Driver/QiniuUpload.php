<?php
namespace Qsnh\think\Upload\Driver;

use Qiniu\Qiniu;

class QiniuUpload
{

	protected $app = null;

	public function __construct($config)
	{
		$this->app = Qiniu::create([
			'access_key' => $config['access_key'],
		    'secret_key' => $config['secret_key'],
		    'bucket'     => $config['bucket'],
		]);
	}

	public function upload(SplFileInfo $file)
	{
		$filename = $file->getFilename();

		$filepath = $file->getPath() . DIRECTORY_SEPARATOR . $filename;

		$result = $this->app->uploadFile($filepath, $filename);

		return $result;
	}

}