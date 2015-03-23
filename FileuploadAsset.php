<?php

namespace mito\jqueryupload;

class FileuploadAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@mito/jqueryupload/assets';
    public $js = [
        'js/ajaxupload.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'mito\jqueryupload\FileuploadBaseAsset',
    ];
}
