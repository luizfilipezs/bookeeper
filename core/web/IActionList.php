<?php

namespace app\core\web;

/**
 * Implements action `list`.
 */
interface IActionList
{
    /**
     * Lists results according to the given search term. Response will be in the
     * JSON format.
     * 
     * @param string $search (Optional) If none is provided, the search filter will be
     * ignored.
     * 
     * @return array[] Array of results, where each element contains the keys `id`
     * and `text`.
     */
    public function actionList(?string $search): array;
}
