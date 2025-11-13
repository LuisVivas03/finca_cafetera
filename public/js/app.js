// Funciones JavaScript para el sistema
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Formatear números como moneda
    window.formatCurrency = function(amount) {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        }).format(amount);
    };

    // Formatear fechas
    window.formatDate = function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-CO');
    };

    // Mostrar alertas
    window.showAlert = function(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const container = document.querySelector('.container');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
        }

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    };

    // Calcular total automáticamente en formularios
    const calcularTotales = function() {
        // Calcular total de venta
        const kilosVenta = document.getElementById('kilos-vendidos');
        const precioVenta = document.getElementById('precio-venta');
        const totalVenta = document.getElementById('total-venta');

        if (kilosVenta && precioVenta && totalVenta) {
            const calcularVenta = () => {
                const kilos = parseFloat(kilosVenta.value) || 0;
                const precio = parseFloat(precioVenta.value) || 0;
                totalVenta.value = (kilos * precio).toFixed(2);
            };

            kilosVenta.addEventListener('input', calcularVenta);
            precioVenta.addEventListener('input', calcularVenta);
        }

        // Calcular total de jornal
        const horasJornal = document.getElementById('horas-trabajadas');
        const tarifaJornal = document.getElementById('tarifa-hora');
        const totalJornal = document.getElementById('total-jornal');

        if (horasJornal && tarifaJornal && totalJornal) {
            const calcularJornal = () => {
                const horas = parseFloat(horasJornal.value) || 0;
                const tarifa = parseFloat(tarifaJornal.value) || 0;
                totalJornal.value = (horas * tarifa).toFixed(2);
            };

            horasJornal.addEventListener('input', calcularJornal);
            tarifaJornal.addEventListener('input', calcularJornal);
        }
    };

    calcularTotales();

    // Manejar envío de formularios con AJAX
    const forms = document.querySelectorAll('form[data-ajax="true"]');
    forms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const action = this.getAttribute('action') || window.location.href;
            
            try {
                const response = await fetch(action, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message, 'success');
                    if (result.redirect) {
                        setTimeout(() => {
                            window.location.href = result.redirect;
                        }, 1500);
                    } else {
                        this.reset();
                    }
                } else {
                    showAlert(result.message, 'danger');
                }
            } catch (error) {
                showAlert('Error al procesar la solicitud', 'danger');
                console.error('Error:', error);
            }
        });
    });
});

// Función para confirmar eliminaciones
window.confirmDelete = function(message = '¿Está seguro de que desea eliminar este registro?') {
    return confirm(message);
};

// Función para cargar datos dinámicamente
window.loadData = async function(url, containerId) {
    try {
        const response = await fetch(url);
        const data = await response.json();
        
        const container = document.getElementById(containerId);
        if (container) {
            container.innerHTML = data.html || data;
        }
        
        return data;
    } catch (error) {
        console.error('Error loading data:', error);
        showAlert('Error al cargar los datos', 'danger');
    }
};