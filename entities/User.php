<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "User".
 * 
 * @property int $id 
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 * 
 * @property-read ReadingList[] $readingLists
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
     * @return bool Wether password is valid for the current user.
     */
    public function validatePassword(string $password): bool
    {
        return $this->password === $password;
    }

    /**
     * Returns a query to the related records from table `ReadingList`.
     * 
     * @return ActiveQuery
     */
    public function getReadingLists(): ActiveQuery
    {
        return $this->hasMany(ReadingList::class, ['userId' => 'id']);
    }
}
