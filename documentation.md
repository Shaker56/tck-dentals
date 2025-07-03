# TCK Dentals Website Documentation

## Database Structure

### Patients Table
```sql
CREATE TABLE patients (
    patient_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    postcode VARCHAR(10) NOT NULL,
    medical_history TEXT,
    insurance_provider VARCHAR(100),
    insurance_number VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Appointments Table
```sql
CREATE TABLE appointments (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    service VARCHAR(100) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id)
);
```

### Staff Table
```sql
CREATE TABLE staff (
    staff_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'dentist', 'receptionist') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Laws and Regulations Considered

### 1. Data Protection and GDPR Compliance

The website has been developed with strict adherence to the General Data Protection Regulation (GDPR) requirements:

- **Data Collection Consent**: Clear consent forms for collecting patient data
- **Data Minimization**: Only collecting necessary information
- **Right to Access**: Patients can request their data
- **Right to Erasure**: Patients can request data deletion
- **Data Security**: Encryption of sensitive data
- **Data Retention**: Clear policies on data retention periods
- **Privacy Policy**: Comprehensive privacy policy explaining data usage

### 2. Healthcare Data Protection

Additional measures specific to healthcare data:

- **Medical Records Security**: Encrypted storage of medical history
- **Access Control**: Role-based access to patient records
- **Audit Trails**: Logging of all access to patient data
- **Secure Communication**: Encrypted email and messaging systems

### 3. Website Accessibility

The website complies with WCAG 2.1 Level AA standards:

- **Screen Reader Compatibility**: Proper ARIA labels and semantic HTML
- **Keyboard Navigation**: Full keyboard accessibility
- **Color Contrast**: Meeting WCAG contrast requirements
- **Text Resizing**: Support for text scaling
- **Alternative Text**: Proper alt text for images

### 4. Security Measures

Implementation of various security measures:

- **HTTPS**: Secure communication protocol
- **Password Hashing**: Secure storage of passwords
- **Input Validation**: Prevention of SQL injection and XSS attacks
- **Session Management**: Secure session handling
- **Regular Backups**: Automated backup system
- **Firewall Protection**: Web application firewall

### 5. Medical Advertising Regulations

Compliance with medical advertising regulations:

- **Accurate Information**: No misleading claims about treatments
- **Professional Tone**: Maintaining professional communication
- **Treatment Claims**: Evidence-based treatment descriptions
- **Pricing Transparency**: Clear display of service costs

## Impact of Laws and Regulations on Design

The implementation of these laws and regulations has significantly influenced the website's design and functionality:

1. **User Interface**:
   - Clear consent checkboxes for data collection
   - Accessible design elements
   - Clear privacy policy links
   - Transparent pricing information

2. **Data Management**:
   - Structured data collection forms
   - Secure data storage systems
   - Data export functionality
   - Automated data retention policies

3. **Security Features**:
   - Multi-factor authentication for staff
   - Encrypted data transmission
   - Secure session management
   - Regular security audits

4. **Content Management**:
   - Clear medical disclaimers
   - Professional language
   - Evidence-based treatment descriptions
   - Transparent pricing

## Additional Features for Enhanced Functionality

1. **Patient Portal**:
   - Online medical history access
   - Appointment history
   - Treatment plans
   - Secure messaging with staff

2. **Automated Reminders**:
   - Appointment reminders
   - Follow-up notifications
   - Treatment plan updates
   - Prescription renewals

3. **Integration Capabilities**:
   - Electronic health records (EHR) integration
   - Insurance provider systems
   - Payment processing
   - Laboratory systems

4. **Reporting Tools**:
   - Appointment analytics
   - Patient demographics
   - Treatment success rates
   - Financial reports

## Future Considerations

1. **Technology Updates**:
   - Regular security patches
   - Feature updates
   - Performance optimization
   - New technology integration

2. **Regulatory Changes**:
   - Monitoring of regulatory updates
   - Compliance adjustments
   - Policy updates
   - Staff training

3. **User Experience**:
   - Continuous feedback collection
   - Interface improvements
   - Mobile optimization
   - New feature development 