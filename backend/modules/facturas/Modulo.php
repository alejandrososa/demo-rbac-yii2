<?php

namespace backend\modules\facturas;
use yii\base\BootstrapInterface;
use Yii;

/**
 * facturas module definition class
 */
class Modulo extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\facturas\controllers';

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {

        Yii::$app->urlManager->addRules([
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            '<module:\w+>'=>'<module>/facturas/index',
            '<module:\w+>/<action:\w+>'=>'<module>/facturas/<action>',
            '<module:\w+>/<action:\w+>/<id:\d+>'=>'<module>/facturas/<action>',
        ],false);

    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
