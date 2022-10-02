<?php

namespace app\controllers;

use app\core\exceptions\FriendlyException;
use app\core\web\{
    IActionCreate,
    IActionDelete,
    IActionIndex,
    IActionUpdate,
    IActionView
};
use app\entities\BookReading;
use app\entities\BookWork;
use app\forms\BookReadingForm;
use app\forms\search\BookReadingSearch;
use Yii;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Provides actions for handling operations related to the model `BookReading`.
 */
class BookReadingController extends Controller implements IActionIndex, IActionView, IActionCreate, IActionUpdate, IActionDelete
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
                    'view' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                    'delete' => ['post'],
                    'list-works' => ['get'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actionIndex(): string
    {
        $searchModel = new BookReadingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionView(int $id): string
    {
        $model = BookReading::findOne($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate(): string|Response
    {
        $model = new BookReadingForm();

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
        $model = BookReadingForm::findOne($id);

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
        $model = BookReading::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->delete() !== false) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Leitura removida com sucesso.');
            }
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a leitura. Provavelmente há outros itens vinculados a ela.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a leitura.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * List works by the give book ID.
     * 
     * @param int $bookId Book identifier.
     * @param string $search Search term.
     * 
     * @return array[] Results found.
     */
    public function actionListWorks(int $bookId, string $search): array
    {
        $this->response->format = Response::FORMAT_JSON;

        return BookWork::find()
            ->select(['Work.id', 'Work.title AS text'])
            ->joinWith('work')
            ->where(['BookWork.bookId' => $bookId])
            ->filterWhere(['like', 'Work.title', $search])
            ->asArray()
            ->all();
    }

    /**
     * Saves the record into the database.
     * 
     * @param BookReadingForm $model Record being created/updated.
     * 
     * @return bool Whether the record was saved successfully.
     */
    private function saveModel(BookReadingForm $model): bool
    {
        if (!$model->load($this->request->post())) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->saveOrFail();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Leitura salva com sucesso!');

            return true;
        } catch (FriendlyException $friendlyException) {
            Yii::$app->session->setFlash('error', $friendlyException->getMessage());
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível salvar a leitura.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return false;
    }
}
