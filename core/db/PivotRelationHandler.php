<?php

namespace app\core\db;

use app\core\exceptions\RelationAlreadyExistsException;
use Yii;
use yii\base\Component;

/**
 * Enables creation and deletion of records in pivot tables by simply
 * passing the two records related to it.
 * 
 * @property string $fieldNameA
 * @property string $fieldNameB
 * @property string $pivotClass
 * 
 * @property-read ActiveRecord $pivotRecord
 * @property-read string $tableA
 * @property-read string $tableB
 * @property-read array $relationFields Associative array with relation fields as keys
 * and their corresponding values.
 */
class PivotRelationHandler extends Component
{
    const EVENT_BEFORE_CREATE = 'before-create';
    const EVENT_BEFORE_DELETE = 'before-delete';

    public static $entitiesNamespace = 'app\entities';

    private ?string $_fieldNameA;
    private ?string $_fieldNameB;
    private ?string $_pivotClass;
    private ?ActiveRecord $_pivotRecord;
    
    public function __construct(
        public ActiveRecord $instanceA,
        public ActiveRecord $instanceB,
        string $pivotClass = null
    )
    {
        $this->pivotClass = $pivotClass;
    }

    /**
     * {@inheritdoc}
     */
    public function on($name, $handler, $data = null, $append = true)
    {
        parent::on($name, $handler, $data ?? $this->pivotRecord, $append);
    }

    public function getTableA(): string
    {
        return $this->instanceA::tableName();
    }

    public function getTableB(): string
    {
        return $this->instanceB::tableName();
    }

    public function getFieldNameA(): string
    {
        return $this->_fieldNameA ?: lcfirst($this->tableA) . 'Id';
    }

    public function setFieldNameA(?string $value): void
    {
        $this->_fieldNameA = $value;
    }

    public function getFieldNameB(): string
    {
        return $this->_fieldNameB ?: lcfirst($this->tableB) . 'Id';
    }

    public function setFieldNameB(?string $value): void
    {
        $this->_fieldNameB = $value;
    }

    public function getPivotClass(): string
    {
        return $this->_pivotClass ?: self::$entitiesNamespace . '\\' . $this->tableA . $this->tableB;
    }

    public function setPivotClass(?string $value): void
    {
        $this->_pivotClass = $value;
    }

    public function getPivotRecord(): ActiveRecord
    {
        return $this->_pivotRecord ??= Yii::createObject($this->pivotClass);
    }

    public function getRelationFields(): array
    {
        return [
            $this->fieldNameA => $this->instanceA->primaryKey,
            $this->fieldNameB => $this->instanceB->primaryKey,
        ];
    }

    public function createRelation(): ActiveRecord
    {
        $this->trigger(self::EVENT_BEFORE_CREATE);
        $this->validateCreation();

        $record = $this->getPivotRecord();

        $record->setAttributes($this->relationFields);
        $record->saveOrFail(true, [
            $this->fieldNameA,
            $this->fieldNameB,
        ]);
        
        return $record;
    }

    public function removeRelation(): void
    {
        $this->trigger(self::EVENT_BEFORE_DELETE);

        if (!$record = $this->findRelation()) {
            throw new \Exception('Relation does not exist.');
        }

        if ($record->delete() === false) {
            throw new \Exception('Relation could not be removed.');
        }
    }

    private function validateCreation(): void
    {
        if ($this->instanceA->isNewRecord) {
            throw new \Exception('The record must be saved before adding relations to it.');
        }

        if ($this->instanceB->isNewRecord) {
            throw new \Exception('The relation must be saved first.');
        }

        if ($this->findRelation() !== null) {
            throw new RelationAlreadyExistsException();
        }
    }

    private function findRelation(): ?ActiveRecord
    {
        return $this->getPivotRecord()->findOne($this->relationFields);
    }
}
