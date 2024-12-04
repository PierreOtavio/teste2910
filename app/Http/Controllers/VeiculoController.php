<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode; //extensão para o Qr Code

class VeiculoController extends Controller
{
    public function index(Request $request)
    {
        $query = Veiculo::query();
        if ($request->filled('search')) {
            $query->where('placa', 'like', '%' . $request->search . '%')
                  ->orwhere('chassi', 'like', '%' . $request->search . '%')
                  ->orwhere('marca', 'like', '%' . $request->search . '%')
                  ->orwhere('modelo', 'like', '%' . $request->search . '%');
        }

        $veiculos = $query->get();
        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        return view('veiculos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validation = $request->validate([
            'ano' => 'required|integer|digits:4',
            'marca' => 'required|string|max: 50',
            'modelo' => 'required|string|max: 50',
            'placa' => 'required|string|max: 7|unique:veiculos',
            'cor' => 'required|string|max: 20',
            'chassi' => 'required|string|max: 17|unique:veiculos',
            'capacidade' => 'required|integer|max: 20',
            'km_atual' => 'required|integer',
            'km_revisao' => 'required|integer',
            'observacao' =>'nullable|string|',
            'funcionamento' => 'required|string',
        ]);

        $veiculo = Veiculo::create($validation);

        // Gerar QR Code com o ID do veículo
        $qrCode = QrCode::generate($veiculo->id);  // Use o ID do veículo aqui
        $fileName = time() . '.svg'; // Nome único para o arquivo
        file_put_contents(public_path('qrcodes/' . $fileName), $qrCode);
    
        // Atualizar o veículo com o caminho do QR Code
        $veiculo->update(['qr_code' => $fileName]);
       

        
        return redirect()->route('veiculos.index')->with('sucess', 'Veículo criado com sucesso!');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Veiculo  $veiculo
     * @return \Illuminate\Http\Response
     */
    public function show(Veiculo $veiculo)
    {
       
        return view('veiculos.show', compact('veiculo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Veiculo  $veiculo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $veiculo = Veiculo::findOrFail($id);
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(Request $request,$id) 
    {
        $veiculo = Veiculo::findOrFail($id);
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'ano' => 'required|integer',
                'marca' => 'required|string|max:255',
                'modelo' => 'required|string|max:255',
                'chassi' => 'required|string|size:17',
                'placa' => 'required|string|max:7',
                'km_atual' => 'required|integer',
                'km_revisao' => 'required|integer',
                'observacao' => 'nullable|string',
                'funcionamento' => 'nullable|string',
            ]);
        
            $veiculo->update($validatedData);
            return redirect()->route('veiculos.index')->with('success', 'Veículo editado com sucesso');
    }


 
    public function destroy($id)
    {
        $veiculo = Veiculo::findOrFail($id);
        $veiculo->delete();

        return redirect()->route('veiculos.index')->with('success', 'Veículo deletado com sucesso');
    }

    public function mudarStatus(Request $request, $id) 
    { 
        try {
            $veiculo = Veiculo::findOrFail($id);
            
            // Valida que o valor de funcionamento é 0 ou 1
            $request->validate([
                'funcionamento' => 'required|in:0,1'
            ]);

            // Atualiza o funcionamento do veículo
            $veiculo->update([
                'funcionamento' => $request->funcionamento
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    //PARTE DE SOLICITAR UM VEÍCULO:
    public function solicitarCarro( Request $request, $id) {
        
        $veiculo = Veiculo::findOrFail($id);
        return view('solicitar.create', compact('veiculo'));
    }

    public function solicitarIndex(Request $request) {
        $query = Veiculo::query();
        if ($request->filled('search')) {
            $query->where('placa', 'like', '%' . $request->search . '%')
                  ->orwhere('chassi', 'like', '%' . $request->search . '%')
                  ->orwhere('marca', 'like', '%' . $request->search . '%')
                  ->orwhere('modelo', 'like', '%' . $request->search . '%');
        }

        $veiculos = $query->get();
        return view('solicitar.index', compact('veiculos'));
    }
}