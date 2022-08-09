<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use Yii;
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
            'id' => Yii::t('app/label', 'ID'),
            'name' => Yii::t('app/label', 'Nome'),
        ];
    }

    public function getItems(): ActiveQuery
    {
        return $this->hasMany(ReadingListItem::class, ['readingListId' => 'id']);
    }
}
