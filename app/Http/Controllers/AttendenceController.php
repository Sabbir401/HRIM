<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\attendence;
use App\Models\employee;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class AttendenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $zk;

    public function index()
    {
        $attendence = attendence::get();
        return response()->json($attendence);
    }

    public function getAttendance(Request $request)
    {
        $month = $request->query('month');
        $year = Carbon::now()->year;

        $employees = employee::with(['attendence' => function($query) use ($month, $year) {
            $query->whereMonth('Date', $month)->whereYear('Date', $year);
        }])->get();

        return response()->json($employees);
    }



    public function create()
    {
        Artisan::call('zktuser:fetch');
        return response()->json(['message' => 'Attendance fetched successfully.']);
    }

    public function store(Request $request)
    {
        $attendanceData = $request->input('attendanceData');
        $year = now()->year;
        $month = $request->input('month');

        foreach ($attendanceData as $employeeData) {
            $employeeId = $employeeData['id'];
            $attendance = $employeeData['attendance'];

            foreach ($attendance as $day => $status) {
                if (!empty($status)) {
                    attendence::updateOrCreate(
                        [
                            'EID' => $employeeId,
                            'Date' => Carbon::create($year, $month + 1, $day + 1)->toDateString(),
                        ],
                        [
                            'Status' => $status,
                            'Time' => now()->toTimeString()
                        ]
                    );
                }
            }
        }

        return response()->json(['success' => true]);
    }

    // public function store(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $startDate = $request->input('From_Date');
    //         $endDate = $request->input('To_Date');

    //         $timeIn = $request->input('Time_In');
    //         $status = ($timeIn && $timeIn > '11:00:00') ? 'L' : 'P';

    //         $date = $startDate;
    //         while ($date <= $endDate) {
    //             $employee = attendence::create([
    //                 'EID' => $request->input('Employee_Id'),
    //                 'Date' => $date,
    //                 'Time_In' => $request->input('Time'),
    //                 'Status' => $status,
    //             ]);
    //             $date = date('Y-m-d', strtotime($date . ' +1 day'));
    //         }

    //         DB::commit();
    //         $response = [
    //             'success'   =>  true,
    //             'message'   =>  'Attendance saved successfully',
    //         ];
    //         return response()->json($response);
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         $response = [
    //             'success'   =>  false,
    //             'message'   =>  'Error while inserting attendance: ' . $e->getMessage(),
    //         ];
    //         return response()->json($response, 500);
    //     }
    // }


    /**
     * Display the specified resource.
     */
    public function show(attendence $attendence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $EID = $request->input('Employee_Id');
        $startDate = $request->input('From_Date');
        $endDate = $request->input('To_Date');

        $attendances = attendence::select('employees.full_name', 'attendences.Date', 'attendences.Time_In', 'attendences.Time_Out', 'attendences.Status')
            ->join('employees', 'employees.id', '=', 'attendences.EID')
            ->whereBetween('attendences.Date', [$startDate, $endDate])
            ->where('attendences.EID', $EID)
            ->get();

        return response()->json($attendances);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, attendence $attendence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(attendence $attendence)
    {
        //
    }
}
