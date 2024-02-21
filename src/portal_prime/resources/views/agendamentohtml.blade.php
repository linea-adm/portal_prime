<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agendamento de Entrega - Linea Alimentos</title>
  <script src="https://unpkg.com/vue@3.2.21/dist/vue.global.prod.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"
    integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    .header-bg {
      /* background-color: #4a90e2; */
      /* background-color:#425789; */
      background-color:#000;
      color: #fff;
      padding: 1rem;
    }

    .topo {
      background-color: #4a90e2;
      background-color:#425789;

      background-color:#000;
      color: #fff;
      text-align: center;
      position: fixed;
      width: 100%;
      padding: 0.5rem;
      z-index: 1000;
      /* Adicionei z-index para garantir que o topo fique sobre outros elementos */
    }

    .footer-bg {
      /* background-color: #4a90e2; */
      background-color:#425789;

      background-color:#000;
      color: #fff;
      text-align: center;
      position: fixed;
      bottom: 0;
      width: 100%;
      padding: 0.5rem;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      margin-bottom: 130px;
    }

    .header-text {
      color: #fff;
    }

    .table th {
      background-color: #edf2f7;
    }

    .table th,
    .table td {
      padding: 0.75rem;
      text-align: left;
      border-bottom: 1px solid #e2e8f0;
    }

    .table tbody tr:hover {
      background-color: #f8fafc;
    }

    .pagination {
      margin-top: 1em;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-bg {
      background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
      background-color: #fff;
      padding: 1.5rem;
      border-radius: 0.375rem;
      margin: auto;
    }

    /* Adicione ou ajuste estilos CSS conforme necessário */
    .modal-content table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1rem;
    }

    .modal-content th,
    .modal-content td {
      border: 1px solid #e2e8f0;
      padding: 8px;
      text-align: left;
    }

    .modal-content th {
      background-color: #edf2f7;
    }

    .dados-cliente {
      margin-top: 80px;
    }
  </style>
</head>

