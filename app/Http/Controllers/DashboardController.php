<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClientes = Cliente::count();
        return view('gestor.dashboard', compact('totalClientes'));

    }
}
