<?php

namespace App\Http\Controllers\Backend\CommonPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminViewController extends Controller
{
    public function dashboard()
    {
        return view('backend.common-pages.dashboard.dashboard');
    }
}
