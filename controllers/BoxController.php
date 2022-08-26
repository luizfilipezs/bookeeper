<?php

namespace app\controllers;

use app\core\exceptions\FriendlyException;
use app\core\web\ICrudActions;
use app\entities\Box;
use app\forms\BoxForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;
use yii\web\{
    Controller,
    Response
};

/**
 * Provides actions for handling operations related to the model `Box`.
 */
class BoxController extends Controller implements ICrudActions
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
                    'delete' => ['post'],
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
            'query' => Box::find()->orderBy(['id' => SORT_DESC]),
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

        return Box::find()
            ->select(['id', 'title AS text'])
            ->filterWhere(['like', 'title', $search])
            ->asArray()
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function actionView(int $id): string
    {
        $model = Box::findOne($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate(): string|Response
    {
        $model = new BoxForm();

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
        $model = BoxForm::findOne($id);

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
        $model = Box::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->delete() !== false) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Livro removido com sucesso.');
            }
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a box. Provavelmente há outros itens vinculados a ela.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a box.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * Saves the record into the database.
     * 
     * @param BoxForm $model Form to the record being created/updated.
     * 
     * @return bool Whether the record was saved successfully.
     */
    private function saveModel(BoxForm $model): bool
    {
        if (!$model->load($this->request->post())) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->saveOrFail();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Livro salvo com sucesso!');

            return true;
        } catch (FriendlyException $friendlyException) {
            Yii::$app->session->setFlash('error', $friendlyException->getMessage());
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível salvar a box.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return false;
    }
}
