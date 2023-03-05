<?php

use app\entities\Author;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\forms\search\WorkSearch $searchModel
 * @var yii\data\DataProviderInterface $dataProvider
 */

$authorData = !$model->authorId ? [] : Author::find()
    ->select(['name', 'id'])
    ->where(['id' => $model->authorId])
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
</div>

<div class="row my-2">
    <div class="col-3">
        <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<hr class="my-3">

<?php

ActiveForm::end();
