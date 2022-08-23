<?php

namespace app\core\web;

/**
 * Implements actions `index`, `list`, `view`, `create`, `update`, and `delete`.
 */
interface ICrudActions extends IActionIndex, IActionList, IActionView, IActionCreate, IActionUpdate, IActionDelete
{
}
