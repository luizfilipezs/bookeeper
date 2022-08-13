<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Work".
 * 
 * @property int $id
 * @property string $title
 * @property string $subtitle
 * 
 * @property-read Author[] $authors
 * 
 * @property-read string[] $authorNames
 */
class Work extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title', 'subtitle'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Título',
            'subtitle' => 'Subtítulo',
        ];
    }

    /**
     * Returns a query to the related records from table `Author`.
     * 
     * @return ActiveQuery
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'authorId'])
            ->viaTable(WorkAuthor::tableName(), ['workId' => 'id']);
    }

    /**
     * Creates a new relation between the given `Author` and the current work.
     * 
     * @param Author $author Author to be added to the current work.
     * 
     * @return WorkAuthor Pivot relation record.
     * 
     * @throws \app\core\exceptions\RelationAlreadyExistsException If relation already exists.
     */
    public function addAuthor(Author $author): WorkAuthor
    {
        return $this->addRelation($author);
    }

    /**
     * Removes an existing relation between the given `Author` and the current work.
     * 
     * @param Author $author Author to be removed from the current work.
     */
    public function removeAuthor(Author $author): void
    {
        $this->removeRelation($author);
    }

    /**
     * Returns all author names without repetitions.
     * 
     * @return string[] Author names.
     */
    public function getAuthorNames(): array
    {
        return $this->getAuthors()
            ->select('name')
            ->distinct()
            ->column();
    }
}
