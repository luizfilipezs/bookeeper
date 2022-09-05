<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\entities\Book $model
 */

?>

<div class="d-flex flex-row justify-content-start" book-id="<?= $model->id ?>">
    <div class="flex-grow-1 p-4">
        <p><?= $model->title . ($model->subtitle ? ' — ' . $model->subtitle : '') ?></p>
        <p class="text-secondary">
            <i><?= implode(', ', $model->authorNames) ?></i>
        </p>
    </div>
    <div class="d-flex flex-column justify-content-center" style="flex-basis: 50px">
        <i class="fa-solid fa-trash-can" style="cursor: pointer" onclick="removeItemByBookId('<?= $model->id ?>')"></i>
    </div>
</div>
