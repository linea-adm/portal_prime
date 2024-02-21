<template>
    <div class="container mx-auto p-4 rounded-lg overflow-hidden shadow-lg">
        <!-- Cabeçalho com Logo -->
        <!-- Dados do Cliente -->
        <div class="grid grid-cols-2 gap-4 mt-9 mb-5">
            <!-- Card dos Dados do Cliente -->
            <div class="rounded-lg overflow-hidden shadow-md">
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2"> </h2>
                    <p><strong class="text-xl font-semibold mb-2">Cliente: {{ dadosCliente.nomeFantasia }}</strong></p>
                    <p><strong>Município-UF:</strong> {{ dadosCliente.municipioEstado }}</p>
                    <p><strong>CNPJ:</strong> {{ dadosCliente.cnpj }}</p>
                    <p><strong>E-mail:</strong> {{ dadosCliente.email }}</p>
                </div>
            </div>

            <!-- Card dos Botões de Ação -->
            <div class="rounded-lg overflow-hidden shadow-md">
                <div class="p-4">
                    <h2 class="text-xl font-semibold mb-2 pl-6 pr-6">Fale Conosco</h2>
                    <div class="flex flex-col gap-2 pl-6 pr-6">
                        <button @click="contatoWhatsapp" class="bg-green-500 text-white px-4 py-2 rounded-md font-bold">
                            <i class="fab fa-whatsapp"></i>
                            Contato por Whatsapp
                        </button>
                        <button @click="enviarEmail" class="bg-blue-500 text-white px-4 py-2 rounded-md font-bold">
                            <i class="fas fa-envelope"></i>
                            Enviar E-mail</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Notas Fiscais -->
        <h2 class="text-2xl font-semibold mb-4">Notas Fiscais</h2>
        <div class="mb-4 flex">
            <input type="date" v-model="dataAgendamento" class="border rounded-md p-1 mr-2">
            <input type="time" v-model="horaAgendamento" class="border rounded-md p-1">
            <button @click="agendarEmMassa" class="px-4 py-2 bg-green-500 text-white rounded-md ml-2  font-bold">
                <i class="fas fa-clock"></i>
                Agendar Selecionadas
            </button>

            <input type="text" v-model="termoPesquisa" placeholder="Pesquisar por Nota Fiscal"
                class="border rounded-md ml-5 p-2">
        </div>

        <div class="mb-4">
        </div>

        <table class="min-w-full table shadow-md">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" @change="selecionarTodasNotas" class="form-checkbox h-5 w-5 text-blue-500"
                            alt="Selecionar Todas as Notas" title="Selecionar Todas as Notas">

                    </th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Nota
                        Fiscal
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Emissão
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Pedido
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">
                        Transportadora</th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Agendar
                        Para
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-extrabold text-gray-500 uppercase tracking-wider">Detalhes
                        do
                        Pedido
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="nota in notasFiltradasPaginadas" :key="nota.f2_doc"
                    class="transition-colors ease-in-out duration-300" @click="marcarCheckbox(nota)">

                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" v-model="nota.agendar" class="form-checkbox h-5 w-5 text-blue-500">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ nota.f2_doc }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ formatarData(nota.c5_emissao) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ nota.c5_num }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ nota.f2_transp }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">

                        <!-- Adiciona a verificação para exibir o botão "Agendar esta entrega" ou os campos e botão de confirmação -->
                        <template v-if="!nota.mostrarCamposDataHora && !nota.agendamentoConfirmado">
                            <button @click="mostrarCamposDataHora(nota)"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md ml-2   font-bold">
                                <i class="fas fa-clock"></i>
                                Agendar esta entrega
                            </button>
                        </template>
                        <template v-else>
                            <input type="date" v-model="dataAgendamento" class="border rounded-md p-1 mr-2"
                                v-if="nota.mostrarCamposDataHora">
                            <input type="time" v-model="horaAgendamento" class="border rounded-md p-1"
                                v-if="nota.mostrarCamposDataHora">
                            <button @click="confirmarAgendamento(nota)"
                                class="px-3 py-1 bg-green-500 text-white rounded-md ml-2 " v-if="nota.mostrarCamposDataHora"
                                alt="Confirmar Agendamento" title="Confirmar Agendamento">

                                <i class="fas fa-check"></i>
                            </button>
                            <button @click="fecharLinha(nota)" class="px-1 py-0 bg-gray-500 text-white rounded-md ml-2"
                                v-if="nota.mostrarCamposDataHora" alt="Descartar" title="Descartar">
                                <i class="fas fa-times"></i>
                            </button>
                        </template>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button @click="verDetalhes(nota)" class="bg-yellow-500 text-white px-4 py-2 rounded-md font-bold">
                            <i class="fas fa-info-circle"></i> Detalhes
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>


        <!-- <div class="pagination">
            <button @click="paginaAtual--" :disabled="paginaAtual === 1"
                class="px-4 py-2 bg-blue-500 text-white rounded-md">Anterior</button>
            <span class="text-gray-700">Página {{ paginaAtual }} de {{ Math.ceil(notasFiscais.length / itensPorPagina)
            }}</span>
            <button @click="paginaAtual++" :disabled="paginaAtual === Math.ceil(notasFiscais.length / itensPorPagina)"
                class="px-4 py-2 bg-blue-500 text-white rounded-md">Próxima</button>
        </div> -->
        <div class="pagination">
            <button @click="paginaAtual--" :disabled="paginaAtual === 1"
                class="px-4 py-2 bg-blue-500 text-white rounded-md">Anterior</button>
            <span class="text-gray-700">Página {{ paginaAtual }} de {{ Math.ceil(notasFiltradas.length / itensPorPagina)
            }}</span>
            <button @click="paginaAtual++" :disabled="paginaAtual === Math.ceil(notasFiltradas.length / itensPorPagina)"
                class="px-4 py-2 bg-blue-500 text-white rounded-md">Próxima</button>
        </div>

        <!-- Modal de Detalhes do Pedido -->
        <div v-if="detalhesVisiveis" class="fixed inset-0 flex items-center justify-center modal-bg">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg">

                <div v-if="loading" class="progress">
                    <div class="progress-bar" role="progressbar" :style="{ width: progressPercent + '%' }"
                        aria-valuenow="progressPercent" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <h2 v-if="loading" class="text-lg font-semibold mb-4">Carregando Detalhes do Pedido...</h2>
                <h2 v-if="!loading" class="text-lg font-semibold mb-4">Detalhes do Pedido</h2>

                <table v-if="!loading" class="min-w-full border">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Produto</th>
                            <th class="border px-4 py-2">Descrição</th>
                            <th class="border px-4 py-2">Quantidade</th>
                            <th class="border px-4 py-2">Valor Unitário</th>
                            <th class="border px-4 py-2">Valor Total</th> <!-- Adiciona a nova coluna -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in detalhesPedido" :key="item.f2_doc">
                            <td class="border px-4 py-2">{{ item.b1_cod }}</td>
                            <td class="border px-4 py-2">{{ item.b1_desc }}</td>
                            <td class="border px-4 py-2">{{ item.d2_quant }}</td>
                            <td class="border px-4 py-2">{{ item.d2_prcven }}</td>
                            <td class="border px-4 py-2">{{ item.d2_total }}</td> <!-- Calcula e exibe o Valor Total -->
                        </tr>
                    </tbody>
                </table>
                <!-- Exibir o Valor Total Geral -->
                <p class="mt-4" v-if="!loading"><strong> Total Geral:</strong> R$ {{ valorTotalGeralCalculado }}</p>

                <button @click="fecharDetalhes" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md">Fechar</button>
            </div>
        </div>


    </div>
