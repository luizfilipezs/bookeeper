<?php

/**
 * @var yii\web\View $this
 * @var app\entities\Tag $model
 */

$this->title = 'Cadastrar tag';

echo $this->render('_form', [
    'model' => $model,
]);
