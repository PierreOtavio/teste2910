<?php

    namespace App\Http\Controllers;

    use Dompdf\Dompdf;
    use Dompdf\Options;
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
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Style\Style;


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
                'data_final' => 'required|date|after_or_equal:data_inicial',
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

            $solicitar->hora_inicial = Carbon::now();
            $solicitar->save();

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

            if($solicitar->situacao === 'Finalizada') {
                $veiculo->funcionamento = 0;
                $veiculo->save();
            }

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

            if ($solicitar->situacao = 'Aceito') {
            $veiculo = Veiculo::findOrFail($id);
            $veiculo->funcionamento = 1;
            }

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

        public function gerarPDF($id)
        {
        // Buscar a solicitação pelo ID com o relacionamento do usuário e do veículo
    $solicitar = Solicitar::with('user', 'veiculo')->findOrFail($id);

    // Obter os dados necessários
    $user = $solicitar->user;  // Dados do usuário
    $veiculo = $solicitar->veiculo;  // Dados do veículo

    // Passar as variáveis para a view
    $dados = compact('user', 'veiculo', 'solicitar'); // Passar 'user', 'veiculo' e 'solicitar'

    // Renderizar a view Blade como HTML
    $html = view('solicitar.pdf', $dados)->render();

    // Configurar o Dompdf
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);

    // Carregar o HTML no Dompdf
    $dompdf->loadHtml($html);

    // Configurar o tamanho e a orientação da página
    $dompdf->setPaper('A4', 'portrait');

    // Renderizar o PDF
    $dompdf->render();
    // Retornar o PDF para download
    return response($dompdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="relatorio_uso_veiculo.pdf"');
}
        

        

public function exportarTodasExcel()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


$sheet->getStyle('B2:L2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THICK);

    // Cabeçalho
    $sheet->setCellValue('B2', 'Colaborador');
    $sheet->setCellValue('C2', 'Email');
    $sheet->setCellValue('D2', 'ID da Solicitação');
    $sheet->setCellValue('E2', 'Veículo');
    $sheet->setCellValue('F2', 'Placa');
    $sheet->setCellValue('G2', 'Data Inicial');
    $sheet->setCellValue('H2', 'Data Final');
    $sheet->setCellValue('I2', 'Hora Inicial');
    $sheet->setCellValue('J2', 'Hora Final');
    $sheet->setCellValue('K2', 'Motivo');
    $sheet->setCellValue('L2', 'Situação');

    // Estilizar o cabeçalho
    $sheet->getStyle('B2:L2')->applyFromArray([
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
    $row = 3;
    foreach ($solicitars as $solicitar) {
        $sheet->setCellValue('B' . $row, $solicitar->user->name);
        $sheet->setCellValue('C' . $row, $solicitar->user->email);
        $sheet->setCellValue('D' . $row, $solicitar->id);
        $sheet->setCellValue('E' . $row, $solicitar->veiculo->marca . ' ' . $solicitar->veiculo->modelo);
        $sheet->setCellValue('F' . $row, $solicitar->veiculo->placa);
        $sheet->setCellValue('G' . $row, \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/Y'));
        $sheet->setCellValue('H' . $row, \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/Y'));
        $sheet->setCellValue('I' . $row, $solicitar->hora_inicial);
        $sheet->setCellValue('J' . $row, $solicitar->hora_final);
        $sheet->setCellValue('K' . $row, $solicitar->motivo);
        $sheet->setCellValue('L' . $row, $solicitar->situacao);
        $row++;
    }

    // Ajustar largura das colunas
    foreach (range('B', 'L') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    $lastRow = $row - 1; // O valor de $row após o loop aponta para a próxima linha, então subtraímos 1
    $sheet->getStyle("B3:L{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Baixar o arquivo
    $writer = new Xlsx($spreadsheet);
    $filename = 'todas_solicitacoes.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $writer->save('php://output');
}       
}