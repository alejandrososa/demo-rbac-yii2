<?php

namespace frontend\modules\api;

/**
 * api module definition class
 */
class Modulo extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\api\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;

//        public $defaultRoute = 'product';
        // custom initialization code goes here
    }
}
