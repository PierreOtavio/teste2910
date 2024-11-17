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
        public function index(Request $request, $id = null)
        {
            $user = Auth::user();

            // Se for responsável, mostrar todas as solicitações
            if ($user->cargo == 0) {
                $solicitars = Solicitar::with('veiculo')->get();
            } else {
                // Colaboradores comuns veem apenas suas solicitações
                $solicitars = Solicitar::where('user_id', $user->id)->with('veiculo')->get();
            }

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
        
        public function ver(Solicitar $solicitar, Veiculo $veiculo,$id) {
            $solicitar = Solicitar::find($id);
            
            // Verifica se a solicitação foi encontrada
            if (!$solicitar) {
                return redirect()->route('solicitar.index')->with('error', 'Solicitação não encontrada');
            }
            
            $solicitar = Solicitar::with('user')->findOrFail($id);

            $veiculo = $solicitar->veiculo;
            return view('solicitar.ver',compact('veiculo','solicitar'));
        }

        public function start($id) {
            $solicitar = Solicitar::find($id);
            $veiculo = $solicitar->veiculo;
            return view('solicitar.start', compact('veiculo','solicitar'))->with('success', 'Solicitação iniciada.');
        }

        public function prosseguir(Request $request, $id) {
            // dd($request->all());
            $solicitar = Solicitar::find($id);
            $veiculo = $solicitar->veiculo;
        
            $request->validate([
                'placa_confirmar' => 'required|string',
                'velocimetro_inicio' => 'required|string',
            ]);
            
            if ($request->input('placa_confirmar') !== $veiculo->placa) {
                return redirect()->back()->with('error', 'A placa informada não corresponde à placa do veículo.');
            }
            
            $veiculo->placa_confirmar = $request->placa_confirmar;
            $veiculo->km_atual = $request->velocimetro_inicio;
            $veiculo->save();
        
            return redirect()->route('solicitar.end', ['id' => $solicitar->id]);
        }
        

        public function end(Request $request, $id) {
            $solicitar = Solicitar::find($id);
            $veiculo = $solicitar->veiculo;
        
            // Validar os dados recebidos
            $request->validate([
                'placa_confirmar' => 'required|string',
                'velocimetro_final' => 'required|string',
            ]);
        
            // Verificar se a placa confirmada corresponde à placa do veículo
            if ($request->placa_confirmar !== $veiculo->placa) {
                return redirect()->back()->with('error', 'A placa informada não corresponde à placa do veículo.');
            }
        
            // Atualizar o veículo com os novos dados
            $veiculo->placa_confirmar = $request->placa_confirmar;
            $veiculo->km_atual = $request->velocimetro_final;
            $veiculo->save();
            return view('solicitar.show', compact('veiculo', 'solicitar'))->with('success', 'Solicitação finalizada com sucesso!');
        }

        public function aceitar($id) {
            $solicitar = Solicitar::findOrFail($id);
            $solicitar->situacao = 'Aceito';
            $solicitar->save();

            return redirect()->back()->with('success', 'Solicitação aceita.');
        }

        public function recusar($id) {
            $solicitar = Solicitar::findOrFail($id);
            $solicitar->situacao = 'Recusado';
            $solicitar->save();

            return redirect()->back()->with('success', 'Solicitação recusada.');
        }



    }