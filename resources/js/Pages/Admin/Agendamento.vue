<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    agendamentos: Array
});
</script>

<template>
    <Head title="Agendamentos - Admin" />

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
                                    <th class="px-4 py-2 text-left">Cliente</th>
                                    <th class="px-4 py-2 text-left">Data</th>
                                    <th class="px-4 py-2 text-left">Ações</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="agendamento in agendamentos" :key="agendamento.id">
                                    <td class="px-4 py-2">{{ agendamento.servico.nome }}</td>
                                    <td class="px-4 py-2">{{ agendamento.nome_cliente }}</td>
                                    <td class="px-4 py-2">{{ agendamento.data_agendamento }}</td>
                                    <td class="px-4 py-2">{{ agendamento.status }}</td>
                                    <td class="px-4 py-2">
                                        <button class="text-blue-500">Editar</button>
                                        <button class="text-red-500">Excluir</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { defineProps } from 'vue';

// Filtro para formatar a data
export default {
    filters: {
        formatDate(value) {
            if (!value) return '';
            return new Date(value).toLocaleDateString();
        }
    }
}
</script>
