<?php

namespace mito\jqueryupload;

class PreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';
    public $js = [
        'js/jquery.fileupload-process.js',
    ];
    public $depends = [
        'mito\jqueryupload\LoadImageAsset',
        'mito\jqueryupload\CanvasToBlobAsset',
        'mito\jqueryupload\FileuploadAsset',
    ];
}
