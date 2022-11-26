<?php

namespace app\controllers;

use app\core\exceptions\FriendlyException;
use app\core\web\ICrudActions;
use app\entities\Tag;
use app\forms\search\TagSearch;
use Yii;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Provides actions for handling operations related to the model `Tag`.
 */
class TagController extends Controller implements ICrudActions
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
        $searchModel = new TagSearch();
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

        return Tag::find()
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
        $model = Tag::findOne($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate(): string|Response
    {
        $model = new Tag();

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
        $model = Tag::findOne($id);

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
        $model = Tag::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->delete() !== false) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Tag removida com sucesso.');
            }
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a tag. Provavelmente há outros itens vinculados a ela.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a tag.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * Saves the record into the database.
     * 
     * @param Tag $model Record being created/updated.
     * 
     * @return bool Whether the record was saved successfully.
     */
    private function saveModel(Tag $model): bool
    {
        if (!$model->load($this->request->post())) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->saveOrFail();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Tag salva com sucesso!');

            return true;
        } catch (FriendlyException $friendlyException) {
            Yii::$app->session->setFlash('error', $friendlyException->getMessage());
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível salvar a tag.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return false;
    }
}
