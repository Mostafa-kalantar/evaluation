<script setup>
import {httpRequest} from "@custom-helpers/axios.js";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";

const username = ref("");
const password = ref("");

const login = (value) => {
    httpRequest(route("auth.login"), {
        username: username.value,
        password: password.value,
    }).then(function (response) {
        if (response.data.token) {
            const token = response.data.token.authorization;
            localStorage.setItem("auth_token", token);
            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            router.visit(response.data.redirect_route);
        }
    });
};
</script>

<template>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <div class="col-6">

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="text-center mb-4">Login</h4>

                    <div class="alert alert-primary text-center" role="alert">
                        <span>@ADMIN: </span><strong><u>username=admin</u></strong> & <strong><u>password=admin</u></strong>
                        <br/>
                        <span>@USER (Scope: 1): </span><strong><u>username=user</u></strong> & <strong><u>password=user</u></strong>
                        <br/>
                        <span>@USER (Scope: 2): </span><strong><u>username=user2</u></strong> & <strong><u>password=user</u></strong>
                    </div>

                    <div>
                        <!-- Username -->
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text"
                                   name="username"
                                   class="form-control"
                                   v-model="username"
                                   required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                v-model="password"
                                required>
                        </div>

                        <button
                            @click="login"
                            type="submit"
                            class="btn btn-primary w-100">
                            Login
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</template>

<style>

</style>
