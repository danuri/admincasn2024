<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Spmt extends BaseController
{
    public function index()
    {
        return view('penetapan/spmt');
    }
}
