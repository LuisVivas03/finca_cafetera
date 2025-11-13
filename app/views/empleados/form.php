<?php
// Formulario para crear/editar clientes (se incluye en cliente_form.php)
?>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Tipo de Entidad *</label>
            <select class="form-select" name="tipo_entidad" id="tipoEntidad" required>
                <option value="">Seleccionar...</option>
                <option value="persona" <?= ($cliente['tipo_entidad'] ?? '') == 'persona' ? 'selected' : '' ?>>Persona Natural</option>
                <option value="empresa" <?= ($cliente['tipo_entidad'] ?? '') == 'empresa' ? 'selected' : '' ?>>Empresa</option>
            </select>
        </div>
    </div>
</div>

<!-- Campos para Persona Natural -->
<div id="camposPersona" class="row" style="display: <?= ($cliente['tipo_entidad'] ?? '') == 'persona' ? 'block' : 'none' ?>;">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Tipo de Identificación *</label>
            <select class="form-select" name="tipo_identificacion">
                <option value="">Seleccionar...</option>
                <option value="CC" <?= ($cliente['tipo_identificacion'] ?? '') == 'CC' ? 'selected' : '' ?>>Cédula de Ciudadanía</option>
                <option value="CE" <?= ($cliente['tipo_identificacion'] ?? '') == 'CE' ? 'selected' : '' ?>>Cédula de Extranjería</option>
                <option value="PAS" <?= ($cliente['tipo_identificacion'] ?? '') == 'PAS' ? 'selected' : '' ?>>Pasaporte</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Número de Identificación *</label>
            <input type="text" class="form-control" name="numero_identificacion" 
                   value="<?= $cliente['numero_identificacion'] ?? '' ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Nombres *</label>
            <input type="text" class="form-control" name="nombres" 
                   value="<?= $cliente['nombres'] ?? '' ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Apellidos *</label>
            <input type="text" class="form-control" name="apellidos" 
                   value="<?= $cliente['apellidos'] ?? '' ?>">
        </div>
    </div>
</div>

<!-- Campos para Empresa -->
<div id="camposEmpresa" class="row" style="display: <?= ($cliente['tipo_entidad'] ?? '') == 'empresa' ? 'block' : 'none' ?>;">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">NIT *</label>
            <input type="text" class="form-control" name="numero_identificacion" 
                   value="<?= $cliente['numero_identificacion'] ?? '' ?>" 
                   placeholder="Ej: 900123456-7">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Razón Social *</label>
            <input type="text" class="form-control" name="razon_social" 
                   value="<?= $cliente['razon_social'] ?? '' ?>">
        </div>
    </div>
</div>

<!-- Campos comunes -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" class="form-control" name="telefono" 
                   value="<?= $cliente['telefono'] ?? '' ?>">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" 
                   value="<?= $cliente['email'] ?? '' ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" class="form-control" name="direccion" 
                   value="<?= $cliente['direccion'] ?? '' ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Municipio</label>
            <input type="text" class="form-control" name="municipio" 
                   value="<?= $cliente['municipio'] ?? '' ?>">
        </div>
    </div>
</div>

<script>
document.getElementById('tipoEntidad').addEventListener('change', function() {
    const tipo = this.value;
    document.getElementById('camposPersona').style.display = tipo === 'persona' ? 'block' : 'none';
    document.getElementById('camposEmpresa').style.display = tipo === 'empresa' ? 'block' : 'none';
});
</script>