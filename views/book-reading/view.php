<?php

use app\entities\BookReading;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\entities\BookReading $model
 */

$this->title = "Leitura do livro \"{$model->book->title}\"";

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
        [
            'label' => 'Livro',
            'value' => Html::a(
                text: $model->book->title,
                url: Url::to(['/book/view', 'id' => $model->bookId])
            ),
        ],
        [
            'label' => 'Obras',
            'value' => function (BookReading $model) {
                $titles = $model->getWorks()
                    ->select('Work.title')
                    ->column();

                return Html::tag('i', implode('<br>', $titles));
            }
        ],
        [
            'attribute' => 'isComplete',
            'value' => $model->isComplete ? 'Sim' : 'Não',
        ],
        'startDate:date',
        'endDate:date',
    ],
]) ?>
