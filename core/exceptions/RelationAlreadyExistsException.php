<?php

namespace app\core\exceptions;

/**
 * Thrown when `app\core\PivotRelationHandler` tries to create a relation
 * that already exists on database.
 */
class RelationAlreadyExistsException extends \Exception
{
    public function __construct(string $message = 'Relation already exists.')
    {
        parent::__construct($message);
    }
}
