<?php

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use app\entities\Work;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Obras';
?>

<div class="row">
    <div class="col-12 flex flex-row justify-content-between">
        <p class="fs-2">
            <?= $this->title ?>
        </p>
        <?= Html::a('Cadastrar', Url::toRoute('create'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
<hr>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => '',
    'columns' => [
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '<div class="d-flex justify-content-around">{update}</div>',
        ],
        [
            'label' => (new Work)->getAttributeLabel('title'),
            'value' => function (Work $model) {
                return $model->title . ($model->subtitle ? ' - ' . $model->subtitle : '');
            }
        ],
        [
            'label' => 'Autores',
            'value' => function (Work $model) {
                return implode(', ', $model->authorNames);
            },
        ],
    ],
]) ?>
