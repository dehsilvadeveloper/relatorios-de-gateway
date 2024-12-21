<?php

namespace App\Domain\Report\Models;

use App\Domain\Report\Models\Report;
use Database\Factories\ReportStatusFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportStatus extends Model
{
    use HasFactory;

    protected $table = 'report_statuses';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ReportStatusFactory::new();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'report_status_id');
    }
}
