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
 * @method static \Illuminate\Database\Eloquent\Builder|Record newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Record newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Record query()
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereBearing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereTsMs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereBatteryLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Record whereBatteryTemp($value)
 * @mixin Eloquent
 */
class Record extends Eloquent
{
    public function datetime() {
        return (Carbon::createFromTimestampMs($this->ts_ms)->diffForHumans());
    }

    // Retrieve all unique sessions from the records table
    // Create non-existing devices
    // Create non-existing sessions
    public static function sortDevicesAndSessions() {
        $devices = Record::select("device_id")->groupBy("device_id")->pluck("device_id");
        foreach ($devices as $device_id) {
            $device = Device::whereDeviceId($device_id)->first();
            if (empty($device)) {
                $device = new Device();
                $device->device_id = $device_id;
                $device->save();
            }
        }
        $sessions = Record::select("session_id")->groupBy("session_id")->pluck("session_id");
        foreach ($sessions as $session_id) {
            $session = Session::whereSessionId($session_id)->first();
            if (empty($session)) {
                $session = new Session();
                $session->device_id = Record::whereSessionId($session_id)->first()->device_id;
                $session->session_id = $session_id;
                $session->first_seen = Record::whereSessionId($session_id)->orderBy("created_at")->first()->created_at;
                $session->last_seen = Record::whereSessionId($session_id)->orderBy("created_at", "DESC")->first()->created_at;
                $session->duration = $session->last_seen->diffInMinutes($session->first_seen);
                $session->ended = $session->duration>360;
                $session->save();
            }
        }
    }
}
