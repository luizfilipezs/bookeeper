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
            [['authorIds', 'tagIds'], 'filter', 'filter' => function (array|string $value) {
                return  is_array($value) ? $value : [];
            }],
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
            $this->resetAuthors();
        }

        if ($this->hasNewTags()) {
            $this->resetTags();
        }
    }

    /**
     * Removes the old authors and saves the new ones.
     */
    private function resetAuthors(): void
    {
        $this->removeAllAuthors();
        $this->saveAuthors();
    }

    /**
     * Removes the old tags and saves the new ones.
     */
    private function resetTags(): void
    {
        $this->removeAllTags();
        $this->saveTags();
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
     * Creates relations between the book and its authors.
     */
    private function saveAuthors(): void
    {
        foreach ($this->authorIds as $authorId) {
            $this->addAuthorById($authorId);
        }
    }

    /**
     * Creates a relation between a author and the work.
     * 
     * @param int $authorId The author identifier.
     */
    private function addAuthorById(int $authorId): void
    {
        $author = Author::findOne($authorId);

        try {
            $this->addAuthor($author);
        } catch (RelationAlreadyExistsException $e) {
            return;
        }
    }

    /**
     * Creates relations between the work and its tags.
     */
    private function saveTags(): void
    {
        foreach ($this->tagIds as $tagId) {
            $this->addTagById($tagId);
        }
    }

    /**
     * Creates a relation between a tag and the work.
     * 
     * @param int $tagId The tag identifier.
     */
    private function addTagById(int $tagId): void
    {
        $tag = Tag::findOne($tagId);

        try {
            $this->addTag($tag);
        } catch (RelationAlreadyExistsException $e) {
            return;
        }
    }
}
