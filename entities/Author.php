<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use app\core\enums\Nationality;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "Author".
 * 
 * @property int $id
 * @property string $name
 * @property string $nationality
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read Work[] $works
 */
class Author extends ActiveRecord
{
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
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name', 'nationality'], 'string'],
            ['nationality', 'in', 'range' => Nationality::values()],
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
            'nationality' => 'Nacionalidade',
            'createdAt' => 'Criado em',
            'updatedAt' => 'Atualizado em',
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
