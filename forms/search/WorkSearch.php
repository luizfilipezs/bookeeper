<?php

namespace app\forms\search;

use app\core\base\SearchInterface;
use app\entities\Work;
use yii\base\Model;
use yii\data\{
    ActiveDataProvider,
    DataProviderInterface
};
use yii\db\ActiveQueryInterface;

/**
 * Represents a search for `Work` records.
 */
class WorkSearch extends Model implements SearchInterface
{
    /**
     * Work title.
     * 
     * @var string
     */
    public $title;

    /**
     * Author ID.
     * 
     * @var int
     */
    public $authorId;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'authorId'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'title' => 'Nome',
            'authorId' => 'Autor',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function search(array $params = []): DataProviderInterface
    {
        $this->load($params);

        return new ActiveDataProvider([
            'query' => $this->getQuery(),
            'pagination' => [
                'pageSize' => false,
            ],
        ]);
    }

    /**
     * Returns a new query based on the specified parameters.
     * 
     * @return ActiveQueryInterface Active query with all filters applied.
     */
    private function getQuery(): ActiveQueryInterface
    {
        return Work::find()
            ->leftJoin('WorkAuthor', 'WorkAuthor.workId = Work.id')
            ->filterWhere([
                'WorkAuthor.authorId' => $this->authorId,
            ])
            ->andFilterWhere(['like', 'Work.title', $this->title])
            ->orderBy([
                'Work.createdAt' => SORT_DESC,
            ]);
    }
}
