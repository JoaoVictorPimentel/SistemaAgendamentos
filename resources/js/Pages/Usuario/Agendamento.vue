<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    agendamentos: Object,
});

const goToPage = (page) => {
    if (page > 0 && page <= props.agendamentos.last_page) {
        router.get(route('usuario.agendamento'), { page });
    }
};
</script>

<template>

    <Head title="Agendamentos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Agendamentos
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Tabela de Agendamentos -->
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
                                        <button
                                            class="px-4 mr-2 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">Editar</button>

                                        <button
                                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">Excluir</button>

                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Paginação -->
                        <div class="flex justify-between mt-4">
                            <button @click="goToPage(agendamentos.current_page - 1)"
                                :disabled="agendamentos.current_page === 1"
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                Anterior
                            </button>

                            <span class="text-gray-800">
                                Página {{ agendamentos.current_page }} de {{ agendamentos.last_page }}
                            </span>

                            <button @click="goToPage(agendamentos.current_page + 1)"
                                :disabled="agendamentos.current_page === agendamentos.last_page"
                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                                Próxima
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>