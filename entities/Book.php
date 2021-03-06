<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use app\core\enums\BookConservationState;
use app\core\exceptions\FriendlyException;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Book".
 * 
 * @property int $id
 * @property int $publishingCompanyId
 * @property string $title
 * @property string $subtitle
 * @property string $language
 * @property int $pages
 * @property string $year
 * @property string $conservationState
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
            [['publishingCompanyId', 'title', 'conservationState'], 'required'],
            [['language'], 'default', 'value' => 'Português'],
            [['conservationState'], 'default', 'value' => BookConservationState::New->value],
            [['publishingCompanyId', 'pages'], 'integer'],
            [['title', 'subtitle', 'language', 'year', 'conservationState'], 'string'],
            [['conservationState'], 'in', 'range' => BookConservationState::values()],
            [['publishingCompanyId'], 'exist', 'skipOnError' => true, 'targetClass' => PublishingCompany::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app/label', 'ID'),
            'publishingCompanyId' => Yii::t('app/label', 'Editora'),
            'title' => Yii::t('app/label', 'Título'),
            'subtitle' => Yii::t('app/label', 'Subtítulo'),
            'language' => Yii::t('app/label', 'Idioma'),
            'pages' => Yii::t('app/label', 'Número de páginas'),
            'year' => Yii::t('app/label', 'Ano de publicação'),
            'conservationState' => Yii::t('app/label', 'Estado de conservação'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete(): bool
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $this->removeWorks();

        return true;
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

    private function removeWorks(): void
    {
        try {
            foreach ($this->works as $work) $this->removeWork($work);
        } catch (\Exception $e) {
            throw new FriendlyException(Yii::t('app/error', 'Não foi possível remover todas as obras.'));
        }
    }
}
