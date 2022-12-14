<?php

namespace Engelsystem\Test\Unit\Middleware\Stub;

use Engelsystem\Controllers\BaseController;

class ControllerImplementation extends BaseController
{
    /**
     * @param array $permissions
     */
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function actionStub(): string
    {
        return '';
    }
}
