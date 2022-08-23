<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use app\core\exceptions\FriendlyException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ReadingList".
 * 
 * @property int $id
 * @property string $name
 * 
 * @property-read Book[] $books
 * @property-read ReadingListItem[] $items
 */
class ReadingList extends ActiveRecord
{
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

    /**
     * Returns a query to the related records from table `ReadingListItem`.
     * 
     * @return ActiveQuery
     */
    public function getItems(): ActiveQuery
    {
        return $this->hasMany(ReadingListItem::class, ['readingListId' => 'id']);
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
