<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    agendamentosSemana: Object,
    ganhosSemana: Object,
    agendamentosMes: Object,
    ganhosMes: Object,
    agendamentosAno: Object,
    ganhosAno: Object
});
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-pink-700">
                Dashboard Admin
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-pink-500">
                        <h3 class="text-xl font-semibold text-gray-800">Resultados da semana</h3>
                        <p class="text-2xl font-bold text-pink-600">Agendamentos: {{ props.agendamentosSemana }}</p>
                        <p class="text-2xl font-bold text-pink-600">R${{ props.ganhosSemana }},00</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-pink-500">
                        <h3 class="text-xl font-semibold text-gray-800">Resultados do mês</h3>
                        <p class="text-2xl font-bold text-pink-600">Agendamentos: {{ props.agendamentosMes }}</p>
                        <p class="text-2xl font-bold text-pink-600">R${{ props.ganhosMes }},00</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-pink-500">
                        <h3 class="text-xl font-semibold text-gray-800">Resultados do ano</h3>
                        <p class="text-2xl font-bold text-pink-600">Agendamentos: {{ props.agendamentosAno }}</p>
                        <p class="text-2xl font-bold text-pink-600">R${{ props.ganhosAno }},00</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
