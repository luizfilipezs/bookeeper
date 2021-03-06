<?php

namespace app\controllers;

use app\entities\Work;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class WorkController extends Controller
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
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Work::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionList(?string $search): array
    {
        $this->response->format = Response::FORMAT_JSON;

        return Work::find()
            ->select(['id', 'title AS text'])
            ->filterWhere(['like', 'title', $search])
            ->asArray()
            ->all();
    }

    public function actionCreate(): string|Response
    {
        $model = new Work();

        if ($this->request->isPost && $this->saveModel($model)) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $model = Work::findOne($id);

        if ($this->request->isPost && $this->saveModel($model)) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    private function saveModel(Work $model): bool
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
            Yii::$app->session->setFlash('error', 'N??o foi poss??vel salvar a obra.');
            return false;
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return true;
    }
}
