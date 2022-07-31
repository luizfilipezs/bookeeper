<?php

/**
 * @var yii\web\View $this
 * @var app\forms\BookForm $model
 */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'method' => 'post',
]);

?>

<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'title')->textInput(['maxLength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'subtitle')->textInput(['maxLength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-2">
        <?= $form->field($model, 'language')->textInput(['maxLength' => true]) ?>
    </div>
    <div class="col-2">
        <?= $form->field($model, 'pages')->textInput(['maxLength' => true]) ?>
    </div>
    <div class="col-2">
        <?= $form->field($model, 'year')->textInput(['maxLength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'workIds')->hiddenInput()->label(false) ?>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?= Html::a('Cancelar', Url::to(['index'])) ?>
        <?= Html::submitButton('Salvar') ?>
    </div>
</div>

<?php

ActiveForm::end();