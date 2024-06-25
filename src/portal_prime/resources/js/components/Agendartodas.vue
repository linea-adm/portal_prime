<template>
<div class="container mx-auto p-4 rounded-lg overflow-hidden shadow-lg">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center bg-sky-50 p-4 mt-6 rounded-lg shadow-md">
        <!-- Dados do Cliente -->
        <div class="flex-1">
            <h2 class="text-2xl font-semibold mb-2">Cliente: {{ dadosCliente.nomeFantasia }}</h2>
            <p><strong>Município-UF:</strong> {{ dadosCliente.municipioEstado }}</p>
            <p><strong>CNPJ:</strong> {{ dadosCliente.cnpj }}</p>
            <p><strong>E-mail:</strong> {{ dadosCliente.email }}</p>
        </div>

        <!-- Botões de Ação -->
        <div class="flex flex-col items-end space-y-2">
            <button @click="enviarEmail" class="flex items-center bg-blue-500 text-white px-4 py-2 rounded-md font-bold hover:bg-blue-600">
                <i class="fas fa-envelope mr-2"></i> Enviar E-mail
            </button>
            <form :action="logoutUrl" method="POST" class="w-full">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" class="flex items-center justify-center w-full bg-red-500 text-white px-4 py-2 rounded-md font-bold hover:bg-red-600">
                    <i class="fas fa-sign-out-alt mr-2"></i> Sair
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Notas Fiscais -->
    <h2 class="text-2xl font-semibold mb-4 mt-6">Notas Fiscais</h2>
    <div class="mb-4 flex items-center space-x-4">
        <!-- Inputs para agendamento em massa -->
        <div v-if="modoSelecao" class="flex items-center gap-2">
            <input type="date" v-model="dataAgendamento" class="border rounded-md p-1">
            <input type="time" v-model="horaAgendamento" class="border rounded-md p-1">
            <button @click="agendarEmMassa" class="px-4 py-2 bg-green-500 text-white rounded-md font-bold">
                <i class="fas fa-clock"></i> Agendar Selecionadas
            </button>
        </div>
        <button @click="toggleModoSelecao" class="px-4 py-2 bg-blue-500 text-white rounded-md font-bold">
            <i class="fas fa-check-square"></i> {{ modoSelecao ? 'Cancelar Seleção' : 'Selecionar Notas Para Agendamento' }}
        </button>
        <input type="text" v-model="termoPesquisa" placeholder="Pesquisar por Nota Fiscal" class="border rounded-md p-2 flex-1">
    </div>

    <!-- Tabela de Notas Fiscais -->
    <table class="min-w-full table-auto shadow-md rounded-lg">
        <thead>
            <tr class="bg-sky-100">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <input type="checkbox" @change="selecionarTodasNotas" class="form-checkbox h-5 w-5 text-blue-500" :disabled="!modoSelecao">
                </th>
                <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Nota
                    Fiscal</th>
                <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                    Emissão</th>
                <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Pedido
                </th>
                <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                    Transportadora</th>
                <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                    Agendar Para</th>
                <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                    Detalhes do Pedido</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(nota, index) in notasFiltradasPaginadas" :key="nota.f2_doc" :class="[index % 2 === 0 ? 'bg-gray-50' : 'bg-white', 'transition-colors', 'ease-in-out', 'duration-300', 'hover:bg-gray-200']">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" v-model="nota.agendar" class="form-checkbox h-5 w-5 text-blue-500" :disabled="!modoSelecao">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div v-if="nota.loading" class="flex items-center justify-center">
                        <i class="fas fa-spinner fa-spin"></i> {{ nota.f2_doc }}
                    </div>
                    <div v-else>
                        {{ nota.f2_doc }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ formatarData(nota.c5_emissao) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ nota.c5_num }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ nota.f2_transp }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button @click="mostrarCamposDataHora(nota)" class="px-4 py-2 text-white rounded-md font-bold" :class="{ 'bg-gray-500': modoSelecao, 'bg-blue-500 hover:bg-blue-700': !modoSelecao }" :disabled="modoSelecao">
                        <i class="fas fa-clock"></i> Agendar esta entrega
                    </button>
                    <div v-if="nota.mostrarCamposDataHora" class="mt-2">
                        <input type="date" v-model="nota.dataAgendamento" class="border rounded-md p-1 mr-2">
                        <input type="time" v-model="nota.horaAgendamento" class="border rounded-md p-1">
                        <button @click="abrirModalConfirmacao([nota])" class="px-3 py-1 bg-green-500 text-white rounded-md ml-2">
                            <i class="fas fa-check"></i>
                        </button>
                        <button @click="fecharLinha(nota)" class="px-1 py-0 bg-gray-500 text-white rounded-md ml-2">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button @click="verDetalhes(nota)" class="bg-yellow-500 text-white px-4 py-2 rounded-md font-bold">
                        <i class="fas fa-info-circle"></i> Detalhes
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Modal de Confirmação -->
    <div v-if="mostrarModalConfirmacao" class="fixed inset-0 flex items-center justify-center modal-bg">
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-4">Confirmar Agendamento</h2>
            <p>Confirma o agendamento para a data: {{ formatarData(notasParaConfirmar[0]?.dataAgendamento.replaceAll('-', '')) || formatarData(dataAgendamento.replaceAll('-', '')) }} e
                hora: {{ notasParaConfirmar[0]?.horaAgendamento || horaAgendamento }} para as seguintes notas:</p>
                <p class="text-red-500 font-bold">(Todas as notas selecionadas serão agendadas para esta data e hora)</p>
            <ul>
                <li v-for="nota in notasParaConfirmar" :key="nota.f2_doc">{{ nota.f2_doc }}</li>
            </ul>
            <div class="flex justify-end mt-4">
                <button @click="confirmarAgendamento" class="px-4 py-2 bg-green-500 text-white rounded-md mr-2">Confirmar</button>
                <button @click="fecharModalConfirmacao" class="px-4 py-2 bg-red-500 text-white rounded-md">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Erro -->
    <div v-if="mostrarErro" class="fixed inset-0 flex items-center justify-center modal-bg">
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-4">Erro</h2>
            <p>{{ mensagemErro }}</p>
            <div class="flex justify-end mt-4">
                <button @click="fecharErro" class="px-4 py-2 bg-red-500 text-white rounded-md">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Sucesso -->
    <div v-if="mostrarSucesso" class="fixed inset-0 flex items-center justify-center modal-bg">
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold mb-4">Sucesso</h2>
            <p>{{ mensagemSucesso }}</p>
            <div class="flex justify-end mt-4">
                <button @click="fecharSucesso" class="px-4 py-2 bg-green-500 text-white rounded-md">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Paginação -->
    <div class="pagination">
        <button @click="paginaAtual--" :disabled="paginaAtual === 1" class="px-4 py-2 bg-blue-500 text-white rounded-md">Anterior</button>
        <span class="text-gray-700">Página {{ paginaAtual }} de {{ Math.ceil(notasFiltradas.length / itensPorPagina)
                }}</span>
        <button @click="paginaAtual++" :disabled="paginaAtual === Math.ceil(notasFiltradas.length / itensPorPagina)" class="px-4 py-2 bg-blue-500 text-white rounded-md">Próxima</button>
    </div>

    <!-- Modal de Detalhes do Pedido -->
    <div v-if="detalhesVisiveis" class="fixed inset-0 flex items-center justify-center modal-bg">
        <div class="modal-content bg-white p-6 rounded-lg shadow-lg overflow-auto max-w-screen-lg">
            <div v-if="loading" class="progress w-full h-2 bg-gray-200 rounded">
                <div class="progress-bar bg-blue-500 h-2 rounded" :style="{ width: progressPercent + '%' }"></div>
            </div>
            <h2 v-if="loading" class="text-lg font-semibold mb-4">Carregando Detalhes do Pedido...</h2>
            <h2 v-if="!loading" class="text-lg font-semibold mb-4">Detalhes do Pedido</h2>
            <table v-if="!loading" class="min-w-full border overflow-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">Produto</th>
                        <th class="border px-4 py-2">Descrição</th>
                        <th class="border px-4 py-2">Quantidade</th>
                        <th class="border px-4 py-2">Valor Unitário</th>
                        <th class="border px-4 py-2">Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in detalhesPedido" :key="item.f2_doc" :class="[index % 2 === 0 ? 'bg-gray-50' : 'bg-white']">
                        <td class="border px-4 py-2">{{ item.b1_cod }}</td>
                        <td class="border px-4 py-2 font-semibold">{{ item.b1_desc }}</td>
                        <td class="border px-4 py-2">{{ item.d2_quant }}</td>
                        <td class="border px-4 py-2">{{ formatarValor(item.d2_prcven) }}</td>
                        <td class="border px-4 py-2">{{ formatarValor(item.d2_total) }}</td>
                    </tr>
                </tbody>
            </table>
            <p class="mt-4" v-if="!loading"><strong>Total Geral:</strong> {{ formatarValor(valorTotalGeralCalculado)
                    }}</p>
            <button @click="fecharDetalhes" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md">Fechar</button>
        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['dadosCliente', 'dadosNotasFiscais', 'logoutUrl'],
    data() {
        return {
            username: 'magno.borges',
            password: 'Eic@ti0100',
            selecionarTodas: false,
            notasFiscais: [],
            detalhesVisiveis: false,
            detalhesPedido: [],
            paginaAtual: 1,
            itensPorPagina: 10,
            dataAgendamento: '',
            horaAgendamento: '',
            termoPesquisa: '',
            mostrarModalConfirmacao: false,
            modoSelecao: false,
            notasParaConfirmar: [],
            mostrarErro: false,
            mensagemErro: '',
            mostrarSucesso: false,
            mensagemSucesso: '',
            loading: false,
            progressPercent: 0,
            emailLogistica: '',
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };
    },
    mounted() {
        this.ocultarPreloader();
    },
    created() {
        this.obterEmailLogistica();
        this.notasFiscais = this.dadosNotasFiscais.map(nota => {
            nota.agendar = false;
            nota.mostrarCamposDataHora = false;
            nota.agendamentoConfirmado = false;
            return nota;
        });
    },
    computed: {
        // Filtra as notas fiscais com base no termo de pesquisa
        notasFiltradas() {
            const termo = this.termoPesquisa.toLowerCase().trim();
            return this.notasFiscais.filter(nota => {
                const numeroNota = nota.f2_doc.toString().toLowerCase();
                return numeroNota.includes(termo);
            });
        },
        // Retorna um subconjunto das notas fiscais para paginação
        notasFiltradasPaginadas() {
            const inicio = (this.paginaAtual - 1) * this.itensPorPagina;
            const fim = inicio + this.itensPorPagina;
            return this.notasFiltradas.slice(inicio, fim);
        },
        // Calcula o valor total dos detalhes do pedido
        valorTotalGeralCalculado() {
            return this.detalhesPedido.reduce((totalGeral, item) => {
                return totalGeral + parseFloat(item.d2_total);
            }, 0).toFixed(2);
        },
        // Verifica se todas as notas fiscais estão selecionadas
        todasNotasSelecionadas: {
            get() {
                return this.notasFiscais.length > 0 && this.notasFiscais.every(nota => nota.agendar);
            },
            set(value) {
                this.notasFiscais.forEach(nota => {
                    nota.agendar = value;
                });
            },
        },
    },
    methods: {

        // Busca o e-mail da logística do backend
        async obterEmailLogistica() {
            try {
                const response = await axios.get('/primeapi/logistica-email');
                this.emailLogistica = response.data.email;
            } catch (error) {
                console.error('Erro ao obter o e-mail da logística:', error);
            }
        },
        // Abre o modal de confirmação de agendamento
        abrirModalConfirmacao(notas) {
            if (this.modoSelecao && notas.length === 0) {
                this.mostrarErro = true;
                this.mensagemErro = 'Nenhuma nota foi selecionada para agendamento.';
                return;
            }
            this.notasParaConfirmar = notas.length > 0 ? notas : this.notasFiscais.filter(nota => nota.agendar);
            this.mostrarModalConfirmacao = true;
        },
        // Fecha o modal de confirmação de agendamento
        fecharModalConfirmacao() {
            this.mostrarModalConfirmacao = false;
        },
        // Confirma o agendamento e envia os dados para o servidor
        async confirmarAgendamento() {
            try {
                const agendamentoDados = this.notasParaConfirmar.map(nota => ({
                    filial: nota.f2_filial,
                    dt_agendamento: nota.dataAgendamento.replaceAll('-', ''),
                    hr_agendamento: nota.horaAgendamento.replace(':', ''),
                    tipo: '114',
                    cliente: nota.a1_cod,
                    chave_nfe: nota.f2_chvnfe,
                }));

                const authHeader = 'Basic ' + btoa(this.username + ':' + this.password);
                const headers = {
                    'Authorization': authHeader
                };
                await axios.post('http://www.erplineaalimentos.com.br:8191/rest/AgendarEntrega/agendamento', agendamentoDados, {
                    headers
                });

                this.mostrarSucesso = true;
                this.mensagemSucesso = "Agendamento realizado com sucesso.";
                this.enviarEmailConfirmacao();
                this.fecharModalConfirmacao();
            } catch (error) {
                this.mostrarErro = true;
                this.mensagemErro = "Erro ao agendar a entrega: " + (error.response ? error.response.data : error.message); // Erro mais detalhado
            }
        },
        // Envia um e-mail para o cliente e para a logística
        enviarEmail() {
            const email = this.emailLogistica;
            const subject = 'Contato do Cliente';
            const emailBody = 'Olá, gostaria de obter mais informações sobre o agendamento.';
            document.location = `mailto:${email}?subject=${subject}&body=${emailBody}`;
        },
        // Envia um e-mail de confirmação para o cliente e para a logística

        enviarEmailConfirmacao() {
            const emailDados = {

                data: this.formatarData(this.notasParaConfirmar[0]?.dataAgendamento) || this.formatarData(this.dataAgendamento),
                hora: this.notasParaConfirmar[0]?.horaAgendamento || this.horaAgendamento,

                notas: this.notasParaConfirmar.map(nota => ({
                    // data: nota.dataAgendamento.replaceAll('-', ''),
                    // hora: nota.horaAgendamento.replace(':', ''),
                    f2_doc: nota.f2_doc,
                    c5_emissao: this.formatarData(nota.c5_emissao),
                    c5_num: nota.c5_num,
                    f2_transp: nota.f2_transp
                })),
                emailCliente: this.dadosCliente.email
            };

            axios.post('/primeapi/enviar-confirmacao-agendamento', emailDados)
                .then(response => {
                    console.log(response.data.message);
                })
                .catch(error => {
                    console.error("Erro ao enviar e-mail de confirmação:", error);
                });
        },
        // Busca e exibe os detalhes do pedido
        async verDetalhes(nota) {
            try {
                this.detalhesVisiveis = true;
                this.loading = true;
                this.progressPercent = 5;

                const response = await axios.get('/primeapi/detalhes-pedido', {
                    params: {
                        filtro_cliente: nota.a1_cod,
                        filtro_loja: nota.a1_loja,
                        filtro_nota: nota.f2_doc,
                    },
                    onDownloadProgress: progressEvent => {
                        const totalLength = progressEvent.lengthComputable ? progressEvent.total :
                            (progressEvent.target && (progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length')));
                        if (totalLength !== null) {
                            this.$nextTick(() => {
                                this.progressPercent = Math.round((progressEvent.loaded * 100) / totalLength);
                            });
                        }
                    },
                });

                this.detalhesPedido = response.data;
            } catch (error) {
                this.mostrarErro = true;
                this.mensagemErro = "Erro ao buscar detalhes do pedido: " + error.message;
            } finally {
                this.loading = false;
            }
        },
        // Fecha o modal de detalhes do pedido
        fecharDetalhes() {
            this.detalhesVisiveis = false;
        },
        // Agendamento em massa das notas fiscais selecionadas
        agendarEmMassa() {
            if (!this.dataAgendamento || !this.horaAgendamento) {
                this.mostrarErro = true;
                this.mensagemErro = 'Preencha a data e a hora de agendamento.';
                return;
            }
            const notasSelecionadas = this.notasFiscais.filter(nota => nota.agendar);
                    // Adiciona a data e hora de agendamento para cada nota selecionada
            notasSelecionadas.forEach(nota => {
                nota.dataAgendamento = this.dataAgendamento;
                nota.horaAgendamento = this.horaAgendamento;
            });
            this.abrirModalConfirmacao(notasSelecionadas);
        },
        // Formata a data no formato DD/MM/AAAA
        formatarData(data) {
            const match = data.match(/^(\d{4})(\d{2})(\d{2})$/);
            if (match) {
                return `${match[3]}/${match[2]}/${match[1]}`;
            } else {
                return data;
            }
        },
        // Formata valores para notação brasileira
        formatarValor(valor) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(valor);
        },
        // Marca ou desmarca uma nota fiscal para agendamento
        marcarCheckbox(nota) {
            if (this.modoSelecao) {
                nota.agendar = !nota.agendar;
            }
        },
        // Exibe os campos de data e hora para agendamento
        mostrarCamposDataHora(nota) {
            if (!this.modoSelecao) {
                nota.mostrarCamposDataHora = !nota.mostrarCamposDataHora;
                nota.agendamentoConfirmado = false;
            }
        },
        // Fecha os campos de data e hora de uma nota fiscal
        fecharLinha(nota) {
            nota.mostrarCamposDataHora = false;
            nota.agendamentoConfirmado = false;
        },
        // Seleciona ou desmarca todas as notas fiscais
        selecionarTodasNotas() {
            if (this.modoSelecao) {
                this.todasNotasSelecionadas = !this.todasNotasSelecionadas;
            }
        },
        // Alterna o modo de seleção de notas fiscais
        toggleModoSelecao() {
            this.modoSelecao = !this.modoSelecao;
            this.notasFiscais.forEach(nota => {
                nota.agendar = false;
                nota.mostrarCamposDataHora = false;
            });
        },
        // Oculta o preloader da página
        ocultarPreloader() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.display = 'none';
            } else {
                console.error('Elemento do preloader não encontrado!');
            }
        },
        // Fecha o modal de erro
        fecharErro() {
            this.mostrarErro = false;
        },
        // Fecha o modal de sucesso
        fecharSucesso() {
            this.mostrarSucesso = false;
        },
    },
};
</script>

<style scoped>
.modal-bg {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    max-width: 800px;
    width: 100%;
}

.progress {
    width: 100%;
    height: 8px;
    border-radius: 4px;
    background-color: #f3f4f6;
    overflow: hidden;
    position: relative;
}

.progress-bar {
    height: 100%;
    background-color: #3b82f6;
    position: absolute;
    top: 0;
    left: 0;
    transition: width 0.3s ease;
}

.bg-gray-50 {
    background-color: #f9fafb;
}

.bg-white {
    background-color: #ffffff;
}

.table-auto tbody tr:hover {
    background-color: #f1f5f9;
}
</style>
