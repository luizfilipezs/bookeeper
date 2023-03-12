<?php

namespace app\forms;

use app\core\exceptions\FriendlyException;
use app\core\exceptions\RelationAlreadyExistsException;
use app\entities\{
    Book,
    BookReading,
    Translator,
    Work
};

/**
 * This form is used to create/update book records.
 */
class BookForm extends Book
{
    /**
     * List of translator IDs.
     * 
     * @var string[]
     */
    public $translatorIds;

    /**
     * List of work IDs.
     * 
     * @var string[]
     */
    public $workIds;

    /**
     * List of author IDs. Used to create a default work.
     * 
     * @var string[]
     */
    public $authorIds;

    /**
     * List of tag IDs. Used to create a default work.
     * 
     * @var string[]
     */
    public $tagIds;

    /**
     * Whether a work should automatically be created if none is specified.
     * 
     * @var bool
     */
    public $canAutoCreateWork;

    /**
     * Whether the book was already read.
     * 
     * @var bool
     */
    public $markAsRead;

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
    public function init(): void
    {
        parent::init();

        $this->canAutoCreateWork ??= $this->isNewRecord;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            [['canAutoCreateWork', 'markAsRead'], 'boolean'],
            [['markAsRead'], 'default', 'value' => false],
            [['authorIds', 'tagIds'], 'safe'],
            [['translatorIds', 'workIds'], 'filter', 'filter' => function ($value) {
                return  is_array($value) ? $value : [];
            }],
            ['translatorIds', 'exist', 'targetClass' => Translator::class, 'targetAttribute' => 'id', 'allowArray' => true],
            ['workIds', 'exist', 'targetClass' => Work::class, 'targetAttribute' => 'id', 'allowArray' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'markAsRead' => 'Lido',
            'canAutoCreateWork' => 'Criar obra automaticamente',
            'translatorIds' => 'Tradutores da obra',
            'workIds' => 'Obras',
            'authorIds' => 'Autores da obra',
            'tagIds' => 'Tags da obra',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->canAutoCreateWork && !$this->hasWorks()) {
            $this->generateWork();
        }

        if ($this->hasNewWorks()) {
            $this->resetWorks();
        }

        if ($this->hasNewTranslators()) {
            $this->resetTranslators();
        }

        if ($this->markAsRead) {
            $this->saveReading();
        }
    }

    /**
     * Checks whether there are works to be saved or already existing in database.
     * 
     * @return bool Validation result.
     */
    private function hasWorks(): bool
    {
        return $this->workIds || $this->getWorks()->exists();
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
     * Removes the old works and saves the new ones.
     */
    private function resetWorks(): void
    {
        $this->removeAllWorks();
        $this->saveWorks();
    }

    /**
     * Removes the old translators and saves the new ones.
     */
    private function resetTranslators(): void
    {
        $this->removeAllTranslators();
        $this->saveTranslators();
    }

    /**
     * Saves a new work with the same title and subtitle of the current book and
     * add it to the work list.
     * 
     * @throws FriendlyException If work could not be saved.
     */
    private function generateWork(): void
    {
        $workForm = new WorkForm();

        $workForm->title = $this->title;
        $workForm->subtitle = $this->subtitle;
        $workForm->authorIds = $this->authorIds;
        $workForm->tagIds = $this->tagIds;
        
        if (!$workForm->save()) {
            throw new FriendlyException('Não foi possível salvar a obra referente ao livro. Será necessário cadastrá-la manualmente.');
        }

        $this->workIds[] = $workForm->id;
    }

    /**
     * Creates relations between the book and its works.
     */
    private function saveWorks(): void
    {
        foreach ($this->workIds as $workId) {
            $this->addWorkById($workId);
        }
    }

    /**
     * Creates a relation between a work and the book.
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

    /**
     * Checks whether translators list changed.
     * 
     * @return bool Validation result.
     */
    private function hasNewTranslators(): bool
    {
        $currentTranslatorIds = $this->getTranslators()
            ->select('Translator.id')
            ->column();

        return $this->translatorIds != $currentTranslatorIds;
    }

    /**
     * Creates relations between the book and its translators.
     */
    private function saveTranslators(): void
    {
        foreach ($this->translatorIds as $translatorId) {
            $this->addTranslatorById($translatorId);
        }
    }

    /**
     * Creates a relation between a translator and the book.
     * 
     * @param int $translatorId The translator identifier.
     */
    private function addTranslatorById(int $translatorId): void
    {
        $translator = Translator::findOne($translatorId);

        try {
            $this->addTranslator($translator);
        } catch (RelationAlreadyExistsException $e) {
            return;
        }
    }

    /**
     * Creates a new `BookReading` to record to mark the book as read.
     * 
     * @throws FriendlyException If saving fails.
     */
    private function saveReading(): void
    {
        $reading = new BookReading();
        $reading->bookId = $this->id;
        $reading->isComplete = true;

        if (!$reading->save()) {
            throw new FriendlyException('Não foi possível marcar o livro como lido.');
        }
    }
}
