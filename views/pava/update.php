<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pava */

$this->title = 'Update Pava: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pavas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pava-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
