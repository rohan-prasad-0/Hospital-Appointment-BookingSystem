# Hospital-Appointment-BookingSystem

A comprehensive web-based Hospital Appointment Booking System designed to streamline the process of scheduling and managing patient appointments. This system features distinct interfaces and functionalities tailored for administrators, doctors, patients, and receptionists, ensuring efficient healthcare management and an improved patient experience.

## ✨ Key Features & Benefits

*   **User Management (Admin)**:
    *   Add, edit, and delete doctor and receptionist accounts.
    *   View all registered doctors and receptionists.
*   **Appointment Management**:
    *   Patients can book and cancel appointments.
    *   Admins can view all scheduled appointments.
    *   Doctors can manage their schedules (implied by "Doctor Sched..." screenshot).
*   **Role-Based Dashboards**:
    *   **Patient Dashboard**: View upcoming appointments, booking history.
    *   **Admin Dashboard**: Overview of system activities, user management.
    *   **Doctor Dashboard**: Manage schedules and view patient appointments.
*   **Secure Authentication**: User registration and login functionalities.
*   **User-Friendly Interface**: Leverages Bootstrap for a responsive and intuitive design.

## 🚀 Technologies Used

This project is built using a combination of popular web technologies:

### Languages

*   **JavaScript**: For interactive frontend elements and dynamic content.
*   **PHP**: The primary server-side scripting language for backend logic and database interaction.

### Frontend Libraries / Frameworks

*   **Bootstrap**: Used for responsive design, styling, and UI components (`css/bootstrap-grid.css`).

### Database

*   **MySQL / MariaDB (Implied)**: A relational database is typically used with PHP applications to store user data, appointment details, doctor information, etc. (Though not explicitly listed, it's a core dependency for such a system).

## 📋 Prerequisites & Dependencies

Before you begin, ensure you have the following installed:

*   **Web Server**: Apache or Nginx (with PHP module enabled).
*   **PHP**: Version 7.4 or higher is recommended.
*   **Database Server**: MySQL or MariaDB.
*   **Web Browser**: A modern web browser (Chrome, Firefox, Edge, Safari).

## ⚙️ Installation & Setup Instructions

Follow these steps to get the Hospital Appointment Booking System up and running on your local machine:

1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/rohan-prasad-0/Hospital-Appointment-BookingSystem.git
    cd Hospital-Appointment-BookingSystem
    ```

2.  **Set up Your Web Server**:
    *   Place the project files in your web server's document root (e.g., `htdocs` for Apache, `www` for Nginx).
    *   Ensure your web server is configured to serve PHP files.

3.  **Database Setup**:
    *   Create a new database in your MySQL/MariaDB server (e.g., `hospital_db`).
    *   **Import Database Schema**: (A `database.sql` file is usually provided for this. If not present, you'll need to create tables manually based on application logic).
        ```sql
        -- Example command (replace 'your_username' and 'your_password' with actual credentials)
        -- mysql -u your_username -p your_password hospital_db < database.sql
        ```
    *   **Database Configuration**: Locate a configuration file (e.g., `config.php`, `db_connect.php`, or similar) and update the database connection details (hostname, username, password, database name) to match your setup.

4.  **Access the Application**:
    *   Open your web browser and navigate to the URL where your web server is hosting the project (e.g., `http://localhost/Hospital-Appointment-BookingSystem`).

## 🖥️ Usage & Screenshots

The system provides a multi-role interface for various users. Below are some key screens:

### Home Page
<img width="1854" height="951" alt="home" src="https://github.com/user-attachments/assets/341f4426-4843-4c11-9234-75047303d091" />

### Register
<img width="1867" height="949" alt="register" src="https://github.com/user-attachments/assets/78fe4d86-b793-4382-8e49-45fa372caef8" />

### Patient Dashboard
<img width="1867" height="949" alt="pdashb" src="https://github.com/user-attachments/assets/28d87164-6bd3-465d-8a6e-796ad9d30fd1" />

### Doctor Schedule
(Screenshot for "Doctor Sched..." is partially available in the provided data, indicating a dedicated view for doctors to manage their schedules.)
_Further screenshots for Admin Panel, Booking Flow, etc., can be added here._

## 🛠️ Configuration Options

*   **Database Connection**:
    *   Modify `db_connection.php` (or similar file) to update database credentials (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).
*   **Base URL**:
    *   If your application requires a specific base URL configuration, adjust it in the primary configuration file.
*   **User Roles**:
    *   User roles (admin, doctor, patient, receptionist) are managed within the system logic and database.

## 📁 Project Structure

```
├── README.md
├── admin_add_user.php                # Admin: Add new users (doctors/receptionists)
├── admin_add_user_1.php              # Admin: Process for adding new users
├── admin_dashboard.php               # Admin: Main dashboard
├── admin_delete_doctor.php           # Admin: Delete a doctor's record
├── admin_delete_receptionist.php     # Admin: Delete a receptionist's record
├── admin_edit_doctor.php             # Admin: Edit doctor details form
├── admin_edit_doctor_1.php           # Admin: Process for editing doctor details
├── admin_edit_receptionist.php       # Admin: Edit receptionist details form
├── admin_edit_receptionist_1.php     # Admin: Process for editing receptionist details
├── admin_profile.php                 # Admin: Admin's own profile view
├── admin_view_appointments.php       # Admin: View all appointments
├── admin_view_doctors.php            # Admin: View list of doctors
├── admin_view_receptionists.php      # Admin: View list of receptionists
├── book_appointment_1.php            # Patient: Step 1 of booking an appointment
├── book_appointment_2.php            # Patient: Step 2 of booking an appointment
├── booking_confirmation.php          # Patient: Appointment confirmation page
├── cancel_appointment.php            # Patient: Cancel an existing appointment
└── css/                              # Directory for CSS stylesheets
    ├── bootstrap-grid.css            # Bootstrap Grid system CSS
    ├── bootstrap-grid.min.css        # Minified Bootstrap Grid system CSS
    ├── bootstrap-grid.rtl.css        # Right-to-Left (RTL) Bootstrap Grid system CSS
    └── bootstrap-grid.rtl.min.css    # Minified RTL Bootstrap Grid system CSS
```

## 🤝 Contributing Guidelines

We welcome contributions to improve this Hospital Appointment Booking System!

1.  **Fork** the repository.
2.  **Clone** your forked repository: `git clone https://github.com/YOUR_USERNAME/Hospital-Appointment-BookingSystem.git`
3.  **Create a new branch**: `git checkout -b feature/your-feature-name` or `bugfix/issue-description`
4.  **Make your changes** and ensure they follow the existing code style.
5.  **Commit your changes**: `git commit -m "feat: Add new feature"`. Use descriptive commit messages.
6.  **Push to your branch**: `git push origin feature/your-feature-name`
7.  **Open a Pull Request** to the `main` branch of the original repository.

Please ensure your pull requests are well-described and include relevant tests or examples if applicable.

## 📄 License Information

This project does not currently specify a license. All rights are reserved by the project owner.

## 🙏 Acknowledgments

*   **Bootstrap** for providing a robust and responsive CSS framework.
