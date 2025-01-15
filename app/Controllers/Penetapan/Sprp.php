<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Sprp extends BaseController
{
    public function index()
    {
        return view('penetapan/sprp/index');
    }
}
