<?php

namespace app\controllers;

use app\core\exceptions\FriendlyException;
use app\core\web\{
    IActionIndex,
    IActionCreate,
    IActionDelete,
    IActionUpdate
};
use app\entities\Book;
use app\entities\ReadingList;
use app\forms\ReadingListForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;
use yii\filters\VerbFilter;
use yii\web\{
    Controller,
    Response
};

/**
 * Provides actions for handling operations related to the model `ReadingList`.
 */
class ReadingListController extends Controller implements IActionIndex, IActionCreate, IActionUpdate, IActionDelete
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
                    'delete' => ['post'],
                    'search-books' => ['get'],
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
            'query' => ReadingList::find(),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function actionCreate(): string|Response
    {
        $model = new ReadingListForm();

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
        $model = ReadingListForm::findOne($id);

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
        $model = ReadingList::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->delete() !== false) {
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Lista removida com sucesso.');
            }
        } catch (IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a lista. Provavelmente há outros itens vinculados a ela.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível excluir a lista.');
        } finally {
            $transaction->isActive && $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * Searches books by their title or subtitle and returns an array with the results
     * to be used in the form.
     * 
     * @param string $search (Optional) Search term.
     * 
     * @return array[] Array of results, where each element is an array with the keys
     * `id`, `text`, and `template`.
     */
    public function actionSearchBooks(?string $search): array
    {
        $this->response->format = Response::FORMAT_JSON;

        /** @var Book[] */
        $books = Book::find()
            ->filterWhere(['like', 'title', $search])
            ->orFilterWhere(['like', 'subtitle', $search])
            ->all();

        /** @var array[] */
        $results = [];

        foreach ($books as $book)
            $results[] = [
                'id' => $book->id,
                'text' => $book->title,
                'template' => $this->renderPartial('_list-item', [
                    'model' => $book,
                ]),
            ];

        return $results;
    }

    /**
     * Saves the record into the database.
     * 
     * @param ReadingListForm $model Form to the record being created/updated.
     * 
     * @return bool Whether the record was saved successfully.
     */
    private function saveModel(ReadingListForm $model): bool
    {
        if (!$model->load($this->request->post())) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->saveOrFail();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Lista salva com sucesso!');
        } catch (FriendlyException $friendlyException) {
            Yii::$app->session->setFlash('error', $friendlyException->getMessage());
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Não foi possível salvar a lista.');
        } finally {
            if ($transaction->isActive) {
                $transaction->rollBack();
                return false;
            }
        }

        return true;
    }
}
