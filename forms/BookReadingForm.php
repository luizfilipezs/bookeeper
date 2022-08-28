<?php

namespace app\forms;

use app\core\exceptions\RelationAlreadyExistsException;
use app\entities\{
    BookReading,
    Work
};

/**
 * This form is used to create/update book reading records.
 */
class BookReadingForm extends BookReading
{
    /**
     * List of work IDs.
     * 
     * @var string[]
     */
    public $workIds;

    /**
     * {@inheritdoc}
     */
    public final static function tableName(): string
    {
        return BookReading::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ['workIds', 'filter', 'filter' => fn ($value) => is_array($value) ? $value : []],
            ['workIds', 'exist', 'targetClass' => Work::class, 'targetAttribute' => 'id', 'allowArray' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'workIds' => 'Obras',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->hasNewWorks()) {
            $this->resetWorks();
        }
    }

    /**
     * Removes the old works and saves the new ones.
     */
    private function resetWorks(): void
    {
        $this->removeAllWorks();
        $this->saveWorks();
    }

    /**
     * Checks whether works list changed.
     * 
     * @return bool Validation result.
     */
    private function hasNewWorks(): bool
    {
        $currentWorkIds = $this->getWorks()
            ->select('Work.id')
            ->column();
        
        return $this->workIds != $currentWorkIds;
    }

    /**
     * Creates relations between the reading list and the works.
     */
    private function saveWorks(): void
    {
        foreach ($this->workIds as $workId) {
            $this->addWorkById($workId);
        }
    }

    /**
     * Creates a relation between a work and the book reading.
     * 
     * @param int $workId The work identifier.
     */
    private function addWorkById(int $workId): void
    {
        $work = Work::findOne($workId);

        try {
            $this->addWork($work);
        } catch (RelationAlreadyExistsException $e) {
            return;
        }
    }
}
