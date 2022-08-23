<?php

namespace app\core\web;

use yii\web\Response;

/**
 * Implements action `delete`.
 */
interface IActionDelete
{
    /**
     * Deletes a record.
     * 
     * @param int $id Record ID.
     * 
     * @return Response Redirect to the index view.
     */
    public function actionDelete(int $id): Response;
}
