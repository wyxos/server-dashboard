<script setup>
import { FormBuilder } from '@wyxos/vision';

const form = FormBuilder.create({
    name: '',
    encoding: 'utf8',
    collation: 'utf8_general_ci',
    project: [],
    users: [],
})
    .submitAt('/dashboard/resources/database');
</script>

<template>
    <div>
        <h2>Create Database</h2>

        <form @submit.prevent="form.submit()" class="flex flex-col gap-4 lg:w-[800px]">
            <div class="form-group">
                <label for="name">Database Name</label>
                <input type="text" id="name" name="name" class="form-control" v-model="form.name">
            </div>
            <wyxos-error :form="form" name="name"></wyxos-error>

            <div class="form-group">
                <label for="encoding">Encoding</label>
                <select name="encoding" id="encoding" v-model="form.encoding">
                    <option value="utf8">UTF-8</option>
                    <option value="latin1">Latin1</option>
                    <option value="utf8mb4">UTF-8 MB4</option>
                </select>
            </div>
            <wyxos-error :form="form" name="encoding"></wyxos-error>

            <div class="form-group">
                <label for="collation">Collation</label>
                <select name="collation" id="collation" v-model="form.collation">
                    <option value="utf8_general_ci">UTF-8 General CI</option>
                    <option value="latin1_swedish_ci">Latin1 Swedish CI</option>
                    <option value="utf8mb4_unicode_ci">UTF-8 MB4 Unicode CI</option>
                </select>
            </div>
            <wyxos-error :form="form" name="collation"></wyxos-error>

            <div class="form-group">
                <label for="projects">Project</label>
                <select name="projects" id="projects" multiple v-model="form.projects">
                    <option value="1">Project 1</option>
                    <option value="2">Project 2</option>
                    <option value="3">Project 3</option>
                </select>
            </div>
            <wyxos-error :form="form" name="projects"></wyxos-error>

            <div class="form-group">
                <label for="users">Users</label>
                <select name="users" id="users" multiple v-model="form.users">
                    <option value="1">User 1</option>
                    <option value="2">User 2</option>
                    <option value="3">User 3</option>
                </select>
            </div>
            <wyxos-error :form="form" name="users"></wyxos-error>

            <button type="submit" class="button">Create Database</button>
        </form>
    </div>
</template>

<style scoped>

</style>
