<?php
namespace App\Http\Controllers\Admin\Backup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
use function redirect;
class DropboxAppController extends Controller
{
    protected $dropbox;
    protected $app;
    protected $dropbox_app;
    public function __construct(){
        $this->dropbox = \App\Models\Dropbox::first();
        $this->app = new DropboxApp($this->dropbox->app_key, $this->dropbox->app_secret, $this->dropbox->access_token);
        $this->dropbox_app = new Dropbox($this->app);
    }
    public function store(Request $request){
        $request->validate([
            'app_key' => 'required',
            'app_secret' => 'required',
        ]);
        try {
            $this->dropbox->app_key = $request->app_key;
            $this->dropbox->app_secret = $request->app_secret;
            $this->dropbox->redirect_url = $request->redirect_url;
            $this->dropbox->email = $request->email;
            $this->dropbox->update();
            $authUrl = 'https://www.dropbox.com/oauth2/authorize?client_id='.$request->app_key.'&token_access_type=offline&response_type=code&redirect_uri='.$request->redirect_url;
            return Inertia::location($authUrl);
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort(500);
            }
        }
    }
    public function token(){
        try {
            if (isset($_GET['code'])) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.dropbox.com/oauth2/token');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "code=".$_GET['code']."&grant_type=authorization_code&redirect_uri=".$this->dropbox->redirect_url);
                curl_setopt($ch, CURLOPT_USERPWD,  $this->dropbox->app_key . ':' .  $this->dropbox->app_secret);
                $headers = array();
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result =json_decode(curl_exec($ch));
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                $dropbox = \App\Models\Dropbox::first();
                $dropbox->refresh_token = $result->refresh_token;
                $dropbox->update();
            }
            return Inertia::render('Admin/Backup/Authorize');
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort(500);
            }
        }
    }
    public function authorizeComplete(){
        try {
            Artisan::call('dropbox-token:generate');
            sleep(3);
            Artisan::call('backup:run --only-db');
            return redirect()->route('admin.backup.settings')->with('status', 'Authorized successfully');
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort(500);
            }
        }
    }
    public function softDelete(Request $request){
        try {
            $this->dropbox_app->delete($request->path);
            return redirect()->back();
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort(500);
            }
        }
    }
    public function trash(){
        try {
            $listFolderContents = $this->dropbox_app->listFolder('/'.config('app.name'), [
                "include_deleted" => true,
                "include_has_explicit_shared_members" => true,
                "include_media_info" => true,
                "include_mounted_folders" => false,
                "include_non_downloadable_files" => false,
                "path" => '/'. config('app.name'),
                "recursive" => false
            ])->getData();
            $files  = $listFolderContents['entries'];
            $deletedFile = array_filter($files, function ($var) {
                return ($var['.tag'] == 'deleted');
            });
            return Inertia::render('Admin/Backup/Trash', [
                'trash' =>  $deletedFile
            ]);
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort(500);
            }
        }
    }
    public function restore(Request $request){
        try {
            $revisions = $this->dropbox_app->listRevisions($request->path,  ["limit" => 1]);
            $path = $revisions[0]->path_lower;
            $rev = $revisions[0]->rev;
            $this->dropbox_app->restore($path, $rev);
            return redirect()->route('admin.backup.index');
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort(500);
            }
        }
    }
    public function backup(){
        try {
            Artisan::call('backup:run');
            $output =  Artisan::output();
            $output = explode('...', $output);
            return redirect()->route('admin.backup.index')->with('output', $output);
        }catch (\Exception $e){
            if (config('app.debug')){
                echo $e->getMessage();
            }else{
                return abort(500);
            }
        }
    }
}
