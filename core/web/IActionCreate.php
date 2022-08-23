<?php

namespace app\core\web;

use yii\web\Response;

/**
 * Implements action `create`.
 */
interface IActionCreate
{
    /**
     * Renders and process the creation form.
     * 
     * @return string|Response Rendered view or redirect to the index view.
     */
    public function actionCreate(): string|Response;
}
