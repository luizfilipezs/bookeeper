<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use app\core\exceptions\RelationAlreadyExistsException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Work".
 * 
 * @property int $id
 * @property string $title
 * @property string $subtitle
 * 
 * @property-read Author[] $authors
 * @property-read Tag[] $tags
 * 
 * @property-read string[] $authorNames
 * @property-read string[] $tagNames
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
     * @throws RelationAlreadyExistsException If relation already exists.
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
     * Removes all relations with authors.
     */
    public function removeAllAuthors(): void
    {
        foreach ($this->authors as $author) {
            $this->removeAuthor($author);
        }
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

    /**
     * Returns a query to the related records from table `Tag`.
     * 
     * @return ActiveQuery
     */
    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tagId'])
            ->viaTable(WorkTag::tableName(), ['workId' => 'id']);
    }

    /**
     * Creates a new relation between the given `Tag` and the current work.
     * 
     * @param Tag $tag Tag to be added to the current work.
     * 
     * @return WorkTag Pivot relation record.
     * 
     * @throws RelationAlreadyExistsException If relation already exists.
     */
    public function addTag(Tag $tag): WorkTag
    {
        return $this->addRelation($tag);
    }

    /**
     * Removes an existing relation between the given `Tag` and the current work.
     * 
     * @param Tag $tag Tag to be removed from the current work.
     */
    public function removeTag(Tag $tag): void
    {
        $this->removeRelation($tag);
    }

    /**
     * Removes all relations with tags.
     */
    public function removeAllTags(): void
    {
        foreach ($this->tags as $tag) {
            $this->removeTag($tag);
        }
    }

    /**
     * Returns all tag names.
     * 
     * @return string[] Tag names.
     */
    public function getTagNames(): array
    {
        return $this->getTags()
            ->select('name')
            ->column();
    }
}
