<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\entities\Work $model
 */

$this->title = "Obra \"{$model->title}\"";

?>

<div class="row">
    <div class="col-12 flex flex-row justify-content-between">
        <p class="fs-2">
            <?= $model->title ?>
        </p>
        <?= Html::a('Voltar', Url::toRoute('index'), ['class' => 'btn btn-light']) ?>
    </div>
</div>
<hr>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title',
        [
            'attribute' => 'subtitle',
            'visible' => !!$model->subtitle,
        ],
        [
            'label' => 'Autores',
            'value' => implode(', ', $model->authorNames),
        ],
        [
            'label' => 'Tags',
            'value' => fn () => implode(', ', $model->tagNames),
            'format' => 'html',
            'visible' => !!$model->tagNames,
        ],
    ],
]) ?>
