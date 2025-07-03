<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'tck_dentals');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');

// Start session for authentication
session_start();

// Check if user is logged in and has appropriate role
function checkAuth() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized access']);
        exit;
    }

    // Check if user has appropriate role (admin or dentist)
    if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'dentist') {
        http_response_code(403);
        echo json_encode(['error' => 'Insufficient permissions']);
        exit;
    }
}

// Database connection
function getConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
        return $conn;
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
}

// Handle API requests
header('Content-Type: application/json');

// Check authentication for all requests
checkAuth();

// Get the request method and endpoint
$method = $_SERVER['REQUEST_METHOD'];
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch($endpoint) {
    case 'patients':
        handlePatients($method);
        break;
    case 'medical-records':
        handleMedicalRecords($method);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
        break;
}

// Handle patient-related requests
function handlePatients($method) {
    $conn = getConnection();
    
    switch($method) {
        case 'GET':
            // Get patients list or specific patient
            $patientId = isset($_GET['id']) ? $_GET['id'] : null;
            
            if ($patientId) {
                $stmt = $conn->prepare("SELECT * FROM patients WHERE patient_id = ?");
                $stmt->execute([$patientId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                // Add pagination
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                $offset = ($page - 1) * $limit;
                
                $stmt = $conn->prepare("SELECT * FROM patients LIMIT ? OFFSET ?");
                $stmt->execute([$limit, $offset]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            echo json_encode($result);
            break;
            
        case 'POST':
            // Add new patient
            $data = json_decode(file_get_contents('php://input'), true);
            
            $stmt = $conn->prepare("INSERT INTO patients (first_name, last_name, email, phone, date_of_birth, address, city, postcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['phone'],
                $data['date_of_birth'],
                $data['address'],
                $data['city'],
                $data['postcode']
            ]);
            
            echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
            break;
            
        case 'PUT':
            // Update patient
            $data = json_decode(file_get_contents('php://input'), true);
            $patientId = isset($_GET['id']) ? $_GET['id'] : null;
            
            if (!$patientId) {
                http_response_code(400);
                echo json_encode(['error' => 'Patient ID required']);
                break;
            }
            
            $stmt = $conn->prepare("UPDATE patients SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, address = ?, city = ?, postcode = ? WHERE patient_id = ?");
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['phone'],
                $data['date_of_birth'],
                $data['address'],
                $data['city'],
                $data['postcode'],
                $patientId
            ]);
            
            echo json_encode(['success' => true]);
            break;
            
        case 'DELETE':
            // Delete patient
            $patientId = isset($_GET['id']) ? $_GET['id'] : null;
            
            if (!$patientId) {
                http_response_code(400);
                echo json_encode(['error' => 'Patient ID required']);
                break;
            }
            
            $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = ?");
            $stmt->execute([$patientId]);
            
            echo json_encode(['success' => true]);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
}

// Handle medical records requests
function handleMedicalRecords($method) {
    $conn = getConnection();
    
    switch($method) {
        case 'GET':
            // Get medical records list or specific record
            $recordId = isset($_GET['id']) ? $_GET['id'] : null;
            
            if ($recordId) {
                $stmt = $conn->prepare("SELECT * FROM medical_records WHERE record_id = ?");
                $stmt->execute([$recordId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                // Add pagination
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                $offset = ($page - 1) * $limit;
                
                $stmt = $conn->prepare("SELECT * FROM medical_records LIMIT ? OFFSET ?");
                $stmt->execute([$limit, $offset]);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            echo json_encode($result);
            break;
            
        case 'POST':
            // Add new medical record
            $data = json_decode(file_get_contents('php://input'), true);
            
            $stmt = $conn->prepare("INSERT INTO medical_records (patient_id, record_type, diagnosis, treatment, notes, date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['patient_id'],
                $data['record_type'],
                $data['diagnosis'],
                $data['treatment'],
                $data['notes'],
                $data['date']
            ]);
            
            echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
            break;
            
        case 'PUT':
            // Update medical record
            $data = json_decode(file_get_contents('php://input'), true);
            $recordId = isset($_GET['id']) ? $_GET['id'] : null;
            
            if (!$recordId) {
                http_response_code(400);
                echo json_encode(['error' => 'Record ID required']);
                break;
            }
            
            $stmt = $conn->prepare("UPDATE medical_records SET record_type = ?, diagnosis = ?, treatment = ?, notes = ? WHERE record_id = ?");
            $stmt->execute([
                $data['record_type'],
                $data['diagnosis'],
                $data['treatment'],
                $data['notes'],
                $recordId
            ]);
            
            echo json_encode(['success' => true]);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
}
?> 