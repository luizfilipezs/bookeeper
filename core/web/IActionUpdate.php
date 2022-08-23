<?php

namespace app\core\web;

use yii\web\Response;

/**
 * Implements action `update`.
 */
interface IActionUpdate
{
    /**
     * Renders and process the update form.
     * 
     * @return string|Response Rendered view or redirect to the index view.
     */
    public function actionUpdate(int $id): string|Response;
}
