<?php

/**
 * @var yii\web\View $this
 * @var app\entities\ReadingList $model
 */

$this->title = 'Editar lista de leitura';

echo $this->render('_form', [
    'model' => $model,
]);
