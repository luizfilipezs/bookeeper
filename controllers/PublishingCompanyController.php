<?php

namespace app\controllers;

use app\core\exceptions\FriendlyException;
use app\core\web\ICrudActions;
use app\entities\PublishingCompany;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;
use yii\web\{
    Controller,
    Response
};


/**
 * Provides actions for handling operations related to the model `PublishingCompany`.
 */
class PublishingCompanyController extends Controller implements ICrudActions
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
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PublishingCompany::find()->orderBy('name ASC'),
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

        return PublishingCompany::find()
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
        $model = PublishingCompany::findOne($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate(): string|Response
    {
        $model = new PublishingCompany();

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
        $model = PublishingCompany::findOne($id);

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
        $model = PublishingCompany::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->delete() !== false) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Editora removida com sucesso.');
            }
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a editora. Provavelmente há outros itens vinculados a ele.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a editora.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * Saves the record into the database.
     * 
     * @param PublishingCompany $model Record being created/updated.
     * 
     * @return bool Whether the record was saved successfully.
     */
    private function saveModel(PublishingCompany $model): bool
    {
        if (!$model->load($this->request->post())) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->saveOrFail();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Editora salva com sucesso!');

            return true;
        } catch (FriendlyException $friendlyException) {
            Yii::$app->session->setFlash('error', $friendlyException->getMessage());
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível salvar a editora.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return false;
    }
}
