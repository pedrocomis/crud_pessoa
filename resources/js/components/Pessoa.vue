<template>
    <div class="container mt-4">
        <h1 class="mb-4">Cadastro de Pessoas</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h5 mb-3">
                    {{ modoEdicao ? 'Editar pessoa' : 'Novo cadastro' }}
                </h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input
                            id="nome"
                            v-model="form.nome"
                            placeholder="Digite o nome"
                            class="form-control"
                        />
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            id="email"
                            v-model="form.email"
                            placeholder="Digite o e-mail"
                            class="form-control"
                            type="email"
                        />
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button v-if="!modoEdicao" @click="salvar" class="btn btn-primary">
                        Cadastrar
                    </button>
                    <button v-else @click="atualizar" class="btn btn-success">
                        Salvar alterações
                    </button>
                    <button v-if="modoEdicao" @click="cancelarEdicao" class="btn btn-secondary">
                        Cancelar
                    </button>
                </div>

                <div v-if="mensagemSucesso" class="alert alert-success mt-3">
                    {{ mensagemSucesso }}
                </div>

                <div v-if="mensagemErro" class="alert alert-danger mt-3">
                    {{ mensagemErro }}
                </div>
            </div>
        </div>

        <h2 class="mb-3">Lista de Pessoas</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">E-mail</th>
                    <th scope="col" class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="pessoa in pessoas" :key="pessoa.id">
                    <td>{{ pessoa.nome }}</td>
                    <td>{{ pessoa.email }}</td>
                    <td class="text-end">
                        <button @click="editar(pessoa.id)" class="btn btn-sm btn-warning me-2">
                            Editar
                        </button>
                        <button @click="excluir(pessoa.id)" class="btn btn-sm btn-danger">
                            Excluir
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            form: {
                nome: "",
                email: "",
            },
            pessoas: [],
            modoEdicao: false,
            pessoaEditandoId: null,
            mensagemSucesso: "",
            mensagemErro: "",
        };
    },

    mounted() {
        this.listar();
    },

    methods: {
        async listar() {
            const resposta = await axios.get("http://localhost:8000/api/pessoas");
            this.pessoas = resposta.data;
        },

        limparFormulario() {
            this.form.nome = "";
            this.form.email = "";
            this.modoEdicao = false;
            this.pessoaEditandoId = null;
        },

        mostrarMensagemSucesso(mensagem) {
            this.mensagemErro = "";
            this.mensagemSucesso = mensagem;
        },

        mostrarErro(mensagem) {
            this.mensagemSucesso = "";
            this.mensagemErro = mensagem;
        },

        async salvar() {
            try {
                await axios.post("http://localhost:8000/api/pessoas", {
                    nome: this.form.nome,
                    email: this.form.email,
                });

                this.limparFormulario();
                this.listar();
                this.mostrarMensagemSucesso("Pessoa cadastrada com sucesso!");
            } catch (error) {
                const mensagem = error.response?.data?.message || "Erro ao cadastrar pessoa.";
                this.mostrarErro(mensagem);
            }
        },

        async editar(id) {
            try {
                const resposta = await axios.get(`http://localhost:8000/api/pessoas/${id}`);
                const pessoa = resposta.data;

                this.modoEdicao = true;
                this.pessoaEditandoId = pessoa.id;
                this.form.nome = pessoa.nome;
                this.form.email = pessoa.email;
            } catch (error) {
                this.mostrarErro("Não foi possível carregar a pessoa para edição.");
            }
        },

        async atualizar() {
            try {
                await axios.put(`http://localhost:8000/api/pessoas/${this.pessoaEditandoId}`, {
                    nome: this.form.nome,
                    email: this.form.email,
                });

                this.limparFormulario();
                this.listar();
                this.mostrarMensagemSucesso("Pessoa atualizada com sucesso!");
            } catch (error) {
                const mensagem = error.response?.data?.message || "Erro ao atualizar pessoa.";
                this.mostrarErro(mensagem);
            }
        },

        async excluir(id) {
            if (!window.confirm("Deseja realmente excluir esta pessoa?")) {
                return;
            }

            try {
                await axios.delete(`http://localhost:8000/api/pessoas/${id}`);
                this.listar();
                this.mostrarMensagemSucesso("Pessoa excluída com sucesso!");
            } catch (error) {
                this.mostrarErro("Não foi possível excluir a pessoa.");
            }
        },

        cancelarEdicao() {
            this.limparFormulario();
            this.mensagemErro = "";
        },
    },
};
</script>