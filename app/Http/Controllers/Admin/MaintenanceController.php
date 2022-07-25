<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;

class MaintenanceController extends Controller
{
    public function index(){
        return Inertia::render('Admin/Maintenance/Settings', [
            'maintenance_mode' => Maintenance::firstOrFail()
        ]);
    }

    public function settings($status){
       if ($status == 'on'){
           Artisan::call('up');
            $maintenance = Maintenance::firstOrFail();
            $maintenance->mode = 'on';
            $maintenance->update();
       }elseif ($status == 'off'){
           Artisan::call('down');
           $maintenance = Maintenance::firstOrFail();
           $maintenance->mode = 'off';
           $maintenance->update();
       }
       return redirect()->back();
    }
}
