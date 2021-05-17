<?php

namespace App\Http\Controllers;

use App\Models\Coordinate;
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
        $Coordinate->uuid = (empty($values[0])) ? null : $values[0];
        $Coordinate->ts = (empty($values[1])) ? null : $values[1];
        $Coordinate->latitude = (empty($values[2])) ? null : $values[2];
        $Coordinate->longitude = (empty($values[3])) ? null : $values[3];
        $Coordinate->altitude = (empty($values[4])) ? null : $values[4];
        $Coordinate->bearing = (empty($values[5])) ? null : $values[5];
        $Coordinate->accuracy = (empty($values[6])) ? null : $values[6];
        $Coordinate->speed = (empty($values[7])) ? null : $values[7];
        $Coordinate->battery = (empty($values[8])) ? null : $values[8];
        $Coordinate->save();
        $response = ["status" => "OK"];
        return response()->json($response);
    }

    public function index() {
        $coordinate = Coordinate::orderBy("ts", "DESC")->first();
        return view("index")->with('coordinate', $coordinate);
    }
}
