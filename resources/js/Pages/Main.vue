<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">Your link</h2>
                <p>Your link is <a :href="customEmail" class="text-blue-500">{{ customEmail }}</a></p>
                <button @click="closeModal"
                        class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">Close
                </button>
            </div>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h1 class="text-2xl font-bold text-center mb-6">Registration</h1>
            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">User name</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        maxlength="50"
                        placeholder="Введите имя пользователя"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-medium mb-2">Phone number</label>
                    <input
                        type="text"
                        id="phone"
                        pattern="^\+?[0-9]{10,15}$"
                        maxlength="16"
                        v-model="form.phone"
                        placeholder="Введите номер телефона"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
                <div class="text-center">
                    <button
                        type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "Main",
    data() {
        return {
            form: {
                name: '',
                phone: '',
            },
            customEmail: '',
            showModal: false,
        };
    },

    methods: {
        submitForm() {
            axios.post('/api/v1/register', this.form)
                .then(response => {
                    this.customEmail = response.data;
                    this.showModal = true;
                    this.form.name = '';
                    this.form.phone = '';
                })
                .catch(error => {
                    if (error.response) {
                        console.log(error.response.data);
                    } else {
                        console.log(error.message);
                    }
                });
        },

        closeModal() {
            this.showModal = false;
        }
    }
}
</script>

<style scoped>

</style>
