<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PessoaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_crud_da_pessoa_pelo_json_api(): void
    {
        $response = $this->postJson('/api/pessoas', [
            'nome' => 'Maria Souza',
            'email' => 'maria@email.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('nome', 'Maria Souza')
            ->assertJsonPath('email', 'maria@email.com');

        $pessoaId = $response->json('id');

        $this->getJson('/api/pessoas')
            ->assertStatus(200)
            ->assertJsonFragment([
                'nome' => 'Maria Souza',
                'email' => 'maria@email.com',
            ]);

        $this->getJson('/api/pessoas/' . $pessoaId)
            ->assertStatus(200)
            ->assertJsonPath('id', $pessoaId);

        $this->putJson('/api/pessoas/' . $pessoaId, [
            'nome' => 'Maria Atualizada',
            'email' => 'maria.nova@email.com',
        ])->assertStatus(200)
            ->assertJsonPath('nome', 'Maria Atualizada')
            ->assertJsonPath('email', 'maria.nova@email.com');

        $this->deleteJson('/api/pessoas/' . $pessoaId)
            ->assertStatus(200)
            ->assertJsonPath('mensagem', 'Pessoa excluída com sucesso!');
    }
}
