<?php
/**
 * Formulario para crear/editar clientes
 * Se incluye en cliente_form.php
 */
?>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Tipo de Entidad *</label>
            <select class="form-select" name="tipo_entidad" id="tipoEntidad" required>
                <option value="">Seleccionar...</option>
                <option value="persona" <?= (isset($cliente['tipo_entidad']) && $cliente['tipo_entidad'] == 'persona') ? 'selected' : '' ?>>Persona Natural</option>
                <option value="empresa" <?= (isset($cliente['tipo_entidad']) && $cliente['tipo_entidad'] == 'empresa') ? 'selected' : '' ?>>Empresa</option>
            </select>
        </div>
    </div>
</div>

<!-- Campos para Persona Natural -->
<div id="camposPersona" class="row" style="display: <?= (isset($cliente['tipo_entidad']) && $cliente['tipo_entidad'] == 'persona') ? 'block' : 'none'; ?>;">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Tipo de Identificación *</label>
            <select class="form-select" name="tipo_identificacion" required>
                <option value="">Seleccionar...</option>
                <option value="CC" <?= (isset($cliente['tipo_identificacion']) && $cliente['tipo_identificacion'] == 'CC') ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                <option value="CE" <?= (isset($cliente['tipo_identificacion']) && $cliente['tipo_identificacion'] == 'CE') ? 'selected' : '' ?>>Cédula de Extranjería</option>
                <option value="PAS" <?= (isset($cliente['tipo_identificacion']) && $cliente['tipo_identificacion'] == 'PAS') ? 'selected' : '' ?>>Pasaporte</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Número de Identificación *</label>
            <input type="text" class="form-control" name="numero_identificacion" 
                   value="<?= $cliente['numero_identificacion'] ?? '' ?>" required
                   placeholder="Ej: 123456789">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Nombres *</label>
            <input type="text" class="form-control" name="nombres" 
                   value="<?= $cliente['nombres'] ?? '' ?>" required
                   placeholder="Ej: Juan Carlos">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Apellidos *</label>
            <input type="text" class="form-control" name="apellidos" 
                   value="<?= $cliente['apellidos'] ?? '' ?>" required
                   placeholder="Ej: Pérez Gómez">
        </div>
    </div>
</div>

<!-- Campos para Empresa -->
<div id="camposEmpresa" class="row" style="display: <?= (isset($cliente['tipo_entidad']) && $cliente['tipo_entidad'] == 'empresa') ? 'block' : 'none'; ?>;">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">NIT *</label>
            <input type="text" class="form-control" name="numero_identificacion" 
                   value="<?= $cliente['numero_identificacion'] ?? '' ?>" required
                   placeholder="Ej: 900123456-7">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Razón Social *</label>
            <input type="text" class="form-control" name="razon_social" 
                   value="<?= $cliente['razon_social'] ?? '' ?>" required
                   placeholder="Ej: Café Especial S.A.S.">
        </div>
    </div>
</div>

<!-- Campos comunes -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" class="form-control" name="telefono" 
                   value="<?= $cliente['telefono'] ?? '' ?>"
                   placeholder="Ej: 3001234567">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" 
                   value="<?= $cliente['email'] ?? '' ?>"
                   placeholder="Ej: cliente@empresa.com">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" class="form-control" name="direccion" 
                   value="<?= $cliente['direccion'] ?? '' ?>"
                   placeholder="Ej: Calle 123 #45-67">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Municipio</label>
            <input type="text" class="form-control" name="municipio" 
                   value="<?= $cliente['municipio'] ?? '' ?>"
                   placeholder="Ej: Popayán">
        </div>
    </div>
</div>

<script>
// Mostrar/ocultar campos según tipo de entidad
document.getElementById('tipoEntidad').addEventListener('change', function() {
    const tipo = this.value;
    
    // Ocultar todos los campos primero
    document.getElementById('camposPersona').style.display = 'none';
    document.getElementById('camposEmpresa').style.display = 'none';
    
    // Mostrar campos correspondientes
    if (tipo === 'persona') {
        document.getElementById('camposPersona').style.display = 'block';
    } else if (tipo === 'empresa') {
        document.getElementById('camposEmpresa').style.display = 'block';
    }
    
    // Actualizar required attributes
    updateRequiredFields(tipo);
});

function updateRequiredFields(tipo) {
    const personaFields = document.querySelectorAll('#camposPersona [required]');
    const empresaFields = document.querySelectorAll('#camposEmpresa [required]');
    
    if (tipo === 'persona') {
        personaFields.forEach(field => field.required = true);
        empresaFields.forEach(field => field.required = false);
    } else if (tipo === 'empresa') {
        personaFields.forEach(field => field.required = false);
        empresaFields.forEach(field => field.required = true);
    } else {
        personaFields.forEach(field => field.required = false);
        empresaFields.forEach(field => field.required = false);
    }
}

// Inicializar campos requeridos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const tipoInicial = document.getElementById('tipoEntidad').value;
    updateRequiredFields(tipoInicial);
});
</script>