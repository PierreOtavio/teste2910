<?php

    namespace App\Http\Controllers;

    use App\Controllers\VeiculoController;
    use App\Models\Solicitar;
    use App\Models\Veiculo;
    use Illuminate\Http\Request;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;
    use Mpdf\Mpdf;


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
            return view('solicitar.ver', compact('veiculo','solicitar'));
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
                'obs_user' => 'required|string',
            ]);
            
            if ($request->placa_confirmar2 !== $veiculo->placa) {
                return redirect()->back()->with('error', 'A placa informada não corresponde à placa do veículo.');
            }
            
            $veiculo->placa_confirmar2 = $request->placa_confirmar2;
            $veiculo->km_atual = $request->velocimetro_final;
            $veiculo->funcionamento = 0;
            $veiculo->save();
            
            $solicitar->hora_final = Carbon::now();
            $solicitar->situacao = 'Finalizada';
            $solicitar->obs_user = $request->input('obs_user');

            $solicitar->save();

            
            
            // dd($request->all());
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

            $veiculo = Veiculo::findOrFail($id);
            if ($solicitar->situacao == 'Aceito') {
                $veiculo->funcionamento = 1;
                $veiculo->update();
            } else {
                $veiculo->save();
            }

            // dd($solicitar->situacao);
            return redirect()->route('solicitar.show',  ['id' => $solicitar->veiculo->id] )->with('success', 'Solicitação aceita.');
        }

        public function recusar($id) {
            $solicitar = Solicitar::findOrFail($id);
            $solicitar->situacao = 'Recusado';
            $solicitar->save();

            $veiculo = Veiculo::findOrFail($id);
            if ($solicitar->situacao == 'Recusado') {
                $veiculo->funcionamento = 0;
                $veiculo->update();
            } else {
                $veiculo->save();
            }

            return redirect()->route('solicitar.show', $solicitar->veiculo->id )->with('danger', 'Solicitação recusada.');
        }

        public function finalizadas() {
            $user = Auth::user();

            // dd($solicitars);
            if (auth()->user()->cargo == 0) {
                $solicitars = Solicitar::where('hora_final')->with('veiculo')->get();
            } else {
                $solicitars = Solicitar::where('user_id', $user->id)
                    ->where('hora_final')
                    ->with('veiculo')
                    ->get();
            }

            return view('solicitar.finalizadas', compact('solicitars'));
        }

        public function gerarPDF($id) {
            $solicitar = Solicitar::findOrFail($id);
            $veiculo = Veiculo::findOrFail($id);
            $user = User::findOrFail($id);
            if ($solicitar) {
        // Obtém a placa do veículo
            $hora_inicial = $solicitar->placa;

        // Busca a solicitação associada ao veículo (caso exista)
            $solicitar = Solicitar::where('veiculo_id', $id)->first();

        // Obtém a hora inicial, caso exista
            $hora_inicial = $solicitar ? $solicitar->hora_inicial : 'N/A';

            $data1 = \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/y');
            $data2 = \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/y');
            $hora1 = \Carbon\Carbon::parse($solicitar->hora_inicio)->format('h:i A');
            $hora2 = \Carbon\Carbon::parse($solicitar->hora_final)->format('h:i A');
            $km = 

            $mpdf = new Mpdf();
            $html = '<h1>Relatório do Veículo</h1>';
            $html .= "<p>Colaborador:  $user->name  </p>";
            $html .= "<p>ID:  $user->id  </p>";
            $html .= "<p>Telefone:    </p>";
            $html .= "<p>Email:  $user->email  </p>";
            $html .= "<p>Veículo:  $veiculo->marca $veiculo->modelo </p>";
            $html .= "<p>Placa: $veiculo->placa </p>";
            $html .= "<p>O.S.:  $solicitar->id  </p>";
            $html .= "<p>Data:  $data1 - $data2  </p>";
            $html .= "<p>Hora:  $hora1 - $hora2  </p>";
            $html .= "<p>Km:  $km  </p>";
            $mpdf->WriteHTML($html);

            return response($mpdf->Output(), 200)->header('Content-Type', 'application/pdf');
            }
        }
}