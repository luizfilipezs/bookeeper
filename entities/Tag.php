<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Tag".
 * 
 * @property int $id
 * @property int|null $userId
 * @property string $name
 * 
 * @property-read User $user
 * 
 * @property-read bool $isEditable Whether the user has permission to edit or delete the record.
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
    public function behaviors(): array
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'userId',
                'updatedByAttribute' => false,
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
            ['name', 'unique'],
            ['userId', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => 'id'],
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
     * Returns a query to the related record from table `User`.
     * 
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'userId']);
    }

    /**
     * Checks whether the user has permission to edit or delete the record.
     * 
     * @return bool Validation result.
     */
    public function getIsEditable(): bool
    {
        return $this->userId === Yii::$app->user->identity->id;
    }
}
