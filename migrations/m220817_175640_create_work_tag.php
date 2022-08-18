<?php

use app\entities\{
    Tag,
    Work,
    WorkTag
};
use yii\db\Migration;

/**
 * Class m220817_175640_create_work_tag
 */
class m220817_175640_create_work_tag extends Migration
{
    /**
     * Tags to create by default.
     * 
     * @var string[]
     */
    const TAG_NAMES = [
        'Amor e paixão',
        'Astronomia',
        'Autoritarismo',
        'Biografia',
        'Comunismo',
        'Contos',
        'Correspondências',
        'Cristianismo',
        'Crônicas',
        'Diário',
        'Distopia',
        'Ensaios',
        'Epopeia',
        'Escravidão',
        'Fascismo',
        'Filosofia',
        'Fideísmo',
        'Física',
        'Fome',
        'Gótico',
        'História',
        'Infantil',
        'Lenda',
        'Mitologia',
        'Modernismo',
        'Naturalismo',
        'Nazismo',
        'Novela',
        'Parnasianismo',
        'Pobreza',
        'Poemas',
        'Poesia',
        'Pós-modernismo',
        'Prosa poética',
        'Psicopatia',
        'Realismo',
        'Romance',
        'Romantismo',
        'Satírico',
        'Socialismo',
        'Sonetos',
        'Suspense e terror',
        'Teatro',
        'Totalitarismo',
        'Utopia',
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Tag::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->createTable(WorkTag::tableName(), [
            'id' => $this->primaryKey(),
            'workId' => $this->integer()->notNull(),
            'tagId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_work_tag_work', WorkTag::tableName(), 'workId', Work::tableName(), 'id');
        $this->addForeignKey('fk_work_tag_tag', WorkTag::tableName(), 'tagId', Tag::tableName(), 'id');

        $this->createDefaultTags();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_work_tag_tag', WorkTag::tableName());
        $this->dropForeignKey('fk_work_tag_work', WorkTag::tableName());

        $this->dropTable(WorkTag::tableName());
        $this->dropTable(Tag::tableName());
    }

    /**
     * Create the tags defined in the constant `TAG_NAMES`.
     * 
     * @throws app\core\exceptions\FriendlyException If a tag could not be created.
     */
    private function createDefaultTags(): void
    {
        foreach (self::TAG_NAMES as $tagName) {
            $tag = new Tag();
            $tag->name = $tagName;
            $tag->saveOrFail();
        }
    }
}
