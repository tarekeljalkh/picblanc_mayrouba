<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YearController extends Controller
{
    public function switch($year)
    {
        // optional validation
        if (!preg_match('/^\d{4}$/', $year)) {
            abort(400, 'Invalid year');
        }

        $cookie = cookie('active_year', $year, 60 * 24 * 365); // 1 year

        return back()->withCookie($cookie);
    }
}
