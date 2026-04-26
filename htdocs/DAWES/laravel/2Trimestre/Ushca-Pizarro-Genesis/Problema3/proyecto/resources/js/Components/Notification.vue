<script setup>
// Datos que entran
defineProps({
    mensaje: String,
    tipo: {
        type: String,
        default: 'success'
    }
});

// Eventos que salen hacia el padre
const emit = defineEmits(['close']);
</script>

<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="mensaje" 
             class="fixed top-5 right-5 z-[2000] max-w-sm w-full shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden text-white"
             :class="tipo === 'success' ? 'bg-emerald-500' : 'bg-rose-500'">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i v-if="tipo === 'success'" class="fas fa-check-circle text-xl"></i>
                        <i v-else class="fas fa-exclamation-circle text-xl"></i>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold">
                            {{ mensaje }}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="emit('close')" class="inline-flex hover:text-gray-200 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>