<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use Illuminate\Http\Request;

class UserController extends Controller
{
   
public function userpage(FlureeService $fluree)
{
   $query = [
    "select" => [
        "?u" => [
            "firstname",
            "lastname",
            "email",
            "username",
            "appointmentletter"
        ]
    ],
    "where" => [
        ["?u", "userdetails/isactive", "1"], 
        ["?u", "userdetails/is_deleted", false]
    ],
    "opts" => [
        "limit" => 50
    ]
];

    $users = $fluree->query($query);
 
    return view('users.userpage', compact('users'));
}

}
