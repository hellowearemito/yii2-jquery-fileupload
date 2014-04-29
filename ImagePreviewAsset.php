<?php

namespace nkovacs\jqueryupload;

class ImagePreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@nkovacs/jqueryupload/assets';
    public $js = [
        'js/jquery.fileupload-image.js',
    ];
    public $depends = [
        'nkovacs\jqueryupload\PreviewAsset',
    ];
}
