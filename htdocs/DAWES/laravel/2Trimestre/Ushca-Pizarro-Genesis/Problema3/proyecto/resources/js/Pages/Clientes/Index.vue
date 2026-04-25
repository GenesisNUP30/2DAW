<script setup>
import { ref } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';

const props = defineProps({
    clientes: Object,
    paises: Array
});

const editando = ref(false);
const modalAbierto = ref(false);
const soloLectura = ref(false);
const confirmandoBorrado = ref(false);
const clienteAEliminar = ref(null);

const mensajeFlash = ref(null);
const tipoFlash = ref('success'); 

const mostrarNotificacion = (msg, tipo = 'success') => {
    mensajeFlash.value = msg;
    tipoFlash.value = tipo;

    // Desaparece automáticamente a los 3 segundos
    setTimeout(() => {
        mensajeFlash.value = null;
    }, 3000);
};

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

const validarFormulario = () => {
    form.clearErrors(); // Limpiamos errores previos
    let valido = true;

    // Validación manual lado cliente
    const campos = ['nombre', 'cif', 'telefono', 'correo', 'cuenta_corriente', 'pais', 'fecha_alta'];
    campos.forEach(campo => {
        if (!form[campo]) {
            form.setError(campo, `El campo ${campo.replace('_', ' ')} es obligatorio`);
            valido = false;
        }
    });

    if (form.importe_cuota_mensual <= 0) {
        form.setError('importe_cuota_mensual', 'La cuota debe ser mayor a 0');
        valido = false;
    }

    return valido;
};

const submit = () => {
    if (editando.value) {
        form.put(`/v3/clientes/${form.id}`, {
            onSuccess: () => {
                cerrarModal();
                mostrarNotificacion('El cliente se ha actualizado correctamente');
            },
            onError: () => mostrarNotificacion('Error al intentar realizar la operación', 'error'),
        });
    } else {
        form.post('/v3/clientes', {
            onSuccess: () => {
                cerrarModal();
                mostrarNotificacion('El cliente se ha creado correctamente');
            },
            onError: () => mostrarNotificacion('Error al intentar realizar la operación', 'error'),
        });
    }
};

const abrirVer = (cliente) => {
    cargarDatos(cliente);
    soloLectura.value = true;
    modalAbierto.value = true;
};

const abrirCrear = () => {
    editando.value = false;
    soloLectura.value = false;
    form.reset();
    form.clearErrors();
    modalAbierto.value = true;
};

const abrirEditar = (cliente) => {
    editando.value = true;
    soloLectura.value = false;
    cargarDatos(cliente);
    modalAbierto.value = true;
};

const cargarDatos = (cliente) => {
    form.clearErrors();
    form.id = cliente.id;
    form.nombre = cliente.nombre;
    form.cif = cliente.cif;
    form.telefono = cliente.telefono;
    form.correo = cliente.correo;
    form.cuenta_corriente = cliente.cuenta_corriente;
    form.pais = cliente.pais;
    form.fecha_alta = cliente.fecha_alta ? cliente.fecha_alta.split('T')[0] : '';
    form.importe_cuota_mensual = cliente.importe_cuota_mensual;
};

const cerrarModal = () => {
    modalAbierto.value = false;
    soloLectura.value = false;
    form.reset();
};

const abrirConfirmacion = (cliente) => {
    clienteAEliminar.value = cliente;
    confirmandoBorrado.value = true;
};

const cerrarConfirmacion = () => {
    confirmandoBorrado.value = false;
    clienteAEliminar.value = null;
};

const ejecutarBorrado = () => {
    if (!clienteAEliminar.value) return;

    router.delete(`/v3/clientes/${clienteAEliminar.value.id}`, {
        onSuccess: () => {
            cerrarConfirmacion();
            mostrarNotificacion('El cliente se ha eliminado correctamente');
        },
        onError: () => {
            cerrarConfirmacion();
            mostrarNotificacion('Error al intentar realizar la operación', 'error');
        }
    });
};
</script>

