<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for pivot table "WorkTag", which represents
 * a relation of many tags to one work.
 * 
 * @property int $id
 * @property int $workId
 * @property int $tagId
 * 
 * @property-read Tag $tag
 * @property-read Work $work
 */
class WorkTag extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // common rules
            [['workId', 'tagId'], 'required'],
            [['workId', 'tagId'], 'integer'],
            // relations
            ['workId', 'exist', 'skipOnError' => true, 'targetClass' => Work::class, 'targetAttribute' => 'id'],
            ['tagId', 'exist', 'skipOnError' => true, 'targetClass' => Tag::class, 'targetAttribute' => 'id'],
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
     * Returns a query to the related record from table `Tag`.
     * 
     * @return ActiveQuery
     */
    public function getTag(): ActiveQuery
    {
        return $this->hasOne(Tag::class, ['id' => 'tagId']);
    }
}
