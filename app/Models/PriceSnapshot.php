<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceSnapshot extends Model
{
    protected $fillable = ['tracked_coin_id','timestamp_utc','price_usd','percent_change_24h','volume_24h','market_cap','raw'];
    protected $casts = ['raw' => 'array', 'timestamp_utc' => 'datetime'];
    public function coin(){ return $this->belongsTo(TrackedCoin::class, 'tracked_coin_id'); }
    public function snapshots(){ return $this->hasMany(PriceSnapshot::class); }
}
