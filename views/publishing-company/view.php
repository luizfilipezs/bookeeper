<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\entities\PublishingCompany $model
 */

$this->title = "Editora \"{$model->name}\"";

?>

<div class="row">
    <div class="col-12 flex flex-row justify-content-between">
        <p class="fs-2">
            <?= $model->name ?>
        </p>
        <?= Html::a('Voltar', Url::toRoute('index'), ['class' => 'btn btn-light']) ?>
    </div>
</div>
<hr>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
    ],
]) ?>
