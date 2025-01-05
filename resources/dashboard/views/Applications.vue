<script setup>
import { ref } from 'vue';

// Sample data for applications
const applications = ref([
    { id: 1, name: 'My Portfolio', framework: 'Laravel', version: '10.3.0' },
    { id: 2, name: 'Dashboard', framework: 'Nuxt', version: '3.2.1' },
    { id: 3, name: 'Media Server', framework: 'Deno', version: '2.1.0' },
]);

const editApp = (appId) => {
    console.log(`Edit application with ID: ${appId}`);
    // Add your edit logic here (e.g., navigate to an edit page)
};
</script>

<template>
    <div class="app-list">
        <h2>Installed Applications</h2>
        <table class="table text-white">
            <thead>
            <tr>
                <th>Name</th>
                <th>Framework/Technology</th>
                <th>Version</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="app in applications" :key="app.id">
                <td>{{ app.name }}</td>
                <td>{{ app.framework }}</td>
                <td>{{ app.version }}</td>
                <td>
                    <button @click="editApp(app.id)" class="edit-button">Edit</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
