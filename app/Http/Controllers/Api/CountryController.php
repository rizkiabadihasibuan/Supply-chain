<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Exception;
use App\Services\CountryService;
use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\Country\CountryCollection;
use App\Http\Requests\Country\StoreCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Http\Requests\Country\SearchCountryRequest;

class CountryController extends BaseApiController
{
    /**
     * @var CountryService
     */
    protected $CountryService;

    /**
     * Constructor for Dependency Injection
     *
     * @param CountryService $CountryService
     */
    public function __construct(CountryService $CountryService)
    {
        $this->CountryService = $CountryService;
    }

    /**
     * index method
     */
    public function index(Request $request)
    {
        try {
            $countries = $this->CountryService->getAllCountries();
            return response()->json([
                'success' => true,
                'message' => 'Countries retrieved successfully',
                'data' => $countries
            ], 200);
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
            $countryData = null;
            if (is_numeric($id)) {
                $dbCountry = $this->CountryService->getCountryById((int) $id);
                if ($dbCountry) {
                    $countryData = $this->CountryService->getCountryByName($dbCountry->name);
                }
            } else {
                $countryData = $this->CountryService->getCountryByName($id);
            }

            if (!$countryData) {
                return $this->sendError('Country not found', [], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Country detail retrieved successfully',
                'data' => $countryData
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute show', [$e->getMessage()], 500);
        }
    }
    /**
     * store method
     */
    public function store(StoreCountryRequest $request)
    {
        try {
            $data = $request->validated();
            if (isset($data['iso_code'])) {
                $data['code'] = $data['iso_code'];
                unset($data['iso_code']);
            }
            $country = $this->CountryService->createCountry($data);
            return $this->sendSuccess('Country created successfully', new CountryResource($country));
        } catch (Exception $e) {
            return $this->sendError('Failed to execute store', [$e->getMessage()], 500);
        }
    }
    /**
     * update method
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        try {
            $data = $request->validated();
            if (isset($data['iso_code'])) {
                $data['code'] = $data['iso_code'];
                unset($data['iso_code']);
            }
            $country = $this->CountryService->updateCountry((int) $id, $data);
            if (!$country) {
                return $this->sendError('Country not found', [], 404);
            }
            return $this->sendSuccess('Country updated successfully', new CountryResource($country));
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
            $deleted = $this->CountryService->deleteCountry((int) $id);
            if (!$deleted) {
                return $this->sendError('Country not found or could not be deleted', [], 404);
            }
            return $this->sendSuccess('Country deleted successfully');
        } catch (Exception $e) {
            return $this->sendError('Failed to execute destroy', [$e->getMessage()], 500);
        }
    }
    /**
     * search method
     */
    public function search(Request $request)
    {
        try {
            $keyword = $request->query('q') ?? $request->input('query') ?? '';
            $countries = $this->CountryService->searchCountry($keyword);
            return response()->json([
                'success' => true,
                'message' => 'Countries search results',
                'data' => $countries
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute search', [$e->getMessage()], 500);
        }
    }

    public function coordinates($country)
    {
        try {
            $coords = $this->CountryService->getCoordinates($country);
            if (!$coords) {
                return $this->sendError('Country not found', [], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Country coordinates retrieved successfully',
                'data' => $coords
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute coordinates', [$e->getMessage()], 500);
        }
    }

    public function currency($country)
    {
        try {
            $currency = $this->CountryService->getCurrency($country);
            if (!$currency) {
                return $this->sendError('Country not found', [], 404);
            }
            return response()->json([
                'success' => true,
                'message' => 'Country currency retrieved successfully',
                'data' => $currency
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to execute currency', [$e->getMessage()], 500);
        }
    }
}