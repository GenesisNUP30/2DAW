@extends('layouts.plantilla01')

@section('titulo', 'Modificar Tarea')

@section('estilos')
<style>
    .error {
        color: red;
    }
</style>
@endsection

@section('cuerpo')
   <h1>Modificar Tarea</h1>

   <form action="{{ url('modificar/' . $id) }}" method="POST" enctype="multipart/form-data">
        <label>ID:</label><br>
        <input type="text" name="id" value="{{ $id }}" readonly><br><br>

        <label>NIF/CIF:</label><br>
        <input type="text" name="nifCif" value="{{ $nif_cif }}"><br>
        {!! \App\Models\Funciones::verErrores('nif_cif') !!}
        <br>

        <label>Persona de contacto:</label><br>
        <input type="text" name="personaNombre" value="{{ $persona_contacto }}"><br>
        {!! \App\Models\Funciones::verErrores('nombre_persona') !!}
        <br>

        <label>Teléfono:</label><br>
        <input type="text" name="telefono" value="{{ $telefono }}"><br>
        {!! \App\Models\Funciones::verErrores('telefono') !!}
        <br>

        <label>Correo electrónico:</label><br>
        <input type="text" name="correo" value="{{ $correo }}"><br>
        {!! \App\Models\Funciones::verErrores('correo') !!}
        <br>

        <label>Descripción de la tarea:</label><br>
        <textarea name="descripcion">{{ $descripcion }}</textarea><br>
        {!! \App\Models\Funciones::verErrores('descripcion') !!}
        <br>

        <label>Dirección:</label><br>
        <input type="text" name="direccionTarea" value="{{ $direccion }}"><br><br>

        <label>Población:</label><br>
        <input type="text" name="poblacion" value="{{ $poblacion }}"><br><br>

        <label>Código Postal:</label><br>
        <input type="text" name="codigoPostal" value="{{ $codigo_postal }}"><br><br>

        <label>Provincia:</label><br>
        <select name="provincia">
            <option value="">Seleccione provincia</option>
            <?php \App\Models\Funciones::mostrarProvincias($provincia) ?>
        </select><br>
        {!! \App\Models\Funciones::verErrores('provincia') !!}
        <br>

        <label>Estado:</label><br>
        <select name="estadoTarea">
            <option value="B" {{ $estado_tarea == "B" ? "selected" : "" }}>Esperando ser aprobada</option>
            <option value="P" {{ $estado_tarea == "P" ? "selected" : "" }}>Pendiente</option>
            <option value="R" {{ $estado_tarea == "R" ? "selected" : "" }}>Realizada</option>
            <option value="C" {{ $estado_tarea == "C" ? "selected" : "" }}>Cancelada</option>
        </select><br><br>

        <label>Operario encargado:</label><br>
        <select name="operarioEncargado">
            <option value="">Seleccione operario</option>
            <option value="Juan Pérez" {{ $operario_encargado == "Juan Pérez" ? "selected" : "" }}>Juan Pérez</option>
            <option value="María López" {{ $operario_encargado == "María López" ? "selected" : "" }}>María López</option>
            <option value="Carlos Ruiz" {{ $operario_encargado == "Carlos Ruiz" ? "selected" : "" }}>Carlos Ruiz</option>
            <option value="Ana María Fernández" {{ $operario_encargado == "Ana María Fernández" ? "selected" : "" }}>Ana María Fernández</option>
            <option value="Sara Martínez" {{ $operario_encargado == "Sara Martínez" ? "selected" : "" }}>Sara Martínez</option>
            <option value="Lucía Hurtado" {{ $operario_encargado == "Lucía Hurtado" ? "selected" : "" }}>Lucía Hurtado</option>
        </select><br><br>

        <label>Fecha de realización:</label><br>
        <input type="date" name="fechaRealizacion" value="{{ $fecha_realizacion }}"><br>
        {!! \App\Models\Funciones::verErrores('fechaRealizacion') !!}
        <br>

        <label for="anotacionesAnteriores">Anotaciones anteriores:</label><br>
        <textarea id="anotacionesAnteriores" name="anotacionesAnteriores">{{ $anotaciones_anteriores }}</textarea><br><br>

        <label for="fichero_resumen">Fichero resumen:</label>
        <input type="file" id="fichero_resumen" name="fichero_resumen"><br><br>

        <label for="fotos">Fotos del trabajo:</label>
        <input type="file" id="fotos" name="fotos[]" multiple><br><br>

        <a class="btn btn-cancel" href="{{ url('/') }}">Cancelar</a>
        <input type="submit" value="Actualizar tarea">
    </form>
@endsection