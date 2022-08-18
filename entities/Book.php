<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use app\core\enums\BookConservationState;
use app\core\exceptions\FriendlyException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Book".
 * 
 * @property int $id
 * @property int $publishingCompanyId
 * @property string $title
 * @property string $subtitle
 * @property string $language
 * @property int $volumes
 * @property int $pages
 * @property string $year
 * @property string $conservationState
 * 
 * @property-read PublishingCompany $publishingCompany
 * @property-read Work[] $works
 * 
 * @property-read string[] $authorNames
 * @property-read string[] $tagNames
 */
class Book extends ActiveRecord
{
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
            [['publishingCompanyId', 'title', 'conservationState', 'volumes'], 'required'],
            [['publishingCompanyId', 'volumes', 'pages'], 'integer'],
            [['title', 'subtitle', 'language', 'year', 'conservationState'], 'string'],
            ['conservationState', 'in', 'range' => BookConservationState::values()],
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
            'title' => 'Título',
            'subtitle' => 'Subtítulo',
            'language' => 'Idioma',
            'volumes' => 'Qntd. de volumes',
            'pages' => 'Número de páginas',
            'year' => 'Ano de publicação',
            'conservationState' => 'Estado de conservação',
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
     * Returns a query to the related records from table `Work`.
     * 
     * @return ActiveQuery
     */
    public function getWorks(): ActiveQuery
    {
        return $this->hasMany(Work::class, ['id' => 'workId'])
            ->viaTable(BookWork::tableName(), ['bookId' => 'id']);
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
        try {
            foreach ($this->works as $work) $this->removeWork($work);
        } catch (\Exception $e) {
            throw new FriendlyException('Não foi possível remover todas as obras vinculadas ao livro.');
        }
    }

    /**
     * Returns all author names without repetitions.
     * 
     * @return string[] Author names.
     */
    public function getAuthorNames(): array
    {
        return $this->getWorks()
            ->select('Author.name')
            ->distinct()
            ->joinWith('authors', false)
            ->column();
    }

    /**
     * Returns all tag names without repetitions.
     * 
     * @return string[] Tag names.
     */
    public function getTagNames(): array
    {
        return $this->getWorks()
            ->select('Tag.name')
            ->distinct()
            ->joinWith('tags', false)
            ->column();
    }
}
