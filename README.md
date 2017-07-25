Thinkphp5 FileUplaod Library.
======

Thinkphp5 has only local file upload feature,But many projects require use Qiniu or Aliyun cloud storage.So i wrote a library.

### First
Create `upload.php` file on the `application/extra` directory.And copy the following code:
~~~
return [
    'driver' => 'aliyun',
    /** limit file size,unit:byte,if the value equal 0 the file size will not restricted.  */
    'size' => 1024 * 1024,
    /** allow file extension.if is empty the file extension has no limit. */
    'ext'  => [],
    /** allow file type by file mime.if is empty the file type has no limit. */
    'type' => [],
    'path' => './images/',
    'default' => [
        /** access url */
        'remote_url' => 'http://xx.com/public/',
    ],
    'qiniu' => [
        'access_key' => '',
        'secret_key' => '',
        'bucket' => '',
        /** access url */
        'remote_url' => '',
    ],
    'aliyun' => [
        /** Internal net url or External net url */
        'oss_server' => '',
        'access_key_id' => '',
        'access_key_secret' => '',
        'bucket' => '',
        /** access url */
        'remote_url' => '',
    ],
];
~~~

Please open bucket read authority if you use aliyun driver.because bucket read authority was closed,the server will return AccessDeined.

### Usage
~~~
<?php
use Qsnh\think\Upload\Upload;

$upload = new Upload(config('upload'));

// $result = $upload->upload('avatar', '123.jpg');
$result = $upload->upload('avatar'); // first parameter is folder path; second parameter is custom filename(default: null) 

if (!$result) {
    $this->error($upload->getErrors());
}

halt($result);

~~~

The upload() method has an optional parammeters.It default value is `file`.And it equal the name of file component.


