<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use Yii;
use yii\behaviors\{
    BlameableBehavior,
    TimestampBehavior
};
use yii\db\{
    ActiveQuery,
    Expression
};

/**
 * This is the model class for table "Tag".
 * 
 * @property int $id
 * @property int|null $userId
 * @property string $name
 * @property string $createdAt
 * @property string $updatedAt
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
            'createdAt' => 'Criada em',
            'updatedAt' => 'Atualizada em',
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
        if (Yii::$app->user->isGuest) {
            return false;
        }

        return $this->userId === Yii::$app->user->identity->id;
    }
}
