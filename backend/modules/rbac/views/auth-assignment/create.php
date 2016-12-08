<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AuthAssignment */
/* @var $roles backend\models\Roles */
/* @var $usuarios backend\models\Usuarios */

$this->title = 'Create Auth Assignment';
$this->params['breadcrumbs'][] = ['label' => 'Auth Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        'usuarios' => $usuarios,
    ]) ?>

</div>
