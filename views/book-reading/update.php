<?php

/**
 * @var yii\web\View $this
 * @var app\entities\BookReading $model
 */

$this->title = 'Editar leitura de livro';

echo $this->render('_form', [
    'model' => $model,
]);
