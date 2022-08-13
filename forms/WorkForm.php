<?php

namespace app\forms;

use app\core\exceptions\RelationAlreadyExistsException;
use app\core\helpers\ArrayHelper;
use app\entities\{
    Author,
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'authorIds' => 'Autores',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert && $this->hasNewAuthors()) {
            $this->removePreviousAuthors();
            $this->saveAuthors();
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

        return !ArrayHelper::every($this->authorIds, function ($authorId) use ($currentAuthorIds) {
            return in_array($authorId, $currentAuthorIds);
        });
    }

    /**
     * Removes all relations with authors.
     */
    private function removePreviousAuthors(): void
    {
        foreach ($this->authors as $author) {
            $this->removeAuthor($author);
        }
    }
}
