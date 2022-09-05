<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\entities\Box $model
 */

$this->title = "Box \"{$model->title}\"";

$bookTitles = $model->getBooks()
    ->select('Book.title')
    ->column();

$authorNames = $model->getBooks()
    ->select('Author.name')
    ->distinct()
    ->joinWith('bookWorks.work.workAuthors.author')
    ->column();

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
            'label' => 'Livros',
            'value' => fn () => implode('<br>', $bookTitles),
            'format' => 'html',
            'visible' => !!$bookTitles,
        ],
        [
            'label' => 'Autores',
            'value' => fn () => implode('<br>', $authorNames),
            'format' => 'html',
            'visible' => !!$authorNames,
        ],
    ],
]) ?>