<template>
    <div class="p-8 bg-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Clientes con Inertia + Tailwind</h1>
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
                            <th class="px-5 py-3">Teléfono</th>
                            <th class="px-5 py-3">País</th>
                            <th class="px-5 py-3">Cuota Mensual</th>
                            <th class="px-5 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cliente in clientes.data" :key="cliente.id" class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-5 py-4 text-sm">{{ cliente.nombre }}</td>
                            <td class="px-5 py-4 text-sm">{{ cliente.cif }}</td>
                            <td class="px-5 py-4 text-sm">{{ cliente.telefono }}</td>
                            <td class="px-5 py-4 text-sm">{{ cliente.pais_relacion?.nombre }}</td>
                            <td class="px-5 py-4 text-sm">
                                {{ Number(cliente.importe_cuota_mensual).toFixed(2) }} {{ cliente.moneda }}
                            </td>
                            <td class="px-5 py-4 text-sm text-center font-medium">
                                <button @click="abrirVer(cliente)" class="text-blue-600 hover:text-blue-900 mx-2"><i class="fas fa-eye mr-1"></i>Ver</button>
                                <button @click="abrirEditar(cliente)" class="text-orange-600 hover:text-orange-900 mr-3"><i class="fas fa-edit mr-1"></i>Editar</button>
                                <button @click="abrirConfirmacion(cliente)" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash mr-1"></i>Borrar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <p class="text-sm text-gray-600 italic">
                        Mostrando {{ clientes.from }} a {{ clientes.to }} de {{ clientes.total }} clientes
                    </p>
                    <nav class="inline-flex shadow-sm rounded-md overflow-hidden border border-gray-300">
                        <template v-for="(link, k) in clientes.links" :key="k">
                            <Link 
                                v-if="link.url" 
                                :href="link.url" 
                                v-html="link.label"
                                class="px-3 py-2 text-sm font-semibold transition-colors border-r last:border-r-0"
                                :class="link.active ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'"
                            />
                            <span v-else v-html="link.label" class="px-3 py-2 text-sm text-gray-400 bg-white border-r last:border-r-0"></span>
                        </template>
                    </nav>
                </div>
            </div>
        </div>

        <Transition
    enter-active-class="transform ease-out duration-300 transition"
    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
