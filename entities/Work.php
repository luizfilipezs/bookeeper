<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Work".
 * 
 * @property int $id
 * @property string $title
 * @property string $subtitle
 * 
 * @property-read Author[] $authors
 * 
 * @property-read string[] $authorNames
 */
class Work extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title', 'subtitle'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app/label', 'ID'),
            'title' => Yii::t('app/label', 'Título'),
            'subtitle' => Yii::t('app/label', 'Subtítulo'),
        ];
    }

    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'authorId'])
            ->viaTable(WorkAuthor::tableName(), ['workId' => 'id']);
    }

    public function addAuthor(Author $author): WorkAuthor
    {
        return $this->addRelation($author);
    }

    public function removeAuthor(Author $author): void
    {
        $this->removeRelation($author);
    }

    public function getAuthorNames(): array
    {
        return $this->getAuthors()
            ->select('Author.name')
            ->column();
    }
}
