<?php

namespace mito\jqueryupload;

class FileuploadAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@mito/jqueryupload/assets';
    public $js = [
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
        'js/ajaxupload.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
    ];
}
