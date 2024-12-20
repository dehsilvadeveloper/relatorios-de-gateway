<?php

namespace App\Domain\GatewayLog\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\GatewayLogFactory;

class GatewayLog extends Model
{
    use HasFactory;

    protected $table = 'gateway_logs';
    protected $primaryKey = 'id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'service_name',
        'consumer_id',
        'latency_proxy',
        'latency_gateway',
        'latency_request',
        'raw_log'
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return GatewayLogFactory::new();
    }
}