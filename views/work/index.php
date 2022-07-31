<?php

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

use app\entities\Work;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Obras';

echo Html::a('Cadastrar', Url::to(['create']));

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
            'buttons' => [
                'update' => function (string $url) {
                    return Html::a(Yii::t('app/action', 'Editar'), $url);
                },
            ],
        ],
        [
            'label' => (new Work)->getAttributeLabel('title'),
            'value' => function (Work $model) {
                return $model->title . ($model->subtitle ? ' - ' . $model->subtitle : '');
            }
        ],
        [
            'label' => 'Autores',
            'value' => function (Work $model) {
                return implode(', ', $model->authorNames);
            },
        ],
    ],
]);
