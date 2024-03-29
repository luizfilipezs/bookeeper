<?php

use app\core\enums\BookConservationState;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\entities\Book $model
 */

$this->title = "Livro \"{$model->title}\"";

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
            'attribute' => 'isbn',
            'visible' => !!$model->isbn,
        ],
        [
            'attribute' => 'subtitle',
            'visible' => !!$model->subtitle,
        ],
        [
            'label' => 'Autores',
            'value' => implode(', ', $model->authorNames),
        ],
        [
            'label' => 'Tradutores',
            'value' => implode(', ', $model->translatorNames),
        ],
        [
            'label' => 'Obras',
            'value' => fn () => implode('<br>', $model->getWorks()->select('title')->column()),
            'format' => 'html',
            'visible' => $model->getWorks()->count() > 1,
        ],
        'year',
        [
            'label' => 'Editora',
            'attribute' => 'publishingCompany.name',
        ],
        [
            'label' => 'Tags',
            'value' => fn () => implode(', ', $model->tagNames),
            'format' => 'html',
            'visible' => !!$model->tagNames,
        ],
        [
            'attribute' => 'conservationState',
            'value' => BookConservationState::from($model->conservationState)->label(),
        ],
        'pages',
    ],
]) ?>
