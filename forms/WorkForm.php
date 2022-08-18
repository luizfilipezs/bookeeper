<?php

namespace app\forms;

use app\core\exceptions\RelationAlreadyExistsException;
use app\entities\{
    Author,
    Tag,
    Work
};

/**
 * This form is used to create/update book records.
 */
class WorkForm extends Work
{
    /**
     * List of author IDs.
     * 
     * @var string[]
     */
    public $authorIds;

    /**
     * List of tag IDs.
     * 
     * @var string[]
     */
    public $tagIds;

    /**
     * {@inheritdoc}
     */
    public final static function tableName(): string
    {
        return Work::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ['authorIds', 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id', 'allowArray' => true],
            ['tagIds', 'exist', 'targetClass' => Tag::class, 'targetAttribute' => 'id', 'allowArray' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'authorIds' => 'Autores',
            'tagIds' => 'Tags',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->hasNewAuthors()) {
            !$insert && $this->removeAllAuthors();
            $this->saveAuthors();
        }

        if ($this->hasNewTags()) {
            !$insert && $this->removeAllTags();
            $this->saveTags();
        }
    }

    /**
     * Creates relations between the book and its authors.
     */
    private function saveAuthors(): void
    {
        $authors = Author::findAll($this->authorIds);

        foreach ($authors as $author) {
            $this->saveAuthorRelation($author);
        }
    }

    /**
     * Creates a relation between a author and the book.
     */
    private function saveAuthorRelation(Author $author): void
    {
        try {
            $this->addAuthor($author);
        } catch (RelationAlreadyExistsException $e) {
            return;
        }
    }

    /**
     * Checks wether authors list changed.
     * 
     * @return bool Validation result.
     */
    private function hasNewAuthors(): bool
    {
        $currentAuthorIds = $this->getAuthors()
            ->select('id')
            ->column();
        
        return $this->authorIds != $currentAuthorIds;
    }

    /**
     * Checks wether authors list changed.
     * 
     * @return bool Validation result.
     */
    private function hasNewTags(): bool
    {
        $currentTagIds = $this->getTags()
            ->select('id')
            ->column();

        return $this->tagIds != $currentTagIds;
    }

    /**
     * Creates relations between the work and its tags.
     */
    private function saveTags(): void
    {
        $tags = Tag::findAll($this->tagIds);

        foreach ($tags as $tag) {
            $this->saveTagRelation($tag);
        }
    }

    /**
     * Creates a relation between a tag and the work.
     */
    private function saveTagRelation(Tag $tag): void
    {
        try {
            $this->addTag($tag);
        } catch (RelationAlreadyExistsException $e) {
            return;
        }
    }
}
