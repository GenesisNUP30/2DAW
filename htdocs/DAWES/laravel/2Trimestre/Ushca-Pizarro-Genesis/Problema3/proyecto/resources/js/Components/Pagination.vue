<script setup>
import { Link } from '@inertiajs/vue3';

// Definimos las "props": lo que el componente necesita recibir del padre
defineProps({
    links: Array,
    meta: Object
});
</script>

<template>
    <div v-if="links.length > 3" class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="hidden sm:block">
            <p class="text-sm text-gray-600 italic">
                Mostrando <span class="font-bold">{{ meta.from }}</span> a 
                <span class="font-bold">{{ meta.to }}</span> de 
                <span class="font-bold">{{ meta.total }}</span> clientes
            </p>
        </div>

        <nav class="inline-flex shadow-sm rounded-md overflow-hidden border border-gray-300">
            <template v-for="(link, k) in links" :key="k">
                <Link 
                    v-if="link.url" 
                    :href="link.url" 
                    v-html="link.label"
                    class="px-3 py-2 text-sm font-semibold transition-colors border-r last:border-r-0"
                    :class="link.active 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-white text-gray-700 hover:bg-gray-100'"
                />
                <span 
                    v-else 
                    v-html="link.label" 
                    class="px-3 py-2 text-sm text-gray-400 bg-white border-r last:border-r-0"
                ></span>
            </template>
        </nav>
    </div>
</template>