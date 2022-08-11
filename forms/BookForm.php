<?php

namespace app\forms;

use app\core\exceptions\FriendlyException;
use app\core\exceptions\RelationAlreadyExistsException;
use app\entities\{
    Book,
    Work
};

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
    public $workIds;

    /**
     * Wether a work should automatically be created if none is specified.
     * 
     * @var bool
     */
    public $canAutoCreateWork = true;

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
    public function rules(): array
    {
        return [
            ...parent::rules(),
            [['canAutoCreateWork'], 'boolean'],
            [['workIds'], 'exist', 'targetClass' => Work::class, 'targetAttribute' => 'id', 'allowArray' => true],
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

        if ($this->canAutoCreateWork && !$this->workIds && !$this->getWorks()->exists()) {
            $this->generateWork();
        }

        $this->saveWorks();
    }

    /**
     * Saves a new work with the same title and subtitle of the current book and
     * add it to the work list.
     * 
     * @throws FriendlyException If work could not be saved.
     */
    private function generateWork(): void
    {
        $work = new Work();
        $work->title = $this->title;
        $work->subtitle = $this->subtitle;
        
        if (!$work->save()) {
            throw new FriendlyException('Não foi possível salvar a obra referente ao livro. Será necessário cadastrá-la manualmente.');
        }

        $this->workIds[] = $work->id;
    }

    /**
     * Creates relations between the book and its works.
     */
    private function saveWorks(): void
    {
        $works = Work::findAll($this->workIds);

        foreach ($works as $work) {
            $this->saveWorkRelation($work);
        }
    }

    /**
     * Creates a relation between a work and the book.
     */
    private function saveWorkRelation(Work $work): void
    {
        try {
            $this->addWork($work);
        } catch (RelationAlreadyExistsException $e) {
            return;
        }
    }
}
