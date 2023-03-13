<?php

/**
 * @var yii\web\View $this
 * @var app\entities\Translator $model
 */

$this->title = 'Cadastrar tradutor';

echo $this->render('_form', [
    'model' => $model,
]);
