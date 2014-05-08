<?php

namespace nkovacs\jqueryupload;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\base\InvalidConfigException;

class UploadWidget extends \yii\widgets\InputWidget
{
    /**
     * @var array|string the upload URL. This parameter will be processed by [[\yii\helpers\Url::to()]].
     */
    public $uploadUrl = '';

    /**
     * @var integer chunk size in bytes, 0 to disable chunked uploads
     */
    public $chunkSize = 0;

    /**
     * @var boolean whether to allow uploading multiple images
     */
    public $multiple = false;

    /**
     * @var boolean register assets automatically
     */
    public $registerAssets = true;

    /**
     * @var array ui messages
     */
    public $strings = [
        'upload-label' => 'Upload',
        'delete-label' => "Delete",
        'cancel-label' => "Cancel",
        'retry-label' => "Retry",
        'upload-failed' => "Upload failed",
    ];

    /**
     * @var integer maximum number of images that can be uploaded
     * note: this can be circumvented by the user
     */
    public $maximum = false;

    /**
     * @var string selector for counting uploaded files
     */
    public $uploadedSelector = false;

    /**
     * @var array|boolean size (width,height,crop) of the image, false to disable
     */
    public $imageResize = false;
    /**
     * @var array|boolean size (width,height) of the preview, false to disable
     */
    public $preview = false;

    /**
     * @var boolean whether to immediately remove failed uploads
     */
    public $removeFailed = false;

    /**
     * @var boolean whether to clear existing uploads on selecting a file
     */
    public $clearOnUpload = false;

    /**
     * @var string|false id of the uploaded file container, false to create one
     * Note: this widget should precede the container
     */
    public $uploadsContainer = false;

    /**
     * @var array container options
     */
    public $containerOptions = [];

    /**
     * @var string|false selector for progress percentage
     */
    public $progress = false;
    /**
     * @var string|false selector for progressbar
     */
    public $progressbar = false;
    /**
     * @var string|false selector for progressbar container
     */
    public $progressContainer = false;
    /**
     * @var css property to modify for global progress bar
     */
    public $progressbarAllProperty = 'width';
    /**
     * @var css property to modify for file progress bar
     */
    public $progressbarProperty = 'width';
    /**
     * @var string|false selector for error message container
     */
    public $errorContainer = false;

    /**
     * @var string html template for uploaded file
     */
    public $fileTemplate = false;

    /**
     * @var array selectors for template items
     */
    public $templateSelectors = [
        'filename' => false,
        'preview' => false,
        'progress' => false,
        'progressbar' => false,
        'retry' => false,
        'cancel' => false,
        'delete' => false,
        'error' => false,
    ];

    /**
     * Register assets
     * @param array $options which optional assets to register:
     *     imagePreview
     *     videoPreview
     *     audioPreview
     */
    public function registerAssets($options)
    {
        $options = array_merge([
            'imagePreview' => false,
            'videoPreview' => false,
            'audioPreview' => false,
        ], $options);

        FileuploadAsset::register($this->getView());
        if ($options['audioPreview']) {
            AudioPreviewAsset::register($this->getView());
        }
        if ($options['imagePreview']) {
            ImagePreviewAsset::register($this->getView());
        }
        if ($options['videoPreview']) {
            VideoPreviewAsset::register($this->getView());
        }
    }

    /**
     * Returns the options for the upload JS plugin.
     * @return array the options
     */
    protected function getClientOptions()
    {
        $options = [
            'uploadUrl' => Url::to($this->uploadUrl),
            'chunkSize' => $this->chunkSize,
            'strings' => $this->strings,
            'progressbar' => $this->progressbar,
            'progress' => $this->progress,
            'progressContainer' => $this->progressContainer,
            'errorContainer' => $this->errorContainer,
            'fileTemplate' => $this->fileTemplate,
            'templateSelectors' => $this->templateSelectors,
            'progressbarAllProperty' => $this->progressbarAllProperty,
            'progressbarProperty' => $this->progressbarProperty,
            'maximum' => $this->maximum,
            'uploadedSelector' => $this->uploadedSelector,
            'removeFailed' => $this->removeFailed,
            'clearOnUpload' => $this->clearOnUpload,
        ];

        $clientSideResize = '/Android(?!.*Chrome)|Opera/.test(window.navigator && navigator.userAgent)';

        if ($this->preview !== false) {
            $options['previewResize'] = true;
            $options['previewWidth'] = $this->preview[0];
            $options['previewHeight'] = $this->preview[1];
        }

        if ($this->imageResize !== false) {
            $options['imageResize'] = true;
            $options['imageMaxWidth'] = $this->imageResize[0];
            $options['imageMaxHeight'] = $this->imageResize[1];
            $options['imageCrop'] = isset($this->imageResize[2]) ? $this->imageResize[2] : false;
        }

        return $options;
    }

