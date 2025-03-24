# Blood Bank Management System

A comprehensive web-based Blood Bank Management System built with PHP, MySQL, and JavaScript. This system helps blood banks manage donors, track blood inventory, and handle blood requests from hospitals efficiently.

## Features

- **Donor Management**
  - Add, edit, and delete donor information
  - Track donor history and blood type
  - Search and filter donors

- **Blood Inventory Management**
  - Track blood units by type and status
  - Monitor collection and expiry dates
  - Real-time inventory updates
  - Low stock alerts

- **Blood Request Management**
  - Handle requests from hospitals
  - Track request status
  - Automatic inventory updates
  - Priority-based request handling

- **Reporting and Analytics**
  - Blood type distribution
  - Donation trends
  - Request statistics
  - Inventory reports

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/blood-bank-system.git
   ```

2. Create a MySQL database and import the schema:
   ```bash
   mysql -u your_username -p your_database < database/schema.sql
   ```

3. Configure the database connection:
   - Copy `config/database.example.php` to `config/database.php`
   - Update the database credentials in `config/database.php`

4. Set up your web server:
   - Point your web server to the project directory
   - Ensure PHP has write permissions for the necessary directories

5. Access the system:
   - Open your web browser
   - Navigate to `http://localhost/blood-bank-system`

## Directory Structure

```
blood-bank-system/
├── assets/
│   ├── css/
│   └── js/
├── config/
│   └── database.php
├── includes/
├── database/
│   └── schema.sql
├── index.php
├── donors.php
├── inventory.php
├── requests.php
└── reports.php
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, please open an issue in the GitHub repository or contact the maintainers.

## Acknowledgments

- Bootstrap for the UI framework
- Font Awesome for icons
- All contributors who have helped improve this system 