<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\entities\Book $model
 */

?>

<div class="d-flex flex-row justify-content-start" book-id="<?= $model->id ?>">
    <div>
        <?= Html::img('https://m.media-amazon.com/images/I/41rw7ra7UmL.jpg', [
            'style' => 'width: 100px',
        ]) ?>
    </div>
    <div class="flex-grow-1 p-4">
        <p><?= $model->title ?></p>
        <p><?= $model->subtitle ?></p>
        <p class="text-secondary">
            <i><?= implode(', ', $model->authorNames) ?></i>
        </p>
    </div>
    <div class="d-flex flex-column justify-content-center" style="flex-basis: 50px">
        <i class="fa-solid fa-trash-can" style="cursor: pointer" onclick="removeItemByBookId('<?= $model->id ?>')"></i>
    </div>
</div>
