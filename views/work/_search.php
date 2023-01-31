<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\forms\search\WorkSearch $searchModel
 * @var yii\data\DataProviderInterface $dataProvider
 */

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
        <?= $form->field($model, 'authorName')->textInput() ?>
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
