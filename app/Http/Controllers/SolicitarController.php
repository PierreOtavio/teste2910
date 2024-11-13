<?php

namespace App\Http\Controllers;

use App\Models\Solicitar;
use Illuminate\Http\Request;

class SolicitarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('solicitar.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Solicitar  $solicitar
     * @return \Illuminate\Http\Response
     */
    public function show(Solicitar $solicitar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Solicitar  $solicitar
     * @return \Illuminate\Http\Response
     */
    public function edit(Solicitar $solicitar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Solicitar  $solicitar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Solicitar $solicitar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Solicitar  $solicitar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solicitar $solicitar)
    {
        //
    }

    public function solicitarCarro(Request $request,Veiculo $veiculo) {

        $veiculos = Veiculo::all();
        return view('solicitar.index', compact('veiculo'));

    }
}
