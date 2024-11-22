<?php

    namespace App\Http\Controllers;

    use App\Controllers\VeiculoController;
    use App\Models\Solicitar;
    use App\Models\Veiculo;
    use Illuminate\Http\Request;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;


    class SolicitarController extends Controller
    {

        public function index()
        {
            $user = Auth::user();

            if ($user->cargo == 0) {
                $solicitars = Solicitar::whereNull('hora_final')->with('veiculo')->get();
            } else {
                $solicitars = Solicitar::where('user_id', $user->id)
                    ->whereNull('hora_final')
                    ->with('veiculo')
                    ->get();
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
        
        
        public function ver(Solicitar $solicitar, Veiculo $veiculo,$id) {
            $solicitar = Solicitar::find($id);
            
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
        
        public function end($id) {
            $solicitar = Solicitar::find($id);
            $veiculo = $solicitar->veiculo;
            return view('solicitar.end', compact('veiculo','solicitar'))->with('success', 'Solicitação iniciada.');
        }
        
        public function finalizar(Request $request, $id)
        {
            $solicitar = Solicitar::find($id);
            $veiculo = $solicitar->veiculo;
            
            $request->validate([
                'placa_confirmar2' => 'required|string',
                'velocimetro_final' => 'required|string',
            ]);
            
            if ($request->placa_confirmar2 !== $veiculo->placa) {
                return redirect()->back()->with('error', 'A placa informada não corresponde à placa do veículo.');
            }
            
            $veiculo->placa_confirmar2 = $request->placa_confirmar2;
            $veiculo->km_atual = $request->velocimetro_final;
            $veiculo->save();
            
            $solicitar->hora_final = Carbon::now();
            $solicitar->situacao = 'Finalizada'; 
            $solicitar->save();  
            
            $user = Auth::user();

            if ($user->cargo == 0) {
                $solicitars = Solicitar::whereNull('hora_final')->with('veiculo')->get();
            } else {
                $solicitars = Solicitar::where('user_id', $user->id)
                    ->whereNull('hora_final')
                    ->with('veiculo')
                    ->get();
            }

            return redirect()->route('solicitar.show', ['id' => $solicitar->veiculo->id] )->with('success', 'Solicitação finalizada com sucesso!');
        }

        public function aceitar($id) {
            $solicitar = Solicitar::findOrFail($id);
            $solicitar->situacao = 'Aceito';
            $solicitar->save();

            return redirect()->route('solicitar.show',  ['id' => $solicitar->veiculo->id] )->with('success', 'Solicitação aceita.');
        }

        public function recusar($id) {
            $solicitar = Solicitar::findOrFail($id);
            $solicitar->situacao = 'Recusado';
            $solicitar->save();

            return redirect()->route('solicitar.show', $solicitar->veiculo->id )->with('danger', 'Solicitação recusada.');
        }

        public function finalizadas() {
            $user = Auth::user();

            if ($user->cargo == 0) {
                $solicitars = Solicitar::where('hora_final')->with('veiculo')->get();
            } else {
                $solicitars = Solicitar::where('user_id', $user->id)
                    ->where('hora_final')
                    ->with('veiculo')
                    ->get();
            }

            return view('solicitar.finalizadas', compact('solicitars'));
        }

    }