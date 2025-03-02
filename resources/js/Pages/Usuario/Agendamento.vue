<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { ref, watch, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    agendamentos: Object,
    servicos: Array,
    horasDisponiveis: Array,
});

const filtroData = ref({
    de: '',
    ate: ''
});

const filtrarAgendamentos = () => {
    router.get(route('usuario.agendamento'), {
        de: filtroData.value.de,
        ate: filtroData.value.ate
    }, {
        preserveState: true,
    });
};

const hoje = new Date().toISOString().split('T')[0];
const dataMinima = ref(hoje);

const temMultiplosAgendamentosNaSemana = computed(() => {
    if (!props.agendamentos || !Array.isArray(props.agendamentos.data)) {
        return false;
    }

    const semanas = new Map();

    props.agendamentos.data.forEach(agendamento => {
        const semana = new Date(agendamento.data_agendamento).getWeekNumber();

        if (semanas.has(semana)) {
            semanas.set(semana, semanas.get(semana) + 1);
        } else {
            semanas.set(semana, 1);
        }
    });

    return Array.from(semanas.values()).some(count => count > 1);
});

Date.prototype.getWeekNumber = function () {
    const d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
    const dayNum = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dayNum);
    const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
    return Math.ceil(((d - yearStart) / 86400000 + 1) / 7);
};

const showModal = ref(false);
const selectedAgendamento = ref(null);
const horasDisponiveisModal = ref([]);

watch(() => selectedAgendamento.value?.data_agendamento, async (newData) => {
    if (newData) {
        try {
            const response = await axios.get('/api/horarios-disponiveis', {
                params: {
                    data_agendamento: newData,
                },
            });
            horasDisponiveisModal.value = response.data;
        } catch (error) {
            console.error('Erro ao buscar horas disponíveis:', error);
        }
    } else {
        horasDisponiveisModal.value = [];
    }
});

const goToPage = (page) => {
    if (page > 0 && page <= props.agendamentos.last_page) {
        router.get(route('usuario.agendamento'), { page });
    }
};

const deleteAgendamento = (id, dataAgendamento) => {
    const hoje = new Date();
    const dataAgendamentoDate = new Date(dataAgendamento);
    const diffTime = dataAgendamentoDate - hoje;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 2) {
        Swal.fire("Aviso", "Só é possível excluir agendamentos com mais de dois dias de antecedência. Entre em contato com (11)1111-1111!", "warning");
        return;
    }

    Swal.fire({
        title: "Tem certeza?",
        text: "Essa ação não pode ser desfeita!",
        icon: "warning",
        showCancelButton: true,
        customClass: {
            container: 'bg-pink-50',
            title: 'text-pink-600 font-bold text-lg',
            content: 'text-pink-700',
            confirmButton: 'bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-400 focus:ring-pink-500 focus:ring-offset-2',
            cancelButton: 'bg-pink-400 text-white px-6 py-2 rounded-lg hover:bg-pink-250 focus:ring-pink-300 focus:ring-offset-2',
        },
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('usuario.agendamento.destroy', id), {
                onSuccess: () => {
                    Swal.fire("Excluído!", "O agendamento foi removido.", "success");
                },
                onError: () => {
                    Swal.fire("Erro!", "Ocorreu um erro ao excluir.", "error");
                }
            });
        }
    });
};

const openModalEdicao = (agendamento) => {
    const hoje = new Date();
    const dataAgendamento = new Date(agendamento.data_agendamento);
    const diffTime = dataAgendamento - hoje;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 2) {
        Swal.fire("Aviso", "Só é possível editar agendamentos com mais de dois dias de antecedência. Entre em contato com (11)1111-1111!", "warning");
        return;
    }

    selectedAgendamento.value = {
        ...agendamento,
        data_agendamento: agendamento.data_agendamento.split('T')[0]
    };
    showModal.value = true;
};

const closeModal = () => {
    selectedAgendamento.value = null;
    showModal.value = false;
};

const updateAgendamento = () => {
    router.put(route('usuario.agendamento.update', selectedAgendamento.value.id), {
        ...selectedAgendamento.value,
        onSuccess: () => {
            Swal.fire("Sucesso", "O agendamento foi atualizado com sucesso.", "success");
            closeModal();
        },
        onError: () => {
            Swal.fire("Erro", "Ocorreu um erro ao atualizar o agendamento.", "error");
        },
    });
};

