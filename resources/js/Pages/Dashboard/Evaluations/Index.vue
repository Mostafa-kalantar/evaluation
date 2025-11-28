<script setup>
import MainLayout from "@pages/Layouts/Dashboard/MainLayout.vue";
import {computed, onMounted, ref} from 'vue';
import {router} from '@inertiajs/vue3';
import * as bootstrap from 'bootstrap';
import {httpRequest} from "@custom-helpers/axios.js";

const props = defineProps({
    evaluations: Object,
});
const deleteId = ref(null);
let modalElement;
let modalInstance = null;

onMounted(() => {
    modalElement = document.getElementById("deleteModal");
    modalInstance = new bootstrap.Modal(modalElement, {});
});

const openDeleteModal = (id) => {
    deleteId.value = id;
    modalInstance.show();
};

const confirmDelete = () => {
    if (!deleteId.value) return;

    httpRequest(route("evaluations.delete", { id: deleteId.value }), {
    }, 'DELETE').then(function (response) {
        if (response.status === 'success') {
            router.visit(route('evaluations.list'));
            modalInstance.hide();
        }
    });
};

const goTo = (url) => {
    if (url) router.visit(url);
};
</script>

<template>
    <MainLayout></MainLayout>
    <div class="container" style="margin-top:1rem;">
        <h4 class="py-4 mb-6">Evaluations List</h4>
        <button class="btn btn-success mb-4 mt-4" @click="router.visit(route('dashboard.evaluations.create_index'))">
            Create Evaluation
        </button>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
            <tr>
                <th scope="col" class="text-center">#</th>
                <th scope="col" class="text-center">Scope Title</th>
                <th scope="col" class="text-center">Username</th>
                <th scope="col" class="text-center">Evaluation Title</th>
                <th scope="col" class="text-center">Created At (UTC)</th>
                <th scope="col" class="text-center">Updated At (UTC)</th>
                <th scope="col" class="text-center">Operation</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in evaluations.data" :key="item.id">
                <td class="text-end text-center">{{ ++index }}</td>
                <td class="text-end text-center">{{ item.user.scope_title }}</td>
                <td class="text-end text-center">{{ item.user.username }}</td>
                <td class="text-end text-center">{{ item.title }}</td>
                <td class="text-end text-center">{{ item.created_at }}</td>
                <td class="text-end text-center">{{ item.updated_at }}</td>
                <td class="text-center">
                    <button class="btn btn-primary btn-sm me-1" @click="goTo(route('evaluations.read', { id: item.id }))">Update</button>
                    <button class="btn btn-danger btn-sm" @click="openDeleteModal(item.id)">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li
                    class="page-item"
                    :class="{ disabled: !evaluations.links[0].url }"
                >
                    <button class="page-link" @click="goTo(evaluations.links[0].url)" :disabled="!evaluations.links[0].url">
                        Previous
                    </button>
                </li>

                <li
                    v-for="link in evaluations.links.slice(1, -1)"
                    :key="link.label"
                    class="page-item"
                    :class="{ active: link.active, disabled: !link.url }"
                >
                    <button class="page-link" @click="goTo(link.url)" :disabled="!link.url" v-html="link.label"></button>
                </li>

                <li
                    class="page-item"
                    :class="{ disabled: !evaluations.links[evaluations.links.length-1].url }"
                >
                    <button class="page-link" @click="goTo(evaluations.links[evaluations.links.length-1].url)" :disabled="!evaluations.links[evaluations.links.length-1].url">
                        Next
                    </button>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete this evaluation?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        No
                    </button>

                    <button type="button" class="btn btn-danger" @click="confirmDelete">
                        Yes, Delete
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
