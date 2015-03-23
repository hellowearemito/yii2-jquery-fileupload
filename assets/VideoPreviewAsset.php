<?php

namespace mito\jqueryupload\assets;

class VideoPreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';
    public $js = [
        'js/jquery.fileupload-video.js',
    ];
    public $depends = [
        'mito\jqueryupload\assets\PreviewAsset',
    ];
}
