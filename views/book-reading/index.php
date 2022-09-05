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
        [
            'attribute' => 'book.title',
            'label' => 'Livro',
        ],
        [
            'label' => 'Obras',
            'value' => function (BookReading $model) {
                $titles = $model->getWorks()
                    ->select('Work.title')
                    ->column();

                if (!$titles) {
                    return 'Todas';
                }

                $textTitles = implode(', ', $titles);
                $maxLength = 100;

                if (strlen($textTitles) > $maxLength) {
                    $textTitles = substr($textTitles, 0, $maxLength) . '...';
                }

                return Html::tag('i', $textTitles);
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'endDate',
            'value' => function (BookReading $model) {
                return $model->endDate ? Yii::$app->formatter->asDate($model->endDate, 'short') : '';
            },
        ],
        [
            'attribute' => 'isComplete',
            'value' => function (BookReading $model) {
                $iconClass = $model->isComplete ?
                    'fa-solid fa-circle-check text-success' :
                    'fa-solid fa-circle-xmark text-danger';

                return Html::tag('i', '', [
                    'class' => $iconClass,
                ]);
            },
            'format' => 'html',
        ],
    ],
]) ?>
