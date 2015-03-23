<?php

namespace mito\jqueryupload;

class AudioPreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';
    public $js = [
        'js/jquery.fileupload-audio.js',
    ];
    public $depends = [
        'mito\jqueryupload\PreviewAsset',
    ];
}
