<script setup>
import MainLayout from "@pages/Layouts/Dashboard/MainLayout.vue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import {httpRequest} from "@custom-helpers/axios.js";

const props = defineProps({
    evaluation: Object,
});

// مقدار اولیه فیلدها
const id = ref(props.evaluation.id);
const title = ref(props.evaluation.title);

const submitForm = () => {
    httpRequest(route("evaluations.update", {id: id.value}), {
        title: title.value,
    }, 'PUT').then(function (response) {
        if (response.status === 'success') {
            router.visit(route('evaluations.list'))
        }
    });
};

const cancel = () => {
    router.visit(route('evaluations.list'));
};
</script>

<template>
    <MainLayout/>

    <div class="container mt-4">
        <h4 class="mb-4">Update Evaluation</h4>

        <form @submit.prevent="submitForm">
            <div class="mb-3">
                <label for="title" class="form-label">Evaluation Title</label>
                <input type="text" class="form-control" id="title" v-model="title">
            </div>

            <button type="submit" class="btn btn-success me-2">Submit</button>
            <button type="button" class="btn btn-secondary" @click="cancel">Cancel</button>
        </form>
    </div>
</template>
