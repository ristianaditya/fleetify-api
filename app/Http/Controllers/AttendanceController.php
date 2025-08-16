<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $tz   = config('app.timezone', 'Asia/Jakarta');
        $now  = Carbon::now($tz);
        $today= $now->toDateString();
        $time = $now->format('H:i:s');

        $attendance = Attendance::firstOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $today],
            ['clock_in_time' => $time]
        );

        if (is_null($attendance->clock_in_time)) {
            $attendance->update(['clock_in_time' => $time]);
        }

        AttendanceHistory::create([
            'attendance_id' => $attendance->id,
            'action'        => 'clock_in',
            'action_time'   => $now,
        ]);

        return response()->json([
            'message'    => 'Absen masuk berhasil',
            'attendance' => $attendance
        ], 200);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $tz   = config('app.timezone', 'Asia/Jakarta');
        $now  = Carbon::now($tz);
        $today= $now->toDateString();
        $time = $now->format('H:i:s');

        $attendance = Attendance::where('employee_id', $validated['employee_id'])
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['message' => 'Belum ada absensi masuk'], 400);
        }

        if (is_null($attendance->clock_out_time)) {
            $attendance->update(['clock_out_time' => $time]);
        }

        AttendanceHistory::create([
            'attendance_id' => $attendance->id,
            'action'        => 'clock_out',
            'action_time'   => $now,
        ]);

        return response()->json([
            'message'    => 'Absen keluar berhasil',
            'attendance' => $attendance
        ], 200);
    }

    public function history($employeeId)
    {
        $attendances = Attendance::with('histories')
            ->where('employee_id', $employeeId)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return response()->json($attendances, 200);
    }
}
