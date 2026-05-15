<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
   
public function userpage(FlureeService $fluree)
{
   // Get current authenticated user
   $currentUser = Session::get('fluree_user');
   if (!$currentUser) {
       return redirect()->route('login');
   }

   $query = [
    "select" => [
        "?u" => [
            "_id",
            "userdetails/_id",
            "firstname",
            "lastname",
            "email",
            "username",
            "mobileno",
            "designation",
            "isactive",
            "appointmentletter",
            "role_id",
            "dept_code",
            "inst_id"
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
 
    return view('users.userpage', compact('users', 'currentUser'));
}

}
