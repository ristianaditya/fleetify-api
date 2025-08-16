<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee.departement']);

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->has('department_id')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('departement_id', $request->department_id);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->paginate($request->get('per_page', 10));

        $attendances->getCollection()->transform(function ($attendance) {
            $departement = $attendance->employee->departement;

            $status_in = 'tidak absen';
            if ($attendance->clock_in_time) {
                $status_in = $attendance->clock_in_time <= $departement->max_clock_in
                    ? 'tepat waktu'
                    : 'terlambat';
            }

            $status_out = 'tidak absen';
            if ($attendance->clock_out_time) {
                $status_out = $attendance->clock_out_time >= $departement->max_clock_out
                    ? 'tepat waktu'
                    : 'pulang cepat';
            }

            return [
                'employee_id'    => $attendance->employee_id,
                'employee_name'  => $attendance->employee->name,
                'departement'     => $departement,
                'date'           => $attendance->date,
                'clock_in_time'  => $attendance->clock_in_time,
                'status_in'      => $status_in,
                'clock_out_time' => $attendance->clock_out_time,
                'status_out'     => $status_out,
            ];
        });

        return response()->json($attendances);
    }
}

