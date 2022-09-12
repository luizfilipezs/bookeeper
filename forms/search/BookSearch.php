<?php

namespace app\forms\search;

use app\core\base\SearchInterface;
use app\entities\{
    Author,
    Book,
    PublishingCompany,
    Tag,
    Work
};
use yii\base\Model;
use yii\data\{
    ActiveDataProvider,
    DataProviderInterface
};
use yii\db\ActiveQueryInterface;

/**
 * Represents a search for `Book` records.
 */
class BookSearch extends Model implements SearchInterface
{
    /**
     * Number of results per page.
     * 
     * @var int
     */
    const PAGE_SIZE = 25;

    const ORDER_BY_DATE_DESC = '-createdAt';
    const ORDER_BY_DATE_ASC = 'createdAt';
    const ORDER_BY_TITLE_ASC = 'title';
    const ORDER_BY_TITLE_DESC = '-title';

    /**
     * Array with labels for the ordering options, where the keys are the options.
     */
    const ORDER_BY_OPTIONS = [
        self::ORDER_BY_DATE_DESC => 'Mais recentes',
        self::ORDER_BY_DATE_ASC => 'Mais antigos',
        self::ORDER_BY_TITLE_ASC => 'Título (A-Z)',
        self::ORDER_BY_TITLE_DESC => 'Título (Z-A)',
    ];

    /**
     * Book title.
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
     * Work ID.
     * 
     * @var int
     */
    public $workId;

    /**
     * Publishing company ID.
     * 
     * @var int
     */
    public $publishingCompanyId;

    /**
     * Comma-separated list of tag IDs.
     * 
     * @var string
     */
    public $tagIds;

    /**
     * Ordering criteria.
     * 
     * @var string
     */
    public $orderBy = self::ORDER_BY_DATE_DESC;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['orderBy', 'required'],
            ['orderBy', 'string'],
            ['orderBy', 'in', 'range' => array_keys(self::ORDER_BY_OPTIONS)],
            ['title', 'string'],
            [['workId', 'authorId', 'publishingCompanyId'], 'integer'],
            ['tagIds', 'each', 'rule' => ['integer']],
            ['workId', 'exist', 'skipOnError' => true, 'targetClass' => Work::class, 'targetAttribute' => 'id'],
            ['authorId', 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => 'id'],
            ['publishingCompanyId', 'exist', 'skipOnError' => true, 'targetClass' => PublishingCompany::class, 'targetAttribute' => 'id'],
            ['tagIds', 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => 'id', 'allowArray' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'title' => 'Título',
            'workId' => 'Obra',
            'authorId' => 'Autor',
            'publishingCompanyId' => 'Editora',
            'tagIds' => 'Tags',
            'orderBy' => 'Ordenar por',
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
                'pageSize' => self::PAGE_SIZE,
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
        return Book::find()
            ->joinWith([
                'bookWorks',
                'bookWorks.work.workAuthors',
                'bookWorks.work.workTags',
            ], false)
            ->filterWhere([
                'Book.publishingCompanyId' => $this->publishingCompanyId,
                'BookWork.workId' => $this->workId,
                'WorkAuthor.authorId' => $this->authorId,
                'WorkTag.tagId' => $this->tagIds,
            ])
            ->andFilterWhere(['like', 'Book.title', $this->title])
            ->orderBy(match ($this->orderBy) {
                self::ORDER_BY_DATE_ASC => 'Book.createdAt ASC',
                self::ORDER_BY_DATE_DESC => 'Book.createdAt DESC',
                self::ORDER_BY_TITLE_ASC => 'Book.title ASC',
                self::ORDER_BY_TITLE_DESC => 'Book.title DESC',
            });
    }
}