>
    <div v-if="mensajeFlash" 
         class="fixed top-5 right-5 z-[2000] max-w-sm w-full shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
         :class="tipoFlash === 'success' ? 'bg-emerald-500' : 'bg-rose-500'">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i v-if="tipoFlash === 'success'" class="fas fa-check-circle text-white text-xl"></i>
                    <i v-else class="fas fa-exclamation-circle text-white text-xl"></i>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-bold text-white">
                        {{ mensajeFlash }}
                    </p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="mensajeFlash = null" class="inline-flex text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</Transition>

        <div v-if="modalAbierto" class="fixed inset-0 z-[1050] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
    
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[95vh] flex flex-col overflow-hidden">
        
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas" :class="soloLectura ? 'fa-eye text-blue-600' : (editando ? 'fa-edit text-orange-600' : 'fa-plus text-green-600')"></i>
                {{ soloLectura ? ' Detalles del Cliente' : (editando ? ' Editar Cliente' : ' Nuevo Cliente') }}
            </h2>
            <button @click="cerrarModal" class="text-gray-400 hover:text-gray-600 text-2xl transition-colors">&times;</button>
        </div>

        <div class="p-6 overflow-y-auto">
            <form @submit.prevent="submit" id="formCliente" class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border">
                    <p v-if="form.errors.nombre" class="text-red-500 text-[11px] mt-1">{{ form.errors.nombre }}</p>
                </div>


                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Teléfono</label>
                    <input v-model="form.telefono" :disabled="soloLectura" type="text"
                        :class="{'border-red-500 ring-1 ring-red-500': form.errors.telefono, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border text-sm">
                    <p v-if="form.errors.telefono" class="text-red-500 text-[11px] mt-1">{{ form.errors.telefono }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Correo Electrónico</label>
                    <input v-model="form.correo" :disabled="soloLectura" type="email"
                        :class="{'border-red-500 ring-1 ring-red-500': form.errors.correo, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border text-sm">
                    <p v-if="form.errors.correo" class="text-red-500 text-[11px] mt-1">{{ form.errors.correo }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Cuenta Corriente (IBAN)</label>
                    <input v-model="form.cuenta_corriente" :disabled="soloLectura" type="text"
                        :class="{'border-red-500 ring-1 ring-red-500': form.errors.cuenta_corriente, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border text-sm">
                    <p v-if="form.errors.cuenta_corriente" class="text-red-500 text-[11px] mt-1">{{ form.errors.cuenta_corriente }}</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">País</label>
                    <select v-model="form.pais" :disabled="soloLectura"
                        :class="{'border-red-500 ring-1 ring-red-500': form.errors.pais, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border text-sm">
                        <option value="">Seleccionar...</option>
                        <option v-for="p in paises" :key="p.iso2" :value="p.iso2">{{ p.nombre }}</option>
                    </select>
                    <p v-if="form.errors.pais" class="text-red-500 text-[11px] mt-1">{{ form.errors.pais }}</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Cuota Mensual</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400">€</span>
                        <input v-model.number="form.importe_cuota_mensual" :disabled="soloLectura" type="number" step="0.01"
                            :class="{'border-red-500 ring-1 ring-red-500': form.errors.importe_cuota_mensual, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                            class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 pl-7 border text-sm">
                    </div>
                    <p v-if="form.errors.importe_cuota_mensual" class="text-red-500 text-[11px] mt-1 ">{{ form.errors.importe_cuota_mensual }}</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Fecha de Alta</label>
                    <input v-model="form.fecha_alta" :disabled="soloLectura" type="date"
                        :class="{'border-red-500 ring-1 ring-red-500': form.errors.fecha_alta, 'bg-gray-50 cursor-not-allowed': soloLectura}"
                        class="w-full border-gray-300 rounded-lg shadow-sm p-2.5 border text-sm">
                    <p v-if="form.errors.fecha_alta" class="text-red-500 text-[11px] mt-1">{{ form.errors.fecha_alta }}</p>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end gap-3">
            <button type="button" @click="cerrarModal" 
                class="px-5 py-2.5 rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition-all font-semibold shadow-sm">
                {{ soloLectura ? 'Cerrar' : 'Cancelar' }}
            </button>
            <button v-if="!soloLectura" type="submit" form="formCliente"
                class="px-5 py-2.5 rounded-lg text-white font-bold transition-all shadow-md flex items-center gap-2"
                :class="editando ? 'bg-orange-500 hover:bg-orange-600' : 'bg-blue-600 hover:bg-blue-700'"
                :disabled="form.processing">
                <i v-if="form.processing" class="fas fa-circle-notch animate-spin"></i>
                {{ editando ? 'Actualizar Cliente' : 'Guardar Cliente' }}
            </button>
        </div>
    </div>
</div>
<div v-if="confirmandoBorrado" 
     class="fixed inset-0 z-[1100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
    
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 border border-gray-200">
        <div class="flex items-center gap-4 mb-4">
            <div class="bg-red-100 p-3 rounded-full">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Confirmar eliminación</h3>
        </div>

        <p class="text-gray-600 mb-6">
            ¿Estás seguro de eliminar al cliente <strong>{{ clienteAEliminar?.nombre }}</strong>? 
            Esta acción no se puede deshacer.
        </p>

        <div class="flex justify-end gap-3">
            <button @click="cerrarConfirmacion" 
                class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition">
                Cancelar
            </button>
            <button @click="ejecutarBorrado" 
                class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-md shadow-sm transition">
                Sí, eliminar
            </button>
        </div>
    </div>
</div>
    </div>
</template>
