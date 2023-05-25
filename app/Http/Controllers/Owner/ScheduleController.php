<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalOwner;
use App\Models\AnimalSchedule;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScheduleController extends Controller
{
    public function index()
    {
        return view("pages.owner.schedule.index")->with([
            "animals" => AnimalOwner::with(["AnimalSchedules", "Animal"])->whereUserId(Auth::user()->id)->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "schedule_name" => "array|distinct",
            "schedule_name.*" => "required",
            "schedule_time" => "array",
            "schedule_time.*" => "required",
            "schedule_type" => "array",
            "schedule_type.*" => "required",
        ]);
        DB::beginTransaction();
        try {
            if ($this->isNameIncludeInArray($request->schedule_name)) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data tidak valid");
            }

            if ($this->isNull($request->schedule_name) || $this->isNull($request->schedule_time)) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data tidak valid");
            }
            $animal = Animal::firstOrCreate([
                "name" => Str::title($request->name)
            ]);

            if ($this->alreadyHasAnimal($animal->id)) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data nama produk jadi sudah teregistrasi sebelumnya. Pilih nama bahan baku lainnya!");
            }

            $animal_owner = AnimalOwner::create([
                "animal_id" => $animal->id,
                "user_id" => Auth::user()->id,
            ]);

            foreach ($request->schedule_name as $idx => $value) {
                AnimalSchedule::create([
                    "animal_owner_id" => $animal_owner->id,
                    "schedule_name" => Str::title($request->schedule_name[$idx]),
                    "schedule_time" => $request->schedule_time[$idx],
                    "schedule_type" => $request->schedule_type[$idx]
                ]);
            }

            DB::commit();
            return redirect()->back()->with("success", "Data berhasil disimpan");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function update(Request $request, AnimalOwner $animalOwner)
    {
        $request->validate([
            "name" => "required",
            "schedule_name" => "array|distinct",
            "schedule_name.*" => "required",
            "schedule_time" => "array",
            "schedule_time.*" => "required",
            "schedule_type" => "array",
            "schedule_type.*" => "required",
        ]);

        DB::beginTransaction();
        try {
            if ($this->isNameIncludeInArray($request->schedule_name)) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data tidak valid");
            }

            if ($this->isNull($request->schedule_name) || $this->isNull($request->schedule_time)) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data tidak valid");
            }
            $animal = Animal::firstOrCreate([
                "name" => Str::title($request->name)
            ]);

            if ($this->alreadyHasAnimal($animal->id) && $animalOwner->Animal->name != $animal->name) {
                DB::rollBack();
                return redirect()->back()->with("error", "Data tidak valid");
            }

            $animalOwner->update([
                "animal_id" => $animal->id
            ]);

            AnimalSchedule::whereAnimalOwnerId($animalOwner->id)->delete();

            foreach ($request->schedule_name as $idx => $value) {
                AnimalSchedule::create([
                    "animal_owner_id" => $animalOwner->id,
                    "schedule_name" => Str::title($request->schedule_name[$idx]),
                    "schedule_time" => $request->schedule_time[$idx],
                    "schedule_type" => $request->schedule_type[$idx]
                ]);
            }

            DB::commit();
            return redirect()->back()->with("success", "Data berhasil disimpan");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function show($animal_id)
    {
        $schedule = AnimalSchedule::with("AnimalOwner.Animal")->whereHas("AnimalOwner", function ($query) use ($animal_id) {
            $query->where('animal_id', $animal_id)->where('user_id', Auth::user()->id);
        })->get();
        return response()->json($schedule);
    }

    private function isNameIncludeInArray($array)
    {
        if (count($array) != count(array_unique($array))) {
            return true; // ada nilai yang muncul lebih dari dua kali
        }
        return false;
    }

    private function isNull($array)
    {
        if (in_array(null, $array)) {
            return true;
        }
        return false;
    }

    private function alreadyHasAnimal($animal_id)
    {
        return AnimalOwner::whereAnimalId($animal_id)->whereUserId(Auth::user()->id)->exists();
    }
}
