<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\WatchlistService;
use App\Http\Resources\Watchlist\WatchlistResource;
use App\Http\Resources\Watchlist\WatchlistCollection;
use App\Http\Requests\Watchlist\StoreWatchlistRequest;
use App\Http\Requests\Watchlist\UpdateWatchlistRequest;

class WatchlistController extends BaseApiController
{
    /**
     * @var WatchlistService
     */
    protected $WatchlistService;

    /**
     * Constructor for Dependency Injection
     *
     * @param WatchlistService $WatchlistService
     */
    public function __construct(WatchlistService $WatchlistService)
    {
        $this->WatchlistService = $WatchlistService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->WatchlistService->index(...);
            // return new WatchlistCollection($result);
            return $this->sendSuccess('Method index executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute index', [$e->getMessage()], 500);
        }
    }
    /**
     * store method
     */
    public function store(StoreWatchlistRequest $request)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->WatchlistService->store(...);
            // return new WatchlistResource($result);
            return $this->sendSuccess('Method store executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute store', [$e->getMessage()], 500);
        }
    }
    /**
     * update method
     */
    public function update(UpdateWatchlistRequest $request, $id)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->WatchlistService->update(...);
            // return new WatchlistResource($result);
            return $this->sendSuccess('Method update executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute update', [$e->getMessage()], 500);
        }
    }
    /**
     * destroy method
     */
    public function destroy($id)
    {
        try {
            // No business logic here. Delegate to service.
            // $result = $this->WatchlistService->destroy(...);
            // return new WatchlistResource($result);
            return $this->sendSuccess('Method destroy executed');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute destroy', [$e->getMessage()], 500);
        }
    }

}