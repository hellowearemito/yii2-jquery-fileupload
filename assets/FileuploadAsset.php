<?php

namespace mito\jqueryupload\assets;

class FileuploadAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@mito/jqueryupload/assets/ajaxupload';
    public $js = [
        'js/ajaxupload.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'mito\jqueryupload\assets\FileuploadBaseAsset',
    ];
}
