<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use app\core\enums\BookConservationState;
use app\core\exceptions\FriendlyException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "Book".
 * 
 * @property int $id
 * @property int $publishingCompanyId
 * @property string $isbn
 * @property string $title
 * @property string $subtitle
 * @property string $language
 * @property int $volumes
 * @property int $pages
 * @property string $year
 * @property string $conservationState
 * @property string $comments
 * @property string $acquiredAt
 * @property float $estimatedValue
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read BookWork[] $bookWorks
 * @property-read BookTranslator[] $bookTranslators
 * @property-read PublishingCompany $publishingCompany
 * @property-read Work[] $works
 * @property-read Translator[] $translators
 * 
 * @property-read string[] $authorNames
 * @property-read string[] $tagNames
 * @property-read string[] $translatorNames
 */
class Book extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // default values
            ['language', 'default', 'value' => 'Português'],
            ['conservationState', 'default', 'value' => BookConservationState::New->value],
            ['volumes', 'default', 'value' => 1],
            // common rules
            [['publishingCompanyId', 'title'], 'required'],
            [['publishingCompanyId', 'volumes', 'pages'], 'integer'],
            ['estimatedValue', 'number'],
            [['title', 'subtitle', 'language', 'year', 'conservationState', 'isbn'], 'string'],
            ['conservationState', 'in', 'range' => BookConservationState::values()],
            ['comments', 'string', 'max' => 255],
            ['acquiredAt', 'date', 'format' => 'php:Y-m-d'],
            // relations
            ['publishingCompanyId', 'exist', 'skipOnError' => true, 'targetClass' => PublishingCompany::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'publishingCompanyId' => 'Editora',
            'isbn' => 'ISBN',
            'title' => 'Título',
            'subtitle' => 'Subtítulo',
            'language' => 'Idioma',
            'volumes' => 'Qntd. de volumes',
            'pages' => 'Número de páginas',
            'year' => 'Ano de publicação',
            'conservationState' => 'Estado de conservação',
            'comments' => 'Observações',
            'acquiredAt' => 'Data de aquisição',
            'estimatedValue' => 'Valor estimado',
            'createdAt' => 'Criado em',
            'updatedAt' => 'Atualizado em',
        ];
    }

    /**
     * Returns a query to the related record from table `PublishingCompany`.
     * 
     * @return ActiveQuery
     */
    public function getPublishingCompany(): ActiveQuery
    {
        return $this->hasOne(PublishingCompany::class, ['id' => 'publishingCompanyId']);
    }

    /**
     * Returns a query to the related records from table `BookWork`.
     * 
     * @return ActiveQuery
     */
    public function getBookWorks(): ActiveQuery
    {
        return $this->hasMany(BookWork::class, ['bookId' => 'id']);
    }

    /**
     * Returns a query to the related records from table `Work` via a JOIN
     * operation with the pivot table `BookWork`.
     * 
     * @return ActiveQuery
     */
    public function getWorks(): ActiveQuery
    {
        return $this->hasRelation(Work::class);
    }

    /**
     * Creates a new relation between the given `Work` and the current book.
     * 
     * @param Work $work Work to be added to the current book.
     * 
     * @return BookWork Pivot relation record.
     * 
     * @throws \app\core\exceptions\RelationAlreadyExistsException If relation already exists.
     */
    public function addWork(Work $work): BookWork
    {
        return $this->addRelation($work);
    }

    /**
     * Removes an existing relation between the given `Work` and the current book.
     * 
     * @param Work $work Work to be removed from the current book.
     */
    public function removeWork(Work $work): void
    {
        $this->removeRelation($work);
    }

    /**
     * Removes all works related to the current book.
     * 
     * @throws FriendlyException If some relation could no be deleted.
     */
    public function removeAllWorks(): void
    {
        foreach ($this->works as $work) {
            $this->removeWork($work);
        }
    }

    /**
     * Returns a query to the related records from table `BookTranslator`.
     * 
     * @return ActiveQuery
     */
    public function getBookTranslators(): ActiveQuery
    {
        return $this->hasMany(BookTranslator::class, ['bookId' => 'id']);
    }

    /**
     * Returns a query to the related records from table `Translator` via a JOIN
     * operation with the pivot table `BookTranslator`.
     * 
     * @return ActiveQuery
     */
    public function getTranslators(): ActiveQuery
    {
        return $this->hasRelation(Translator::class);
    }

    /**
     * Creates a new relation between the given `Translator` and the current book.
     * 
     * @param Translator $translator Translator to be added to the current book.
     * 
     * @return BookTranslator Pivot relation record.
     * 
     * @throws \app\core\exceptions\RelationAlreadyExistsException If relation already exists.
     */
    public function addTranslator(Translator $translator): BookTranslator
    {
        return $this->addRelation($translator);
    }

    /**
     * Removes an existing relation between the given `Translator` and the current book.
     * 
     * @param Translator $translator Translator to be removed from the current book.
     */
    public function removeTranslator(Translator $translator): void
    {
        $this->removeRelation($translator);
    }

    /**
     * Removes all translators related to the current book.
     * 
     * @throws FriendlyException If some relation could no be deleted.
     */
    public function removeAllTranslators(): void
    {
        foreach ($this->translators as $translator) {
            $this->removeTranslator($translator);
        }
    }

    /**
     * Returns all author names without repetitions.
     * 
     * @return string[] Author names.
     */
    public function getAuthorNames(): array
    {
        $names = $this->getWorks()
            ->select('Author.name')
            ->joinWith('workAuthors.author', false)
            ->addOrderBy('WorkAuthor.id ASC')
            ->column();

        return array_unique($names);
    }

    /**
     * Returns all translator names.
     * 
     * @return string[] Translator names.
     */
    public function getTranslatorNames(): array
    {
        return $this->getTranslators()
            ->select('Translator.name')
            ->addOrderBy('BookTranslator.id ASC')
            ->column();
    }

    /**
     * Returns all tag names without repetitions.
     * 
     * @return string[] Tag names.
     */
    public function getTagNames(): array
    {
        $names = $this->getWorks()
            ->select('Tag.name')
            ->joinWith('workTags.tag', false)
            ->addOrderBy('WorkTag.id ASC')
            ->column();

        return array_unique($names);
    }
}
