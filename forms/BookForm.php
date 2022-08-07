<?php

namespace app\forms;

use app\core\exceptions\RelationAlreadyExistsException;
use app\core\validation\ForeignKey;
use app\entities\Book;
use app\entities\Work;
use Yii;

/**
 * This form is used to create/update book records.
 */
class BookForm extends Book
{
    /**
     * List of work IDs.
     * 
     * @var string[]
     */
    #[ForeignKey(
        model: Work::class,
        multiple: true
    )]
    public $workIds;

    /**
     * {@inheritdoc}
     */
    public final static function tableName(): string
    {
        return Book::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'workIds' => Yii::t('app/label', 'Obras'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$this->workIds && !$this->getWorks()->exists()) {
            $this->generateWork();
        }

        $this->saveWorks();
    }

    private function saveWorks(): void
    {
        $works = Work::findAll($this->workIds);

        foreach ($works as $work) {
            $this->saveWorkRelation($work);
        }
    }

    private function saveWorkRelation(Work $work): void
    {
        try {
            $this->addWork($work);
        } catch (RelationAlreadyExistsException $e) {
            return;
        }
    }

    private function generateWork(): void
    {
        $work = new Work();
        $work->title = $this->title;
        $work->subtitle = $this->subtitle;
        $work->saveOrFail();

        $this->workIds = "{$work->id}";
    }
}
