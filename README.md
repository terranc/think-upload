Thinkphp5 FileUplaod Library.
======

Thinkphp5 has only local file upload feature,But many projects require use Qiniu or Aliyun cloud storage.So i wrote a library.

### First
1.Create `upload.php` file on the `application/extra` directory.And copy the following code:
~~~
<?php
return [
    /** default,qiniu,aliyun */
    'driver' => 'default', 
    /** limit file size,unit:byte,if the value equal 0 the file size will not restricted.  */
    'size' => 1024*1024,
    /** allow file extension.if is empty the file extension has no limit. */
    'ext' => [],
    /** allow file type by file mime.if is empty the file type has no limit. */
    'type' => [],
    'default' => [
        /** file access url */
        'remote_url' => 'http://xx.com/public/',
    ],
    'qiniu' => [
        'access_key' => '',
        'secret_key' => '',
        'bucket' => '',
    ],
    'aliyun' => [
        /** Internal net url or External net url */
        'oss_server' => '', 
        'access_key_id' => '',
        'access_key_secret' => '',
    ],
];
~~~
