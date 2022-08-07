<?php

namespace app\core\validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ForeignKey extends Exist
{
    /**
     * @param string $model Model class from which the foreign key is expected.
     */
    public function __construct(public string $model, bool $multiple = false, string $message = null, array|string $on = [])
    {
        /** @var ActiveRecord */
        $modelInstance = (new $model);

        parent::__construct(
            model: $model,
            column: $modelInstance->primaryKey()[0],
            multiple: $multiple,
            message: $message,
            on: $on,
        );
    }
}
