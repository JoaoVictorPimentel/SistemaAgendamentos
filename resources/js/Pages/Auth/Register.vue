<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Registrar" />

        <div class="bg-white text-black">
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="name" value="Nome" class="text-pink-700" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full rounded-lg border border-pink-300 bg-pink-50 text-gray-900 focus:ring-pink-500 focus:border-pink-500"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <InputError class="mt-2 text-red-500" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" class="text-pink-700" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full rounded-lg border border-pink-300 bg-pink-50 text-gray-900 focus:ring-pink-500 focus:border-pink-500"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-2 text-red-500" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Senha" class="text-pink-700" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full rounded-lg border border-pink-300 bg-pink-50 text-gray-900 focus:ring-pink-500 focus:border-pink-500"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2 text-red-500" :message="form.errors.password" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password_confirmation" value="Confirmar Senha" class="text-pink-700" />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full rounded-lg border border-pink-300 bg-pink-50 text-gray-900 focus:ring-pink-500 focus:border-pink-500"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2 text-red-500" :message="form.errors.password_confirmation" />
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <Link
                        :href="route('login')"
                        class="rounded-md text-sm text-pink-600 hover:text-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2"
                    >
                        Já tem uma conta?
                    </Link>

                    <PrimaryButton
                        class="ms-4 bg-pink-500 text-white hover:bg-pink-400 focus:ring-pink-500 focus:ring-offset-2 rounded-lg"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Registrar
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
