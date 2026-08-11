<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PessoaController extends Controller
{
    public function index()
    {
        $pessoas = Pessoa::orderBy('id', 'desc')->get();

        return response()->json($pessoas);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:pessoas,email'],
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
        ]);

        $pessoa = Pessoa::create($dados);

        return response()->json($pessoa, 201);
    }

    public function show(int $id)
    {
        $pessoa = Pessoa::findOrFail($id);

        return response()->json($pessoa);
    }

    public function update(Request $request, int $id)
    {
        $pessoa = Pessoa::findOrFail($id);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('pessoas', 'email')->ignore($pessoa->id)],
        ], [
            'nome.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
        ]);

        $pessoa->fill($dados);
        $pessoa->save();

        return response()->json($pessoa);
    }

    public function destroy(int $id)
    {
        $pessoa = Pessoa::findOrFail($id);
        $pessoa->delete();

        return response()->json([
            'mensagem' => 'Pessoa excluída com sucesso!',
        ]);
    }
}