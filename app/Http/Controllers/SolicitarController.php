<?php

    namespace App\Http\Controllers;

    use App\Controllers\VeiculoController;
    use App\Models\Solicitar;
    use App\Models\Veiculo;
    use Illuminate\Http\Request;

    class SolicitarController extends Controller
    {
        public function index()
        {
            $veiculos = Veiculo::all();
            return view ('solicitar.index', compact ('veiculos'));
        }

        public function create(Veiculo $veiculo) 
        {
            return view ('solicitar.create');
        }
        
        public function store(Request $request)
        {
            $validation = $request->validate([
                'hora_inicial' => 'required|string', 
                'data_inicial' => 'required|date',
                'data_final' => 'required|date',
                'motivo' => 'required|string|max:255'
            ]);

    
            $solicitar = Solicitar::create($validation);

            return redirect()->route('solicitacao.index')->with('Sua solicitação foi enviada com sucesso!');
        }

        /**
         * Display the specified resource.
         *
         * @param  \App\Models\Solicitar  $solicitar
         * @return \Illuminate\Http\Response
         */
        public function show(Veiculo $veiculo, Solicitar $solicitar)
        {    
            $solicitars = Solicitar::all();
            return view ('solicitar.show', compact ('veiculo','solicitars'));
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

        // public function solicitarCarro(Request $request,Solicitar $solicitar) {

        //     $veiculo = Veiculo::findOrFail($id);
        //     return view('solicitar.create', compact('veiculo'));

        // }

        public function ver(Solicitar $solicitar, Veiculo $veiculo) {

            $veiculo = $solicitar->veiculo;
            return view('solicitar.ver',compact('veiculo','solicitar'));
        }
     }