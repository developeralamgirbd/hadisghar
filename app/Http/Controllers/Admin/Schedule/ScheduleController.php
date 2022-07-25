<?php
namespace App\Http\Controllers\Admin\Schedule;
use App\Http\Controllers\Controller;
use App\Models\ScheduleList;
use App\Models\taskScheduleFrequencie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
class ScheduleController extends Controller
{
     public function calender(){
         try {
             Artisan::call('schedule:list');
             return Inertia::render('Admin/Schedule/Calender', [
                 'schedule' => ScheduleList::with('frequency')->get()
             ]);
         }catch (\Exception $e){
             if (config('app.debug')){
                echo  $e->getMessage();
             }else{
                 return abort(404);
             }
         }
     }
    public function settings(){
        try {
            return Inertia::render('Admin/Schedule/Settings', [
                'schedules' => ScheduleList::with('frequency')->get(),
                'schedule_frequencies' => taskScheduleFrequencie::all(),
            ]);
        }catch (\Exception $e){
            if (config('app.debug')){
                echo  $e->getMessage();
            }else{
                return abort(404);
            }
        }
    }
    public function run(Request $request){
        try {
            Artisan::call($request->command);
           $output =  Artisan::output();
           $output = explode('...', $output);
            return redirect()->back()->with('output', $output);
        }catch (\Exception $e){
            if (config('app.debug')){
                echo  $e->getMessage();
            }else{
                return abort(404);
            }
        }
    }
    public function store(Request $request){
        try {
            foreach ($request->frequency as $row){
                list($k, $v) =  explode('=>', $row);
                $arr = array($k => $v);
                foreach ($arr as $key => $item){
                    ScheduleList::where('command_name', $key)->update(['frequency_id' => $item]);
                }
            }
            return redirect()->route('admin.schedule.index');
        }catch (\Exception $e){
            if (config('app.debug')){
                echo  $e->getMessage();
            }else{
                return abort(404);
            }
        }
    }
}
