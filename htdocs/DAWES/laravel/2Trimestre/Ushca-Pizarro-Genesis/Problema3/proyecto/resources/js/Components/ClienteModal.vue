<script setup>
defineProps({
    show: Boolean,
    editando: Boolean,
    soloLectura: Boolean,
    form: Object,
    paises: Array,
});

const emit = defineEmits(['close', 'submit']);
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[1050] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[95vh] flex flex-col overflow-hidden">
            
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas" :class="soloLectura ? 'fa-eye text-blue-600' : (editando ? 'fa-edit text-orange-600' : 'fa-plus text-green-600')"></i>
                    {{ soloLectura ? " Detalles del Cliente" : (editando ? " Editar Cliente" : " Nuevo Cliente") }}
                </h2>
                <button @click="emit('close')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>

            <div class="p-6 overflow-y-auto">
                <form @submit.prevent="emit('submit')" id="formCliente" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">CIF / DNI</label>
                        <input v-model="form.cif" :disabled="soloLectura" type="text"
                            :class="{'border-red-500 ring-1 ring-red-500': form.errors.cif, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border">
                        <p v-if="form.errors.cif" class="text-red-500 text-[11px] mt-1">{{ form.errors.cif }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nombre Completo</label>
                        <input v-model="form.nombre" :disabled="soloLectura" type="text"
                            :class="{'border-red-500 ring-1 ring-red-500': form.errors.nombre, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border">
                        <p v-if="form.errors.nombre" class="text-red-500 text-[11px] mt-1">{{ form.errors.nombre }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Teléfono</label>
                        <input v-model="form.telefono" :disabled="soloLectura" type="text"
                            :class="{'border-red-500 ring-1 ring-red-500': form.errors.telefono, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Correo Electrónico</label>
                        <input v-model="form.correo" :disabled="soloLectura" type="email"
                            :class="{'border-red-500 ring-1 ring-red-500': form.errors.correo, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Cuenta Corriente (IBAN)</label>
                        <input v-model="form.cuenta_corriente" :disabled="soloLectura" type="text"
                            :class="{'border-red-500 ring-1 ring-red-500': form.errors.cuenta_corriente, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">País</label>
                        <select v-model="form.pais" :disabled="soloLectura"
                            :class="{'border-red-500 ring-1 ring-red-500': form.errors.pais, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border text-sm">
                            <option value="">Seleccionar...</option>
                            <option v-for="p in paises" :key="p.iso2" :value="p.iso2">{{ p.nombre }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Cuota Mensual</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400">€</span>
                            <input v-model.number="form.importe_cuota_mensual" :disabled="soloLectura" type="number" step="0.01"
                                class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 pl-7 border">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Fecha de Alta</label>
                        <input v-model="form.fecha_alta" :disabled="soloLectura" type="date"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border text-sm">
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
                <button type="button" @click="emit('close')" class="px-5 py-2.5 rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition-all font-semibold">
                    {{ soloLectura ? "Cerrar" : "Cancelar" }}
                </button>
                <button v-if="!soloLectura" type="submit" form="formCliente"
                    class="px-5 py-2.5 rounded-lg text-white font-bold transition-all shadow-md flex items-center gap-2"
                    :class="editando ? 'bg-orange-500 hover:bg-orange-600' : 'bg-blue-600 hover:bg-blue-700'"
                    :disabled="form.processing">
                    <i v-if="form.processing" class="fas fa-circle-notch animate-spin"></i>
                    {{ editando ? "Actualizar Cliente" : "Guardar Cliente" }}
                </button>
            </div>
        </div>
    </div>
</template>