<?php

    namespace App\Http\Controllers;

    use App\Controllers\VeiculoController;
    use App\Models\Solicitar;
    use App\Models\Veiculo;
    use Illuminate\Http\Request;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;

    class SolicitarController extends Controller
    {
        public function index()
        {
            // Carrega as solicitações junto com os veículos relacionados
            $solicitars = Solicitar::with('veiculo')->get();
    
            // Retorna a view com os dados
            return view('solicitar.show', compact('solicitars'));
        }


        public function create($veiculo_id) 
        {
            $veiculo = Veiculo::findOrFail($veiculo_id);
            return view('solicitar.create', compact('veiculo'));
        }
        
        public function store(Request $request)
        {
            $data['user_id'] = Auth::id();
            
            $validation = $request->validate([
                'veiculo_id' => 'required|exists:veiculos,id',
                'hora_inicial' => 'required|string', 
                'data_inicial' => 'required|date',
                'data_final' => 'required|date',
                'motivo' => 'required|string|max:255',
            ]);

            $solicitar = new Solicitar();
            $solicitar->veiculo_id = $request->veiculo_id;
            $solicitar->data_inicial = $request->data_inicial;
            $solicitar->hora_inicial = $request->hora_inicial;
            $solicitar->data_final = $request->data_final;
            $solicitar->motivo = $request->motivo;
            $solicitar->user_id = Auth::id();
            $solicitar->save();

            return redirect()->route('solicitar.index');
        }

        /**
         * Display the specified resource.
         *
         * @param  \App\Models\Solicitar  $solicitar
         * @return \Illuminate\Http\Response
         */
        public function show(Request $request, $id = null)
        {    
            $user = Auth::user();

            // Se um ID for passado, exibe detalhes de uma solicitação específica
            if ($id) {
                $solicitar = Solicitar::with('veiculo')->find($id);

                if (!$solicitar) {
                    return redirect()->route('solicitar.index')->with('error', 'Solicitação não encontrada');
                }

                return view('solicitar.show', compact('solicitar'));
            }

            // Exibe todas as solicitações ou apenas as do usuário
            $solicitars = $user->cargo == 0
                ? Solicitar::with('veiculo')->get()
                : $user->solicitars()->with('veiculo')->get();

            return view('solicitar.show', compact('solicitars'));
        }


            // $solicitar = Solicitar::where('user_id', Auth::id())->find($id);

            // // Verifica se a solicitação foi encontrada
            // if (!$solicitar) {
            //     return redirect()->route('solicitacao.index')->with('error', 'Solicitação não encontrada');
            // }   

            // // Recupera o veículo relacionado à solicitação
            // $veiculo = $solicitar->veiculo;

            // return view('solicitar.show', compact('solicitar', 'veiculo'));
            
        

        public function aprovarOuReprovar($id, Solicitar $solicitar, Veiculo $veiculo, $request) {

            // Busca a solicitação e relaciona com o veículo;
            $solicitacao = Solicitar::with('veiculo')->findOrFail($id);
            $veiculo = $solicitacao->veiculo;

            $solicitacao->situacao = $request->situacao;
            $solicitacao->save();

            if ($request->situacao === 'aprovado') {
                $veiculo->funcionamento == 1;
            } else {
                $veiculo->funcionamento == 0;
            }
            $veiculo->save();
            // dd($veiculo, $solicitacao);

            /* Como não temos ainda uma view de ver solicitações aceitas ou recusadas,
             eu vou deixar pra voltar pra pagina anterior. */
            return redirect()->back()->with('sucess', 'Solicitação aprovada com sucesso');
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
        public function update(Request $request, $id)
        {
            
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

        public function ver(Solicitar $solicitar, Veiculo $veiculo,$id) {
            $solicitar = Solicitar::find($id);

            // Verifica se a solicitação foi encontrada
                if (!$solicitar) {
                    return redirect()->route('solicitacao.index')->with('error', 'Solicitação não encontrada');
                }

            $veiculo = $solicitar->veiculo;
            return view('solicitar.ver',compact('veiculo','solicitar'));
     }}