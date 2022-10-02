<?php

use app\entities\{
    Book,
    Work
};
use app\forms\search\BookReadingSearch;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\{
    Html,
    Url
};
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var BookReadingSearch $model
 * @var yii\data\DataProviderInterface $dataProvider
 */

$bookData = !$model->bookId ? [] : Book::find()
    ->select(['title', 'id'])
    ->where(['id' => $model->bookId])
    ->indexBy('id')
    ->column();

$workData = !$model->workId ? [] : Work::find()
    ->select(['title', 'id'])
    ->where(['id' => $model->workId])
    ->indexBy('id')
    ->column();

$form = ActiveForm::begin([
    'method' => 'get',
]);

?>

<hr class="my-3">

<div class="row my-2">
    <div class="col-6">
        <?= $form->field($model, 'bookId')->widget(Select2::class, [
            'data' => $bookData,
            'options' => [
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
    <div class="col-6">
        <?= $form->field($model, 'workId')->widget(Select2::class, [
            'data' => $workData,
            'options' => [
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

<div class="row mb-2">
    <div class="col-6">
        <?= $form->field($model, 'startDate')->widget(DateControl::class, [
            'type' => DateControl::FORMAT_DATE,
            'displayFormat' => 'php: d/m/Y',
        ]) ?>
    </div>
    <div class="col-6">
        <?= $form->field($model, 'endDate')->widget(DateControl::class, [
            'type' => DateControl::FORMAT_DATE,
            'displayFormat' => 'php: d/m/Y',
        ]) ?>
    </div>
</div>

<div class="row my-2">
    <div class="col-6">
        <?= $form->field($model, 'status')->dropDownList(BookReadingSearch::STATUS_OPTIONS, [
            'prompt' => 'Todas',
        ]) ?>
    </div>
</div>

<div class="row my-2">
    <div class="col-3">
        <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<hr class="my-3">

<?php

ActiveForm::end();
