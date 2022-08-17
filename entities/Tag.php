<?php

namespace app\entities;

use app\core\db\ActiveRecord;

/**
 * This is the model class for table "Tag".
 * 
 * @property int $id
 * @property string $name
 */
class Tag extends ActiveRecord
{
    /**
     * Finds a record by the column `name`.
     * 
     * @param string $name Tag name.
     * 
     * @return static|null Tag matching the name, or `null` if nothing matches.
     */
    public static function findByName(string $name): ?static
    {
        return static::findOne(['name' => $name]);
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
        ];
    }
}
