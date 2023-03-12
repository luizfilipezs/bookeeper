<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use app\core\enums\Nationality;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "Translator".
 * 
 * @property int $id
 * @property string $name
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read Work[] $works
 */
class Translator extends ActiveRecord
{
    /**
     * Finds a record by the column `name`.
     * 
     * @param string $name Translator name.
     * 
     * @return static|null Translator matching the name, or `null` if nothing matches.
     */
    public static function findByName(string $name): ?static
    {
        return static::findOne(['name' => $name]);
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
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string'],
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
            ->viaTable(BookTranslator::tableName(), ['authorId' => 'id']);
    }
}
