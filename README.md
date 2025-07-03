# TCK Dentals Website

A modern, responsive website for a dental practice that allows patients to register, book appointments, and view services. The website also includes a staff area for managing appointments and patient records.

## Features

- Responsive design that works on all devices
- Patient registration system
- Online appointment booking
- Service information and pricing
- Contact form with location map
- Staff area for managing appointments and patient records
- Modern and user-friendly interface

## Technologies Used

- HTML5
- CSS3
- JavaScript
- Font Awesome icons
- Google Fonts
- Google Maps API

## Project Structure

```
tck-dentals/
├── index.html
├── services.html
├── appointments.html
├── register.html
├── contact.html
├── css/
│   └── style.css
├── js/
│   └── main.js
└── images/
    ├── dental-hero.jpg
    ├── checkup.jpg
    ├── cleaning.jpg
    ├── whitening.jpg
    ├── filling.jpg
    ├── crown.jpg
    └── root-canal.jpg
```

## Setup Instructions

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/tck-dentals.git
   ```

2. Navigate to the project directory:
   ```bash
   cd tck-dentals
   ```

3. Open the website in your browser:
   - Double-click on `index.html` or
   - Use a local server (e.g., Live Server in VS Code)

## Database Structure

The website uses a database to store patient information and appointments. The database structure includes:

### Patients Table
- Patient ID
- First Name
- Last Name
- Email
- Phone
- Date of Birth
- Address
- Medical History
- Insurance Information

### Appointments Table
- Appointment ID
- Patient ID
- Date
- Time
- Service
- Status
- Notes

## Security Considerations

- All forms include client-side validation
- HTTPS is required for the live website
- Patient data is encrypted in the database
- Staff area is protected by authentication
- GDPR compliance implemented

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contact

Your Name - your.email@example.com
Project Link: https://github.com/yourusername/tck-dentals 