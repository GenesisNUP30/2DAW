<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    clientes: Array,
    paises: Array
});

const editando = ref(false);
const modalAbierto = ref(false);

const form = useForm({
    id: null,
    nombre: '',
    cif: '',
    telefono: '',
    correo: '',
    cuenta_corriente: '',
    pais: '',
    fecha_alta: '',
    importe_cuota_mensual: 0
});

const submit = () => {
    // 1. Validación JS simple antes de enviar
    if (form.nombre === '') {
        alert("El nombre es obligatorio");
        return;
    }

    // 2. Envío mediante Inertia (Integra automáticamente errores de Laravel)
    if (editando.value) {
        form.put(`/v3/clientes/${form.id}`, {
            onSuccess: () => cerrarModal()
        });
    } else {
        form.post('/v3/clientes', {
            onSuccess: () => cerrarModal()
        });
    }
};

const abrirCrear = () => {
    editando.value = false;
    form.reset();
    modalAbierto.value = true;
};

const abrirEditar = (cliente) => {
    editando.value = true;
    form.clearErrors();
    form.id = cliente.id;
    form.nombre = cliente.nombre;
    form.cif = cliente.cif;
    form.telefono = cliente.telefono;
    form.correo = cliente.correo;
    form.cuenta_corriente = cliente.cuenta_corriente;
    form.pais = cliente.pais;
    form.fecha_alta = cliente.fecha_alta.split('T')[0];
    form.importe_cuota_mensual = cliente.importe_cuota_mensual;
    modalAbierto.value = true;
};

const cerrarModal = () => {
    modalAbierto.value = false;
    form.reset();
};
</script>

<template>
    <div class="p-8 bg-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Clientes V3 (Inertia + Tailwind)</h1>
                <button @click="abrirCrear" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                    Nuevo Cliente
                </button>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-left text-sm uppercase font-semibold">
                            <th class="px-5 py-3">Nombre</th>
                            <th class="px-5 py-3">CIF</th>
                            <th class="px-5 py-3">País</th>
                            <th class="px-5 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cliente in clientes" :key="cliente.id" class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-5 py-4 text-sm">{{ cliente.nombre }}</td>
                            <td class="px-5 py-4 text-sm">{{ cliente.cif }}</td>
                            <td class="px-5 py-4 text-sm">{{ cliente.pais_relacion?.nombre }}</td>
                            <td class="px-5 py-4 text-sm text-center">
                                <button @click="abrirEditar(cliente)" class="text-orange-600 hover:text-orange-900 mr-3">Editar</button>
                                <button @click="$inertia.delete(route('clientes.v3.destroy', cliente.id))" class="text-red-600 hover:text-red-900">Borrar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="modalAbierto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full p-6 shadow-xl">
                <h2 class="text-xl font-bold mb-4">{{ editando ? 'Editar Cliente' : 'Nuevo Cliente' }}</h2>
                
                <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input v-model="form.nombre" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2">
                        <p v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">CIF</label>
                        <input v-model="form.cif" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2">
                        <p v-if="form.errors.cif" class="text-red-500 text-xs mt-1">{{ form.errors.cif }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">País</label>
                        <select v-model="form.pais" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm border p-2">
                            <option v-for="p in paises" :key="p.iso2" :value="p.iso2">{{ p.nombre }}</option>
                        </select>
                        <p v-if="form.errors.pais" class="text-red-500 text-xs mt-1">{{ form.errors.pais }}</p>
                    </div>

                    <div class="col-span-2 flex justify-end space-x-3 mt-4">
                        <button type="button" @click="cerrarModal" class="bg-gray-200 px-4 py-2 rounded">Cancelar</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded" :disabled="form.processing">
                            {{ editando ? 'Actualizar' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>