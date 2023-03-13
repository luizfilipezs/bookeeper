<?php

/**
 * @var yii\web\View $this
 * @var app\entities\Translator $model
 */

$this->title = 'Editar tradutor';

echo $this->render('_form', [
    'model' => $model,
]);
