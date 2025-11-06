<?php

namespace App\Controllers;

class Pengaturan extends BaseController
{
    public function index(): string
    {
        return view('pengaturan');
    }

    function save() {
        // Logic to save settings goes here
        return redirect()->back()->with('message', 'Pengaturan berhasil disimpan');
    }

}
