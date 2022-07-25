<?php

namespace App\Http\Controllers\Admin\Mail;

use App\Http\Controllers\Controller;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MailSettingController extends Controller
{
    public function setting(){
        return Inertia::render('Admin/Mail/Settings', [
            'mail_settings' => MailSetting::firstOrFail()
        ]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'mail_transport'    => 'required',
            'mail_host'         => 'required',
            'mail_port'         => 'required',
            'mail_username'     => 'required',
            'mail_password'     => 'required',
            'mail_encryption'   => 'required',
            'mail_from'         => 'required|email',
        ]);

        try {
            $mail_setting = MailSetting::findOrFail($id);
            $mail_setting->mail_transport   = $request->mail_transport;
            $mail_setting->mail_host        = $request->mail_host;
            $mail_setting->mail_port        = $request->mail_port;
            $mail_setting->mail_username    = $request->mail_username;
            $mail_setting->mail_password    = $request->mail_password;
            $mail_setting->mail_encryption  = $request->mail_encryption;
            $mail_setting->mail_from        = $request->mail_from;
            $mail_setting->update();

            return redirect()->back();
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort(500);
            }
        }


    }
}
