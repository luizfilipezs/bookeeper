<?php

namespace app\controllers;

use app\core\exceptions\FriendlyException;
use app\core\web\ICrudActions;
use app\entities\Author;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;
use yii\web\{
    Controller,
    Response
};

/**
 * {@inheritdoc}
 */
class AuthorController extends Controller implements ICrudActions
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function actionList(?string $search): array
    {
        $this->response->format = Response::FORMAT_JSON;

        return Author::find()
            ->select(['id', 'name AS text'])
            ->filterWhere(['like', 'name', $search])
            ->asArray()
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function actionView(int $id): string
    {
        $model = Author::findOne($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function actionDelete(int $id): Response
    {
        $model = Author::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->delete() !== false) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Autor removido com sucesso.');
            }
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir o autor. Provavelmente há outros itens vinculados a ele.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir o autor.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * Saves the record into the database.
     * 
     * @param Author $model Record being created/updated.
     * 
     * @return bool Whether the record was saved successfully.
     */
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

            return true;
        } catch (FriendlyException $friendlyException) {
            Yii::$app->session->setFlash('error', $friendlyException->getMessage());
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível salvar o autor.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return false;
    }
}
