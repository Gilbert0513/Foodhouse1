<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display sales report
     */
    public function salesReport()
    {
        return view('admin.reports.sales');
    }

    /**
     * Display inventory report
     */
    public function inventoryReport()
    {
        return view('admin.reports.inventory');
    }

    /**
     * Display customer report
     */
    public function customerReport()
    {
        return view('admin.reports.customers');
    }

    /**
     * Display daily report
     */
    public function dailyReport()
    {
        return view('admin.reports.daily');
    }
}