<?php

namespace app\controllers;

use app\entities\PublishingCompany;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;


class PublishingCompanyController extends Controller
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
