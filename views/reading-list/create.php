<?php

/**
 * @var yii\web\View $this
 * @var app\entities\ReadingList $model
 */

$this->title = 'Cadastrar lista de leitura';

echo $this->render('_form', [
    'model' => $model,
]);
