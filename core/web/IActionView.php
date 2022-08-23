<?php

namespace app\core\web;

/**
 * Implements action `view`.
 */
interface IActionView
{
    /**
     * Renders a detail view.
     * 
     * @return string Rendered view.
     */
    public function actionView(int $id): string;
}
