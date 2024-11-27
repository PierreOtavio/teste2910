<?php

namespace Database\Factories;

use App\Http\Controllers\VeiculoController;
use App\Models\Veiculo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class VeiculoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
    return [
        'ano' => $this->faker->year(),
        'marca' => $this->faker->word(),
        'modelo' => $this->faker->word(),
        'placa' => strtoupper($this->faker->bothify('???-####')), // Gera uma placa no formato padrão
        'cor' => $this->faker->safeColorName(),
        'chassi' => strtoupper($this->faker->bothify('###############')), // 17 caracteres para o chassi
        'capacidade' => $this->faker->numberBetween(2, 7), // Exemplo de capacidade para veículos
        'km_atual' => $this->faker->numberBetween(0, 300000),
        'observacao' => $this->faker->paragraph(),
        ];
        $veiculo->fill($data);
        $veiculo->save();

        $this->seedQRCode($veiculo);

        return $data;
    }

    public function seedQRCode(Veiculo $veiculo) {
        // Gerar QR Code com o ID do veículo
        $qrCode = QrCode::generate($veiculo->id);  // Use o ID do veículo aqui
        $fileName = time() . '.svg'; // Nome único para o arquivo
        file_put_contents(public_path('qrcodes/' . $fileName), $qrCode);
    
        // Atualizar o veículo com o caminho do QR Code
        $veiculo->update(['qr_code' => $fileName]);
    }
}
