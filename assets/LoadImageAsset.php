<?php

namespace mito\jqueryupload\assets;

class LoadImageAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/blueimp-load-image';
    public $js = [
        'js/load-image.all.min.js',
    ];
}
