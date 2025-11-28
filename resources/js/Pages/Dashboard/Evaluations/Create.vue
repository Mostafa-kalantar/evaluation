<script setup>
import MainLayout from "@pages/Layouts/Dashboard/MainLayout.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import {httpRequest} from "@custom-helpers/axios.js";

const title = ref("");
const errors = ref({});


const submitForm = () => {
    httpRequest(route("evaluations.store"), {
        title: title.value,
    }).then(function (response) {
        if (response.status === 'success') {
            router.visit(route('evaluations.list'))
        }
    });
}

const cancel = () => {
    router.visit(route('evaluations.list'));
};
</script>

<template>
    <MainLayout />

    <div class="container mt-4">
        <h4 class="mb-4">Create Evaluation</h4>

        <form @submit.prevent="submitForm">
            <div class="mb-3">
                <label for="title" class="form-label">Evaluation Title</label>
                <input type="text" class="form-control" id="title" v-model="title">
                <div class="text-danger mt-1" v-if="errors.title">{{ errors.title[0] }}</div>
            </div>

            <button type="submit" class="btn btn-success me-2">Submit</button>
            <button type="button" class="btn btn-secondary" @click="cancel">Cancel</button>
        </form>
    </div>
</template>
