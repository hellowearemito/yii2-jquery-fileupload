<?php

namespace nkovacs\jqueryupload;

class AudioPreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@nkovacs/jqueryupload/assets';
    public $js = [
        'js/jquery.fileupload-audio.js',
    ];
    public $depends = [
        'nkovacs\jqueryupload\FileuploadAsset',
    ];
}
