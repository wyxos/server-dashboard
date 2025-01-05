<script setup>
import {Listing} from "@wyxos/vision";
import {onMounted} from "vue";

const viewDatabase = (dbName) => {
    console.log(`View details for database: ${dbName}`);
    // Add logic to handle database viewing
};

const databases = Listing.create({
    name: '',
})
    .loadFrom('/dashboard/resources/databases')

onMounted(() => {
    databases.load();
});
</script>

<template>
    <div class="db-list">
        <h2>Databases</h2>
        <o-table v-bind="databases.config">
            <o-table-column label="Database Name" key="database_name"/>
            <o-table-column label="Encoding" key="encoding"/>
            <o-table-column label="Collation" key="collation"/>
            <o-table-column label="Project" key="project"/>
            <o-table-column label="Actions">
                <template #default="{ row }">
                    <o-button @click="viewDatabase(row.database_name)">View</o-button>
                </template>
            </o-table-column>
        </o-table>
    </div>
</template>
