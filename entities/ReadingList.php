<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ReadingList".
 * 
 * @property int $id
 * @property string $name
 * 
 * @property-read ReadingListItem[] $items
 */
class ReadingList extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nome',
        ];
    }

    /**
     * Returns a query to the related records from table `ReadingListItem`.
     * 
     * @return ActiveQuery
     */
    public function getItems(): ActiveQuery
    {
        return $this->hasMany(ReadingListItem::class, ['readingListId' => 'id']);
    }
}
