<?php

/**
 * @var yii\web\View $this
 * @var app\entities\Work $model
 */

$this->title = 'Editar livro';

echo $this->render('_form', [
    'model' => $model,
]);
