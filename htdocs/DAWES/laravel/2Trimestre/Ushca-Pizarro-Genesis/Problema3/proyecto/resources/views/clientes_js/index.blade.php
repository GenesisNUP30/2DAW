<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CRUD Clientes JS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body class="container mt-5">

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
                        <button type="submit" class="btn btn-primary">Guardar Cliente</button>
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
                            return `<button class="btn btn-danger btn-sm" onclick="eliminar(${data.id})">Borrar</button>`;
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

            // 2. Preparar datos
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch("{{ route('v2.clientes.store') }}", {
                    method: 'POST',
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
    </script>
</body>

</html>