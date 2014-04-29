<?php

namespace nkovacs\jqueryupload;

class PreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@nkovacs/jqueryupload/assets';
    public $js = [
        'js/load-image.min.js',
        'js/canvas-to-blob.min.js',
        'js/jquery.fileupload-process.js',
    ];
    public $depends = [
        'nkovacs\jqueryupload\FileuploadAsset',
    ];
}
