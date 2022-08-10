<?php

use app\entities\ReadingList;
use app\entities\ReadingListItem;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var ReadingList $model
 */

$this->title = "Lista de leitura \"{$model->name}\"";

?>

<div class="row">
    <div class="col-12 flex flex-row justify-content-between">
        <p class="fs-2">
            <?= $model->name ?>
        </p>
        <?= Html::a('Voltar', Url::toRoute('index'), ['class' => 'btn btn-light']) ?>
    </div>
</div>
<hr>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        [
            'label' => 'Itens',
            'value' => function (ReadingList $model) {
                $itemNames = array_map(fn (ReadingListItem $item) => $item->book->title ?? $item->work->title, $model->items);

                return $itemNames ? implode('<br>', $itemNames) : '—';
            },
            'format' => 'html',
        ]
    ],
]) ?>
