<?php

namespace app\controllers;

use app\core\exceptions\FriendlyException;
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
                    'delete' => ['post'],
                    'search-books' => ['get'],
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
        $model = new ReadingListForm();

        if ($this->request->isPost && $this->saveModel($model)) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

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
