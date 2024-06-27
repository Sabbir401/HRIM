<?php

namespace App\Http\Controllers;

use App\Models\leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leave = leave::get();
        return response()->json($leave);
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
        $request->validate([
            'Employee_Id' => 'required',
            'From_Date' => 'required',
            'To_Date' => 'required',
            'Purpose' => 'required',
            'Leave_Type_Id' => 'required',
        ], [
            'Employee_Id.required' => 'The Employee ID is required.',
            'Leave_Type_Id.required' => 'The Leave Type is required.',
            'From_Date.unique' => 'The Form Date is required.',
            'To_Date.required' => 'The To Date is required.',
            'Purpose.required' => 'The Purpose is required.',
        ]);

        $file_path = null;


        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,xlsx,pdf|max:512'
            ]);

            $file_name = time() . '_' . $request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');
        }

        //      FIND Days Between Dates
        // $startDate = new \DateTime($request->From_Date);
        // $endDate = new \DateTime($request->To_Date);

        // $interval = $startDate->diff($endDate);
        // $daysBetween = $interval->days;
        // dd($daysBetween);

        DB::beginTransaction();
        $leave = leave::create([
            'EID' => $request->input('Employee_Id'),
            'Leave_Type_Id' => $request->input('Leave_Type_Id'),
            'From_Date' => $request->input('From_Date'),
            'To_Date' => $request->input('To_Date'),
            'Purpose' => $request->input('Purpose'),
            'Status' => $request->input('Status'),
            'Attachment_Url' => '/storage/' . $file_path,
        ]);

        $response = [
            'success'   =>  true,
            'message'   =>  'Successfully inserted',
        ];

        DB::commit();
        return response()->json($response);

        DB::rollback();
        $response = [
            'success'   =>  false,
            'message'   =>  'Error while inserting',
        ];
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $leave = leave::where('EID', $id)->get();
        return response()->json($leave);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(leave $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, leave $leave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(leave $leave)
    {
        //
    }
}
