<?php

namespace app\forms\search;

use app\core\base\SearchInterface;
use app\entities\Tag;
use yii\base\Model;
use yii\data\{
    ActiveDataProvider,
    DataProviderInterface
};
use yii\db\ActiveQueryInterface;

/**
 * Represents a search for `Tag` records.
 */
class TagSearch extends Model implements SearchInterface
{
    /**
     * Tag name.
     * 
     * @var string
     */
    public $name;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['name', 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Nome',
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
        return Tag::find()
            ->filterWhere(['like', 'name', $this->name])
            ->orderBy('name');
    }
}
