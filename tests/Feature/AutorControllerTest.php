<?php

namespace Tests\Feature;

use App\Models\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_listar_autores_com_paginacao()
    {
        // Arrange: Criar alguns autores no banco de dados
        Autor::factory()->count(15)->create();

        // Act: Acessar a rota de listagem de autores
        $response = $this->get('/autores');

        // Assert: Verificar se a resposta contém os autores paginados
        $response->assertStatus(200);
        $response->assertViewHas('autores');
    }
    /** @test */
    public function pode_criar_um_novo_autor()
    {
        // Arrange: Criar os dados do autor
        $dados = [
            'nome' => 'Autor Teste',
            'biografia' => 'Biografia do autor teste',
            'data_nascimento' => '1990-01-01',
        ];

        // Act: Enviar uma requisição POST para criar um autor
        $response = $this->post('/autores', $dados);

        // Assert: Verificar se o autor foi criado no banco de dados
        $response->assertRedirect('/autores');
        $this->assertDatabaseHas('autores', ['nome' => 'Autor Teste']);
    }
    /** @test */
    public function pode_atualizar_um_autor_existente()
    {
        // Arrange: Criar um autor
        $autor = Autor::factory()->create();

        // Novos dados
        $dadosAtualizados = [
            'nome' => 'Nome Atualizado',
            'biografia' => 'Biografia atualizada',
        ];

        // Act: Enviar uma requisição PUT para atualizar o autor
        $response = $this->put("/autores/{$autor->id}", $dadosAtualizados);

        // Assert: Verificar se os dados foram atualizados
        $response->assertRedirect('/autores');
        $this->assertDatabaseHas('autores', ['nome' => 'Nome Atualizado']);
    }
    /** @test */
    public function pode_excluir_um_autor()
    {
        // Arrange: Criar um autor
        $autor = Autor::factory()->create();

        // Act: Enviar uma requisição DELETE para excluir o autor
        $response = $this->delete("/autores/{$autor->id}");

        // Assert: Verificar se o autor foi excluído
        $response->assertRedirect('/autores');
        $this->assertDatabaseMissing('autores', ['id' => $autor->id]);
    }
    /** @test */
    public function performance_listar_autores_com_paginacao()
    {
        // Arrange: Criar um grande número de autores
        Autor::factory()->count(500)->create();

        // Start measuring the time
        $start = microtime(true);

        // Act: Acessar a rota de listagem de autores
        $response = $this->get('/autores');

        // Stop measuring the time
        $duration = microtime(true) - $start;

        // Assert: Verificar o tempo de resposta
        $response->assertStatus(200);

        // Verificar se o tempo de resposta foi menor que 2 segundos
        $this->assertTrue($duration < 0.2, "O tempo de resposta foi maior que 2 segundos: {$duration} segundos");
    }
    /** @test */
    public function performance_criar_autores_em_massa()
    {
        // Start measuring the time
        $start = microtime(true);

        // Act: Criar 1000 autores
        Autor::factory()->count(1000)->create();

        // Stop measuring the time
        $duration = microtime(true) - $start;

        // Verificar se o tempo de criação foi menor que 5 segundos
        $this->assertTrue($duration < 0.05, "O tempo de criação de autores foi maior que 5 segundos: {$duration} segundos");
    }
}
