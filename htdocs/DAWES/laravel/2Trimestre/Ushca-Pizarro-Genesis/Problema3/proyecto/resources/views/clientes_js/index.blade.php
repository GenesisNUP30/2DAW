@extends('layouts.app')
@section('content')
<title>CRUD Clientes JS</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<h2>Gestión de Clientes (JS + DataTable)</h2>

<button class="btn btn-primary mb-3" onclick="mostrarModal()">Nuevo Cliente</button>

<table id="tablaClientes" class="table table-striped">
    <thead>
        <tr>
            <th>CIF</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>País</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCliente">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="cliente_id">
                    <div class="mb-3">
                        <label class="form-label">CIF</label>
                        <input type="text" name="cif" id="cif" class="form-control">
                        <div class="invalid-feedback" id="error-cif"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control">
                        <div class="invalid-feedback" id="error-nombre"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control">
                            <div class="invalid-feedback" id="error-telefono"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" id="correo" class="form-control">
                            <div class="invalid-feedback" id="error-correo"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cuenta Corriente</label>
                        <input type="text" name="cuenta_corriente" id="cuenta_corriente" class="form-control">
                        <div class="invalid-feedback" id="error-cuenta_corriente"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">País</label>
                            <select name="pais" id="pais" class="form-select">
                                <option value="">Seleccione...</option>
                                @foreach($paises as $p)
                                <option value="{{ $p->iso2 }}">{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-pais"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cuota Mensual</label>
                            <input type="number" step="0.01" name="importe_cuota_mensual" id="importe_cuota_mensual" class="form-control">
                            <div class="invalid-feedback" id="error-importe_cuota_mensual"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha Alta</label>
                        <input type="date" name="fecha_alta" id="fecha_alta" class="form-control" value="{{ date('Y-m-d') }}">
                        <div class="invalid-feedback" id="error-fecha_alta"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnGuardar" class="btn btn-primary">Guardar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#tablaClientes').DataTable({
            ajax: {
                url: "{{ route('v2.clientes.listado') }}",
                dataSrc: ''
            },
            columns: [{
                    data: 'cif'
                },
                {
                    data: 'nombre'
                },
                {
                    data: 'correo'
                },
                {
                    data: 'pais_relacion.nombre'
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <button class="btn btn-info btn-sm" onclick="verDetalles(${data.id})">Ver</button>
                            <button class="btn btn-warning btn-sm" onclick="editarCliente(${data.id})">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminar(${data.id})">Borrar</button>
                            `;
                    }
                }
            ]
        });
    });

    const modalBS = new bootstrap.Modal(document.getElementById('modalCliente'));
    const form = document.getElementById('formCliente');

    // Función para abrir el modal
    function mostrarModal() {
        form.reset();
        document.getElementById('cliente_id').value = ""; // Limpiar ID para que sea POST
        document.querySelector('.modal-title').innerText = "Nuevo Cliente";
        document.getElementById('btnGuardar').style.display = "block";
        bloquearCampos(false);
        limpiarErrores();
        modalBS.show();
    }

    // Escuchar el envío del formulario
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        limpiarErrores();

        let erroresjs = false;

        const inputs = form.querySelectorAll('input, select');

        inputs.forEach(input => {
            if (input.name !== 'fecha_alta' && input.value.trim() === "") {
                mostrarError(input.name, `El campo ${input.name} es obligatorio`);
                erroresjs = true;
            }
        });

        // Determinar si es CREAR o EDITAR
        const id = document.getElementById('cliente_id').value;
        const url = id ? `api/clientes/${id}` : `api/clientes`;
        const metodo = id ? 'PUT' : 'POST';

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(url, {
                method: metodo,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.status === 422) {
                // Integración con validación del servidor
                Object.keys(result.errors).forEach(field => {
                    mostrarError(field, result.errors[field][0]);
                });
            } else if (response.ok) {
                Swal.fire('¡Éxito!', 'Cliente guardado correctamente', 'success');
                modalBS.hide();
                $('#tablaClientes').DataTable().ajax.reload(); // Recarga la tabla sin refrescar página
            }
        } catch (error) {
            console.error("Error en la petición:", error);
        }
    });

    function mostrarError(campo, mensaje) {
        const input = document.getElementById(campo);
        const divError = document.getElementById(`error-${campo}`);
        if (input) {
            input.classList.add('is-invalid');
            divError.innerText = mensaje;
        }
    }

    function limpiarErrores() {
        document.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');
    }

    // 1. VER DETALLES
    async function verDetalles(id) {
        limpiarErrores();
        form.reset();
        try {
            const response = await fetch(`api/clientes/${id}`);
            const cliente = await response.json();

            // Rellenar campos
            document.getElementById('cliente_id').value = cliente.id;
            document.getElementById('cif').value = cliente.cif;
            document.getElementById('nombre').value = cliente.nombre;
            document.getElementById('telefono').value = cliente.telefono;
            document.getElementById('correo').value = cliente.correo;
            document.getElementById('cuenta_corriente').value = cliente.cuenta_corriente;
            document.getElementById('pais').value = cliente.pais;
            document.getElementById('importe_cuota_mensual').value = cliente.importe_cuota_mensual;
            if (cliente.fecha_alta) {
                const fechaLimpia = cliente.fecha_alta.split('T')[0];
                document.getElementById('fecha_alta').value = fechaLimpia;
            }

            // Configurar Modal para modo "VER"
            document.querySelector('.modal-title').innerText = "Detalles del Cliente";
            document.getElementById('btnGuardar').style.display = "none"; // Escondemos el botón de guardar
            bloquearCampos(true);

            modalBS.show();
        } catch (error) {
            Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
        }
    }

    // 2. PREPARAR EDICIÓN
    async function editarCliente(id) {
        await verDetalles(id); // Reutilizamos la carga de datos
        document.querySelector('.modal-title').innerText = "Editar Cliente";
        document.getElementById('btnGuardar').style.display = "block"; // Mostramos el botón
        bloquearCampos(false); // Habilitamos para editar
    }

    function bloquearCampos(estado) {
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => input.disabled = estado);
    }

    async function eliminar(id) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, borrar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`api/clientes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    Swal.fire('Eliminado', 'El cliente ha sido borrado', 'success');
                    $('#tablaClientes').DataTable().ajax.reload();
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo eliminar', 'error');
            }
        }
    }
</script>
@endsection