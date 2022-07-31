<?php

/**
 * @var yii\web\View $this
 * @var app\entities\Work $model
 */

$this->title = 'Cadastrar obra';

echo $this->render('_form', [
    'model' => $model,
]);
