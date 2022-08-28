<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for pivot table "WorkAuthor", which represents
 * a relation of many authors to one work.
 * 
 * @property int $id
 * @property int $workId
 * @property int $authorId
 * 
 * @property-read Author $author
 * @property-read Work $work
 */
class WorkAuthor extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['workId', 'authorId'], 'required'],
            [['workId', 'authorId'], 'integer'],
            ['workId', 'exist', 'skipOnError' => true, 'targetClass' => Work::class, 'targetAttribute' => 'id'],
            ['authorId', 'exist', 'skipOnError' => true, 'targetClass' => Author::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * Returns a query to the related record from table `Work`.
     * 
     * @return ActiveQuery
     */
    public function getWork(): ActiveQuery
    {
        return $this->hasOne(Work::class, ['id' => 'workId']);
    }

    /**
     * Returns a query to the related record from table `Author`.
     * 
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'authorId']);
    }
}
