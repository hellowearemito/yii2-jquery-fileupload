<?php

namespace mito\jqueryupload;

class PreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@mito/jqueryupload/assets';
    public $js = [
        'js/load-image.min.js',
        'js/canvas-to-blob.min.js',
        'js/jquery.fileupload-process.js',
    ];
    public $depends = [
        'mito\jqueryupload\FileuploadAsset',
    ];
}
