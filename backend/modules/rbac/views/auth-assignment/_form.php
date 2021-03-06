<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AuthAssignment */
/* @var $roles backend\models\Roles */
/* @var $usuarios backend\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="auth-assignment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'item_name')->dropDownList($roles, ['prompt'=>'Seleccionar rol']); ?>

    <?= $form->field($model, 'user_id')->dropDownList($usuarios, ['prompt'=>'Seleccionar usuario']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
