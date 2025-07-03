// Database API handler
class DatabaseAPI {
    constructor(baseUrl = '/staff/api') {
        this.baseUrl = baseUrl;
    }

    // Generic API call method
    async apiCall(endpoint, method = 'GET', data = null) {
        const url = `${this.baseUrl}/${endpoint}`;
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'include' // Include session cookies
        };

        if (data && (method === 'POST' || method === 'PUT')) {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            console.error('API call failed:', error);
            throw error;
        }
    }

    // Patient-related methods
    async getPatients(page = 1, limit = 10) {
        return await this.apiCall(`database.php?endpoint=patients&page=${page}&limit=${limit}`);
    }

    async getPatient(id) {
        return await this.apiCall(`database.php?endpoint=patients&id=${id}`);
    }

    async createPatient(patientData) {
        return await this.apiCall('database.php?endpoint=patients', 'POST', patientData);
    }

    async updatePatient(id, patientData) {
        return await this.apiCall(`database.php?endpoint=patients&id=${id}`, 'PUT', patientData);
    }

    async deletePatient(id) {
        return await this.apiCall(`database.php?endpoint=patients&id=${id}`, 'DELETE');
    }

    // Medical records methods
    async getMedicalRecords(page = 1, limit = 10) {
        return await this.apiCall(`database.php?endpoint=medical-records&page=${page}&limit=${limit}`);
    }

    async getMedicalRecord(id) {
        return await this.apiCall(`database.php?endpoint=medical-records&id=${id}`);
    }

    async createMedicalRecord(recordData) {
        return await this.apiCall('database.php?endpoint=medical-records', 'POST', recordData);
    }

    async updateMedicalRecord(id, recordData) {
        return await this.apiCall(`database.php?endpoint=medical-records&id=${id}`, 'PUT', recordData);
    }
}

// Initialize database API
const dbAPI = new DatabaseAPI();

// UI handlers
document.addEventListener('DOMContentLoaded', function() {
    // Initialize patient records table
    initializePatientRecords();
    
    // Initialize medical records table
    initializeMedicalRecords();
});

// Patient records handlers
async function initializePatientRecords() {
    const searchInput = document.querySelector('#patient-search');
    const statusFilter = document.querySelector('#status-filter');
    const sortSelect = document.querySelector('#sort-select');
    const patientsTable = document.querySelector('#patients-table tbody');
    
    // Load initial data
    await loadPatients();
    
    // Add event listeners
    searchInput?.addEventListener('input', debounce(handlePatientSearch, 300));
    statusFilter?.addEventListener('change', handleStatusFilter);
    sortSelect?.addEventListener('change', handleSort);
    
    // Handle action buttons
    document.querySelectorAll('.action-buttons button').forEach(button => {
        button.addEventListener('click', handlePatientAction);
    });
}

// Medical records handlers
async function initializeMedicalRecords() {
    const searchInput = document.querySelector('#medical-records-search');
    const typeFilter = document.querySelector('#type-filter');
    const recordsTable = document.querySelector('#medical-records-table tbody');
    
    // Load initial data
    await loadMedicalRecords();
    
    // Add event listeners
    searchInput?.addEventListener('input', debounce(handleMedicalRecordSearch, 300));
    typeFilter?.addEventListener('change', handleTypeFilter);
}

// Utility functions
async function loadPatients(page = 1) {
    try {
        const patients = await dbAPI.getPatients(page);
        displayPatients(patients);
    } catch (error) {
        showError('Failed to load patients');
    }
}

async function loadMedicalRecords(page = 1) {
    try {
        const records = await dbAPI.getMedicalRecords(page);
        displayMedicalRecords(records);
    } catch (error) {
        showError('Failed to load medical records');
    }
}

function displayPatients(patients) {
    const tbody = document.querySelector('#patients-table tbody');
    if (!tbody) return;
    
    tbody.innerHTML = patients.map(patient => `
        <tr>
            <td>${patient.first_name} ${patient.last_name}</td>
            <td>${patient.patient_id}</td>
            <td>${patient.last_visit || 'N/A'}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-view" data-id="${patient.patient_id}">View</button>
                    <button class="btn btn-edit" data-id="${patient.patient_id}">Edit</button>
                    <button class="btn btn-delete" data-id="${patient.patient_id}">Delete</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function displayMedicalRecords(records) {
    const tbody = document.querySelector('#medical-records-table tbody');
    if (!tbody) return;
    
    tbody.innerHTML = records.map(record => `
        <tr>
            <td>${record.patient_id}</td>
            <td>${record.record_type}</td>
            <td>${record.date}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-view" data-id="${record.record_id}">View</button>
                    <button class="btn btn-edit" data-id="${record.record_id}">Edit</button>
                </div>
            </td>
        </tr>
    `).join('');
}

async function handlePatientAction(event) {
    const button = event.target;
    const action = button.classList.contains('btn-view') ? 'view' :
                  button.classList.contains('btn-edit') ? 'edit' : 'delete';
    const patientId = button.dataset.id;
    
    try {
        switch(action) {
            case 'view':
                const patient = await dbAPI.getPatient(patientId);
                showPatientDetails(patient);
                break;
            case 'edit':
                const patientToEdit = await dbAPI.getPatient(patientId);
                showEditPatientForm(patientToEdit);
                break;
            case 'delete':
                if (confirm('Are you sure you want to delete this patient?')) {
                    await dbAPI.deletePatient(patientId);
                    await loadPatients();
                }
                break;
        }
    } catch (error) {
        showError(`Failed to ${action} patient`);
    }
}

// Helper functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showError(message) {
    // Implement error display functionality
    console.error(message);
    alert(message);
}

function showPatientDetails(patient) {
    // Implement patient details modal
    console.log('Patient details:', patient);
}

function showEditPatientForm(patient) {
    // Implement edit patient form
    console.log('Edit patient:', patient);
} 