<?php

use app\entities\Box;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Boxes';
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
            'label' => 'Livros',
            'value' => function (Box $model) {
                $bookTitles = $model->getBooks()
                    ->select('Book.title')
                    ->column();

                $italicTitles = array_map(fn (string $title) => "<i>{$title}</br>", $bookTitles);

                return implode(', ', $italicTitles);
            },
            'format' => 'html',
        ],
    ],
]) ?>
