# 📝 TaskFlow - Enhanced

A modern, feature-rich task management and note-taking application with collaboration capabilities.

## ✨ New Features

### 📧 Email Verification System
- **Secure Registration**: Users must verify their email address during signup
- **Token-based Verification**: Secure verification tokens with expiration
- **Development Mode**: Email logging for development environments
- **Password Reset Ready**: Infrastructure prepared for password reset functionality

### 📋 Dual List Types
- **Todo Lists**: Traditional task lists with checkboxes for completion tracking
- **Note Pages**: Freeform text areas for writing notes, ideas, and documentation
- **Visual Indicators**: Clear icons (📋 for todos, 📝 for notes) throughout the interface
- **Type-specific UI**: Different interfaces optimized for each list type

### 🤝 List Sharing & Collaboration
- **Share by Username**: Share lists with other registered users
- **Permission Levels**: 
  - **Read Only**: View list content without modification rights
  - **Read & Write**: Full editing capabilities on shared lists
- **Visual Indicators**: Shared lists marked with 🤝 in the sidebar
- **Access Control**: Comprehensive permission checking on all operations

### 🔍 Advanced Search
- **Real-time Search**: Instant results with intelligent debouncing
- **Comprehensive Coverage**: Search across list titles, descriptions, and content
- **Shared Content**: Include shared lists in search results
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
   -- Execute the SQL in database_migrations.sql
   mysql -u username -p taskflow < database_migrations.sql
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