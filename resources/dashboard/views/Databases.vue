<script setup>
import {Listing, vn} from "@wyxos/vision";
import {onMounted} from "vue";

const { route } = vn

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
    <div>
        <div class="flex justify-between items-center">
            <h2>Databases</h2>

            <router-link :to="route('database.create')" class="button">
                Add Database
            </router-link>
        </div>
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
                    <wyxos-action class="button">
                        <i class="fas fa-eye"></i>
                    </wyxos-action>
                    <wyxos-action class="button danger"></wyxos-action>
                </div>
            </o-table-column>
        </o-table>
    </div>
</template>
