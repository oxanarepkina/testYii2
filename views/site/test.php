<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Test Task';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <h3>Upload here the file you want to work with</h3>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end();

    if ($model->result) {
        ?>
        <h4>Result array:</h4>
        <?
        echo "<pre>";
        print_r($model->result);
        echo "</pre>";
    } ?>
</div>