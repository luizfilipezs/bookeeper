<?php

namespace app\forms;

use app\core\helpers\TimeHelper;
use app\core\validation\{
    Boolean,
    CallbackMethod,
    EnableValidation,
    IsString,
    Required
};
use app\entities\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 */
#[EnableValidation]
class LoginForm extends Model
{
    #[Required]
    #[IsString]
    public $username;

    #[Required]
    #[IsString]
    #[CallbackMethod]
    public $password;

    #[Boolean(
        conversions: [Boolean::FROM_STRING]
    )]
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
            [['username', 'password', 'rememberMe'], 'safe'],
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
            $user = $this->getUser();

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

        $duration = $this->rememberMe ? TimeHelper::getDaysInMs(30) : 0;
        $user = $this->getUser();

        return Yii::$app->user->login($user, $duration);
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
