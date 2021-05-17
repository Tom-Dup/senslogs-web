<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;

/**
 * App\Models\Coordinate
 *
 * @property string|null $uuid
 * @property int|null $ts
 * @property float|null $latitude
 * @property float|null $longitude
 * @property float|null $altitude
 * @property float|null $bearing
 * @property float|null $accuracy
 * @property float|null $speed
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereBearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereTs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereUuid($value)
 * @mixin Eloquent
 * @property float|null $battery
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereBattery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coordinate whereUpdatedAt($value)
 */
class Coordinate extends Eloquent
{
    public function datetime() {
        return (Carbon::createFromTimestampMs($this->ts)->diffForHumans());
    }
}
