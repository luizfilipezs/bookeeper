<?php
/**
 * This view is used by console/controllers/MigrateController.php.
 *
 * The following variables are available in this view:
 * 
 * @var string $className The new migration class name without namespace.
 * @var string $namespace The new migration class namespace.
 */

echo "<?php\n";

if (!empty($namespace)) {
    echo "\nnamespace {$namespace};\n";
}

?>

use yii\db\Migration;

/**
 * Class <?= $className . "\n" ?>
 */
class <?= $className ?> extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "<?= $className ?> cannot be reverted.\n";

        return false;
    }
}
