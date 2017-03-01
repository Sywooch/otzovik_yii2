<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/jquery.fancybox.css',
        'css/jquery-ui.min.css',
        'css/rating.css',
        'css/site.css',
        'css/jquery.datetimepicker.min.css',
    ];
    public $js = [
        'js/jquery-ui.min.js',
        'js/ripples.min.js',
        'js/jquery.fancybox.pack.js',
        'js/map.js',
        'js/custom.js',
        'js/rating.js',
        'js/jquery.datetimepicker.full.min.js',
        'js/parallax.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
