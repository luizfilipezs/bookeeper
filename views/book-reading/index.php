<?php

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use app\entities\BookReading;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Leituras';

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
        'book.title',
        [
            'label' => 'Obras',
            'value' => function (BookReading $model) {
                $titles = $model->getWorks()
                    ->select('Work.title')
                    ->column();

                return Html::tag('i', implode(', ', $titles));
            }
        ],
        [
            'attribute' => 'isComplete',
            'value' => function (BookReading $model) {
                return $model->isComplete ? Html::tag('i', ['class' => 'fa-solid fa-circle-check']) : '';
            },
        ],
    ],
]) ?>
