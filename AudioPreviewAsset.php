<?php

namespace mito\jqueryupload;

class AudioPreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@mito/jqueryupload/assets';
    public $js = [
        'js/jquery.fileupload-audio.js',
    ];
    public $depends = [
        'mito\jqueryupload\PreviewAsset',
    ];
}
