<?php

namespace yrssoft\ueditor;

use Yii;
use yii\web\AssetBundle;
use yii\web\View;

class Asset extends AssetBundle
{
    public $jsOptions = ['position' => View::POS_HEAD];
    public $sourcePath = '@vendor/yrssoft/yrs-ueditor/assets/';
    public $js = [
        'ueditor.config.js',
        'ueditor.all.js',
    ];

    public $css = [
        'themes/default/default.css',
    ];
}
