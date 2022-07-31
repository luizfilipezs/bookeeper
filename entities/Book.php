<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Book".
 * 
 * @property int $id
 * @property int $publishingCompanyId
 * @property string $title
 * @property string $subtitle
 * @property string $year
 * 
 * @property-read PublishingCompany $publishingCompany
 * @property-read Work[] $works
 * 
 * @property-read string[] $authorNames
 */
class Book extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'publishingCompanyId'], 'required'],
            [['publishingCompanyId'], 'integer'],
            [['title', 'subtitle', 'year'], 'string'],
            [['publishingCompanyId'], 'exist', 'skipOnError' => true, 'targetClass' => PublishingCompany::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getPublishingCompany(): ActiveQuery
    {
        return $this->hasOne(PublishingCompany::class, ['id' => 'publishingCompanyId']);
    }

    public function getWorks(): ActiveQuery
    {
        return $this->hasMany(Work::class, ['id' => 'workId'])
            ->viaTable(BookWork::tableName(), ['bookId' => 'id']);
    }

    public function addWork(Work $work): BookWork
    {
        return $this->addRelation($work);
    }

    public function removeWork(Work $work): void
    {
        $this->removeRelation($work);
    }

    public function getAuthorNames(): array
    {
        return $this->getWorks()
            ->select('Author.name')
            ->joinWith('authors')
            ->column();
    }
}
