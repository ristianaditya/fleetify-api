<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Employee extends Model
    {
        use HasFactory;

        protected $fillable = [
            'employee_id',
            'departement_id',
            'name',
            'address'
        ];

        public function departement()
        {
            return $this->belongsTo(Departement::class);
        }
    }
?>
