<?
namespace App\Traits;

trait HostNames{

//public const Users_MS = "http://127.0.0.1:8002/api";


    public function getUsers_MS(){
        return "http://127.0.0.1:8002/api/users";
    }
}
