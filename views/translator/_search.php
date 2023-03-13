<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\forms\search\TranslatorSearch $searchModel
 * @var yii\data\DataProviderInterface $dataProvider
 */

$form = ActiveForm::begin([
    'method' => 'get',
]);

?>

<hr class="my-3">

<div class="row my-2">
    <div class="col-12">
        <?= $form->field($model, 'name')->textInput() ?>
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
