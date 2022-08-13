<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Author".
 * 
 * @property int $id
 * @property string $name
 * @property string $nationality
 * 
 * @property-read Work[] $works
 */
class Author extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name', 'nationality'], 'string'],
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
     * Returns a query to the related records from table `Work`.
     * 
     * @return ActiveQuery
     */
    public function getWorks(): ActiveQuery
    {
        return $this->hasMany(Work::class, ['id' => 'workId'])
            ->viaTable(WorkAuthor::tableName(), ['authorId' => 'id']);
    }
}