    /**
     * adds [] to $name if multiple is true
     * @param string $name
     * @return string
     */
    protected function multipleName($name)
    {
        if (!$this->multiple) {
            return $name;
        }
        if (substr($name, -2) === '[]') {
            return $name;
        }
        return $name . '[]';
    }

    /**
     * remove [] from $name
     * @param string $name
     * @return string
     */
    protected function singleName($name)
    {
        if (substr($name, -2) === '[]') {
            return substr($name, 0, -2);
        }
        return $name;
    }

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * Renders the widget
     * @return string rendering result
     * @throws InvalidConfigException
     */
    public function run()
    {
        if ($this->registerAssets) {
            $options = [];
            if ($this->preview !== false) {
                $options['imagePreview'] = true;
            }
            self::registerAssets($options);
        }

        $id = $this->options['id'];

        if ($this->multiple) {
            $this->options['multiple'] = true;
        } else {
            $this->maximum = 1;
            $this->clearOnUpload = true;
        }

        if ($this->hasModel()) {
            $attributeName = $this->multipleName($this->attribute);
            if ($this->multiple) {
                $input = Html::activeHiddenInput($this->model, $this->singleName($this->attribute), ['id' => null, 'value' => ''])
                         . Html::activeInput('file', $this->model, $attributeName, $this->options);
            } else {
                $input = Html::activeFileInput($this->model, $attributeName, $this->options);
            }
            $inputName = Html::getInputName($this->model, $attributeName);
        } else {
            $inputName = $this->name;
            $input = Html::fileInput($inputName, $this->value, $this->options);
        }

        $view = $this->getView();

        $output = '';

        if ($this->uploadsContainer === false) {

            $divOptions = $this->containerOptions;

            if (!isset($divOptions['id'])) {
                $divOptions['id'] = 'au_' . $id;
            }

            if (!isset($divOptions['class'])) {
                $divOptions['class'] = 'row';
            }

            $divId = $divOptions['id'];

            $output .= Html::beginTag('button', ['class' => 'btn btn-primary au-upload-button']);
            $output .= Html::tag('span', $this->strings['upload-label'], []);
            $output .= $input;
            $output .= Html::endTag('button');

            $output .= Html::beginTag('div', $divOptions);

            $output .= Html::endTag('div');

            $view->registerCss(
                ".au-upload-button {
                    position: relative;
                    overflow: hidden;
                }
                .au-upload-button input {
                    position: absolute;
                    top: 0;
                    right: 0;
                    margin: 0;
                    opacity: 0;
                    -ms-filter: 'alpha(opacity=0)';
                    font-size: 200px;
                    direction: ltr;
                    cursor: pointer;
                }",
                [],
                'au-bootstrap-file-input'
            );
        } else {
            $divId = $this->uploadsContainer;
            $output .= $input;
        }

        if ($this->fileTemplate === false) {
            $this->fileTemplate = <<<EOT
<div class="img-thumbnail au-thumbnail">
    <figure class="preview">
        <div class="progress">
            <div class="progress-bar">
                <span class="progress-text">60%</span>
            </div>
        </div>
        <figcaption class="filename"></figcaption>
    </figure>
    <span class="error"></span>
    <button class="retryButton btn btn-default">{$this->strings['retry-label']}</button>
    <button class="cancelButton btn btn-danger">{$this->strings['cancel-label']}</button>
    <button class="deleteButton btn btn-danger">{$this->strings['delete-label']}</button>
</div>
EOT
            ;

            $view->registerCss('.au-thumbnail img { vertical-align: baseline; }', [], 'au-bootstrap-fix');

            $this->uploadedSelector = "#$divId .au-thumbnail";

            $this->templateSelectors = [
                'filename' => '.filename',
                'preview' => '.preview',
                'progress' => '.progress-text',
                'progressbar' => '.progress-bar',
                'retry' => '.retryButton',
                'cancel' => '.cancelButton',
                'delete' => '.deleteButton',
                'error' => '.error',
            ];

        }

        $options = $this->getClientOptions();
        $options['inputName'] = $inputName;
        $options['divId'] = $divId;
        $options = Json::encode($options);

        $view->registerJs("jQuery('#$id').ajaxupload($options);");
        return $output;
    }
}
