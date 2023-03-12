<?php

use app\entities\Book;
use app\entities\BookTranslator;
use app\entities\Translator;
use yii\db\Migration;

/**
 * Class m230311_214025_create_translator
 */
class m230311_214025_create_translator extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTranslatorTable();
        $this->createBookTranslatorTable();
        $this->updateData();
        $this->removeOldData();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230311_214025_create_translator cannot be reverted.\n";

        return false;
    }

    private function createTranslatorTable(): void
    {
        $this->createTable(Translator::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);
    }

    private function createBookTranslatorTable(): void
    {
        $this->createTable(BookTranslator::tableName(), [
            'id' => $this->primaryKey(),
            'bookId' => $this->integer()->notNull(),
            'translatorId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_book_translator_book', BookTranslator::tableName(), 'bookId', Book::tableName(), 'id');
        $this->addForeignKey('fk_book_translator_translator', BookTranslator::tableName(), 'translatorId', Translator::tableName(), 'id');
    }

    private function updateData(): void
    {
        $books = Book::find()
            ->select(['id', 'translator'])
            ->where(['not', [
                'translator' => '',
            ]])
            ->all();

        foreach ($books as $book) {
            $this->createTranslatorsForBook($book);
        }
    }

    private function createTranslatorsForBook(Book $book): void
    {
        $translatorNames = str_replace(' e ', ', ', $book->translator);
        $translatorNames = explode(', ', $translatorNames);

        foreach ($translatorNames as $translatorName) {
            $this->createBookTranslator($book->id, $translatorName);
        }
    }

    private function createBookTranslator(int $bookId, string $translatorName): void
    {
        $translator = $this->getOrCreateTranslator($translatorName);
        $bookTranslator = new BookTranslator([
            'bookId' => $bookId,
            'translatorId' => $translator->id,
        ]);

        $bookTranslator->saveOrFail();
    }

    private function getOrCreateTranslator(string $name): Translator
    {
        $translator = Translator::findByName($name) ?? new Translator(['name' => $name]);
        $translator->saveOrFail();

        return $translator;
    }

    private function removeOldData(): void
    {
        $this->dropColumn(Book::tableName(), 'translator');
    }
}
