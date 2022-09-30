<?php
namespace App\Traits;

trait HostNames{

// public const Orders_MS = "http://127.0.0.1:8001/api/products";


    public function getOrders_MS(){
        return "http://127.0.0.1:8003/api/products";
    }


    public function getUsers_MS(){
        return "http://127.0.0.1:8001/api/users";
    }
}
