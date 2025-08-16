<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class AttendanceHistory extends Model
    {
        use HasFactory;

        protected $fillable = [
            'attendance_id',
            'action',
            'action_time'
        ];

        public function attendance()
        {
            return $this->belongsTo(Attendance::class);
        }
    }
