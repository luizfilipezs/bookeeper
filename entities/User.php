<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "User".
 * 
 * @property int $id 
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read BookList[] $bookLists
 * @property-read UserConfig $config
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): ?static
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): ?static
    {
        return self::findOne(['accessToken' => $token]);
    }

    /**
     * Finds a record by `username` field.
     *
     * @param string $username Username.
     * 
     * @return static|null User found, or `null`.
     */
    public static function findByUsername(string $username): ?static
    {
        return self::findOne(['username' => $username]);
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
            [['username', 'password', 'authKey'], 'required'],
            [['username', 'password', 'authKey', 'accessToken'], 'string'],
            ['username', 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'username' => 'Nome de usuário',
            'createdAt' => 'Criado em',
            'updatedAt' => 'Atualizado em',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates the given password.
     *
     * @param string $password Password to validate.
     * 
     * @return bool Whether password is valid for the current user.
     */
    public function validatePassword(string $password): bool
    {
        return $this->password === $password;
    }

    /**
     * Returns a query to the related record from table `UserConfig`.
     * 
     * @return ActiveQuery
     */
    public function getConfig(): ActiveQuery
    {
        return $this->hasOne(UserConfig::class, ['userId' => 'id']);
    }

    /**
     * Returns a query to the related records from table `BookList`.
     * 
     * @return ActiveQuery
     */
    public function getBookLists(): ActiveQuery
    {
        return $this->hasMany(BookList::class, ['userId' => 'id']);
    }
}
