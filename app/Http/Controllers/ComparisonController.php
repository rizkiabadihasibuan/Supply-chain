<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    /**
     * Display the comparison engine page.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();

        $country1Code = strtoupper(trim($request->query('country1', '')));
        $country2Code = strtoupper(trim($request->query('country2', '')));

        $country1 = null;
        $country2 = null;
        $risk1 = null;
        $risk2 = null;

        if (!empty($country1Code)) {
            $country1 = Country::where('code', $country1Code)->first();
            if ($country1) {
                $risk1 = $country1->riskScores()->latest()->first();
            }
        }

        if (!empty($country2Code)) {
            $country2 = Country::where('code', $country2Code)->first();
            if ($country2) {
                $risk2 = $country2->riskScores()->latest()->first();
            }
        }

        return view('compare', compact('countries', 'country1', 'country2', 'risk1', 'risk2', 'country1Code', 'country2Code'));
    }
}
