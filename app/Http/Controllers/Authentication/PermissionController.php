<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Services\Authentication\PermissionService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use ApiResponses;
    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function assignRoleHandler(Request $request)
    {
        $authUser = auth()->user()->id;
        $validatedParams = $request->validate(['user_id' => 'required|exists:user,id']);

        $result = $this->permissionService->assignRole($authUser, $validatedParams);
    }
}
