<?php

namespace App\Http\Controllers;

use App\Core\Utilities\PerPage;

class ToolsController extends Controller
{
    public function perPage($per_page)
    {
        PerPage::set($per_page);

        return back();
    }
}
