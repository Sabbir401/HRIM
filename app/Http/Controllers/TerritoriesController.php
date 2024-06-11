<?php

namespace App\Http\Controllers;

use App\Models\territories;
use Illuminate\Http\Request;

class TerritoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $territory = territories::all();

        return response()->json($territory);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(territories $territories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(territories $territories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, territories $territories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(territories $territories)
    {
        //
    }
}
