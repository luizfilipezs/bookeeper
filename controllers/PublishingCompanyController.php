<?php

namespace app\controllers;

use app\core\web\IActionList;
use app\entities\PublishingCompany;
use yii\filters\VerbFilter;
use yii\web\{
    Controller,
    Response
};


/**
 * Provides actions for handling operations related to the model `PublishingCompany`.
 */
class PublishingCompanyController extends Controller implements IActionList
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'list' => ['get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actionList(?string $search): array
    {
        $this->response->format = Response::FORMAT_JSON;

        return PublishingCompany::find()
            ->select(['id', 'name AS text'])
            ->filterWhere(['like', 'name', $search])
            ->asArray()
            ->all();
    }
}
