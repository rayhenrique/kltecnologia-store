<?php

namespace App\Http\Controllers;

use App\Services\CustomerDownloadService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function downloads(Request $request, CustomerDownloadService $downloads): View
    {
        return view('customer.downloads', ['downloads' => $downloads->for($request->user())]);
    }
}
