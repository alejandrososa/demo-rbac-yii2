<?php

namespace frontend\modules\api\controllers;

use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use frontend\models\SignupForm;
use Yii;

use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Response;


/**
 * Default controller for the `api` module
 */
class DefaultController extends ActiveController
{
    public $modelClass = 'common\models\User';

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
            //tipo de autenticación
            [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    HttpBearerAuth::className(),
                ]
            ],
            //control de acceso
            [
                'class' => AccessControl::className(),
                //'only' => ['dashboard'],
                'rules' => [
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['particular'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException(Yii::t('app','No tienes permiso para acceder a {action}', [
                        'action' => $action->id,
                    ]), 403);
                }
            ],
            //negociador
            [
                'class' => ContentNegotiator::className(),
                'only' => ['view', 'index'],  // in a controller
                // if in a module, use the following IDs for user actions
                // 'only' => ['user/view', 'user/index']
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
                'languages' => [
                    'en',
                    'de',
                ],
            ],
            //cors
            [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 86400,
                ],
            ],
        ]);
    }

    /**
     * @return \common\models\User|SignupForm|null
     */
    public function actionCreate()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '')) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $user;
                }
            }
        }
        return $model;
    }

}