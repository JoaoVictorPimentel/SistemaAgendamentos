<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import swal from 'sweetalert2';
import axios from 'axios';

defineProps({
    servicos: Array,
    horasDisponiveis: Array,
    errors: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm({
    servico_id: '',
    nome_cliente: '',
    celular: '',
    data_agendamento: '',
    hora: '',
});

const loading = ref(false);
const horasDisponiveis = ref([]);

watch(() => form.data_agendamento, async (newData) => {
    if (newData) {
        try {
            const response = await axios.get('/api/horarios-disponiveis', {
                params: {
                    data_agendamento: newData,
                },
            });
            horasDisponiveis.value = response.data;
        } catch (error) {
            console.error('Erro ao buscar horas disponíveis:', error);
        }
    } else {
        horasDisponiveis.value = [];
    }
});

const submit = () => {
    loading.value = true;
    form.post(route('usuario.agendamento.store'), {
        onSuccess: () => {
            loading.value = false;
            swal.fire({
                title: 'Sucesso!',
                text: 'Agendamento realizado com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    container: 'bg-pink-50',
                    title: 'text-pink-600 font-bold text-lg',
                    content: 'text-pink-700',
                    confirmButton: 'bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-400 focus:ring-pink-500 focus:ring-offset-2',
                },
            }).then(() => {
                form.reset();
                window.location.href = route('usuario.dashboard');
            });
        },
        onError: (errors) => {
            loading.value = false;
            swal.fire({
                title: 'Erro',
                text: errors?.hora || 'Algo deu errado. Verifique os campos e tente novamente.',
                icon: 'error',
                customClass: {
                    container: 'bg-red-50',
                    title: 'text-red-600 font-bold text-lg',
                    content: 'text-gray-700',
                    confirmButton: 'bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-400 focus:ring-red-500 focus:ring-offset-2',
                },
            });
        },
    });
};
</script>

<template>
    <Head title="Cadastrar Agendamento" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-pink-700">Cadastrar Agendamento</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Formulário de Agendamento -->
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="servico_id" class="block text-sm font-medium text-pink-700">Serviço</label>
                                <select v-model="form.servico_id" id="servico_id"
                                    class="mt-1 block w-full px-3 py-2 border border-pink-300 bg-pink-50 text-gray-900 focus:ring-pink-500 focus:border-pink-500 rounded-lg sm:text-sm">
                                    <option value="">Selecione o Serviço</option>
                                    <option v-for="servico in servicos" :key="servico.id" :value="servico.id">
                                        {{ servico.nome }} + R${{ servico.valor }}
                                    </option>
                                </select>
                                <span v-if="errors.servico_id" class="text-red-500 text-xs">{{ errors.servico_id }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="nome_cliente" class="block text-sm font-medium text-pink-700">Nome do Cliente</label>
                                <input v-model="form.nome_cliente" type="text" id="nome_cliente" placeholder="Nome"
                                    class="mt-1 block w-full px-3 py-2 border border-pink-300 bg-pink-50 text-gray-900 focus:ring-pink-500 focus:border-pink-500 rounded-lg sm:text-sm" />
                                <span v-if="errors.nome_cliente" class="text-red-500 text-xs">{{ errors.nome_cliente }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="celular" class="block text-sm font-medium text-pink-700">Celular</label>
                                <input v-model="form.celular" type="text" id="celular" placeholder="Celular"
                                    class="mt-1 block w-full px-3 py-2 border border-pink-300 bg-pink-50 text-gray-900 focus:ring-pink-500 focus:border-pink-500 rounded-lg sm:text-sm" />
                                <span v-if="errors.celular" class="text-red-500 text-xs">{{ errors.celular }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="data_agendamento" class="block text-sm font-medium text-pink-700">Data de Agendamento</label>
                                <input v-model="form.data_agendamento" type="date" id="data_agendamento"
                                    class="mt-1 block w-full px-3 py-2 border border-pink-300 bg-pink-50 text-gray-900 focus:ring-pink-500 focus:border-pink-500 rounded-lg sm:text-sm" />
                                <span v-if="errors.data_agendamento" class="text-red-500 text-xs">{{ errors.data_agendamento }}</span>
                            </div>

                            <div class="mb-4">
                                <label for="hora" class="block text-sm font-medium text-pink-700">Hora</label>
                                <select v-model="form.hora" id="hora"
                                    class="mt-1 block w-full px-3 py-2 border border-pink-300 bg-pink-50 text-gray-900 focus:ring-pink-500 focus:border-pink-500 rounded-lg sm:text-sm">
                                    <option value="">Selecione a Hora</option>
                                    <option v-for="hora in horasDisponiveis" :key="hora" :value="hora">
                                        {{ hora }}
                                    </option>
                                </select>
                                <span v-if="errors.hora" class="text-red-500 text-xs">{{ errors.hora }}</span>
                            </div>

                            <div class="mb-4 mt-4">
                                <button type="submit"
                                    class="w-full bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-400 focus:ring-pink-500 focus:ring-offset-2 flex items-center justify-center"
                                    :disabled="loading">
                                    <span v-if="loading"
                                        class="animate-spin border-2 border-white border-t-transparent rounded-full h-4 w-4 mr-2"></span>
                                    Criar Agendamento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
