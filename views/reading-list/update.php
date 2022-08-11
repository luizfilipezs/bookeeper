<?php

/**
 * @var yii\web\View $this
 * @var app\entities\ReadingList $model
 */

use kartik\sortable\Sortable;
use yii\helpers\Html;

$this->title = $model->name;

/** @var array[] */
$items = [];

foreach ($model->items as $item) {
    $items[] = [
        'content' => $this->render('_list-item', [
            'model' => $item,
        ]),
    ];
}

?>

<div class="row">
    <div class="col-12">
        <p class="fs-2">
            <?= $this->title ?>
        </p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <form>
            <input type="search" class="form-control" placeholder="Adicionar livros..." style="height: 48px;">
        </form>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <?php

        if ($items) {
            echo Sortable::widget([
                'items' => $items,
            ]);
        } else {
            echo Html::tag('p', 'Nenhum item na lista.');
        }

        ?>
    </div>
</div>
