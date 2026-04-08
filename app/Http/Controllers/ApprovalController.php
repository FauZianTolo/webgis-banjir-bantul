<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function pending()
    {
        return view('auth.approval-pending');
    }
}
