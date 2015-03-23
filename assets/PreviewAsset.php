<?php

namespace mito\jqueryupload\assets;

class PreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';
    public $js = [
        'js/jquery.fileupload-process.js',
    ];
    public $depends = [
        'mito\jqueryupload\assets\LoadImageAsset',
        'mito\jqueryupload\assets\CanvasToBlobAsset',
        'mito\jqueryupload\assets\FileuploadAsset',
    ];
}
