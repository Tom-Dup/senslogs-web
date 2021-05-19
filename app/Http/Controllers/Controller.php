<?php

namespace App\Http\Controllers;

use App\Models\Coordinate;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function record() {
        $response = ["status" => "KO"];
        $data = request()->get("data", "");
        if (empty($data))
            return response()->json($response);
        $values = explode(";", $data);
        if (count($values) < 8)
            return response()->json($response);
        $Coordinate = new Coordinate();
        $i = 0;
        $Coordinate->device_id = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->session_id = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->ts_ms = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->latitude = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->longitude = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->altitude = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->bearing = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->accuracy = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->speed = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->battery_temp = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Coordinate->battery_level = (empty($values[$i])) ? null : $values[$i]; $i++;
        try {
            $Coordinate->save();
        } catch (Exception $e) {

        }
        $response = ["status" => "OK"];
        return response()->json($response);
    }

    public function index() {
        $coordinate = Coordinate::orderBy("ts_ms", "DESC")->first();
        if (empty($coordinate))
            abort("404");
        return view("index")->with('coordinate', $coordinate);
    }

    public function map() {
        $coordinates = Coordinate::where("session_id", "=", "cba77f77-d8b7-4f1e-8ff8-cd64a7e69976")->orderBy("ts_ms")->get();
        $points = [];
        $last = 0;
        foreach ($coordinates as $coordinate) {
            // Keep only one point every 5 secs
            $secs = round($coordinate->ts_ms / 1000);
            if (empty($last) || $secs >= $last+5) {
                $points[$secs] = [
                    'latitude' => $coordinate->latitude,
                    'longitude' => $coordinate->longitude,
                ];
                $last = $secs;
            }
        }
        $coordinate = Coordinate::where("session_id", "=", "cba77f77-d8b7-4f1e-8ff8-cd64a7e69976")->orderBy("ts_ms", "DESC")->first();
        return view("map")->with('points', $points)->with('coordinate', $coordinate);
    }
}
