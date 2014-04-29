<?php

namespace nkovacs\jqueryupload;

class VideoPreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@nkovacs/jqueryupload/assets';
    public $js = [
        'js/jquery.fileupload-video.js',
    ];
    public $depends = [
        'nkovacs\jqueryupload\PreviewAsset',
    ];
}
