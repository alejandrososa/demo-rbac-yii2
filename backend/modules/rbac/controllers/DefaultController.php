<?php

namespace backend\modules\rbac\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Default controller for the `rbac` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
