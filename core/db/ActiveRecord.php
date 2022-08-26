<?php

namespace app\core\db;

use app\core\exceptions\FriendlyException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\StringHelper;

/**
 * Extends Yii2 `ActiveRecord` and implements automatic table name and other tools.
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return StringHelper::basename(static::class);
    }

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
     * Calls `save()` and throws and exception if saving fails.
     * 
     * @param bool $runValidation whether to perform validation (calling [[validate()]])
     * before saving the record. Defaults to `true`. If the validation fails, the record
     * will not be saved to the database and this method will return `false`.
     * 
     * @param array|null $attributeNames list of attribute names that need to be saved.
     * Defaults to `null`, meaning all attributes that are loaded from DB will be saved.
     * 
     * @throws FriendlyException if saving fails.
     */
    public function saveOrFail($runValidation = true, $attributeNames = null): void
    {
        if (!$this->save($runValidation, $attributeNames)) {
            throw new FriendlyException('Não foi possível salvar o registro. ' . implode(' ', $this->firstErrors));
        }
    }

    /**
     * Adds a pivot relation to the current record.
     * 
     * Usually we do this:
     * 
     * ```php
     * $bookAuthor = new BookAuthor();
     * $bookAuthor->bookId = $book->id;
     * $bookAuthor->authorId = $author->id;
     * $bookAuthor->save();
     * ```
     * 
     * But this method automates that routine:
     * 
     * ```php
     * class Book extends ActiveRecord
     * {
     *     public function addAuthor(Author $author): BookAuthor
     *     {
     *         return $this->addRelation($author);
     *     }
     * }
     * ```
     * 
     * It will automatically discover the relation class and its fields.
     * But it is also possible to define custom settings for them:
     * 
     * ```php
     * $this->addRelation(
     *     instance: $author,
     *     pivotClass: MyBookAuthor::class, // default would be BookAuthor::class
     *     fieldNameA: 'myBookId', // default would be 'bookId'
     *     fieldNameB: 'myAuthorId', // default would be 'authorId'
     *     before: function (BookAuthor $record) { // callback function before saving the record
     *         $record->doSomething();
     *     }
     * );
     * ```
     * 
     * @param self $instance Record to be related to the current one.
     * @param string $pivotClass (Optional) Pivot class name.
     * @param string $fieldNameA (Optional) Name of the field which represents this side of the relation.
     * @param string $fieldNameB (Optional) Name of the field which represents the other side of the relation.
     * @param callable $before (Optional) Callback function that will be called before creating the relation.
     * The argument passed to it is the record that is about to be created.
     * 
     * @return self Relation created.
     * 
     * @throws \Exception if creation fails.
     */
    protected function addRelation(self $instance, string $pivotClass = null, string $fieldNameA = null, string $fieldNameB = null, callable $before = null): self
    {
        $handler = $this->getPivotRelationHandler($instance, $pivotClass);

        if ($before) {
            $handler->on(PivotRelationHandler::EVENT_BEFORE_CREATE, fn ($pivotRecord) => call_user_func($before, $pivotRecord));
        }

        $handler->fieldNameA = $fieldNameA;
        $handler->fieldNameB = $fieldNameB;

        return $handler->createRelation();
    }

    /**
     * Like [[addRelation()]], this method deals with a pivot relation - removing it, in this case.
     * 
     * @param self $relatedRecord Record with which the current one has a pivot relation.
     * @param string $pivotClass (Optional) Name of the pivot class.
     * @param callable $before (Optional) Callback function to be executed before the record gets
     * removed. The pivot record will be passed as an argument to it.
     * 
     * Implementation:
     * 
     * ```php
     * public function removeAuthor(Author $author): void
     * {
     *     $this->removeRelation($author);
     * }
     * ```
     * 
     * or
     * 
     * ```php
     * public function removeAuthor(Author $author): void
     * {
     *     $this->removeRelation(
     *         relatedRecord: $author,
     *         pivotClass: BookAuthor::class,
     *         before: function (BookAuthor $record) {
     *             // do something before the record gets removed
     *         }
     *     );
     * }
     * ```
     * 
     * @throws \Exception if the operation fails.
     */
    protected function removeRelation(self $relatedRecord, string $pivotClass = null, callable $before = null): void
    {
        $handler = $this->getPivotRelationHandler($relatedRecord, $pivotClass);

        if ($before) {
            $handler->on(PivotRelationHandler::EVENT_BEFORE_DELETE, fn ($pivotRecord) => call_user_func($before, $pivotRecord));
        }

        $handler->removeRelation();
    }

    /**
     * Creates a query for the records of a relation via a pivot table.
     * 
     * The main difference from this method in comparison to the traditional `hasMany()` with
     * `via()` is that it shorten that operation to a single method and orders the results by
     * the pivot table ID.
     * 
     * @param string $modelClass Target relation model class.
     * @param string $via (Optional) Pivot table model class.
     * 
     * @return ActiveQuery Query from the given model class table.
     */
    protected function hasRelation(string $modelClass, string $via = null): ActiveQuery
    {
        $handler = $this->getPivotRelationHandler(new $modelClass, $via);

        return $handler->createQueryForRelation();
    }

    /**
     * Returns a new `PivotRelationHandler` for handling a relation with the given
     * record.
     * 
     * @param self $relatedRecord Record related to the current one.
     * @param string $pivotClass (Optional) Name of the model class which represents a pivot
     * relation between the current record and the given one.
     * 
     * @return PivotRelationHandler New handler instance properly configured.
     */
    private function getPivotRelationHandler(self $relatedRecord, string $pivotClass = null): PivotRelationHandler
    {
        return new PivotRelationHandler($this, $relatedRecord, $pivotClass);
    }
}
