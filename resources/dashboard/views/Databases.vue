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
            <o-table-column label="Database Name" v-slot="{row}">
                {{ row.name }}
            </o-table-column>
            <o-table-column label="Encoding" v-slot="{row}">
                {{ row.collation }}
            </o-table-column>
            <o-table-column label="Collation" v-slot="{row}">
                {{ row.encoding }}
            </o-table-column>
            <o-table-column label="Projects" v-slot="{row}">
                {{ row.project }}
            </o-table-column>
            <o-table-column label="Users" v-slot="{row}">
                {{ row.users.join(', ') }}
            </o-table-column>
            <o-table-column label="Actions" v-slot="{row }">
                <div class="flex gap-2">
                    <wyxos-action class="cta-icon">
                        <i class="fas fa-eye"></i>
                    </wyxos-action>
                    <wyxos-action class="cta-icon"></wyxos-action>
                </div>
            </o-table-column>
        </o-table>
    </div>
</template>
