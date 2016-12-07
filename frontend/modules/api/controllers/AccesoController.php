<?php

namespace frontend\modules\api\controllers;

use Yii;
use common\models\LoginForm;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\helpers\ArrayHelper;

/**
 * Default controller for the `api` module
 */
class AccesoController extends ActiveController
{
    public $modelClass = 'common\models\User';

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
            //control de acceso
            [
                'class' => AccessControl::className(),
                //'only' => ['dashboard'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                        'verbs' => ['POST']
                    ]
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
                'only' => ['acceso/login'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
                'languages' => [
                    'en',
                    'de',
                ],
            ]
        ]);

    }

    /**
     * @return array|LoginForm
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            $user = Yii::$app->user->getIdentity();
            return ['access_token' => $user->authToken->token];
        } else {
            $model->validate();
            return $model;
        }
    }

}