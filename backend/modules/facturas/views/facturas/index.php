<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\buscadores\FacturasBuscador */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Facturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="facturas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Facturas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'codigo',
            'cantidad',
            'concepto',
            'descripcion:ntext',
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Gestor',
                'attribute' => 'empleado',
                'value' => 'empleado.username',
                'options' => ['width' => '110']
            ],
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'php:d-m-Y H:i:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'php:d-m-Y H:i:s'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => \Yii::$app->user->can('admin') || Yii::$app->user->can('empleado') || Yii::$app->user->can('vendedor'),
                    'delete' => \Yii::$app->user->can('admin')
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::toRoute('/facturas/'.$action.'/'.$model->id);
                }
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
