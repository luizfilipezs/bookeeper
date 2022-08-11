<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\entities\ReadingListItem $model
 */

$book = $model->book;

?>

<div class="d-flex flex-row justify-content-start">
    <div>
        <?= Html::img('https://m.media-amazon.com/images/I/41rw7ra7UmL.jpg', [
            'style' => 'width: 100px',
        ]) ?>
    </div>
    <div class="flex-grow-1 p-4">
        <p><?= $book->title ?></p>
        <p><?= $book->subtitle ?></p>
        <p class="text-secondary">
            <i><?= implode(', ', $book->authorNames) ?></i>
        </p>
    </div>
</div>
