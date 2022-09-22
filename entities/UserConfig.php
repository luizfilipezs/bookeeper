<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "User".
 * 
 * @property int $id 
 * @property string $userId
 * @property bool $reloadFormAfterSaving
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read User $user
 */
class UserConfig extends ActiveRecord
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
            [['reloadFormAfterSaving'], 'boolean'],
            [['reloadFormAfterSaving'], 'default', 'value' => false],
            ['userId', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'reloadFormAfterSaving' => 'Recarregar formulário após cadastro/edição',
            'createdAt' => 'Criado em',
            'updatedAt' => 'Atualizado em',
        ];
    }

    /**
     * Returns a query to the related record from table `User`.
     * 
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }
}
