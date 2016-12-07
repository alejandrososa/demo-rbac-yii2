<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        [
            'label' => 'Home',
            'url' => ['/site/index'],
//            'visible' => \Yii::$app->user->can('admin')
        ],
        [
            'label' => 'Administracion',
            'items' => [
                ['label' => 'Usuarios', 'url' => ['/usuarios/index']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">roles y autorizaciones</li>',
                ['label' => 'Roles y reglas', 'url' => ['/rbac/auth-item']],
                ['label' => 'Asignaciones', 'url' => ['/rbac/auth-assignment']],
                ['label' => 'Reglas', 'url' => ['/rbac/auth-rule']],
            ],
//            'visible' => \Yii::$app->user->can('admin')
        ],
        [
            'label' => 'Analytics',
            'items' => [
                ['label' => 'Global Analytics', 'url' => ['/analytics/index']],
                ['label' => 'Profession & Location', 'url' => ['/analytics/profession']],
            ],
//            'visible' => (\Yii::$app->user->can('admin') || \Yii::$app->user->can('investor'))
        ]
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
