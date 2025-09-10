<template>
    <div class="bg-[#0A0A0A] w-full h-full">
        <div class="flex items-center justify-center h-full">
            <!-- Register Form Container -->
            <div 
                class="flex flex-col items-center p-4 rounded-md bg-[#27272a] form-container min-w-[350px]"
            >
                <h4 class="text-white font-semibold">CHBOOK</h4>
                <!-- Register Form -->
                <div class="flex flex-col gap-2 mt-4 w-full">
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
                        class="cursor-pointer flex items-center justify-center p-2 bg-white text-black font-semibold text-sm rounded-lg mt-2"
                        @click="registerUser"
                    >
                        <p v-if="!isLoading">Register</p>

                        <ProgressSpinner 
                            v-else 
                            style="width: 50px; height: 20px;" 
                            strokeWidth="10" fill="transparent"
                            animationDuration=".5s" 
                            aria-label="Custom ProgressSpinner" 
                        />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Component -->
    <Toast />
</template>

<script setup lang="ts">
import { ref } from "vue";
import apiClient from "../services/apiClient";
import ProgressSpinner from 'primevue/progressspinner';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { register } from "../services/authService";

const toast = useToast();

defineOptions({
    name: "Register",
    components: {
        Toast,
        ProgressSpinner,
    }
});


// The Data to be Used as v-models
const username = ref('');
const email = ref('');
const password = ref('');
let isSubmittig = ref(false);
let isLoading = ref(false);


async function registerUser() {
    if(isSubmittig.value) return;

    try {
        isLoading.value = true;

        const userData= {
            username: username.value,
            email: email.value,
            password: password.value
        };

        await register({
            userData,
            toast
        });

    } catch (error) {
        console.error("Error in Register: ", error);

        toast.add({
            severity: 'error',
            summary: "Erreur",
            detail: "Une erreur est survenue",
            life: 2000
        });

    } finally {
        isLoading.value = false;
    }
};


</script>

<style scoped>
.form-container {
    border: 1px solid rgb(98, 94, 94);
}
label {
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    margin-top: 6px;
}

input {
    border: 1px solid white;
    border-radius: 5px;
    padding: 4px 6px;
    font-size: 12px;
}

input:focus {
    border: none !important;
    outline: 2px solid #41B883;
}
</style>