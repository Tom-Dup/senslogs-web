<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;

/**
 * App\Models\Coordinate
 *
 * @property string $device_id
 * @property string $session_id
 * @property int $ts_ms
 * @property float|null $latitude
 * @property float|null $longitude
 * @property float|null $altitude
 * @property float|null $bearing
 * @property float|null $accuracy
 * @property float|null $speed
 * @property float|null $battery_temp
 * @property int|null $battery_level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereBearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereTsMs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereBatteryLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereBatteryTemp($value)
 * @mixin Eloquent
 */
class Coordinate extends Eloquent
{
    public function datetime() {
        return (Carbon::createFromTimestampMs($this->ts_ms)->diffForHumans());
    }
}