</script>
<template>

    <Head title="Agendamentos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-pink-700">
                Agendamentos
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-pink-900">
                        <!-- Filtro -->
                        <div class="mb-4 flex space-x-2 items-end">
                            <div>
                                <label for="data_inicio" class="block text-sm font-medium text-pink-700">Data de
                                    Início</label>
                                <input v-model="filtroData.de" type="date" id="data_inicio"
                                    class="mt-1 block w-32 px-2 py-1 border bg-pink-50 border-pink-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="data_fim" class="block text-sm font-medium text-pink-700">Data de
                                    Fim</label>
                                <input v-model="filtroData.ate" type="date" id="data_fim"
                                    class="mt-1 block w-32 px-2 py-1 border bg-pink-50 border-pink-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <button @click="filtrarAgendamentos"
                                    class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">
                                    Filtrar
                                </button>
                            </div>
                        </div>

                        <div v-if="temMultiplosAgendamentosNaSemana"
                            class="alert alert-warning ml-2 bg-pink-50 h-12 flex items-center">
                            <strong class="ml-2">Aviso:</strong> Você possui mais de um agendamento nesta semana, favor
                            verificar se consegue marcar para a mesma data!
                        </div>
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Serviço</th>
                                    <th class="px-4 py-2 text-left">Valor</th>
                                    <th class="px-4 py-2 text-left">Cliente</th>
                                    <th class="px-4 py-2 text-left">Celular</th>
                                    <th class="px-4 py-2 text-left">Data</th>
                                    <th class="px-4 py-2 text-left">Hora</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="agendamento in agendamentos.data" :key="agendamento.id">
                                    <td class="px-4 py-2">{{ agendamento.servico.nome }}</td>
                                    <td class="px-4 py-2">R${{ agendamento.servico.valor }}</td>
                                    <td class="px-4 py-2">{{ agendamento.nome_cliente }}</td>
                                    <td class="px-4 py-2">{{ agendamento.celular }}</td>
                                    <td class="px-4 py-2">{{ new
                                        Date(agendamento.data_agendamento).toLocaleDateString('pt-BR')
                                        }}</td>
                                    <td class="px-4 py-2">{{ agendamento.hora }}</td>
                                    <td class="px-4 py-2">{{ agendamento.status }}</td>
                                    <td class="px-4 py-2">
                                        <button @click="openModalEdicao(agendamento)"
                                            class="px-4 mr-2 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">
                                            Editar
                                        </button>

                                        <button @click="deleteAgendamento(agendamento.id, agendamento.data_agendamento)"
                                            class="px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-red-400">
                                            Excluir
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="flex justify-between mt-4">
                            <button @click="goToPage(agendamentos.current_page - 1)"
                                :disabled="agendamentos.current_page === 1"
                                class="px-4 py-2 bg-pink-300 text-pink-800 rounded hover:bg-pink-400">
                                Anterior
                            </button>

                            <span class="text-pink-800">Página {{ agendamentos.current_page }} de {{
                                agendamentos.last_page
                                }}</span>

                            <button @click="goToPage(agendamentos.current_page + 1)"
                                :disabled="agendamentos.current_page === agendamentos.last_page"
                                class="px-4 py-2 bg-pink-300 text-pink-800 rounded hover:bg-pink-400">
                                Próxima
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h2 class="text-lg font-bold mb-4">Editar Agendamento</h2>
                <form>
                    <div class="mb-4">
                        <label for="servico" class="block text-sm font-medium text-pink-700">Serviço</label>
                        <select v-model="selectedAgendamento.servico_id" id="servico"
                            class="mt-1 block w-full px-3 py-2 border border-pink-300 bg-pink-50 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Selecione o Serviço</option>
                            <option v-for="servico in servicos" :key="servico.id" :value="servico.id">
                                {{ servico.nome }} + R${{ servico.valor }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="nome_cliente" class="block text-sm font-medium text-pink-700">Cliente</label>
                        <input v-model="selectedAgendamento.nome_cliente" type="text" id="nome_cliente"
                            class="mt-1 block w-full px-3 py-2 border bg-pink-50 border-pink-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label for="celular" class="block text-sm font-medium text-pink-700">Celular</label>
                        <input v-model="selectedAgendamento.celular" type="text" id="celular"
                            class="mt-1 block w-full px-3 py-2 border bg-pink-50 border-pink-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label for="data_agendamento" class="block text-sm font-medium text-pink-700">Data</label>
                        <input v-model="selectedAgendamento.data_agendamento" type="date" id="data_agendamento"
                            :min="dataMinima"
                            class="mt-1 block w-full px-3 py-2 border bg-pink-50 border-pink-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label for="hora" class="block text-sm font-medium text-pink-700">Hora</label>
                        <select v-model="selectedAgendamento.hora" id="hora"
                            class="mt-1 block w-full px-3 py-2 border bg-pink-50 border-pink-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Selecione a Hora</option>
                            <option v-for="hora in horasDisponiveisModal" :key="hora" :value="hora">
                                {{ hora }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-4 flex justify-end">
                        <button @click="updateAgendamento"
                            class="px-4 mr-2 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">
                            Salvar
                        </button>
                        <button @click="closeModal" class="px-4 py-2 bg-pink-300 text-white rounded hover:bg-pink-400">
                            Fechar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
