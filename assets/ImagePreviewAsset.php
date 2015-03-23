<?php

namespace mito\jqueryupload\assets;

class ImagePreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';
    public $js = [
        'js/jquery.fileupload-image.js',
    ];
    public $depends = [
        'mito\jqueryupload\assets\PreviewAsset',
    ];
}
