<?php
namespace App\Http\Controllers\Admin\Security;
use App\Http\Controllers\Controller;
use App\Models\LimitLoginAttempt;
use Illuminate\Http\Request;
use Inertia\Inertia;
class LimitLoginAttemptController extends Controller
{
    public function index(){
        $limitLoginAttempt = LimitLoginAttempt::first();
       return Inertia::render('Admin/Security/LimitLoginAttempt', [
           'limitLoginAttempt' => $limitLoginAttempt
       ]);    }
    public function update(Request $request, $id){
        $request->validate([
            'attempt' => 'required',
            'seconds' =>  'required'
        ]);
        $limitLoginAttempt = LimitLoginAttempt::findOrFail($id);
        $limitLoginAttempt->attempt = $request->attempt;
        $limitLoginAttempt->second = $request->seconds;
        $limitLoginAttempt->update();
        return redirect()->back();
    }
}
