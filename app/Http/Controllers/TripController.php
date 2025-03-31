<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        $data = $request->validated();

        return $request->user()->trips()->create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        if ($trip->user->id == request()->user()->id) {
            return $trip;
        }

        if (isset($trip->driver) && isset(request()->user()->driver)) {
            if ($trip->driver->id == request()->user()->driver->id) {
                return $trip;
            }
        }

        // if trip douldnt be found through mmethods above then retun 404
        return response()->json([
            'success' => false,
            'message' => 'Couldn.t find the trip.'
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRequest $request, Trip $trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        //
    }

    /**
     * driver's location and id will be added to DB by accepting the trip
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return Trip
     */
    public function accept(Request $request, Trip $trip)
    {
        // validate driver's location
        $request->validate([
            'driver_location' => ['required']
        ]);

        // add driver's id and location to trip's DB in related columns
        $trip->update([
            'driver_id' => $request->user()->id,
            'driver_location' => $request['driver_location']
        ]);

        // eager load driver and user related to current trip
        $trip->load('driver.user');

        // return trip
        return $trip;
    }

    /**
     * starts the trip
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return Trip
     */
    public function start(Request $request, Trip $trip)
    {
        $trip->update([
            'is_started' => true
        ]);

        $trip->load('driver.user');

        return $trip;
    }

    /**
     * completes the trip
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return Trip
     */
    public function end(Request $request, Trip $trip)
    {
        $trip->update([
            'is_completed' => true
        ]);

        $trip->load('driver.user');

        return $trip;
    }

    /**
     * updates driver's location 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Trip $trip
     * @return Trip
     */
    public function location(Request $request, Trip $trip)
    {
        $request->validate([
            'driver_location' => ['required']
        ]);

        $trip->update([
            'driver_location' => $request['driver_location']
        ]);

        $trip->load('driver.user');

        return $trip;
    }
}
