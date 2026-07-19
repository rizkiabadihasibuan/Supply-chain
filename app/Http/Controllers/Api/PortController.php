<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\PortService;
use App\Http\Resources\Port\PortResource;
use App\Http\Resources\Port\PortCollection;

class PortController extends BaseApiController
{
    /**
     * @var PortService
     */
    protected $PortService;

    /**
     * Constructor for Dependency Injection
     *
     * @param PortService $PortService
     */
    public function __construct(PortService $PortService)
    {
        $this->PortService = $PortService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->PortService->index(...);
            // return new PortCollection($result);
            return $this->sendSuccess('Method index executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute index', [$e->getMessage()], 500);
        }
    }
    /**
     * show method
     */
    public function show($id)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->PortService->show(...);
            // return new PortResource($result);
            return $this->sendSuccess('Method show executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute show', [$e->getMessage()], 500);
        }
    }

}