<?php

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use app\entities\Book;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Livros';

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
            'template' => '<div class="d-flex justify-content-around">{view} {update} {delete}</div>',
        ],
        'title',
        [
            'label' => 'Autores',
            'value' => function (Book $model) {
                return implode(', ', $model->authorNames);
            },
        ],
        [
            'label' => 'Tags',
            'value' => function (Book $model) {
                return implode(', ', $model->tagNames);
            },
        ],
    ],
]) ?>
