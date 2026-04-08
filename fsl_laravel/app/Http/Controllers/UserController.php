<?php

namespace App\Http\Controllers;

use App\Services\FlureeService;
use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userpage(FlureeService $fluree)
    {
       $query = [
    "select" => [
        "?u",
        "?firstname",
        "?lastname",
        "?email",
        "?username",
        "?appointmentletter"
    ],
    "from" => "userdetails",
    "where" => [
        ["?u", "userdetails/isactive", "1"],
        ["?u", "userdetails/is_deleted", false],
        ["?u", "userdetails/firstname", "?firstname"],
        ["?u", "userdetails/lastname", "?lastname"],
        ["?u", "userdetails/email", "?email"],
        ["?u", "userdetails/username", "?username"],
        ["?u", "userdetails/appointmentletter", "?appointmentletter"]
    ]
];
        $users = $fluree->query($query);

        return view('users.userpage', compact('users'));
    }
}
