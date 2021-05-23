<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Record;
use App\Models\Session;
use Arr;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $authenticated = false;
    private $session_id = null;
    /**
     * @var Record[]
     */
    private $records = array();
    /**
     * @var Device|null
     */
    private $device = null;
    /**
     * @var Session|null
     */
    private $session;
    /**
     * @var array
     */
    private $points;
    /**
     * @var Record|null
     */
    private $first_record;
    /**
     * @var Record|null
     */
    private $last_record;

    private function init() {
        $this->authenticated = session()->get('authenticated', false);
        $this->session_id = session()->get('session_id', null);
        if (!empty($this->session_id)) {
            $this->init_session();
        }
    }

    private function init_session() {
        if (empty($this->session_id))
            return;
        session()->push('session_id', $this->session_id);
        $this->session = Session::whereSessionId($this->session_id)->first();
        $this->device = $this->session->Device();
        $this->records = $this->session->Records();
        $this->points = $this->session->Points($this->records);
        $this->first_record = $this->session->Record("first");
        $this->last_record = $this->session->Record("last");
    }

    public function auth(Request $request) {
        $this->init();
        if ($this->authenticated==true)
            return redirect(route('dashboard'));
        if ($request->isMethod("POST")) {
            $password = $request->get('password', "");
            if ($password==env("APP_PASSWORD")) {
                session()->put('authenticated', true);
                session()->save();
                return redirect(route("sessions"));
            }
        }
        return view("auth");
    }

    public function logout() {
        session()->put('authenticated', false);
        session()->put('session_id', "");
        session()->save();
        return redirect(route("auth"));
    }

    public function sessions() {
        $this->init();
        if ($this->authenticated!=true)
            return redirect('/auth');
        $this->init_session();
        Record::sortDevicesAndSessions();
        $devices = Device::pluck("name", "device_id");
        $sessions = Session::orderBy("first_seen", "DESC")->get();
        $force = empty($this->session_id);
        return view("sessions")
            ->with("force", $force)
            ->with("devices", $devices)
            ->with("sessions", $sessions)
            ->with("session", $this->session)
            ->with("device", $this->device);
    }

    public function dashboard(Request $request) {
        $this->init();
        if ($this->authenticated!=true)
            return redirect('/auth');
        if ($request->has("select")) {
            $session = Session::whereSessionId($request->get("select"))->first();
            if (!empty($session->session_id)) {
                $this->session_id = $session->session_id;
                $this->init_session();
            }
        }
        if ($this->session_id==null || empty($this->session))
            return redirect(route("sessions"));

        $altitudes = $this->session->Altitudes();
        $keys = json_encode(array_keys($altitudes), JSON_UNESCAPED_SLASHES );
        $values = json_encode(array_values($altitudes), JSON_UNESCAPED_SLASHES );
        $altitudes = ["keys" => $keys, "values" => $values, "min" => min(array_values($altitudes)), "max" => max(array_values($altitudes))];

        $speed = $this->session->Speed();
        $keys = json_encode(array_keys($speed), JSON_UNESCAPED_SLASHES );
        $values = json_encode(array_values($speed), JSON_UNESCAPED_SLASHES );
        $speed = ["keys" => $keys, "values" => $values, "min" => min(array_values($speed)), "max" => max(array_values($speed))];

        return view("dashboard")
            ->with("session", $this->session)
            ->with("device", $this->device)
            ->with("points", $this->points)
            ->with("last_record", $this->last_record)
            ->with('speed', $speed)
            ->with('altitudes', $altitudes);
    }

    public function charts() {
        $this->init();
        if ($this->authenticated!=true)
            return redirect('/auth');
        if ($this->session_id==null)
            return redirect(route("sessions"));
        return view("charts")->with("session", $this->session)
            ->with("device", $this->device);
    }

    public function files() {
        $this->init();
        if ($this->authenticated!=true)
            return redirect('/auth');
        if ($this->session_id==null)
            return redirect(route("sessions"));
        return view("files")->with("session", $this->session)
            ->with("device", $this->device);
    }

    public function settings() {
        $this->init();
        if ($this->authenticated!=true)
            return redirect('/auth');
        if ($this->session_id==null)
            return redirect(route("sessions"));
        return view("charts")->with("session", $this->session)
            ->with("device", $this->device);
    }

    public function map() {
        $this->init();
        if ($this->authenticated!=true)
            return redirect('/auth');
        if ($this->session_id==null)
            return redirect(route("sessions"));
        return view("map")
            ->with("session", $this->session)
            ->with("device", $this->device)
            ->with("points", $this->points)
            ->with("last_record", $this->last_record);
    }

    public function record() {
        $response = ["status" => "KO"];
        $data = request()->get("data", "");
        if (empty($data))
            return response()->json($response);
        $values = explode(";", $data);
        if (count($values) < 8)
            return response()->json($response);
        $Record = new Record();
        $i = 0;
        $Record->device_id = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->session_id = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->ts_ms = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->latitude = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->longitude = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->altitude = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->bearing = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->accuracy = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->speed = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->battery_temp = (empty($values[$i])) ? null : $values[$i]; $i++;
        $Record->battery_level = (empty($values[$i])) ? null : $values[$i]; $i++;
        try {
            $Record->save();
        } catch (Exception $e) {

        }
        $response = ["status" => "OK"];
        return response()->json($response);
    }
}
