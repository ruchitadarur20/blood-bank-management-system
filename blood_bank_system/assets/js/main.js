// Main JavaScript file for Blood Bank Management System

// Auto-refresh dashboard data every 5 minutes
function refreshDashboard() {
    if (window.location.pathname.endsWith('index.php')) {
        window.location.reload();
    }
}

// Set up auto-refresh
setInterval(refreshDashboard, 300000);

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Date validation for blood inventory
function validateDates() {
    const collectionDate = document.getElementById('collection_date');
    const expiryDate = document.getElementById('expiry_date');

    if (collectionDate && expiryDate) {
        const collection = new Date(collectionDate.value);
        const expiry = new Date(expiryDate.value);

        if (expiry <= collection) {
            expiryDate.setCustomValidity('Expiry date must be after collection date');
        } else {
            expiryDate.setCustomValidity('');
        }
    }
}

// Blood type compatibility checker
function checkCompatibility(donorType, recipientType) {
    const compatibility = {
        'O+': ['O+', 'A+', 'B+', 'AB+'],
        'O-': ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'],
        'A+': ['A+', 'AB+'],
        'A-': ['A+', 'A-', 'AB+', 'AB-'],
        'B+': ['B+', 'AB+'],
        'B-': ['B+', 'B-', 'AB+', 'AB-'],
        'AB+': ['AB+'],
        'AB-': ['AB+', 'AB-']
    };

    return compatibility[donorType].includes(recipientType);
}

// Search functionality
function searchTable(tableId, searchInput) {
    const table = document.getElementById(tableId);
    const searchText = searchInput.value.toLowerCase();
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell.textContent.toLowerCase().includes(searchText)) {
                found = true;
                break;
            }
        }

        row.style.display = found ? '' : 'none';
    }
}

// Export table to CSV
function exportToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    let csv = [];
    const rows = table.querySelectorAll('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cols = row.querySelectorAll('td,th');
        const csvRow = [];

        for (let j = 0; j < cols.length; j++) {
            csvRow.push(cols[j].innerText);
        }

        csv.push(csvRow.join(','));
    }

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize date validation listeners
    const collectionDate = document.getElementById('collection_date');
    const expiryDate = document.getElementById('expiry_date');

    if (collectionDate && expiryDate) {
        collectionDate.addEventListener('change', validateDates);
        expiryDate.addEventListener('change', validateDates);
    }
});

// AJAX form submission
function submitFormAjax(formId, successCallback) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!validateForm(formId)) {
            return;
        }

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (successCallback) successCallback(data);
                    showAlert('success', data.message || 'Operation completed successfully');
                } else {
                    showAlert('danger', data.message || 'An error occurred');
                }
            })
            .catch(error => {
                showAlert('danger', 'An error occurred while processing your request');
            });
    });
}

// Alert notification system
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Blood inventory level warning
function checkInventoryLevels() {
    const lowStockThreshold = 10;
    const inventoryCards = document.querySelectorAll('.inventory-card');

    inventoryCards.forEach(card => {
        const units = parseInt(card.dataset.units);
        if (units <= lowStockThreshold) {
            card.classList.add('low-stock');
            card.querySelector('.stock-warning').style.display = 'block';
        }
    });
}

// Initialize inventory level check
document.addEventListener('DOMContentLoaded', checkInventoryLevels); 