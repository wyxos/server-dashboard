<script setup>
import { ref } from 'vue';

// Sample data for databases
const databases = ref([
    {
        database_name: "gardening",
        encoding: "utf8mb4",
        collation: "utf8mb4_general_ci",
        users: null,
        project: { name: "Gardening Blog", framework: "Laravel", version: "10.3.0" },
    },
    {
        database_name: "wyxos",
        encoding: "utf8mb4",
        collation: "utf8mb4_general_ci",
        users: null,
        project: { name: "Portfolio", framework: "Laravel", version: "10.3.0" },
    },
]);

const viewDatabase = (dbName) => {
    console.log(`View details for database: ${dbName}`);
    // Add logic to handle database viewing
};
</script>

<template>
    <div class="db-list">
        <h2>Databases</h2>
        <table class="table text-white">
            <thead>
            <tr>
                <th>Database Name</th>
                <th>Encoding</th>
                <th>Collation</th>
                <th>Project</th>
                <th>Users</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="db in databases" :key="db.database_name">
                <td>{{ db.database_name }}</td>
                <td>{{ db.encoding }}</td>
                <td>{{ db.collation }}</td>
                <td>
                    <div v-if="db.project">
                        <strong>{{ db.project.name }}</strong>
                        <p>{{ db.project.framework }} (v{{ db.project.version }})</p>
                    </div>
                </td>
                <td>{{ db.users || "None" }}</td>
                <td>
                    <button @click="viewDatabase(db.database_name)" class="view-button">View</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
