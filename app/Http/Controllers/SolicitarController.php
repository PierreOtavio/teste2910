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

            if ($request->input('velocimetro_inicio') > ('velocimetro_final')) {
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

        public function recusar($id) {
            $solicitar = Solicitar::findOrFail($id);
            $solicitar->situacao = 'Recusado';
            $solicitar->save();

            $veiculo = Veiculo::findOrFail($id);

            return redirect()->route('solicitar.show', $solicitar->veiculo->id )->with('danger', 'Solicitação recusada.');
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
        $mpdf = new Mpdf();
        $html = "<h1>Relatório de Uso do Veículo</h1>";
        $html .= "<p>Colaborador: {$user->name}</p>";
        $html .= "<p>ID: {$user->id}</p>";
        $html .= "<p>Email: {$user->email}</p>";

        // Loop através das solicitações para adicionar ao PDF
        foreach ($solicitacoes as $solicitar) {
            $veiculo = $solicitar->veiculo;
            $html .= "<h2>Solicitação ID: {$solicitar->id}</h2>";
            $html .= "<p>Veículo: {$veiculo->marca} {$veiculo->modelo}</p>";
            $html .= "<p>Placa: {$veiculo->placa}</p>";
            $html .= "<p>Data Inicial: {$solicitar->data_inicial}</p>";
            $html .= "<p>Data Final: {$solicitar->data_final}</p>";
            $html .= "<p>Quilometragem Inicial: {$solicitar->velocimetro_inicio}</p>";
            $html .= "<p>Quilometragem Final: {$solicitar->velocimetro_final}</p>";
            $html .= "<p>Observações: {$solicitar->obs_user}</p>";
            $html .= "<hr>"; // Linha horizontal para separar as solicitações
        }

        // Escreve o HTML no PDF
        $mpdf->WriteHTML($html);

        // Retorna o PDF como resposta
        return response($mpdf->Output(), 200)->header('Content-Type', 'application/pdf');
        }

        

        public function exportarExcel($id)
        {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

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

            $namec = $solicitar->user->name;
            $data1 = \Carbon\Carbon::parse($solicitar->data_inicial)->format('d/m/y');
            $data2 = \Carbon\Carbon::parse($solicitar->data_final)->format('d/m/y');
            $hora1 = \Carbon\Carbon::parse($solicitar->hora_inicio)->format('h:i A');
            $hora2 = \Carbon\Carbon::parse($solicitar->hora_final)->format('h:i A');

    // Adicionar dados
            $sheet->setCellValue('B2', 'Colaborador:');
            $sheet->setCellValue('B3', $namec);
            $sheet->setCellValue('C2', 'ID:');
            $sheet->setCellValue('C3',$user->id);
            $sheet->setCellValue('D2', 'Telefone:');
            $sheet->setCellValue('D3', 1);
            $sheet->setCellValue('E2', 'Email');
            $sheet->setCellValue('E3', $user->email);
            $sheet->setCellValue('F2', 'Veículo:');
            $sheet->setCellValue('F3', $veiculo->marca, $veiculo->modelo);
            $sheet->setCellValue('G2', 'Placa:');
            $sheet->setCellValue('G3', $veiculo->placa);
            $sheet->setCellValue('H2', 'O.S.:');
            $sheet->setCellValue('H3', $solicitar->id);
            $sheet->setCellValue('I2', 'Data:');
            $sheet->setCellValue('I3', $data1, $data2);
            $sheet->setCellValue('J2', 'Hora Inicial:');
            $sheet->setCellValue('J3', $hora1);
            $sheet->setCellValue('K2', 'Hora Final:');
            $sheet->setCellValue('K3', $hora2);
            $sheet->setCellValue('L2', 'Km:');
            $sheet->setCellValue('L3',1);

            // Aplicar estilo ao cabeçalho
            $sheet->getStyle('B2:L2')->applyFromArray([
                'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFF'], //cor do texto
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '4CAF50'], //cor d fundo
            ],
        ]);

                //ajusta a largura das colunas
                foreach (range('B', 'L') as $col) {
                 $sheet->getColumnDimension($col)->setWidth(20);
                }

                    // Ajustar a altura
                $sheet->getRowDimension('2')->setRowHeight(25);
                $sheet->getRowDimension('3')->setRowHeight(20);

                //bordas normais
                $sheet->getStyle('B2:L3')->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Borda fina
                            'color' => ['argb' => '000000'], // Cor preta
                    ],
                 ],
            ]);

                //borda superior espessa
                $sheet->getStyle('B2:L2')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, // Borda espessa
                                'color' => ['argb' => '000000'], // Cor da borda: preta
                        ],
                    ],
                ]);

                        //alinhamento
                        $sheet->getStyle('B2:L3')->applyFromArray([
                            'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    ]);


                    // Baixar o arquivo
             $writer = new Xlsx($spreadsheet);
             $filename = 'relatorio.xlsx';

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
             header('Content-Disposition: attachment; filename="' . $filename . '"');

                $writer->save('php://output');
            }
        }
    }