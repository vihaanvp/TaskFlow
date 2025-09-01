# 📝 TaskFlow

A modern, feature-rich task management and note-taking application with collaboration capabilities. Perfect for individuals and teams who need both structured todo lists and freeform notes.

![TaskFlow Features](https://img.shields.io/badge/Features-Lists%20%7C%20Notes%20%7C%20Sharing%20%7C%20Search-blue)
![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%207.4-777BB4)
![License](https://img.shields.io/badge/License-MIT-green)

## 🚀 Quick Start

### For Complete Beginners

1. **Download and Install Requirements:**
   - Install [XAMPP](https://www.apachefriends.org/) (includes PHP and MySQL)
   - Or install PHP and MySQL separately

2. **Get TaskFlow:**
   ```bash
   # Download the code
   git clone https://github.com/vihaanvp/TaskFlow.git
   cd TaskFlow
   ```

3. **Setup Database:**
   - Open XAMPP and start Apache and MySQL
   - Go to http://localhost/phpmyadmin
   - Create a new database called `taskflow`
   - Import the `taskflow_database.sql` file (📁 Complete setup with demo data)
   - **Detailed instructions:** See [DATABASE_SETUP.md](DATABASE_SETUP.md)

4. **Configure Application:**
   - Open `includes/config.php` in a text editor
   - Update database credentials if needed (usually just change DB_PASS if you set a MySQL password)
   - For production: set DEBUG to false and update APP_URL

5. **Start Using:**
   - Place TaskFlow folder in your web server directory (like `htdocs` for XAMPP)
   - Open http://localhost/TaskFlow in your browser
   - **Test database:** Visit http://localhost/TaskFlow/test_database.php
   - Create an account and start organizing!

### For Developers

See [DEVELOPMENT.md](DEVELOPMENT.md) for detailed setup instructions, project structure, and development guidelines.

## ✨ Features Overview

### 🚀 Production Ready
- **Simple Setup**: No complex configuration - just import and go
- **Secure Authentication**: Password hashing with PHP's built-in functions  
- **No Email Dependencies**: Immediate registration without email verification
- **Database Optimized**: Proper indexing and foreign key constraints

### 📋 Dual List Types
**Choose the right tool for each task:**

- **📋 Todo Lists**: Traditional task lists with checkboxes
  - Perfect for: Shopping lists, project tasks, daily goals
  - Features: Check/uncheck items, delete completed tasks
  
- **📝 Note Pages**: Freeform text areas for rich content
  - Perfect for: Meeting notes, ideas, documentation, journaling
  - Features: Rich text areas, organized note blocks

### 🤝 List Sharing & Collaboration
**Work together seamlessly:**

- **Share by Username**: Easily share lists with other registered users
- **Permission Levels**: 
  - **Read Only**: View list content without modification rights
  - **Read & Write**: Full editing capabilities on shared lists
- **Visual Indicators**: Shared lists marked with 🤝 in the sidebar
- **Access Control**: Comprehensive permission checking protects your data

### 🔍 Advanced Search
**Find anything instantly:**

- **Real-time Search**: Results appear as you type (300ms delay)
- **Comprehensive Coverage**: Search across list titles, descriptions, and all content
- **Shared Content**: Include shared lists in search results
- **Grouped Results**: Results organized by list with content previews

### ⌨️ Keyboard Shortcuts
**Power user features:**

- `Ctrl/Cmd + K`: Focus search bar
- `Ctrl/Cmd + N`: Create new list
- `Esc`: Close any open modal

### 🎨 Modern User Experience
**Beautiful and intuitive:**

- **Dark Theme**: Easy on the eyes for extended use
- **Modal Dialogs**: Intuitive interfaces for list creation and sharing
- **Hover Actions**: Quick access to share/delete buttons
- **Help System**: Built-in help modal explaining features and shortcuts
- **Mobile Responsive**: Works great on phones and tablets

## 📱 How to Use TaskFlow

### Getting Started
1. **Register an Account**: Create your account - login immediately after registration
2. **Create Your First List**: Choose between Todo or Notes type  
3. **Add Content**: Start adding tasks or writing notes
4. **Stay Organized**: Use search to find content quickly

### Managing Lists
- **Create Lists**: Click "New List" and choose Todo or Notes
- **Switch Lists**: Click any list in the sidebar to switch to it
- **Share Lists**: Hover over a list and click the 🔗 button
- **Delete Lists**: Hover over a list and click the ✕ button

### Working with Content
- **Todo Lists**: Click checkboxes to mark tasks complete
- **Note Pages**: Click "Add Note" to create new note blocks
- **Search Everything**: Use the search bar to find any content instantly
- **Keyboard Shortcuts**: Use Ctrl+K for search, Ctrl+N for new lists

### Collaboration
1. **Share a List**: Click the share button (🔗) next to any list you own
2. **Choose Permissions**: 
   - Read Only: Others can view but not edit
   - Read & Write: Others can view and edit
3. **Enter Username**: Type the exact username of who you want to share with
4. **Start Collaborating**: Shared lists appear with a 🤝 icon

## 🛠️ Technical Details

### Built With
- **Backend**: PHP 7.4+ with PDO for database access
- **Database**: MySQL/MariaDB with proper indexing for performance
- **Frontend**: Vanilla JavaScript for responsive interactions
- **Styling**: Custom CSS with CSS variables for theming

### Security Features
- Password hashing with PHP's `password_hash()`
- SQL injection protection with prepared statements
- XSS protection with proper output escaping
- CSRF protection for sensitive operations
- Session management with proper timeouts

### Performance
- Database indexing for fast searches
- Debounced search for responsive UI
- Minimal JavaScript for fast loading
- Optimized CSS for smooth animations

## 📂 Project Structure

```
TaskFlow/
├── DEVELOPMENT.md        # Developer setup guide
├── README.md            # This file
├── taskflow_database.sql     # Complete database setup
├── index.php           # Homepage
├── dashboard.php       # Main application
├── api/                # API endpoints
├── assets/             # CSS and JavaScript
└── includes/           # PHP utilities and configuration
    ├── config.php      # Application configuration
    ├── db.php          # Database connection
    └── auth.php        # Authentication utilities
```

## 🔧 Configuration

All configuration is handled in `includes/config.php`. Open this file to customize:

```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'taskflow');
define('DB_USER', 'root');
define('DB_PASS', ''); // Set your MySQL password if needed

// Application Configuration
define('APP_NAME', 'TaskFlow');
define('APP_URL', 'http://localhost');
define('DEBUG', false); // Set to true for development debugging

// Email Configuration (for notifications)
define('DEVELOPMENT_MODE', true); // Set to false in production
```

## 🐛 Troubleshooting

### Common Issues

**Database Connection Error**
- Check your database credentials in `includes/config.php`
- Ensure MySQL/MariaDB is running
- Verify database exists and user has permissions

**Email Verification Not Working**
- In development mode, check PHP error logs for email content
- Verification URLs are logged instead of emailed

**Permission Denied**
- Check file permissions for web server access
- Ensure `includes/config.php` file exists and is readable

### Getting Help

1. Check [DEVELOPMENT.md](DEVELOPMENT.md) for detailed setup
2. Review PHP error logs for specific errors
3. Open an issue on GitHub with error details

## 🤝 Contributing

We welcome contributions! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

See [DEVELOPMENT.md](DEVELOPMENT.md) for development setup.

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🎯 Use Cases

**Personal Organization**
- Daily todo lists
- Meeting notes
- Project planning
- Goal tracking

**Team Collaboration**
- Shared project tasks
- Team notes and documentation
- Collaborative planning
- Knowledge sharing

**Academic/Professional**
- Research notes
- Assignment tracking
- Course materials
- Professional documentation

---

**Ready to get organized?** [Download TaskFlow](https://github.com/vihaanvp/TaskFlow) and start managing your tasks and notes today!
- **Grouped Results**: Results organized by list with content previews
- **Quick Navigation**: Click search results to jump directly to content

### 🎯 Enhanced User Experience
- **Keyboard Shortcuts**:
  - `Ctrl/Cmd + K`: Focus search
  - `Ctrl/Cmd + N`: Create new list
  - `Esc`: Close modals
- **Modal Dialogs**: Intuitive interfaces for creating and sharing lists
- **Hover Actions**: Quick access to list actions on hover
- **Help System**: Built-in help modal with shortcuts and feature explanations

### 🗂️ Tagging System (Infrastructure)
- **Database Ready**: Complete tag system infrastructure in place
- **Many-to-Many Relations**: Lists can have multiple tags
- **User-specific**: Each user maintains their own tag library
- **Color Support**: Tags support custom colors for organization

## 🚀 Getting Started

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/vihaanvp/TaskFlow.git
   cd TaskFlow
   ```

2. **Configure Database**
   - Create a MySQL database named `taskflow`
   - Update database credentials in `includes/db.php`

3. **Run Database Migrations**
   ```sql
   -- Execute the SQL in taskflow_database.sql
   mysql -u username -p taskflow < taskflow_database.sql
   ```

4. **Configure Email (Optional)**
   - For production: Configure SMTP settings in `includes/email.php`
   - For development: Email verification links are logged to error logs

5. **Set up Web Server**
   - Point document root to the TaskFlow directory
   - Ensure PHP has write permissions for session handling

### First Steps

1. **Register Account**: Visit the app and create a new account with email verification
2. **Create Lists**: Choose between Todo Lists and Note Pages
3. **Share & Collaborate**: Share lists with other users by username
4. **Search Content**: Use the search bar to quickly find lists and content
5. **Learn Shortcuts**: Click "Help & Shortcuts" in the sidebar for quick reference

## 📊 Database Schema

### Core Tables
- **users**: User accounts with email verification
- **lists**: Lists with type support (todo/note)
- **items**: List content with type-specific handling
- **list_shares**: List sharing and collaboration
- **tags**: User-defined tags for organization
- **list_tags**: Many-to-many relationship for list tagging

### Key Features
- **Foreign Key Constraints**: Maintains data integrity
- **Indexes**: Optimized for search and filtering performance
- **Email Verification**: Secure token-based verification system
- **Collaboration**: Granular permission system for sharing

## 🎨 UI/UX Enhancements

### Design Principles
- **Dark Theme**: Modern dark interface optimized for extended use
- **Responsive**: Works on desktop, tablet, and mobile devices
- **Accessibility**: Keyboard navigation and screen reader friendly
- **Visual Hierarchy**: Clear organization with icons and typography

### Color Scheme
- **Primary**: `#6366f1` (Indigo)
- **Background**: `#18181b` (Dark)
- **Panels**: `#232329` (Slightly lighter)
- **Text**: `#f3f3f5` (Light gray)
- **Success**: `#22c55e` (Green)
- **Error**: `#f87171` (Red)

## 🔧 Technical Details

### Security Features
- **Password Hashing**: BCrypt with proper salting
- **SQL Injection Protection**: Prepared statements throughout
- **CSRF Protection**: Form-based security measures
- **Access Control**: User-specific data isolation
- **Email Verification**: Prevents fake account creation

### Performance Optimizations
- **Database Indexes**: Strategic indexing for common queries
- **JavaScript Debouncing**: Efficient search with request limiting
- **Lazy Loading**: Content loaded on demand
- **Minimal Dependencies**: Pure PHP/JavaScript without heavy frameworks

### Development Features
- **Error Logging**: Comprehensive error tracking
- **Development Mode**: Email simulation for testing
- **Code Organization**: Clean separation of concerns
- **Documentation**: Inline code documentation

## 🚀 Future Enhancements

### Planned Features
- **Rich Text Editor**: Enhanced note editing with formatting
- **File Attachments**: Support for images and documents
- **Export/Import**: Backup and restore functionality
- **Mobile App**: Native mobile applications
- **Real-time Collaboration**: Live editing for shared lists
- **Advanced Tagging**: Tag-based filtering and organization
- **Theme Customization**: User-selectable color themes

### API Endpoints
The application includes RESTful API endpoints for:
- `/api/lists.php` - List management and sharing
- `/api/items.php` - Item CRUD operations
- `/api/search.php` - Search functionality
- `/api/tags.php` - Tag management (infrastructure ready)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

For support, email support@taskflow.local or open an issue in the repository.

---

**TaskFlow** - Your enhanced productivity companion with modern collaboration features.