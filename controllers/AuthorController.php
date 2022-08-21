<?php

namespace app\controllers;

use app\entities\Author;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class AuthorController extends Controller
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
                    'index' => ['get'],
                    'list' => ['get'],
                    'view' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Author::find(),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionList(?string $search): array
    {
        $this->response->format = Response::FORMAT_JSON;

        return Author::find()
            ->select(['id', 'name AS text'])
            ->filterWhere(['like', 'name', $search])
            ->asArray()
            ->all();
    }

    public function actionView(int $id): string
    {
        $model = Author::findOne($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $model = new Author();

        if ($this->request->isPost && $this->saveModel($model)) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $model = Author::findOne($id);

        if ($this->request->isPost && $this->saveModel($model)) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    private function saveModel(Author $model): bool
    {
        if (!$model->load($this->request->post())) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->saveOrFail();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Autor salvo com sucesso!');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível salvar o autor.');
            return false;
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return true;
    }
}
