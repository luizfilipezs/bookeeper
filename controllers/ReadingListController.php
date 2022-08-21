<?php

namespace app\controllers;

use app\entities\ReadingList;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\{
    Controller,
    Response
};

class ReadingListController extends Controller
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
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ReadingList::find(),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $model = new ReadingList();

        if ($this->request->isPost && $this->saveModel($model)) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $model = ReadingList::findOne($id);

        if ($this->request->isPost && $this->saveModel($model)) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $model = ReadingList::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->delete() !== false) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Lista removida com sucesso.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a lista.');
        }

        return $this->redirect(['index']);
    }

    private function saveModel(ReadingList $model): bool
    {
        if (!$model->load($this->request->post())) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->saveOrFail();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Obra salva com sucesso!');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível salvar a obra.');
            return false;
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return true;
    }
}
