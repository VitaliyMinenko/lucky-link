<template>
    <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center space-y-6">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
            <h1 class="text-2xl font-bold mb-4">Link Settings:</h1>

            <div class="mb-4">
                <p>Your link is: <span class="text-blue-500">{{ url }}</span></p>
            </div>

            <button
                v-on:click="generateLink()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg mb-4">
                Generate New Link
            </button>

            <button
                v-on:click="deactivateLink()"
                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg">
                Deactivate Link
            </button>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
            <button
                @click="feelingLucky"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg">
                I'm Feeling Lucky
            </button>
            <div v-if="number">
                <p>Number is {{ number }}</p>
                <p>Your amount is {{ amount }}</p>
                <p v-if="winner === true">You Win</p>
                <p v-else>You Lose</p>
            </div>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
            <button
                @click="viewHistory"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                View History
            </button>
            <div v-if="history.length">
                <ul>
                    <li v-for="(draw, index) in history" :key="index">
                        Number: {{ draw.number }}, Winner: {{ draw.winner ? 'Yes' : 'No' }}, Amount: {{ draw.amount }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import {Inertia} from '@inertiajs/inertia';
import axios from "axios";

export default {
    name: "Page",
    props: {
        url: String,
        page: String,
        name: String,
        phone: String,
    },
    data() {
        return {
            number: '',
            amount: '',
            winner: false,
            history: {},
        };
    },
    methods: {
        generateLink() {
            Inertia.get('/regenerete/page=' + this.page);
        },
        deactivateLink() {
            Inertia.get('/deactivate/page=' + this.page);
        },
        feelingLucky() {
            axios.post('/api/v1/lucky', {'page': this.page})
                .then(response => {
                    let winnerResult = JSON.parse(response.data);
                    this.winner = winnerResult.winner
                    this.amount = winnerResult.amount
                    this.number = winnerResult.number
                })
                .catch(error => {
                    if (error.response) {
                        console.log(error.response.data);
                    } else {
                        console.log(error.message);
                    }
                });
        },
        viewHistory() {
            axios.post('/api/v1/history', {'page': this.page})
                .then(response => {
                    this.history = JSON.parse(response.data);
                    debugger
                })
                .catch(error => {
                    if (error.response) {
                        console.log(error.response.data);
                    } else {
                        console.log(error.message);
                    }
                });
        }
    }
}
</script>
