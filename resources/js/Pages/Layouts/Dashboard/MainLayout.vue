<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

            <!-- Logo -->
            <Link class="navbar-brand" :href="route('dashboard.index')">
                Evaluator
            </Link>

            <!-- Mobile toggle -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu items -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('dashboard.index')">Homepage</Link>
                    </li>
                    <li class="nav-item">
                        <Link class="nav-link" :href="route('evaluations.list')">Evaluations</Link>
                    </li>

                </ul>

                <!-- Search + Logout -->
                <form class="d-flex ms-3">
                    <Link class="btn btn-danger" :href="route('auth.logout')" method="GET">
                        Logout
                    </Link>
                </form>
            </div>
        </div>
    </nav>
</template>
