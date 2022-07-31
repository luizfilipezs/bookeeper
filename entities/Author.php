<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use Yii;
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
            [['nome'], 'required'],
            [['nome', 'nationality'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app/label', 'ID'),
            'title' => Yii::t('app/label', 'Nome'),
        ];
    }

    public function getWorks(): ActiveQuery
    {
        return $this->hasMany(Work::class, ['id' => 'workId'])
            ->viaTable(WorkAuthor::tableName(), ['authorId' => 'id']);
    }
}
