<?php

use app\entities\Tag;
use yii\db\Migration;

/**
 * Class m220905_134125_add_new_tags
 */
class m220905_134125_add_new_tags extends Migration
{
    const TAG_NAMES = [
        'Aventura',
        'Claustrofóbico',
        'Depressão e ansiedade',
        'Fantasia',
        'Interestelar',
        'Magia',
        'Realeza e monarquia',
        'Vida extraterrestre',
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach (self::TAG_NAMES as $tagName) {
            $tag = new Tag(['name' => $tagName]);
            $tag->saveOrFail();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach (self::TAG_NAMES as $tagName) {
            $tag = Tag::findByName($tagName);

            if ($tag && $tag->delete() === false) {
                throw new Exception("Error deleting tag \"{$tag->name}\".");
            }
        }
    }
}
