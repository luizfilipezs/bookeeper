<?php

namespace app\core\web;

/**
 * Implements action `index`.
 */
interface IActionIndex
{
    /**
     * Renders the view which lists the model records.
     * 
     * @return string Rendered view.
     */
    public function actionIndex(): string;
}
