<?php

namespace app\controllers;

use app\core\exceptions\FriendlyException;
use app\core\web\{
    IActionCreate,
    IActionDelete,
    IActionIndex,
    IActionList,
    IActionUpdate,
};
use app\entities\Translator;
use app\forms\search\TranslatorSearch;
use Yii;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;
use yii\web\{
    Controller,
    Response
};

/**
 * Provides actions for handling operations related to the model `Translator`.
 */
class TranslatorController extends Controller implements IActionList, IActionIndex, IActionCreate, IActionUpdate, IActionDelete
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
        $searchModel = new TranslatorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionList(?string $search): array
    {
        $this->response->format = Response::FORMAT_JSON;

        return Translator::find()
            ->select(['id', 'name AS text'])
            ->filterWhere(['like', 'name', $search])
            ->asArray()
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate(): string|Response
    {
        $model = new Translator();

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
        $model = Translator::findOne($id);

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
        $model = Translator::findOne($id);
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
     * @param Translator $model Record being created/updated.
     * 
     * @return bool Whether the record was saved successfully.
     */
    private function saveModel(Translator $model): bool
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
