<template>
    <div class="container mx-auto p-4 rounded-lg overflow-hidden shadow-lg">
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center bg-sky-50 p-4 mt-6 rounded-lg shadow-md">
            <!-- Dados do Cliente -->
            <div class="flex-1">
                <h2 class="text-2xl font-semibold mb-2">Cliente: {{ dadosCliente.nomeFantasia }}</h2>
                <p><strong>Município-UF:</strong> {{ dadosCliente.municipioEstado }}</p>
                <p><strong>CNPJ:</strong> {{ dadosCliente.cnpj }}</p>
                <p><strong>E-mail:</strong> {{ dadosCliente.email_cliente }}</p>
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
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Nota Fiscal</th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Emissão</th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Pedido</th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Transportadora</th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Agendar Para</th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Detalhes do Pedido</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(nota, index) in notasFiltradasPaginadas" :key="nota.f2_doc" :class="[index % 2 === 0 ? 'bg-gray-50' : 'bg-white', 'transition-colors', 'ease-in-out', 'duration-300', 'hover:bg-gray-200']">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" v-model="nota.agendar" class="form-checkbox h-5 w-5 text-blue-500" :disabled="!modoSelecao || nota.agendamentoConfirmado">
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
                        <button v-if="!nota.agendamentoConfirmado" @click="mostrarCamposDataHora(nota)" class="px-4 py-2 text-white rounded-md font-bold" :class="{'bg-gray-500': modoSelecao, 'bg-blue-500 hover:bg-blue-700': !modoSelecao}" :disabled="modoSelecao">
                            <i class="fas fa-clock"></i> Agendar esta entrega
                        </button>
                        <span v-else class="px-4 py-2 bg-green-500 text-white rounded-md font-bold">
                            <i class="fas fa-check"></i> Agendada
                        </span>
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
                <p>Confirma o agendamento para a data: {{ formatarDataParaVisualizacao(notasParaConfirmar[0]?.dataAgendamento) || formatarDataParaVisualizacao(dataAgendamento) }} e hora: {{ notasParaConfirmar[0]?.horaAgendamento || horaAgendamento }} para as seguintes notas:</p>
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
            <span class="text-gray-700">Página {{ paginaAtual }} de {{ Math.ceil(notasFiltradas.length / itensPorPagina) }}</span>
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
                <p class="mt-4" v-if="!loading"><strong>Total Geral:</strong> {{ formatarValor(valorTotalGeralCalculado) }}</p>
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
            emailLogistica: '',
            tsk: 'YWdlbmRhbWVudG8ucHJpbWU6RWljYnJhc2lsJSQjQCEyazI0',
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
        notasFiltradas() {
            const termo = this.termoPesquisa.toLowerCase().trim();
            return this.notasFiscais.filter(nota => {
                const numeroNota = nota.f2_doc.toString().toLowerCase();
                return numeroNota.includes(termo);
            });
        },
        notasFiltradasPaginadas() {
            const inicio = (this.paginaAtual - 1) * this.itensPorPagina;
            const fim = inicio + this.itensPorPagina;
            return this.notasFiltradas.slice(inicio, fim);
        },
        valorTotalGeralCalculado() {
            return this.detalhesPedido.reduce((totalGeral, item) => {
                return totalGeral + parseFloat(item.d2_total);
            }, 0).toFixed(2);
        },
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
        async obterEmailLogistica() {
            try {
                const response = await axios.get('/primeapi/logistica-email');
                this.emailLogistica = response.data.email;
            } catch (error) {
                console.error('Erro ao obter o e-mail da logística:', error);
            }
        },
        abrirModalConfirmacao(notas) {
            if (this.modoSelecao && notas.length === 0) {
                this.mostrarErro = true;
                this.mensagemErro = 'Nenhuma nota foi selecionada para agendamento.';
                return;
            }
            this.notasParaConfirmar = notas.length > 0 ? notas : this.notasFiscais.filter(nota => nota.agendar);

            if (this.modoSelecao) {
                this.notasParaConfirmar.forEach(nota => {
                    nota.dataAgendamento = this.dataAgendamento;
                    nota.horaAgendamento = this.horaAgendamento;
                });
            }

            this.mostrarModalConfirmacao = true;
        },
        fecharModalConfirmacao() {
            this.mostrarModalConfirmacao = false;
        },
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

                const authHeader = 'Basic ' + this.tsk;
                const headers = { 'Authorization': authHeader };
                await axios.post('https://agendamento.lineaalimentos.com.br/agendar-entrega-teste', agendamentoDados, { headers });

                this.mostrarSucesso = true;
                this.mensagemSucesso = "Agendamento realizado com sucesso.";
                this.notasParaConfirmar.forEach(nota => {
                    nota.agendamentoConfirmado = true;
                    nota.mostrarCamposDataHora = false;
                });
                this.enviarEmailConfirmacao();
                this.fecharModalConfirmacao();
            } catch (error) {
                this.mostrarErro = true;
                this.mensagemErro = "Erro ao agendar a entrega: " + error.message;
            }
        },
        enviarEmail() {
            const email = this.emailLogistica;
            const subject = 'Contato do Cliente';
            const emailBody = 'Olá, gostaria de obter mais informações sobre o agendamento.';
            document.location = `mailto:${email}?subject=${subject}&body=${emailBody}`;
        },
        enviarEmailConfirmacao() {
            const emailDados = {
                data: this.formatarDataParaVisualizacao(this.notasParaConfirmar[0]?.dataAgendamento) || this.formatarDataParaVisualizacao(this.dataAgendamento),
                hora: this.notasParaConfirmar[0]?.horaAgendamento || this.horaAgendamento,
                notas: this.notasParaConfirmar.map(nota => ({
                    f2_doc: nota.f2_doc,
                    c5_emissao: this.formatarData(nota.c5_emissao),
                    c5_num: nota.c5_num,
                    f2_transp: nota.f2_transp
                })),
                emailCliente: this.dadosCliente.email_cliente
            };

            axios.post('/primeapi/enviar-confirmacao-agendamento', emailDados)
                .then(response => {
                    console.log(response.data.message);
                })
                .catch(error => {
                    console.error("Erro ao enviar e-mail de confirmação:", error);
                });
        },
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
        fecharDetalhes() {
            this.detalhesVisiveis = false;
        },
        agendarEmMassa() {
            if (!this.dataAgendamento || !this.horaAgendamento) {
                this.mostrarErro = true;
                this.mensagemErro = 'Preencha a data e a hora de agendamento.';
                return;
            }
            const notasSelecionadas = this.notasFiscais.filter(nota => nota.agendar && !nota.agendamentoConfirmado);

            notasSelecionadas.forEach(nota => {
                nota.dataAgendamento = this.dataAgendamento;
                nota.horaAgendamento = this.horaAgendamento;
            });

            this.abrirModalConfirmacao(notasSelecionadas);
        },
        formatarData(data) {
            const match = data.match(/^(\d{4})(\d{2})(\d{2})$/);
            if (match) {
                return `${match[3]}/${match[2]}/${match[1]}`;
            } else {
                return data;
            }
        },
        formatarDataParaVisualizacao(data) {
            if (!data) return '';
            const [year, month, day] = data.split('-');
            return `${day}/${month}/${year}`;
        },
        formatarValor(valor) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(valor);
        },
        marcarCheckbox(nota) {
            if (this.modoSelecao) {
                if (!nota.agendamentoConfirmado) {
                    nota.agendar = !nota.agendar;
                }
            }
        },
        mostrarCamposDataHora(nota) {
            if (!this.modoSelecao) {
                nota.mostrarCamposDataHora = !nota.mostrarCamposDataHora;
                nota.agendamentoConfirmado = false;
            }
        },
        fecharLinha(nota) {
            nota.mostrarCamposDataHora = false;
            nota.agendamentoConfirmado = false;
        },
        selecionarTodasNotas() {
            if (this.modoSelecao) {
                this.notasFiscais.forEach(nota => {
                    if (!nota.agendamentoConfirmado) {
                        nota.agendar = this.todasNotasSelecionadas;
                    }
                });
            }
        },
        toggleModoSelecao() {
            this.modoSelecao = !this.modoSelecao;
            if (!this.modoSelecao) {
                this.notasFiscais.forEach(nota => {
                    nota.agendar = false;
                });
            } else {
                this.notasFiscais.forEach(nota => {
                    if (nota.agendamentoConfirmado) {
                        nota.agendar = false;
                    }
                });
            }
        },
        ocultarPreloader() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.display = 'none';
            } else {
                console.error('Elemento do preloader não encontrado!');
            }
        },
        fecharErro() {
            this.mostrarErro = false;
        },
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
