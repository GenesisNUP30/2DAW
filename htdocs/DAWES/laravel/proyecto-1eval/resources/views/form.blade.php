<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <label class="form-label">NIF/CIF:</label>
        <input type="text" name="nif_cif" class="form-control" value="{{ $nif_cif ?? '' }}">
        {!! \App\Models\Funciones::verErrores('nif_cif') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Persona de contacto:</label>
        <input type="text" name="persona_contacto" class="form-control" value="{{ $persona_contacto ?? '' }}">
        {!! \App\Models\Funciones::verErrores('persona_contacto') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Teléfono:</label>
        <input type="text" name="telefono" class="form-control" value="{{ $telefono ?? '' }}">
        {!! \App\Models\Funciones::verErrores('telefono') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Correo electrónico:</label>
        <input type="text" name="correo" class="form-control" value="{{ $correo ?? '' }}">
        {!! \App\Models\Funciones::verErrores('correo') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Descripción de la tarea:</label>
        <textarea name="descripcion" class="form-control">{{ $descripcion ?? '' }}</textarea>
        {!! \App\Models\Funciones::verErrores('descripcion') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Dirección:</label>
        <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($direccion) ?>">
    </div>

    <div class="form-row">
        <label class="form-label">Población:</label>
        <input type="text" name="poblacion" class="form-control" value="<?= htmlspecialchars($poblacion) ?>"> 
    </div>

    <div class="form-row">
        <label class="form-label">Código Postal:</label>
        <input type="text" name="codigo_postal" class="form-control" value="{{ $codigo_postal ?? '' }}">
    </div>

    <div class="form-row">
        <label class="form-label">Provincia:</label>
        <select name="provincia" class="form-select">
            <option value="">Seleccione provincia</option>
            {!! \App\Models\Funciones::mostrarProvincias($provincia ?? '') !!}
        </select>
        {!! \App\Models\Funciones::verErrores('provincia') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Estado:</label>
        <select name="estado" class="form-select">
            <option value="B" {{ ($estado ?? '') == "B" ? "selected" : "" }}>Esperando ser aprobada</option>
            <option value="P" {{ ($estado ?? '') == "P" ? "selected" : "" }}>Pendiente</option>
            <option value="R" {{ ($estado ?? '') == "R" ? "selected" : "" }}>Realizada</option>
            <option value="C" {{ ($estado ?? '') == "C" ? "selected" : "" }}>Cancelada</option>
        </select>
    </div>

    <div class="form-row">
        <label class="form-label">Operario encargado:</label>
        <select name="operario_encargado" class="form-select">
            <option value="">Seleccione operario</option>
            <option value="Juan Pérez" {{ ($operario_encargado ?? '') == "Juan Pérez" ? "selected" : "" }}>Juan Pérez</option>
            <option value="María López" {{ ($operario_encargado ?? '') == "María López" ? "selected" : "" }}>María López</option>
            <option value="Carlos Ruiz" {{ ($operario_encargado ?? '') == "Carlos Ruiz" ? "selected" : "" }}>Carlos Ruiz</option>
            <option value="Ana María Fernández" {{ ($operario_encargado ?? '') == "Ana María Fernández" ? "selected" : "" }}>Ana María Fernández</option>
            <option value="Sara Martínez" {{ ($operario_encargado ?? '') == "Sara Martínez" ? "selected" : "" }}>Sara Martínez</option>
            <option value="Lucía Hurtado" {{ ($operario_encargado ?? '') == "Lucía Hurtado" ? "selected" : "" }}>Lucía Hurtado</option>
        </select>
    </div>

    <div class="form-row">
        <label class="form-label">Fecha de realización:</label>
        <input type="date" name="fecha_realizacion" class="form-control" value="{{ $fecha_realizacion ?? '' }}">
        {!! \App\Models\Funciones::verErrores('fecha_realizacion') !!}
    </div>

    <div class="form-row">
        <label class="form-label">Anotaciones:</label>
        <textarea id="anotaciones" name="anotaciones" class="form-control">{{ $anotaciones ?? '' }}</textarea>
    </div>

    <div class="mt-4">
        <a href="{!! url('/') !!}" class="btn-cancel">
            <i class="fas fa-times me-1"></i>Cancelar
        </a>
        <button type="submit" class="btn-submit">
            <i class="fas fa-save me-1"></i>Crear tarea
        </button>
    </div>
</form>