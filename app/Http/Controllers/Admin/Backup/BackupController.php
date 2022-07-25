<?php
namespace App\Http\Controllers\Admin\Backup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;
class BackupController extends Controller
{
    public function __construct(){
       Artisan::call('backup:schedule');
    }
   public function index(){
       try {
           $dropbox = \App\Models\Dropbox::first();
           $app = new DropboxApp($dropbox->app_key, $dropbox->app_secret, $dropbox->access_token);
           $dropbox_app = new Dropbox($app);
           $listFolderContents = $dropbox_app->listFolder('/'.config('app.name'));
           $files  = $listFolderContents->getData();
           $files = $files['entries'];
//      get Delete files and send count
           $listFolderContents = $dropbox_app->listFolder('/'.config('app.name'), [
               "include_deleted" => true,
               "include_has_explicit_shared_members" => true,
               "include_media_info" => true,
               "include_mounted_folders" => false,
               "include_non_downloadable_files" => false,
               "path" => '/'. config('app.name'),
               "recursive" => false
           ])->getData();
           $files2  = $listFolderContents['entries'];
           $deletedFile = array_filter($files2, function ($var) {
               return ($var['.tag'] == 'deleted');
           });
//        Next backup schedule date get
           $nextBackupSchedule = \App\Models\Schedule::pluck('backup_next')->first();
           return Inertia::render('Admin/Backup/Manage', [
               'files' => $files,
               'totalDelete' => $deletedFile,
               'nextBackupSchedule' => $nextBackupSchedule
           ]);
       }catch (\Exception $e){
           if (config('app.debug')){
              echo $e->getMessage();
           }else{
               return abort(500);
           }
       }
   }
   public function settings(){
        $dropbox = \App\Models\Dropbox::pluck('email')->first();
       return Inertia::render('Admin/Backup/Settings', [
           'reportEmail' => $dropbox
       ]);
   }
}
