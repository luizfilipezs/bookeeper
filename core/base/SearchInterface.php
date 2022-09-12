<?php

namespace app\core\base;

use yii\data\DataProviderInterface;

/**
 * Implements the method that is responsible for searching with parameters.
 */
interface SearchInterface
{
    /**
     * Returns a data provider with the filtered results.
     * 
     * @param array $params Search parameters.
     * 
     * @return DataProviderInterface Data provider.
     */
    public function search(array $params = []): DataProviderInterface;
}
