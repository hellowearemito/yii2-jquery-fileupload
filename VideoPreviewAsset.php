<?php

namespace mito\jqueryupload;

class VideoPreviewAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@mito/jqueryupload/assets';
    public $js = [
        'js/jquery.fileupload-video.js',
    ];
    public $depends = [
        'mito\jqueryupload\PreviewAsset',
    ];
}
