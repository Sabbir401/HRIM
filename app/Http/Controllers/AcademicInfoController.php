<?php

namespace App\Http\Controllers;

use App\Models\academic_info;
use Illuminate\Http\Request;

class AcademicInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academic = academic_info::with('education', 'scale', 'board', 'education.degree')->get();

        return response()->json($academic);
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
        $academic = academic_info::create([
            'EID' => $request->input('eid'),
            'Level_of_Education_Id' => $request->input('degreeId'),
            'Board_Id' => $request->input('boardId'),
            'Group' => $request->input('major'),
            'Institute_Name' => $request->input('institute'),
            'Scale_Id' => $request->input('scaleId'),
            'Result' => $request->input('result'),
            'Year_of_Passing' => $request->input('yop'),
            'Acheivement' => $request->input('acheivement'),
            'Remarks' => $request->input('remarks'),
        ]);
        $response = [
            'success'   =>  true,
            'message'   =>  'Successfully inserted',
        ];

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $academic = academic_info::with('education', 'scale', 'board', 'education.degree')
        ->where('EID',$id)
        ->get();

        return response()->json($academic);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $academic = academic_info::with('education', 'scale', 'board', 'education.degree')->find($id);

        return response()->json($academic);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, academic_info $academic_info)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(academic_info $academic_info)
    {
        //
    }
}
