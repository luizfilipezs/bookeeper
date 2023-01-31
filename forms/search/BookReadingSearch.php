<?php

namespace app\forms\search;

use app\core\base\SearchInterface;
use app\entities\{
    Book,
    BookReading,
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
class BookReadingSearch extends Model implements SearchInterface
{
    const STATUS_UNFINISHED = 0;
    const STATUS_FINISHED = 1;

    const STATUS_OPTIONS = [
        self::STATUS_UNFINISHED => 'Em andamento',
        self::STATUS_FINISHED => 'Concluída',
    ];

    /**
     * Book ID.
     * 
     * @var int
     */
    public $bookId;

    /**
     * Author ID.
     * 
     * @var int
     */
    public $workId;

    /**
     * Start date in Y-m-d format.
     * 
     * @var string
     */
    public $startDate;

    /**
     * End date in Y-m-d format.
     * 
     * @var string
     */
    public $endDate;

    /**
     * Reading status (0-1).
     * 
     * @var int
     */
    public $status;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bookId', 'workId'], 'integer'],
            [['startDate', 'startDate'], 'date', 'format' => 'Y-m-d'],
            [
                'endDate',
                'compare',
                'operator' => '>=',
                'compareAttribute' => 'startDate',
                'type' => 'date'
            ],
            ['status', 'in', 'range' => array_keys(self::STATUS_OPTIONS)],
            ['status', 'boolean'],
            ['bookId', 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => 'id'],
            ['workId', 'exist', 'skipOnError' => true, 'targetClass' => Work::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'bookId' => 'Livro',
            'workId' => 'Obra',
            'status' => 'Status da leitura',
            'startDate' => 'Data inicial de conclusão',
            'endDate' => 'Data final de conclusão',
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
        return BookReading::find()
            ->leftJoin('BookReadingWork', 'BookReadingWork.bookReadingId = BookReading.id')
            ->filterWhere([
                'BookReading.bookId' => $this->bookId,
                'BookReadingWork.workId' => $this->workId,
                'BookReading.isComplete' => $this->status,
            ])
            ->andFilterWhere(['>=', 'endDate', $this->startDate])
            ->andFilterWhere(['<=', 'endDate', $this->endDate])
            ->orderBy([
                'BookReading.isComplete' => SORT_ASC,
                'BookReading.endDate' => SORT_DESC,
                'BookReading.createdAt' => SORT_DESC,
            ]);
    }
}
