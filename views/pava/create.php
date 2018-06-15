<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pava */

$this->title = 'Create Pava';
$this->params['breadcrumbs'][] = ['label' => 'Pavas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pava-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
