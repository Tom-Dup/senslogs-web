<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;

/**
 * App\Models\Session
 *
 * @property string $session_id
 * @property string $device_id
 * @property string|null $name
 * @property string|null $first_seen
 * @property string|null $last_seen
 * @property float|null $distance
 * @property int|null $duration
 * @property int|null $ended
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Session newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Session newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Session query()
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereEnded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereFirstSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereLastSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Session whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Session extends Eloquent
{

    protected $primaryKey = "session_id";

    protected $casts = [
      "session_id" => "string"
    ];

    protected $dates = [
        'first_seen',
        'last_seen'
    ];

    public $precision = 4;

    public $points = [];

    private $altitudes = [];
    /**
     * @var array|mixed
     */
    private $speed;

    public function Device()
    {
        return Device::whereDeviceId($this->device_id)->first();
    }

    public function Records($sort='ASC')
    {
        return Record::where("session_id", "=", $this->session_id)->orderBy("ts_ms", $sort)->get();
    }

    public function Points($records=null): array
    {
        if (empty($records))
            $records = $this->Records();
        $this->points = [];
        $last = 0;
        foreach ($records as $record) {
            // Keep only one point every $precision secs
            $secs = round($record->ts_ms / 1000);
            if (empty($last) || $secs >= $last+$this->precision) {
                $this->points[] = [
                    'latitude' => $record->latitude,
                    'longitude' => $record->longitude,
                ];
                $last = $secs;
            }
        }
        return $this->points;
    }

    public function Altitudes($records=null): array
    {
        if (empty($records))
            $records = $this->Records();
        $this->altitudes = [];
        $last = 0;
        foreach ($records as $record) {
            // Keep only one point every 60 secs
            $secs = round($record->ts_ms / 1000);
            if (empty($last) || $secs >= $last+60) {
                $this->altitudes[] = $record->altitude;
                $last = $secs;
            }
        }
        return $this->altitudes;
    }

    public function Speed($records=null): array
    {
        if (empty($records))
            $records = $this->Records();
        $this->speed = [];
        $last = 0;
        foreach ($records as $record) {
            // Keep only one point every 60 secs
            $secs = round($record->ts_ms / 1000);
            if (empty($last) || $secs >= $last+60) {
                $this->speed[] = $record->speed;
                $last = $secs;
            }
        }
        return $this->speed;
    }

    public function Record($what="last")
    {
        $query = Record::where("session_id", "=", $this->session_id);
        if ($what=="first")
            return $query->orderBy("ts_ms", "ASC")->first();
        else
            return $query->orderBy("ts_ms", "DESC")->first();
    }

    public function startDate() : Carbon
    {
        return $this->first_seen->setTimezone('Europe/Paris');
    }

    public function endDate(): Carbon
    {
        if (is_true($this->ended) && !empty($this->last_seen))
            return $this->last_seen->setTimezone('Europe/Paris');
        $record = $this->Record("last");
        $this->last_seen = Carbon::createFromTimestampMs($record->ts_ms);
        $this->save();
        return (Carbon::createFromTimestampMs($record->ts_ms)->setTimezone('Europe/Paris'));
    }

    public function duration(): int
    {
        if (is_true($this->ended) && !empty($this->duration))
            return $this->duration;
        $this->duration = $this->endDate()->diffInMinutes($this->startDate());
        $this->save();
        return $this->endDate()->diffInMinutes($this->startDate());
    }

    public function distance(): float
    {
        if (is_true($this->ended) && !empty($this->distance))
            return $this->distance;
        if (empty($this->points))
            $this->Points();
        $this->distance = 0;
        foreach ($this->points as $k => $point) {
            if ($k == 0 || !isset($this->points[$k+1]))
                continue;
            $this->distance = $this->distance + get_distance($this->points[$k]["latitude"], $this->points[$k]["longitude"], $this->points[$k+1]["latitude"], $this->points[$k+1]["longitude"]);
        }
        $this->distance = round($this->distance/1000, 2);
        $this->save();
        return $this->distance;
    }

}
