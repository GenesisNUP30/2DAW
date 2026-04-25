<script setup>
import { ref } from "vue";
import { useForm, router, Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import Notification from "@/Components/Notification.vue";
import ConfirmDialog from "@/Components/ConfirmDialog.vue";
import ClienteModal from "@/Components/ClienteModal.vue";
import DataTable from "@/Components/DataTable.vue";

const props = defineProps({
    clientes: Object,
    paises: Array,
});

const cabeceras = ["Nombre", "CIF", "Teléfono", "País", "Cuota", "Acciones"];

const editando = ref(false);
const modalAbierto = ref(false);
const soloLectura = ref(false);
const confirmandoBorrado = ref(false);
const clienteAEliminar = ref(null);

const mensajeFlash = ref(null);
const tipoFlash = ref("success");

const mostrarNotificacion = (msg, tipo = "success") => {
    mensajeFlash.value = msg;
    tipoFlash.value = tipo;

    // Desaparece automáticamente a los 3 segundos
    setTimeout(() => {
        mensajeFlash.value = null;
    }, 3000);
};

const form = useForm({
    id: null,
    nombre: "",
    cif: "",
    telefono: "",
    correo: "",
    cuenta_corriente: "",
    pais: "",
    fecha_alta: "",
    importe_cuota_mensual: 0,
});

const validarFormulario = () => {
    form.clearErrors(); // Limpiamos errores previos
    let valido = true;

    // Validación manual lado cliente
    const campos = [
        "nombre",
        "cif",
        "telefono",
        "correo",
        "cuenta_corriente",
        "pais",
        "fecha_alta",
    ];
    campos.forEach((campo) => {
        if (!form[campo]) {
            form.setError(
                campo,
                `El campo ${campo.replace("_", " ")} es obligatorio`,
            );
            valido = false;
        }
    });

    if (form.importe_cuota_mensual <= 0) {
        form.setError("importe_cuota_mensual", "La cuota debe ser mayor a 0");
        valido = false;
    }

    return valido;
};

const submit = () => {
    if (editando.value) {
        form.put(`/v3/clientes/${form.id}`, {
            onSuccess: () => {
                cerrarModal();
                mostrarNotificacion(
                    "El cliente se ha actualizado correctamente",
                );
            },
            onError: () =>
                mostrarNotificacion(
                    "Error al intentar realizar la operación",
                    "error",
                ),
        });
    } else {
        form.post("/v3/clientes", {
            onSuccess: () => {
                cerrarModal();
                mostrarNotificacion("El cliente se ha creado correctamente");
            },
            onError: () =>
                mostrarNotificacion(
                    "Error al intentar realizar la operación",
                    "error",
                ),
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
    form.fecha_alta = cliente.fecha_alta
        ? cliente.fecha_alta.split("T")[0]
        : "";
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
            mostrarNotificacion("El cliente se ha eliminado correctamente");
        },
        onError: () => {
            cerrarConfirmacion();
            mostrarNotificacion(
                "Error al intentar realizar la operación",
                "error",
            );
        },
    });
};
</script>

<template>
    <div class="p-8 bg-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <a
                    href="/clientes"
                    class="flex items-center gap-2 text-gray-600 hover:text-blue-600 font-bold transition-colors px-3 py-2 rounded-lg hover:bg-blue-50"
                >
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver a la App</span>
                </a>
                <h1 class="text-3xl font-bold text-gray-800">
                    Clientes con Inertia + Tailwind
                </h1>
                <button
                    @click="abrirCrear"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md transition-all"
                >
                    <i class="fas fa-plus mr-2"></i>Nuevo Cliente
                </button>
            </div>

            <DataTable :headers="cabeceras" :items="clientes.data">
                <template #row="{ item: cliente }">
                    <td class="px-5 py-4 text-sm">{{ cliente.nombre }}</td>
                    <td class="px-5 py-4 text-sm">{{ cliente.cif }}</td>
                    <td class="px-5 py-4 text-sm">{{ cliente.telefono }}</td>
                    <td class="px-5 py-4 text-sm">
                        {{ cliente.pais_relacion?.nombre }}
                    </td>
                    <td class="px-5 py-4 text-sm">
                        {{ Number(cliente.importe_cuota_mensual).toFixed(2) }}
                        {{ cliente.moneda }}
                    </td>
                    <td class="px-5 py-4 text-sm text-center font-medium">
                        <button
                            @click="abrirVer(cliente)"
                            class="text-blue-600 hover:text-blue-900 mr-3"
                        >
                            <i class="fas fa-eye"></i> Ver
                        </button>
                        <button
                            @click="abrirEditar(cliente)"
                            class="text-orange-600 hover:text-orange-900 mr-3"
                        >
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button
                            @click="abrirConfirmacion(cliente)"
                            class="text-red-600 hover:text-red-900"
                        >
                            <i class="fas fa-trash"></i> Borrar
                        </button>
                    </td>
                </template>

                <template #pagination>
                    <Pagination :links="clientes.links" :meta="clientes" />
                </template>
            </DataTable>
        </div>

        <Notification
            :mensaje="mensajeFlash"
            :tipo="tipoFlash"
            @close="mensajeFlash = null"
        />

        <ClienteModal
            :show="modalAbierto"
            :editando="editando"
            :soloLectura="soloLectura"
            :form="form"
            :paises="paises"
            @close="cerrarModal"
            @submit="submit"
        />

        <ConfirmDialog
            :show="confirmandoBorrado"
            title="¿Eliminar Cliente?"
            :message="`¿Estás seguro de eliminar al cliente ${clienteAEliminar?.nombre}?`"
            confirmLabel="Sí, eliminar todo"
            @close="cerrarConfirmacion"
            @confirm="ejecutarBorrado"
        />
    </div>
</template>
