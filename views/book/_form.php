<?php

use app\core\enums\BookConservationState;
use kartik\datecontrol\DateControl;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View $this
 * @var app\forms\BookForm $model
 */

$selectedWorks = $model->getWorks()
    ->select(['Work.title', 'Work.id'])
    ->indexBy('Work.id')
    ->column();

$selectedPublishingCompany = $model->getPublishingCompany()
    ->select(['name', 'id'])
    ->indexBy('id')
    ->column();

$form = ActiveForm::begin([
    'method' => 'post',
]);

?>

<div class="div">
    <div class="col-12">
        <p class="fs-2">
            <?= $this->title ?>
        </p>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-3">
        <?= $form->field($model, 'isbn')->widget(MaskedInput::class, [
            'mask' => '999-99-99999-99-9',
        ]) ?>
    </div>
    <div class="col-3">
        <?= $form->field($model, 'acquiredAt')->widget(DateControl::class, [
            'type' => DateControl::FORMAT_DATE,
            'displayFormat' => 'php: d/m/Y',
        ]) ?>
    </div>
</div>
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
        <?= $form->field($model, 'language')->textInput([
            'maxLength' => true,
            'placeholder' => 'Português',
        ]) ?>
    </div>
    <div class="col-2">
        <?= $form->field($model, 'pages')->textInput(['maxLength' => true]) ?>
    </div>
    <div class="col-2">
        <?= $form->field($model, 'volumes')->textInput(['placeholder' => '1']) ?>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <?= $form->field($model, 'year')->textInput(['maxLength' => true]) ?>
    </div>
    <div class="col-3">
        <?= $form->field($model, 'conservationState')->dropDownList(BookConservationState::labels()) ?>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'comments')->textarea() ?>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <?= $form->field($model, 'publishingCompanyId')->widget(Select2::class, [
            'data' => $selectedPublishingCompany,
            'options' => [
                'placeholder' => 'Selecione...',
            ],
            'pluginOptions' => [
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => Url::to(['publishing-company/list']),
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
    <div class="col-3">
        <?= $form->field($model, 'estimatedValue')->widget(MaskMoney::class) ?>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <?= $form->field($model, 'workIds')->widget(Select2::class, [
            'data' => $selectedWorks,
            'options' => [
                'value' => array_keys($selectedWorks),
                'multiple' => true,
                'placeholder' => 'Selecione...',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => Url::to(['work/list']),
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

<?php if ($model->isNewRecord) : ?>

    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'canAutoCreateWork')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'authorIds')->widget(Select2::class, [
                'options' => [
                    'multiple' => true,
                    'placeholder' => 'Selecione...',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'ajax' => [
                        'url' => Url::to(['author/list']),
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
        <div class="col-6">
            <?= $form->field($model, 'tagIds')->widget(Select2::class, [
                'options' => [
                    'multiple' => true,
                    'placeholder' => 'Selecione...',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'ajax' => [
                        'url' => Url::to(['tag/list']),
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

<?php endif ?>

<div class="row">
    <div class="col-12">
        <?= Html::a('Cancelar', Url::toRoute('index'), ['class' => 'btn btn-danger']) ?>
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>
</div>

<?php

ActiveForm::end();

$canAutoCreateWorkInput = Html::getInputId($model, 'canAutoCreateWork');
$workIdsInput = Html::getInputId($model, 'workIds');
$authorIdsInput = Html::getInputId($model, 'authorIds');
$tagIdsInput = Html::getInputId($model, 'tagIds');

$this->registerJs(<<<JS

jQuery('#{$canAutoCreateWorkInput}')
    .change(function () {
        jQuery('#{$workIdsInput}').parent().toggle(!this.checked);
        jQuery('#{$authorIdsInput}').parent().toggle(this.checked);
        jQuery('#{$tagIdsInput}').parent().toggle(this.checked);
    })
    .change();

JS);
