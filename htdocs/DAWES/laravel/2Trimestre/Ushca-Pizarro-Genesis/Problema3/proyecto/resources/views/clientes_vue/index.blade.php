<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/quasar@2.14.2/dist/quasar.prod.css" rel="stylesheet" type="text/css">
    <title>CRUD Clientes Vue</title>
</head>

<body>
    <div id="q-app">
        <q-layout view="lHh Lpr lFf" container style="height: 90vh" class="shadow-2 rounded-borders">
            <q-page-container>
                <q-page padding>
                    <div class="text-h4 q-mb-md">Gestión de Clientes (Vue + Quasar)</div>
                    <q-btn label="Nuevo Cliente" color="primary" @click="abrirModalCrear" icon="add" class="q-mb-md"></q-btn>

                    <q-table
                        title="Clientes"
                        :rows="clientes"
                        :columns="columns"
                        row-key="id"
                        :loading="loading">
                        <template v-slot:body-cell-acciones="props">
                            <q-td :props="props" class="q-gutter-x-sm">
                                <q-btn flat round color="blue" icon="visibility" @click="verDetalles(props.row)">
                                    <q-tooltip>Ver</q-tooltip>
                                </q-btn>
                                <q-btn flat round color="orange" icon="edit" @click="prepararEdicion(props.row)">
                                    <q-tooltip>Editar</q-tooltip>
                                </q-btn>
                                <q-btn flat round color="red" icon="delete" @click="confirmarEliminar(props.row.id)">
                                    <q-tooltip>Borrar</q-tooltip>
                                </q-btn>
                            </q-td>
                        </template>
                    </q-table>
                    <q-space></q-space>
                    <q-btn
                        flat
                        color="secondary"
                        icon="arrow_back"
                        label="Volver a la App"
                        href="{{ route('clientes.index') }}"></q-btn>

                    <q-dialog v-model="modalAbierto" persistent>
                        <q-card style="min-width: 600px; max-width: 800px;">
                            <q-card-section class="row items-center q-pb-none">
                                <div class="text-h6">@{{ tituloModal }}</div>
                                <q-btn icon="close" flat round dense v-close-popup @click="limpiarFormulario"></q-btn>
                            </q-card-section>

                            <q-card-section class="q-pa-md">
                                <q-form class="row q-col-gutter-md">
                                    <div class="col-12 col-md-4">
                                        <q-input v-model="form.cif" label="CIF" filled :disable="soloLectura"
                                            :error="!!errors.cif" :error-message="errors.cif"></q-input>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <q-input v-model="form.nombre" label="Nombre Completo" filled :disable="soloLectura"
                                            :error="!!errors.nombre" :error-message="errors.nombre"></q-input>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <q-input v-model="form.telefono" label="Teléfono" filled :disable="soloLectura"
                                            :error="!!errors.telefono" :error-message="errors.telefono"></q-input>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <q-input v-model="form.correo" label="Correo Electrónico" filled :disable="soloLectura"
                                            :error="!!errors.correo" :error-message="errors.correo"></q-input>
                                    </div>
                                    <div class="col-12">
                                        <q-input v-model="form.cuenta_corriente" label="Cuenta Corriente" filled :disable="soloLectura"
                                            :error="!!errors.cuenta_corriente" :error-message="errors.cuenta_corriente"></q-input>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <q-select v-model="form.pais" :options="paisesOpciones" label="País" filled
                                            emit-value map-options option-value="iso2" option-label="nombre"
                                            :disable="soloLectura" :error="!!errors.pais" :error-message="errors.pais"></q-select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <q-input v-model.number="form.importe_cuota_mensual" label="Cuota Mensual" filled type="number" step="0.01"
                                            :disable="soloLectura" :error="!!errors.importe_cuota_mensual" :error-message="errors.importe_cuota_mensual">
                                            <template v-slot:append></template>
                                        </q-input>
                                    </div>
                                    <div class="col-12">
                                        <q-input v-model="form.fecha_alta" label="Fecha de Alta" filled type="date" :disable="soloLectura"
                                            :error="!!errors.fecha_alta" :error-message="errors.fecha_alta"></q-input>
                                    </div>
                                </q-form>
                            </q-card-section>

                            <q-card-actions align="right" class="q-pa-md">
                                <q-btn flat label="Cancelar" color="grey" v-close-popup @click="limpiarFormulario"></q-btn>
                                <q-btn v-if="!soloLectura" :label="editando ? 'Actualizar' : 'Guardar'"
                                    color="primary" @click="guardarCliente" :loading="loadingGuardar"></q-btn>
                            </q-card-actions>
                        </q-card>
                    </q-dialog>
                </q-page>
            </q-page-container>
        </q-layout>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quasar@2.14.2/dist/quasar.umd.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const {
            createApp,
            ref,
            onMounted,
            computed
        } = Vue;

        createApp({
            setup() {
                const clientes = ref([]);
                const loading = ref(false);
                const loadingGuardar = ref(false);
                const modalAbierto = ref(false);
                const editando = ref(false);
                const soloLectura = ref(false);
                const errors = ref({});
                const paisesOpciones = JSON.parse('@json($paises)');

                // Definir el estado inicial limpio
                const formInicial = {
                    id: null,
                    cif: '',
                    nombre: '',
                    telefono: '',
                    correo: '',
                    cuenta_corriente: '',
                    pais: '',
                    importe_cuota_mensual: 0,
                    fecha_alta: new Date().toISOString().split('T')[0]
                };

                const form = ref({
                    ...formInicial
                });

                const tituloModal = computed(() => {
                    if (soloLectura.value) return 'Detalles del Cliente';
                    return editando.value ? 'Editar Cliente' : 'Nuevo Cliente';
                });

                const obtenerClientes = async () => {
                    loading.value = true;
                    try {
                        const res = await axios.get("{{ route('v2.clientes.listado') }}");
                        clientes.value = res.data;
                    } finally {
                        loading.value = false;
                    }
                };

                const guardarCliente = async () => {
                    loadingGuardar.value = true;
                    errors.value = {};
                    const url = editando.value ? `api/clientes/${form.value.id}` : `api/clientes`;
                    const metodo = editando.value ? 'put' : 'post';

                    const mensajeExito = editando.value ?
                        'Cliente actualizado correctamente' :
                        'Cliente creado correctamente';

                    try {
                        await axios({
                            method: metodo,
                            url: url,
                            data: form.value,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        Quasar.Notify.create({
                            message: mensajeExito,
                            color: 'positive',
                            icon: 'check_circle'
                        });
                        modalAbierto.value = false;
                        obtenerClientes();
                    } catch (e) {
                        if (e.response && e.response.status === 422) {
                            const laravelErrors = e.response.data.errors;
                            Object.keys(laravelErrors).forEach(key => {
                                errors.value[key] = laravelErrors[key][0];
                            });
                        } else {
                            Quasar.Notify.create({
                                message: 'Error al realizar la operación',
                                color: 'negative'
                            });
                        }
                    } finally {
                        loadingGuardar.value = false;
                    }
                };

                const prepararEdicion = (cliente) => {
                    errors.value = {};
                    editando.value = true;
                    soloLectura.value = false;
                    const fecha = cliente.fecha_alta ? cliente.fecha_alta.split('T')[0] : '';
                    form.value = {
                        ...cliente,
                        fecha_alta: fecha
                    };
                    modalAbierto.value = true;
                };

                const verDetalles = (cliente) => {
                    prepararEdicion(cliente);
                    soloLectura.value = true;
                };

                const limpiarFormulario = () => {
                    form.value = {
                        ...formInicial
                    };
                    errors.value = {};
                };

                const confirmarEliminar = (id) => {
                    Quasar.Dialog.create({
                        title: 'Confirmar',
                        message: '¿Deseas eliminar este cliente? Esta acción no se puede deshacer.',
                        ok: {
                            label: 'Sí, eliminar',
                            color: 'negative',
                            unelevated: true
                        },
                        cancel: {
                            label: 'Cancelar',
                            color: 'grey-7',
                            flat: true
                        },
                        persistent: true
                    }).onOk(async () => {
                        try {
                            await axios.delete(`api/clientes/${id}`, {
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            obtenerClientes();
                            Quasar.Notify.create({
                                message: 'Cliente eliminado correctamente',
                                color: 'negative',
                                icon: 'delete',
                            });
                        } catch (e) {
                            Quasar.Notify.create({
                                message: 'Error al intentar eliminar el cliente',
                                color: 'warning'
                            });
                        }
                    });
                };

                onMounted(obtenerClientes);

                return {
                    clientes,
                    loading,
                    modalAbierto,
                    form,
                    editando,
                    errors,
                    paisesOpciones,
                    soloLectura,
                    tituloModal,
                    loadingGuardar,
                    guardarCliente,
                    prepararEdicion,
                    verDetalles,
                    limpiarFormulario,
                    confirmarEliminar,
                    abrirModalCrear: () => {
                        limpiarFormulario();
                        editando.value = false;
                        soloLectura.value = false;
                        modalAbierto.value = true;
                    },
                    columns: [{
                            name: 'cif',
                            label: 'CIF',
                            field: 'cif',
                            align: 'left',
                            sortable: true
                        },
                        {
                            name: 'nombre',
                            label: 'Nombre',
                            field: 'nombre',
                            align: 'left',
                            sortable: true
                        },
                        {
                            name: 'email',
                            label: 'Correo',
                            field: 'correo',
                            align: 'left',
                            sortable: true
                        },
                        {
                            name: 'pais',
                            label: 'País',
                            field: row => row.pais_relacion ? row.pais_relacion.nombre : row.pais,
                            align: 'left',
                            sortable: true
                        },
                        {
                            name: 'cuotas.importe',
                            label: 'Cuota Mensual',
                            field: 'importe_cuota_mensual',
                            format: val => val ? `${val.toFixed(2)} €` : '0.00 €',
                            align: 'left',
                            sortable: true
                        },
                        {
                            name: 'acciones',
                            label: 'Acciones',
                            align: 'center'
                        }
                    ]
                }
            }
        }).use(Quasar, {
            config: {
                notify: {},
                dialog: {}
            }
        }).mount('#q-app');
    </script>
</body>

</html>