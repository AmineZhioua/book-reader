<template>
    <div class="bg-[#0A0A0A] w-full h-full">
        <div class="flex items-center justify-center h-full">
            <!-- Register Form Container -->
            <div class="flex flex-col items-center p-4 rounded-lg bg-gray-700">
                <h4 class="text-white font-semibold">Welcome</h4>
                <!-- Register Form -->
                <div class="flex flex-col gap-2">
                    <!-- Username Input Field -->
                    <div class="flex flex-col gap-2">
                        <label for="username">Username :</label>
                        <input type="text" name="username" id="username" v-model="username" />
                    </div>

                    <!-- Email Input Field -->
                    <div class="flex flex-col gap-2">
                        <label for="email">Email :</label>
                        <input type="email" name="email" id="email" v-model="email" />
                    </div>

                    <!-- Password Input Field -->
                    <div class="flex flex-col gap-2">
                        <label for="password">Password :</label>
                        <input type="password" name="password" id="password" v-model="password" />
                    </div>

                    <button 
                        class="cursor-pointer p-2 bg-red-500 text-white rounded-lg mt-2"
                        @click="registerUser"
                    >
                        Register
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import axios from "axios";
import { ref } from "vue";

const apiUrl = import.meta.env.VITE_API_URL;

axios.defaults.withCredentials = true;
axios.defaults.baseURL = apiUrl; 
axios.defaults.withXSRFToken = true;

defineOptions({
    name: "Register",
});

// Our Data to be Used as v-models
const username = ref('');
const email = ref('');
const password = ref('');

async function registerUser() {
    try {
        const csrfToken = await axios.get("/sanctum/csrf-cookie");
        console.log(csrfToken)

        // Step 2: Make the registration request
        const registerResponse = await axios.post("/api/register", {
                name: username.value,
                email: email.value,
                password: password.value,
            },
            // {
            //     headers: {
            //         'XSRF-TOKEN': csrfToken.data.v,
            //     }
            // }
        );

        if (registerResponse.status === 201) {
            console.log('User registered!');
        }

    } catch (error) {
        console.error("Error in Register: ", error);
    }
};


</script>

<style scoped>
input {
    border: 1px solid white;
    border-radius: 10px;
}
</style>