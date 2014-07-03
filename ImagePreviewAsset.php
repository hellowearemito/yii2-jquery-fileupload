<?php

namespace mito\jqueryupload;

class ImagePreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@mito/jqueryupload/assets';
    public $js = [
        'js/jquery.fileupload-image.js',
    ];
    public $depends = [
        'mito\jqueryupload\PreviewAsset',
    ];
}