<body class="font-sans" id="app">
  <!-- Pedido de compra / Valor Total da Nota / Quantidade de Itens -->
  <div id="topo" class=" topo bg-blue-500 p-4 mb-6 header-bg">
    <div class="flex items-center justify-between">
      <img src="logo_linea.png" alt="Logo" class="h-12">
      <h1 class="text-3xl font-bold header-text">Agendamento de Entrega - Linea Alimentos</h1>
      <div></div>
    </div>
  </div>

  <div class="container mx-auto p-4  rounded-lg overflow-hidden shadow-lg">
    <!-- Cabeçalho com Logo -->
    <!-- Dados do Cliente -->
    <div class="mb-6 dados-cliente">
      <h2 class="text-2xl font-semibold mb-2">Dados do Cliente</h2>
      <p><strong>Nome Fantasia (Razão Social):</strong> {{ cliente.nomeFantasia }} <strong>Município-UF:</strong> {{
        cliente.municipioEstado }}</p>
      <p><strong>CNPJ:</strong> {{ cliente.cnpj }} <strong>E-mail:</strong> {{ cliente.email }}</p>
    </div>

    <!-- Lista de Notas Fiscais -->
    <h2 class="text-2xl font-semibold mb-4">Notas Fiscais</h2>
    <div class="mb-4 flex">
      <input type="date" v-model="dataAgendamento" class="border rounded-md p-1 mr-2">
      <input type="time" v-model="horaAgendamento" class="border rounded-md p-1">
      <button @click="agendarEmMassa" class="px-4 py-2 bg-green-500 text-white rounded-md ml-2">Agendar em
        Massa</button>
    </div>
    <table class="min-w-full table">
      <thead>
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            <input type="checkbox" @change="selecionarTodasNotas" class="form-checkbox h-5 w-5 text-blue-500">

          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nota Fiscal</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Emissão</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transportadora</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agendar Para</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detalhes do Pedido
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="nota in notasFiscaisPaginadas" :key="nota.F2_DOC" class="transition-colors ease-in-out duration-300"
          @click="marcarCheckbox(nota)">

          <td class="px-6 py-4 whitespace-nowrap">
            <input type="checkbox" v-model="nota.agendar" class="form-checkbox h-5 w-5 text-blue-500">
          </td>
          <td class="px-6 py-4 whitespace-nowrap">{{ nota.F2_DOC }}</td>
          <td class="px-6 py-4 whitespace-nowrap">{{ formatarData(nota.F2_EMISSAO) }}</td>
          <td class="px-6 py-4 whitespace-nowrap">{{ nota.C5_NUM }}</td>
          <td class="px-6 py-4 whitespace-nowrap">{{ nota.F2_TRANSP }}</td>
          <td class="px-6 py-4 whitespace-nowrap">

            <!-- Adiciona a verificação para exibir o botão "Agendar esta entrega" ou os campos e botão de confirmação -->
            <template v-if="!nota.mostrarCamposDataHora && !nota.agendamentoConfirmado">
              <button @click="mostrarCamposDataHora(nota)" class="px-4 py-2 bg-blue-500 text-white rounded-md ml-2">
                Agendar esta entrega
              </button>
            </template>
            <template v-else>
              <input type="date" v-model="dataAgendamento" class="border rounded-md p-1 mr-2"
                v-if="nota.mostrarCamposDataHora">
              <input type="time" v-model="horaAgendamento" class="border rounded-md p-1"
                v-if="nota.mostrarCamposDataHora">
              <button @click="confirmarAgendamento(nota)" class="px-4 py-2 bg-green-500 text-white rounded-md ml-2"
                v-if="nota.mostrarCamposDataHora">
                Confirmar Agendamento
              </button>
              <button @click="fecharLinha(nota)" class="px-4 py-2 bg-gray-500 text-white rounded-md ml-2"
                v-if="nota.mostrarCamposDataHora">
                <i class="fas fa-times"></i>
              </button>
            </template>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <button @click="verDetalhes(nota)" class="bg-yellow-500 text-white px-4 py-2 rounded-md">
              <i class="fas fa-info-circle"></i> Detalhes
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Paginação -->
    <div class="pagination">
      <button @click="paginaAtual--" :disabled="paginaAtual === 1"
        class="px-4 py-2 bg-blue-500 text-white rounded-md">Anterior</button>
      <span class="text-gray-700">Página {{ paginaAtual }} de {{ Math.ceil(notasFiscais.length / itensPorPagina)
        }}</span>
      <button @click="paginaAtual++" :disabled="paginaAtual === Math.ceil(notasFiscais.length / itensPorPagina)"
        class="px-4 py-2 bg-blue-500 text-white rounded-md">Próxima</button>
    </div>

    <!-- Modal de Detalhes do Pedido -->
    <div v-if="detalhesVisiveis" class="fixed inset-0 flex items-center justify-center modal-bg">
      <div class="modal-content bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-lg font-semibold mb-4">Detalhes do Pedido</h2>
        <table class="min-w-full border">
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
            <tr v-for="item in detalhesPedido" :key="item.C6_PRODUTO">
              <td class="border px-4 py-2">{{ item.C6_PRODUTO }}</td>
              <td class="border px-4 py-2">{{ item.C6_DESCRI }}</td>
              <td class="border px-4 py-2">{{ item.C6_QTDVEN }}</td>
              <td class="border px-4 py-2">{{ item.C6_PRCVEN }}</td>
              <td class="border px-4 py-2">{{ calcularValorTotal(item) }}</td> <!-- Calcula e exibe o Valor Total -->
            </tr>
          </tbody>
        </table>
        <!-- Exibir o Valor Total Geral -->
        <p class="mt-4"><strong> Total Geral:</strong> R$ {{ valorTotalGeralCalculado }}</p>

        <button @click="fecharDetalhes" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md">Fechar</button>
      </div>
    </div>


  </div>
  <!-- Rodapé com Copyright -->
  <div class="footer-bg">
    <p>&copy; Linea Alimentos 2024</p>
  </div>

  <script>
    let data = {
      "cliente": {
        "nomeFantasia": "Indústria Linea Alimentos",
        "municipioEstado": "Anápolis-GO",
        "cnpj": "12.345.678/0001-90",
        "email": "cliente@lineaalimentos.com"
      },
      "notasFiscais": [
        {
          "F2_DOC": "123",
          "F2_EMISSAO": "01/01/2024",
          "C5_NUM": "456",
          "F2_TRANSP": "Transportadora A",
          "produtos": [
            {
              "C6_PRODUTO": "Produto A1",
              "C6_DESCRI": "Descrição do Produto A1",
              "C6_QTDVEN": 3,
              "C6_PRCVEN": 15
            },
            {
              "C6_PRODUTO": "Produto A2",
              "C6_DESCRI": "Descrição do Produto A2",
              "C6_QTDVEN": 2,
              "C6_PRCVEN": 20
            },
            {
              "C6_PRODUTO": "Produto A3",
              "C6_DESCRI": "Descrição do Produto A3",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 25
            },
            {
              "C6_PRODUTO": "Produto A4",
              "C6_DESCRI": "Descrição do Produto A4",
              "C6_QTDVEN": 4,
              "C6_PRCVEN": 10
            }
          ]
        },
        {
          "F2_DOC": "789",
          "F2_EMISSAO": "02/01/2024",
          "C5_NUM": "101",
          "F2_TRANSP": "Transportadora B",
          "produtos": [
            {
              "C6_PRODUTO": "Produto B1",
              "C6_DESCRI": "Descrição do Produto B1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 30
            },
            {
              "C6_PRODUTO": "Produto B2",
              "C6_DESCRI": "Descrição do Produto B2",
              "C6_QTDVEN": 2,
              "C6_PRCVEN": 25
            }
          ]
        },
        {
          "F2_DOC": "456",
          "F2_EMISSAO": "03/01/2024",
          "C5_NUM": "202",
          "F2_TRANSP": "Transportadora C",
          "produtos": [
            {
              "C6_PRODUTO": "Produto C1",
              "C6_DESCRI": "Descrição do Produto C1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 50
            }
          ]
        },
        {
          "F2_DOC": "012",
          "F2_EMISSAO": "04/01/2024",
          "C5_NUM": "303",
          "F2_TRANSP": "Transportadora D",
          "produtos": [
            {
              "C6_PRODUTO": "Produto D1",
              "C6_DESCRI": "Descrição do Produto D1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 40
            }
          ]
        },
        {
          "F2_DOC": "345",
          "F2_EMISSAO": "05/01/2024",
          "C5_NUM": "404",
          "F2_TRANSP": "Transportadora E",
          "produtos": [
            {
              "C6_PRODUTO": "Produto E1",
              "C6_DESCRI": "Descrição do Produto E1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 35
            }
          ]
        },
        {
          "F2_DOC": "678",
          "F2_EMISSAO": "06/01/2024",
          "C5_NUM": "505",
          "F2_TRANSP": "Transportadora F",
          "produtos": [
            {
              "C6_PRODUTO": "Produto F1",
              "C6_DESCRI": "Descrição do Produto F1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 30
            }
          ]
        },
        {
          "F2_DOC": "901",
          "F2_EMISSAO": "07/01/2024",
          "C5_NUM": "606",
          "F2_TRANSP": "Transportadora G",
          "produtos": [
            {
              "C6_PRODUTO": "Produto G1",
              "C6_DESCRI": "Descrição do Produto G1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 25
            }
          ]
        },
        {
          "F2_DOC": "234",
          "F2_EMISSAO": "08/01/2024",
          "C5_NUM": "707",
          "F2_TRANSP": "Transportadora H",
          "produtos": [
            {
              "C6_PRODUTO": "Produto H1",
              "C6_DESCRI": "Descrição do Produto H1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 20
            }
          ]
        },
        {
          "F2_DOC": "567",
          "F2_EMISSAO": "09/01/2024",
          "C5_NUM": "808",
          "F2_TRANSP": "Transportadora I",
          "produtos": [
            {
              "C6_PRODUTO": "Produto I1",
              "C6_DESCRI": "Descrição do Produto I1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 15
            }
          ]
        },
        {
          "F2_DOC": "890",
          "F2_EMISSAO": "10/01/2024",
          "C5_NUM": "909",
          "F2_TRANSP": "Transportadora J",
          "produtos": [
            {
              "C6_PRODUTO": "Produto J1",
              "C6_DESCRI": "Descrição do Produto J1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 10
            }
          ]
        },
        {
          "F2_DOC": "111",
          "F2_EMISSAO": "11/01/2024",
          "C5_NUM": "1010",
          "F2_TRANSP": "Transportadora K",
          "produtos": [
            {
              "C6_PRODUTO": "Produto K1",
              "C6_DESCRI": "Descrição do Produto K1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 20
            }
          ]
        },
        {
          "F2_DOC": "222",
          "F2_EMISSAO": "12/01/2024",
          "C5_NUM": "1111",
          "F2_TRANSP": "Transportadora L",
          "produtos": [
            {
              "C6_PRODUTO": "Produto L1",
              "C6_DESCRI": "Descrição do Produto L1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 15
            }
          ]
        },
        {
          "F2_DOC": "333",
          "F2_EMISSAO": "2023-01-01",
          "C5_NUM": "1212",
          "F2_TRANSP": "Transportadora M",
          "produtos": [
            {
              "C6_PRODUTO": "Produto M1",
              "C6_DESCRI": "Descrição do Produto M1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 10
            }
          ]
        },
        {
          "F2_DOC": "444",
          "F2_EMISSAO": "2023-02-01",
          "C5_NUM": "1313",
          "F2_TRANSP": "Transportadora N",
          "produtos": [
            {
              "C6_PRODUTO": "Produto N1",
              "C6_DESCRI": "Descrição do Produto N1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 25
            }
          ]
        },
        {
          "F2_DOC": "555",
          "F2_EMISSAO": "2023-03-01",
          "C5_NUM": "1414",
          "F2_TRANSP": "Transportadora O",
          "produtos": [
            {
              "C6_PRODUTO": "Produto O1",
              "C6_DESCRI": "Descrição do Produto O1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 30
            }
          ]
        },
        {
          "F2_DOC": "666",
          "F2_EMISSAO": "2023-04-01",
          "C5_NUM": "1515",
          "F2_TRANSP": "Transportadora P",
          "produtos": [
            {
              "C6_PRODUTO": "Produto P1",
              "C6_DESCRI": "Descrição do Produto P1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 35
            }
          ]
        },
        {
          "F2_DOC": "777",
          "F2_EMISSAO": "2023-05-01",
          "C5_NUM": "1616",
          "F2_TRANSP": "Transportadora Q",
          "produtos": [
            {
              "C6_PRODUTO": "Produto Q1",
              "C6_DESCRI": "Descrição do Produto Q1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 40
            }
          ]
        },
        {
          "F2_DOC": "888",
          "F2_EMISSAO": "2023-06-01",
          "C5_NUM": "1717",
          "F2_TRANSP": "Transportadora R",
          "produtos": [
            {
              "C6_PRODUTO": "Produto R1",
              "C6_DESCRI": "Descrição do Produto R1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 45
            }
          ]
        },
        {
          "F2_DOC": "999",
          "F2_EMISSAO": "2023-07-01",
          "C5_NUM": "1818",
          "F2_TRANSP": "Transportadora S",
          "produtos": [
            {
              "C6_PRODUTO": "Produto S1",
              "C6_DESCRI": "Descrição do Produto S1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 50
            }
          ]
        },
        {
          "F2_DOC": "101",
          "F2_EMISSAO": "2023-08-01",
          "C5_NUM": "1919",
          "F2_TRANSP": "Transportadora T",
          "produtos": [
            {
              "C6_PRODUTO": "Produto T1",
              "C6_DESCRI": "Descrição do Produto T1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 55
            }
          ]
        },
        {
          "F2_DOC": "112",
          "F2_EMISSAO": "2023-09-01",
          "C5_NUM": "2020",
          "F2_TRANSP": "Transportadora U",
          "produtos": [
            {
              "C6_PRODUTO": "Produto U1",
              "C6_DESCRI": "Descrição do Produto U1",
              "C6_QTDVEN": 1,
              "C6_PRCVEN": 60
            }
          ]
        }
      ]
    }

    const app = Vue.createApp({
      data() {
        return {
          selecionarTodas: false,
          cliente: {
            nomeFantasia: 'Mar Vermelho',
            municipioEstado: 'Anápolis-GO',
            cnpj: '12.345.678/0001-90',
            email: 'cliente@marvermelho.com.br'
          },
          notasFiscais: data.notasFiscais,
          detalhesVisiveis: false,
          detalhesPedido: [],
          paginaAtual: 1,
          itensPorPagina: 10,
          dataAgendamento: '',
          horaAgendamento: '',
          detalhesPopupVisivel: false,
          valorTotalGeral: 0,
        };
      },
      created() {
        this.notasFiscais.forEach(nota => {
          nota.agendar = false; // Adiciona a propriedade 'agendar' a cada nota
          nota.mostrarCamposDataHora = false;
          nota.agendamentoConfirmado = false;
        });
      },
      computed: {
        notasFiscaisPaginadas() {
          const inicio = (this.paginaAtual - 1) * this.itensPorPagina;
          const fim = inicio + this.itensPorPagina;
          return this.notasFiscais.slice(inicio, fim);
        },
        valorTotalGeralCalculado() {
          return this.detalhesPedido.reduce((total, item) => {
            return total + parseFloat(this.calcularValorTotal(item));
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
        verDetalhes(nota) {
          // Lógica para obter detalhes do pedido
          this.detalhesPedido = nota.produtos;
          this.detalhesVisiveis = true;
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
      },
    });

    app.mount('#app');
  </script>

</body>

</html>
