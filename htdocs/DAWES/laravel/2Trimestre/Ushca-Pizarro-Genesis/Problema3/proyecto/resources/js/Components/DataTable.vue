<script setup>
defineProps({
    headers: Array, // Nombres de las columnad
    items: Array    // Datos
});
</script>

<template>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-left text-sm uppercase font-semibold">
                    <th v-for="header in headers" :key="header" class="px-5 py-3">
                        {{ header }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in items" :key="index" class="border-b border-gray-200 hover:bg-gray-50">
                    <slot name="row" :item="item"></slot>
                </tr>
                <tr v-if="items.length === 0">
                    <td :colspan="headers.length" class="px-5 py-10 text-center text-gray-500 italic">
                        No se encontraron registros.
                    </td>
                </tr>
            </tbody>
        </table>
        <slot name="pagination"></slot>
    </div>
</template>