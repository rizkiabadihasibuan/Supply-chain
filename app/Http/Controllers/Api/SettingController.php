<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\SettingService;
use App\Http\Resources\System\SettingResource;
use App\Http\Requests\System\SettingRequest;

class SettingController extends BaseApiController
{
    /**
     * @var SettingService
     */
    protected $SettingService;

    /**
     * Constructor for Dependency Injection
     *
     * @param SettingService $SettingService
     */
    public function __construct(SettingService $SettingService)
    {
        $this->SettingService = $SettingService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->SettingService->index(...);
            // return new SettingResource($result);
            return $this->sendSuccess('Method index executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute index', [$e->getMessage()], 500);
        }
    }
    /**
     * update method
     */
    public function update($id)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->SettingService->update(...);
            // return new SettingResource($result);
            return $this->sendSuccess('Method update executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute update', [$e->getMessage()], 500);
        }
    }

}