<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeletedRequestsController extends Controller
{
    public function show()
    {
        return view('deleted_requests');
    }
}
