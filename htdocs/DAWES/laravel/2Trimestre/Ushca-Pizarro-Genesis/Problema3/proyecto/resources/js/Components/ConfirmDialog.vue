<script setup>
defineProps({
    show: Boolean,
    title: String,
    message: String,
    confirmLabel: {
        type: String,
        default: 'Sí, eliminar'
    }
});

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
    <div v-if="show" 
        class="fixed inset-0 z-[1100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
    
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-200">
            <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                <h3 class="text-lg font-bold text-red-800">{{ title }}</h3>
            </div>

            <div class="p-6">
                <p class="text-gray-700 mb-4">
                    {{ message }}
                </p>
            
                <div class="bg-amber-50 border-l-4 border-amber-500 p-3 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-amber-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-amber-800 font-bold uppercase">Aviso</p>
                            <p class="text-xs text-amber-700 mt-1">
                                Esta acción también marcará como eliminadas todas las <strong>cuotas asociadas</strong> a este cliente.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="emit('close')" 
                        class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        Cancelar
                    </button>
                    <button @click="emit('confirm')" 
                        class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-md transition">
                        {{ confirmLabel }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>