</template>


<script>

import axios from 'axios';
export default {
    props: ['dadosCliente', 'dadosNotasFiscais'],

    data() {
        return {
            selecionarTodas: false,
            notasFiscais: [],
            detalhesVisiveis: false,
            detalhesPedido: [],
            paginaAtual: 1,
            itensPorPagina: 10,
            dataAgendamento: '',
            horaAgendamento: '',
            detalhesPopupVisivel: false,
            valorTotalGeral: 0,
            loading: false,
            progressPercent: 0,
            logoLinea: this.logo + '/logo_linea.png',
            termoPesquisa: '',
        };
    },
    mounted() {
        this.ocultarPreloader();
    },
    created() {
        this.notasFiscais = this.dadosNotasFiscais.map(nota => {
            nota.agendar = false; // Adiciona a propriedade 'agendar' a cada nota
            nota.mostrarCamposDataHora = false;
            nota.agendamentoConfirmado = false;
            return nota;
        });
    },
    computed: {
        // notasFiltradas() {
        //     // Filtra as notas fiscais com base no termo de pesquisa
        //     return this.notasFiscais.filter(nota => {
        //         // Converte o termo de pesquisa e o número da nota para strings
        //         const termo = this.termoPesquisa.toLowerCase();
        //         const numeroNota = nota.f2_doc.toString().toLowerCase();
        //         // Verifica se o número da nota contém o termo de pesquisa
        //         return numeroNota.includes(termo);
        //     });
        // },
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
        notasFiscaisPaginadas() {
            const inicio = (this.paginaAtual - 1) * this.itensPorPagina;
            const fim = inicio + this.itensPorPagina;
            return this.notasFiscais.slice(inicio, fim);
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
        async verDetalhes(nota) {
            try {
                this.detalhesVisiveis = true;
                this.loading = true;
                this.progressPercent = 5;

                const response = await axios.get('/api/detalhes-pedido', {
                    params: {
                        filtro_cliente: nota.a1_cod,
                        filtro_loja: nota.a1_loja,
                        filtro_nota: nota.f2_doc
                    },
                    // Adiciona a função onDownloadProgress para monitorar o progresso do download
                    onDownloadProgress: progressEvent => {
                        const totalLength = progressEvent.lengthComputable ? progressEvent.total :
                            (progressEvent.target && (progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length')));

                        // console.log(typeof(totalLength));
                        // console.log(totalLength);
                        if (totalLength !== null) {
                            this.$nextTick(() => {
                                // console.log(typeof(progressEvent.loaded));
                                // this.progressPercent = Math.round(progressEvent.loaded );
                                this.progressPercent = Math.round((progressEvent.loaded * 100) / 200);

                            });
                        }


                    }
                });

                // Atualize os detalhes do pedido com os dados recebidos
                this.detalhesPedido = response.data;
            } catch (error) {
                console.error('Erro ao buscar detalhes do pedido:', error);
            } finally {
                this.loading = false; // Indica que a API terminou de ser consultada, independentemente do resultado
            }
        },
        fecharDetalhes() {
            this.detalhesVisiveis = false;
        }, agendarEmMassa() {
            // Verifica se a data e a hora de agendamento estão preenchidas
            if (!this.dataAgendamento || !this.horaAgendamento) {
                alert('Preencha a data e a hora de agendamento.');
                return;
            }
            // Lógica para agendamento em massa com as linhas selecionadas
            const notasSelecionadas = this.notasFiscais.filter(nota => nota.agendar);
            // Execute a lógica de agendamento com as notas selecionadas
            console.log('Agendamento em massa para:', notasSelecionadas);
            console.log('Data de Agendamento:', this.dataAgendamento);
            console.log('Hora de Agendamento:', this.horaAgendamento);
        },
        formatarData(data) {
            // Verifica se a data está no formato "YYYY-MM-DD" ou "YYYYMMDD"
            const match = data.match(/^(\d{4})(\d{2})(\d{2})$/);
            if (match) {
                // Formata a data para "DD/MM/YYYY"
                return `${match[3]}/${match[2]}/${match[1]}`;
            } else {
                return data; // Retorna a data original se não estiver no formato esperado
            }
        },
        marcarCheckbox(nota) {
            nota.agendar = !nota.agendar;
        },
        calcularValorTotal(item) {
            return (item.C6_QTDVEN * item.C6_PRCVEN).toFixed(2);
        },
        mostrarCamposDataHora(nota) {
            // Mostra ou oculta os campos de data e hora para a nota específica
            nota.mostrarCamposDataHora = !nota.mostrarCamposDataHora;
            // Oculta o botão "Agendar esta entrega" quando os campos são mostrados
            nota.agendamentoConfirmado = false;
        },
        confirmarAgendamento(nota) {
            // Adicione aqui a lógica para confirmar o agendamento para a nota específica
            // Utilize this.dataAgendamento e this.horaAgendamento para obter os valores
            console.log('Agendamento confirmado para:', nota);
            console.log('Data de Agendamento:', this.dataAgendamento);
            console.log('Hora de Agendamento:', this.horaAgendamento);

            // Você pode querer reiniciar os campos de data e hora após o agendamento
            this.dataAgendamento = '';
            this.horaAgendamento = '';

            // Exibe o botão "Agendar esta entrega" após o agendamento
            nota.agendamentoConfirmado = true;
            // Opcional: Oculta os campos de data e hora após o agendamento
            nota.mostrarCamposDataHora = false;
        },
        fecharLinha(nota) {
            // Ao clicar no botão de fechar, reverter a linha para o estado normal
            nota.mostrarCamposDataHora = false;
            nota.agendamentoConfirmado = false;
        },
        cancelarAgendamento(nota) {
            // Adicione aqui a lógica para cancelar o agendamento para a nota específica
            // console.log('Agendamento cancelado para:', nota);

            // Exibe novamente o botão "Agendar esta entrega"
            nota.agendamentoConfirmado = false;
            // Opcional: Oculta os campos de data e hora após o cancelamento
            nota.mostrarCamposDataHora = false;
        },
        selecionarTodasNotas() {
            this.todasNotasSelecionadas = !this.todasNotasSelecionadas;
        },
        ocultarPreloader() {
            // Obtém o elemento do preloader pelo ID
            const preloader = document.getElementById('preloader');

            // Verifica se o elemento do preloader foi encontrado
            if (preloader) {
                // Esconde o preloader definindo a propriedade de estilo display como 'none'
                preloader.style.display = 'none';
            } else {
                console.error('Elemento do preloader não encontrado!');
            }
        }
    },
}
</script>

