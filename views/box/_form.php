<?php

/**
 * @var yii\web\View $this
 * @var app\forms\BoxForm $model
 */

use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$form = ActiveForm::begin([
    'method' => 'post',
]);

$selectedBooks = $model->getBooks()
    ->select(['Book.title', 'Book.id'])
    ->indexBy('Book.id')
    ->column();

?>

<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'title')->textInput(['maxLength' => true]) ?>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'bookIds')->widget(Select2::class, [
            'data' => $selectedBooks,
            'options' => [
                'value' => array_keys($selectedBooks),
                'multiple' => true,
                'placeholder' => 'Selecione...',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => Url::to(['book/list']),
                    'data' => new JsExpression("({ term }) => ({ search: term })"),
                    'dataType' => 'json',
                    'cache' => true,
                    'processResults' => new JsExpression('results => ({ results })'),
                ],
                'escapeMarkup' => new JsExpression('markup => markup'),
                'templateResult' => new JsExpression('({ text }) => text'),
                'templateSelection' => new JsExpression('({ text }) => text'),
            ],
        ]) ?>
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
