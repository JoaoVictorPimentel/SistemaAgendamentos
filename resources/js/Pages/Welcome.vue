<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Bem-vinda ao Sistema!" />
    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0 text-black">
        <div class="relative flex min-h-screen flex-col items-center justify-center py-16">
            <div class="text-center mb-8">
                <img
                    class="h-36 w-auto mx-auto"
                    src="imgs/logo.png"
                    alt="Logo do salão de beleza"
                />
            </div>

            <div class="text-center mb-12">
                <h1 class="text-3xl font-bold text-pink-700">Bem-vinda ao Sistema de Gestão de Salão de Beleza!</h1>
                <p class="mt-4 text-lg text-gray-700">Gerencie suas clientes e serviços de forma fácil e prática.</p>
            </div>

            <div class="flex flex-col items-center space-y-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="w-64 py-3 text-center text-white bg-pink-500 hover:bg-pink-400 rounded-lg shadow-md transition transform hover:scale-105"
                >
                    Acessar Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="w-64 py-3 text-center text-white bg-pink-500 hover:bg-pink-400 rounded-lg shadow-md transition transform hover:scale-105"
                    >
                        Entrar
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="w-64 py-3 text-center text-white bg-pink-500 hover:bg-pink-400 rounded-lg shadow-md transition transform hover:scale-105"
                    >
                        Cadastrar-se
                    </Link>
                </template>
            </div>
        </div>
    </div>
</template>
