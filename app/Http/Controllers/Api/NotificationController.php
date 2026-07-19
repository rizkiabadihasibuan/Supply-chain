<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\NotificationService;
use App\Http\Resources\Notification\NotificationResource;
use App\Http\Requests\Notification\NotificationPreferenceRequest;
use App\Http\Requests\Notification\SendNotificationRequest;

class NotificationController extends BaseApiController
{
    /**
     * @var NotificationService
     */
    protected $NotificationService;

    /**
     * Constructor for Dependency Injection
     *
     * @param NotificationService $NotificationService
     */
    public function __construct(NotificationService $NotificationService)
    {
        $this->NotificationService = $NotificationService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->NotificationService->index(...);
            // return new NotificationResource($result);
            return $this->sendSuccess('Method index executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute index', [$e->getMessage()], 500);
        }
    }
    /**
     * preferences method
     */
    public function preferences(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->NotificationService->preferences(...);
            // return new NotificationResource($result);
            return $this->sendSuccess('Method preferences executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute preferences', [$e->getMessage()], 500);
        }
    }
    /**
     * send method
     */
    public function send(SendNotificationRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->NotificationService->send(...);
            // return new NotificationResource($result);
            return $this->sendSuccess('Method send executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute send', [$e->getMessage()], 500);
        }
    }

}