<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use app\core\exceptions\FriendlyException;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "BookList".
 * 
 * @property int $id
 * @property int $userId
 * @property string $name
 * 
 * @property-read Book[] $books
 * @property-read BookListItem[] $items
 * @property-read User $user
 */
class BookList extends ActiveRecord
{
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
            'userId' => 'Usuário',
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
     * Returns a query to the related records from table `BookListItem`.
     * 
     * @return ActiveQuery
     */
    public function getItems(): ActiveQuery
    {
        return $this->hasMany(BookListItem::class, ['bookListId' => 'id']);
    }

    /**
     * Returns a query to the related records from table `Book`.
     * 
     * @return ActiveQuery
     */
    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'bookId'])->via('items');
    }

    /**
     * Removes all list items.
     * 
     * @throws FriendlyException If an item could not be removed.
     */
    public function removeAllItems(): void
    {
        foreach ($this->items as $item) {
            if ($item->delete() === false) {
                throw new FriendlyException('Não foi possível remover um dos itens.');
            }
        }
    }
}
