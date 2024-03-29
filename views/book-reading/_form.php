<?php

use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var app\forms\BookReadingForm $model
 */

$selectedBook = $model->isNewRecord ? [] : $model->getBook()
    ->joinWith('publishingCompany', false)
    ->select(['CONCAT(Book.title, " - ", PublishingCompany.name) AS title', 'Book.id'])
    ->indexBy('Book.id')
    ->column();

$selectedWorks = $model->getWorks()
    ->select(['Work.title', 'Work.id'])
    ->indexBy('Work.id')
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
    <div class="col-6">
        <?= $form->field($model, 'bookId')->widget(Select2::class, [
            'data' => $selectedBook,
            'options' => [
                'placeholder' => 'Selecione...',
                'disabled' => !$model->isNewRecord,
            ],
            'pluginOptions' => [
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
    <div class="col-6">
        <?php

        $bookInputId = Html::getInputId($model, 'bookId');

        echo $form->field($model, 'workIds')->widget(Select2::class, [
            'data' => $selectedWorks,
            'options' => [
                'value' => array_keys($selectedWorks),
                'multiple' => true,
                'placeholder' => 'Selecione...',
                'disabled' => !$model->bookId,
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => Url::to(['list-works']),
                    'data' => new JsExpression("({ term }) => ({ bookId: jQuery('#{$bookInputId}').val(), search: term })"),
                    'dataType' => 'json',
                    'cache' => true,
                    'processResults' => new JsExpression('results => ({ results })'),
                ],
                'escapeMarkup' => new JsExpression('markup => markup'),
                'templateResult' => new JsExpression('({ text }) => text'),
                'templateSelection' => new JsExpression('({ text }) => text'),
            ],
        ]);

        if ($model->isNewRecord) : ?>
            <p style="font-size: 12px">
                <i class="fa-solid fa-circle-info"></i> Se nenhum item for selecionado, todas as obras do livro serão incluídas.
            </p>
        <?php endif ?>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <?= $form->field($model, 'isComplete')->checkbox() ?>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <?= $form->field($model, 'startDate')->widget(DateControl::class, [
            'type' => DateControl::FORMAT_DATE,
            'displayFormat' => 'php: d/m/Y',
        ]) ?>
    </div>
    <div class="col-3">
        <?= $form->field($model, 'endDate')->widget(DateControl::class, [
            'type' => DateControl::FORMAT_DATE,
            'displayFormat' => 'php: d/m/Y',
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

$worksInputId = Html::getInputId($model, 'workIds');
$isCompleteInputId = Html::getInputId($model, 'isComplete');
$endDateInputId = Html::getInputId($model, 'endDate');

$this->registerJs(<<<JS

const bookInput = jQuery('#{$bookInputId}');
const worksInput = jQuery('#{$worksInputId}');
const isCompleteInput = jQuery('#{$isCompleteInputId}');
const endDateInput = jQuery('#{$endDateInputId}');

bookInput.change(() => {
    worksInput.val(null).change();
    worksInput.attr('disabled', !bookInput.val());
});

endDateInput.change(function () {
    if (this.value) {
        isCompleteInput.prop('checked', true);
    }
});

JS);
