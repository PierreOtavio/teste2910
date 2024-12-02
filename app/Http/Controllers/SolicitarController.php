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
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
            $veiculo = Veiculo::find($id);
            $veiculo = $solicitar->veiculo;
            
            $request->validate([
                'placa_confirmar' => 'required|string',
                'velocimetro_inicio' => 'required|string',
            ]);
            
            if ($request->input('placa_confirmar') !== $veiculo->placa) {
                return redirect()->back()->with('error', 'A placa informada não corresponde à placa do veículo.');
            }
            
            $veiculo->placa_confirmar = $request->placa_confirmar;
            $veiculo->velocimetro_inicio = $request->velocimetro_inicio;
            $veiculo->km_atual = $request->velocimetro_inicio;
            $veiculo->save();
        
            return redirect()->route('solicitar.end', ['id' => $solicitar->id])->with('success', 'Solicitação iniciada.');
        }
        

        public function end($id, Request $request) {
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

            if ($veiculo->velocimetro_inicio > $request->input('velocimetro_final')) {
                return redirect()->back()->with('error', 'A quilometragem final não pode ser menor que a inicial.');
            }
            
            $veiculo->placa_confirmar2 = $request->placa_confirmar2;
            $veiculo->velocimetro_final = $request->velocimetro_final;
            $veiculo->km_atual = $request->velocimetro_final;
            $veiculo->funcionamento = 0;
            $veiculo->save();
            
            $solicitar->hora_final = Carbon::now();
            $solicitar->situacao = 'Finalizada';
            $solicitar->obs_user = $request->input('obs_user');
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

            return redirect()->route('solicitar.show', ['id' => $solicitar->veiculo->id])->with('success', 'Solicitação finalizada com sucesso!');

        }

        public function aceitar($id) {
            $solicitar = Solicitar::findOrFail($id);
            $solicitar->situacao = 'Aceito';
            $solicitar->save();

            return redirect()->route('solicitar.show',  ['id' => $solicitar->id])->with('success', 'Solicitação aceita.');
        }

        public function recusar($id, Request $request) {
            $solicitar = Solicitar::findOrFail($id);
            $solicitar->situacao = 'Recusado';
            $solicitar->save();

            $veiculo = Veiculo::findOrFail($id);

            $this->motivoRecusado($id, $request);
        }
        
        public function motivoRecusado($id, Request $request) {
            $solicitar = Solicitar::findOrFail($id);

            $request->validate([
                'motivo_recusado' => 'required|string'
            ]);
            
            $solicitar->motivo_recusado = $request->input('motivo_recusado');
            $solicitar->hora_recusado = Carbon::now();
            $solicitar->id_recusado = Auth::id();
            $solicitar->save();
            
            return view('solicitar.recusado', ['id' => $solicitar->id]);
        }

        public function finalizadas() {
            $user = Auth::user();
            $solicitars = Solicitar::where('situacao', 'Finalizada')->get();

            if (auth()->user()->cargo == 0) {
                $solicitars = Solicitar::where('situacao', 'Finalizada')->with('veiculo')->get();
            } else {
                $solicitars = Solicitar::where('user_id', $user->id)
                    ->where('situacao', 'Finalizada')
                    ->with('veiculo')
                    ->get();
            }

            return view('solicitar.finalizadas', compact('solicitars'));
        }

        public function gerarPDF()
        {
        // Captura o usuário logado
        $user = auth()->user();

        // Buscar todas as solicitações finalizadas do usuário
        $solicitacoes = Solicitar::with('veiculo')
            ->where('user_id', $user->id)
            ->where('situacao', 'Finalizada')
            ->get();

        // Verifica se há solicitações para gerar o PDF
        if ($solicitacoes->isEmpty()) {
            return response()->json(['message' => 'Nenhuma solicitação finalizada encontrada.'], 404);
        }

        
        // Gerar o PDF
        foreach ($solicitacoes as $solicitar) {
            $mpdf = new Mpdf();
            $html = "<h1>Relatório de Uso do Veículo</h1>";
            $html .= "<p>Colaborador: {$user->name}</p>";
            $html .= "<p>ID: {$user->id}</p>";
            $html .= "<p>Email: {$user->email}</p>";
            
            $data1 = \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/y');
            $data2 = \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/y');
            $hora1 = \Carbon\Carbon::parse($solicitar->hora_inicio)->format('h:i A');
            $hora2 = \Carbon\Carbon::parse($solicitar->hora_final)->format('h:i A');
            
            // Loop através das solicitações para adicionar ao PDF
            $veiculo = $solicitar->veiculo;
            $percorrido = $veiculo->velocimetro_final - $veiculo->velocimetro_inicio;
            $html .= "<h2>Solicitação ID: {$solicitar->id}</h2>";
            $html .= "<p>Veículo: {$veiculo->marca} {$veiculo->modelo}</p>";
            $html .= "<p>Placa: {$veiculo->placa}</p>";
            $html .= "<p>Data Inicial: $data1</p>";
            $html .= "<p>Data Final: $data2</p>";
            $html .= "<p>Quilometragem Inicial: {$veiculo->velocimetro_inicio} km </p>";
            $html .= "<p>Quilometragem Final: {$veiculo->velocimetro_final} km </p>";
            $html .= "<p>Quilometros Percorridos: $percorrido km</p>";
            $html .= "<p>Observações: {$solicitar->obs_user}</p>";
            $html .= "<hr>"; // Linha horizontal para separar as solicitações
        }

        // Escreve o HTML no PDF
        $mpdf->WriteHTML($html);

        // Retorna o PDF como resposta
        return response($mpdf->Output(), 200)->header('Content-Type', 'application/pdf');
        }

        

        public function exportarTodasExcel()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Cabeçalho
    $sheet->setCellValue('A1', 'Colaborador');
    $sheet->setCellValue('B1', 'Email');
    $sheet->setCellValue('C1', 'Veículo');
    $sheet->setCellValue('D1', 'Placa');
    $sheet->setCellValue('E1', 'Data Inicial');
    $sheet->setCellValue('F1', 'Data Final');
    $sheet->setCellValue('G1', 'Hora Inicial');
    $sheet->setCellValue('H1', 'Hora Final');
    $sheet->setCellValue('I1', 'Motivo');
    $sheet->setCellValue('J1', 'Situação');

    // Estilizar o cabeçalho
    $sheet->getStyle('A1:J1')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 12,
            'color' => ['argb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['argb' => '4CAF50'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ]);

    // Dados
    $solicitars = Solicitar::with(['veiculo', 'user'])->where('situacao', 'Finalizada')->get();
    $row = 2;
    foreach ($solicitars as $solicitar) {
        $sheet->setCellValue('A' . $row, $solicitar->user->name);
        $sheet->setCellValue('B' . $row, $solicitar->user->email);
        $sheet->setCellValue('C' . $row, $solicitar->veiculo->marca . ' ' . $solicitar->veiculo->modelo);
        $sheet->setCellValue('D' . $row, $solicitar->veiculo->placa);
        $sheet->setCellValue('E' . $row, \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/Y'));
        $sheet->setCellValue('F' . $row, \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/Y'));
        $sheet->setCellValue('G' . $row, $solicitar->hora_inicial);
        $sheet->setCellValue('H' . $row, $solicitar->hora_final);
        $sheet->setCellValue('I' . $row, $solicitar->motivo);
        $sheet->setCellValue('J' . $row, $solicitar->situacao);
        $row++;
    }

    // Ajustar largura das colunas
    foreach (range('A', 'J') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Baixar o arquivo
    $writer = new Xlsx($spreadsheet);
    $filename = 'todas_solicitacoes.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $writer->save('php://output');
}

    }