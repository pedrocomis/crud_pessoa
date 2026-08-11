<template>
    <!-- Container principal da tela, usando classes do Bootstrap -->
    <div class="container mt-4">
        <!-- Título da página -->
        <h1 class="mb-4">Cadastro de Pessoas</h1>

        <!-- Formulário de cadastro -->
        <div class="input-group mb-4">
            <!-- Campo de entrada do nome -->
            <!-- v-model liga o campo com a variável "nome" do JavaScript -->
            <input
                v-model="nome"
                placeholder="Digite o nome"
                class="form-control"
            />

            <!-- Botão que chama a função salvar() ao ser clicado -->
            <button @click="salvar" class="btn btn-primary">Cadastrar</button>
        </div>

        <h2>Lista de Pessoas</h2>

        <!-- Lista de pessoas cadastradas -->
        <ul class="list-group">
            <!--
        v-for percorre o array "pessoas"
        Para cada pessoa, cria um item de lista
        :key ajuda o Vue a identificar cada item de forma única
      -->
            <li
                v-for="pessoa in pessoas"
                :key="pessoa.id"
                class="list-group-item d-flex justify-content-between align-items-center"
            >
                <!-- Mostra o nome da pessoa -->
                {{ pessoa.nome }}

                <!-- Ao clicar, envia o ID da pessoa para a função excluir() -->
                <button
                    @click="excluir(pessoa.id)"
                    class="btn btn-sm btn-danger"
                >
                    Excluir
                </button>
            </li>
        </ul>
    </div>
</template>

<script>
// Importa o Axios, biblioteca usada para fazer requisições HTTP
 import axios from "axios";

 export default {
    data() {
        return {
            nome: "", // Guarda o nome digitado no formulário
            pessoas: [], // Guarda a lista de pessoas vindas do banco
        };
    },

    // mounted() é executado automaticamente quando o componente é carregado
    mounted() {
        this.listar();
    },

    methods: {
        // Busca a lista de pessoas cadastradas no Laravel
        async listar() {
            // Faz uma requisição GET para a API do Laravel
            const resposta = await axios.get(
                "http://localhost:8000/api/pessoas"
            );

            // Guarda os dados recebidos na variável "pessoas"
            this.pessoas = resposta.data;
        },

        // Envia um novo cadastro para o Laravel
        async salvar() {
            // Envia o nome digitado para a API do Laravel
            await axios.post("http://localhost:8000/api/pessoas", {
                nome: this.nome,
            });

            // Limpa o campo após salvar
            this.nome = "";

            // Atualiza a lista de pessoas
            this.listar();
        },

        // Remove uma pessoa pelo ID
        async excluir(id) {
            // Envia o ID para a API excluir o registro
            await axios.delete(`http://localhost:8000/api/pessoas/${id}`);

            // Atualiza a lista depois da exclusão
            this.listar();
        },
    },
 };
</script>