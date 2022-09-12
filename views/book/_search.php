<?php

use app\entities\{
    Author,
    PublishingCompany,
    Tag,
    Work
};
use app\forms\search\BookSearch;
use kartik\select2\Select2;
use yii\helpers\{
    Html,
    Url
};
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var BookSearch $model
 * @var yii\data\DataProviderInterface $dataProvider
 */

$publishingCompanyData = !$model->publishingCompanyId ? [] : PublishingCompany::find()
    ->select(['name', 'id'])
    ->where(['id' => $model->publishingCompanyId])
    ->indexBy('id')
    ->column();

$authorData = !$model->authorId ? [] : Author::find()
    ->select(['name', 'id'])
    ->where(['id' => $model->authorId])
    ->indexBy('id')
    ->column();

$workData = !$model->workId ? [] : Work::find()
    ->select(['title', 'id'])
    ->where(['id' => $model->workId])
    ->indexBy('id')
    ->column();

$tagsData = !$model->tagIds ? [] : Tag::find()
    ->select(['name', 'id'])
    ->where(['id' => $model->tagIds])
    ->indexBy('id')
    ->column();

$form = ActiveForm::begin([
    'method' => 'get',
]);

?>

<hr class="my-3">

<div class="row my-2">
    <div class="col-6">
        <?= $form->field($model, 'title')->textInput() ?>
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

<div class="row my-2">
    <div class="col-6">
        <?= $form->field($model, 'authorId')->widget(Select2::class, [
            'data' => $authorData,
            'options' => [
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
    <div class="col-6">
        <?= $form->field($model, 'publishingCompanyId')->widget(Select2::class, [
            'data' => $publishingCompanyData,
            'options' => [
                'placeholder' => 'Selecione...',
            ],
            'pluginOptions' => [
                'allowClear' => true,
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
</div>

<div class="row my-2">
    <div class="col-6">
        <?= $form->field($model, 'tagIds')->widget(Select2::class, [
            'data' => $tagsData,
            'options' => [
                'value' => array_keys($tagsData),
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
    <div class="col-6">
        <?= $form->field($model, 'orderBy')->dropDownList(BookSearch::ORDER_BY_OPTIONS) ?>
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
