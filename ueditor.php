<?php

namespace yrssoft\ueditor;

use yii\web\View;

class UEditor extends \yii\base\widget
{
    const SIMPLE = 1;
    const ADVANCED =2;
    public $model; // $model 必须是 Object 或者 String 类型，但最终会被转换成 String 类型
    public $attribute; // $model 的特性
    public $toolbarSelect; // 工具栏类型选择：简单、高级
    public $simpleToolbars ="['fullscreen', 'source', 'undo', 'redo', 'bold', 'fontborder', 'forecolor', 'backcolor']"; 
    public $advancedToolbars = "['fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'link', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'fontsize', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', 'emotion', 'simpleupload', 'imageleft', 'imageright', 'inserttable', '|' ,'superscript', 'insertcode']";
    // 编辑器参数，更多设置参数请看README.md
    public $defaultParams = [
        'emotionLocalization'=>'true',
    ]; // 内置参数
    public $params = []; // 自定义参数
    public $content = '';
    public function run()
    {
        // 合并内置参数和自定义参数
        $this->params = array_merge($this->defaultParams, $this->params);
        // 如果$model 是 Object，下面语句将会把它转换为 String 
        if (is_object($this->model)) {
            $this->model = preg_match_all('/[a-zA-Z0-9]+/', get_class($this->model), $matches);
            $i = count($matches[0]) - 1;
            $this->model = $matches[0][$i];
        }
        // 判断$model 的合法性，它最终只能是 String 类型
        if (!is_string($this->model)) {
            throw new \yii\web\BadRequestHttpException('参数 $model 配置有误，它必须为 String 或者 Object类型。');
        }
        
        echo '<script id="'.$this->model.'-'.$this->attribute.'" name="'.$this->model.'['.$this->attribute.']'.'" type="text/plain">'.$this->content.'</script>';
        // 若设置了参数数组，则将其生成配置
        if (!empty($this->params)) {
            $config = $this->getCofiguration($this->params);
        } else {
            $config = '';
        }
        if (empty($config)) $config = ',{';
        // 选择工具栏类型：简单功能、高级功能、全部功能或者自定义
        if (!isset($this->params['toolbars'])) {
            switch ($this->toolbarSelect) {
                case self::SIMPLE : $config = $config . 'toolbars:[' . $this->simpleToolbars.']';break;
                case self::ADVANCED : $config = $config . 'toolbars:[' . $this->advancedToolbars.']';break;
                default : break;
            }
        }
        $config .= '}'; // 闭合配置
        // 实例化编辑器
        $script = '';
        $script = $script . "var ue = UE.getEditor('".$this->model."-".$this->attribute."'".$config.");";
        $this->getView()->registerJs($script, View::POS_END);
        // 发布资源到 assets 目录
        Asset::register($this->getView());
    }
    
    /**
     * @param type $params 编辑器参数
     * @return string 编辑器的配置
     */
    private function getCofiguration($params)
    {
        $config = ',{';
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $config = $config . $key .':' .$value .',';
            }
        }
        return $config;
    }
}
