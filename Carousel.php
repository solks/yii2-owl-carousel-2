<?php

namespace custom\owlcarousel2;

use Yii;
use yii\base\Widget;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use custom\owlcarousel2\assets\OwlCarouselAsset;
use custom\owlcarousel2\assets\AnimateCssAsset;

class Carousel extends Widget
{
    const THEME_DEFAULT = 'default';
    const THEME_GREEN = 'green';

    public $theme;

    public $tag = 'div';

    /**
     * @var array the HTML attributes for the carousel container tag.
     */
    public $containerOptions = [];

    public $items = '';

    public $clientOptions = [];

    public $clientScript = '';

    private $_id;

    /**
     * Initializes the widget.
     * This renders the open tags needed by the carousel.
     */
    public function init()
    {
        parent::init();
        $this->_id = $this->getId();
        $this->containerOptions['id'] = $this->_id;
        $this->containerOptions['class'] = (isset($this->containerOptions['class']) && !empty($this->containerOptions['class'])) ?
            'owl-carousel owl-theme ' . $this->containerOptions['class'] : 'owl-carousel owl-theme';
        $this->theme = $this->theme ? $this->theme : self::THEME_DEFAULT;
    }

    public function run()
    {
        if (!empty($this->items)) {
            $this->registerAssets();
            echo Html::beginTag($this->tag, $this->containerOptions) . PHP_EOL;
            echo $this->items . PHP_EOL;
            echo Html::endTag($this->tag) . PHP_EOL;
        }
    }

    public function getOptions()
    {
        $options = ArrayHelper::merge([], $this->clientOptions);
        return json_encode($options);
    }

    public function registerAssets()
    {
        $options = $this->getOptions();
        $view = $this->getView();
//         OwlCarouselAsset::$theme = $this->theme;
//         OwlCarouselAsset::register($view);

        if (isset($this->clientOptions['animateOut']) && (!empty($this->clientOptions['animateOut'])) ||
            (isset($this->clientOptions['animateIn']) && (!empty($this->clientOptions['animateIn'])))
        ) {
            AnimateCssAsset::register($view);
        }

//         Temporary move to bundled.js
//         $script = new JsExpression("
//             $('#{$this->_id}').owlCarousel({$options});
//         ");
//         $view->registerJs($script);

        if (!empty($this->clientScript)) {
            $view->registerJs($this->clientScript);
        }
    }
}
