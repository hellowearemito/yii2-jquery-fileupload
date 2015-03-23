<?php

namespace mito\jqueryupload\assets;

class CanvasToBlobAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/blueimp-canvas-to-blob';
    public $js = [
        'js/canvas-to-blob.min.js',
    ];
}
