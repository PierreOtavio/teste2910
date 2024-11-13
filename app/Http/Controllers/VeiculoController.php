<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Veiculo;
use Illuminate\Http\Request;

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
    public function store(Request $request, Veiculo $veiculo)
    {
        $validation = $request->validate([
            'ano' => 'required|integer|digits:4',
            'marca' => 'required|string|max: 50',
            'modelo' => 'required|string|max: 50',
            'placa' => 'required|string|max: 7',
            'cor' => 'required|string|max: 20',
            'chassi' => 'required|string|max: 17',
            'capacidade' => 'required|integer|max: 20',
            'km_atual' => 'required|integer|',
            'observacao' =>'nullable|string|',
            'funcionamento' => 'required|string',
        ]);

        $veiculo::create($validation);

        return redirect()->route('veiculos.index')->with('sucess',  'Veiculo criado com sucesso!');
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
                'observacao' => 'nullable|string',
                'funcionamento' => 'nullable|string',
            ]);
            // Atualiza o veículo com os dados validados
            $veiculo->update($validatedData);
            
            // Redireciona com mensagem de sucesso
            return redirect()->route('veiculos.index')->with('success', 'Veículo editado com sucesso');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Veiculo  $veiculo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $veiculo = Veiculo::findOrFail($id);
        $veiculo->delete();

        return redirect()->route('veiculos.index')->with('success', 'Veículo deletado com sucesso');
    }


    /**
     * Altera o status de funcionamento do veículo entre disponível e indisponível.
     * 
     * @param Request $request
     * @param Veiculo $veiculo
     * @return \Illuminate\Http\RedirectResponse
     */
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
    
    public function solicitarCarro( Request $request, $id) {
        
        $veiculo = Veiculo::findOrFail($id);
        return view('solicitar.index', compact('veiculo'));
    }
}