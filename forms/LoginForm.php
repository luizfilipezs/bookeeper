<?php

namespace app\forms;

use app\core\helpers\TimeHelper;
use app\entities\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 */
class LoginForm extends Model
{
    /**
     * Username.
     * 
     * @var string
     */
    public $username;

    /**
     * User password.
     * 
     * @var string
     */
    public $password;

    /**
     * Whether to keep user logged in.
     * 
     * @var bool
     */
    public $rememberMe = true;

    /**
     * User to be logged in.
     * 
     * @var User|null
     */
    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'password', 'rememberMe'], 'required'],
            [['username', 'password'], 'string'],
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated.
     */
    public function validatePassword(string $attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->user;

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * 
     * @return bool Whether the user is logged in successfully.
     */
    public function login(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        return Yii::$app->user->login($this->user, $this->rememberMe ? TimeHelper::getDaysInMs(30) : 0);
    }

    /**
     * Finds user by `username`.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->_user ??= User::findByUsername($this->username);
    }
}
