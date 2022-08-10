<?php

/**
 * @var yii\web\View $this
 * @var app\entities\Work $model
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
    <div class="col-12">
        <?= Html::a('Cancelar', Url::toRoute('index'), ['class' => 'btn btn-danger']) ?>
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php

ActiveForm::end();
