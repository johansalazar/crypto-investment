<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackedCoin extends Model
{
    protected $fillable = ['cmc_id','symbol','name','is_active'];
    public function snapshots(){ return $this->hasMany(PriceSnapshot::class); }
}
