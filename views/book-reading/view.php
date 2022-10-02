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
            Leitura <?= $model->isComplete ? 'concluída' : 'agendada' ?>
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
            'format' => 'html',
        ],
        [
            'label' => 'Obras',
            'value' => function (BookReading $model) {
                $titles = $model->getWorks()
                    ->select('Work.title')
                    ->column();

                return $titles ? Html::tag('i', implode('<br>', $titles)) : 'Todas';
            },
            'format' => 'html',
        ],
        [
            'label' => 'Autores',
            'value' => function (BookReading $model) {
                return implode(', ', $model->book->authorNames);
            },
        ],
        'startDate:date',
        'endDate:date',
        [
            'attribute' => 'isComplete',
            'label' => 'Status',
            'value' => $model->isComplete ? 'Concluída' : 'Em andamento',
        ],
    ],
]) ?>
