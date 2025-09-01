# 📖 TaskFlow User Guide

A complete guide to using TaskFlow for task management and note-taking.

## 🏠 Getting Started

### First Time Setup
1. **Register Your Account**
   - Go to the TaskFlow homepage
   - Click "Register" 
   - Fill in username, email, and password
   - Check your email for verification (or PHP logs in development)
   - Click the verification link

2. **Login**
   - Return to TaskFlow and click "Login"
   - Enter your credentials
   - You'll be taken to the dashboard

### Understanding the Interface

**Sidebar (Left Side):**
- 📝 **App Logo & Name** - TaskFlow branding
- 🔍 **Search Bar** - Find lists and content instantly
- 📋 **Lists Navigation** - All your lists and shared lists
- ➕ **New List Button** - Create todo lists or note pages
- ⚙️ **Settings & Help** - Account settings and help
- 🚪 **Logout** - Sign out safely

**Main Panel (Right Side):**
- **List Content** - Current list's tasks or notes
- **Add Items** - Add new tasks or notes to current list

## 📋 Working with Lists

### Creating Lists

1. **Click "New List"** in the sidebar
2. **Choose List Type:**
   - **📋 To-Do List**: For tasks with checkboxes
   - **📝 Notes Page**: For freeform text content
3. **Enter Details:**
   - **Title**: Name your list (required)
   - **Description**: Optional description for context
4. **Click "Create List"**

### List Types Explained

#### 📋 Todo Lists
**Perfect for:**
- Shopping lists
- Project tasks  
- Daily goals
- Checklists

**Features:**
- ✅ Checkboxes to mark tasks complete
- ➕ Add new tasks
- ❌ Delete completed or unwanted tasks
- Visual completion tracking

#### 📝 Note Pages  
**Perfect for:**
- Meeting notes
- Ideas and brainstorming
- Documentation
- Journal entries
- Long-form content

**Features:**
- 📝 Rich text areas for each note
- ➕ Add multiple note blocks
- ❌ Delete individual notes
- Organized note sections

### Managing Lists

**Switch Between Lists:**
- Click any list name in the sidebar
- Active list is highlighted

**Share a List:**
1. Hover over list name in sidebar
2. Click the 🔗 share button
3. Enter recipient's username
4. Choose permission level:
   - **Read Only**: They can view but not edit
   - **Read & Write**: They can view and edit
5. Click "Share List"

**Delete a List:**
1. Hover over list name in sidebar  
2. Click the ❌ delete button
3. Confirm deletion

**Visual Indicators:**
- 📋 = Todo list
- 📝 = Note page
- 🤝 = Shared with you by someone else

## ✅ Working with Todo Lists

### Adding Tasks
1. Select a todo list from sidebar
2. Type your task in the "Add new item" field
3. Press Enter or click "Add"

### Managing Tasks
- **Complete Task**: Click the checkbox ✅
- **Uncomplete Task**: Click the checked box to uncheck
- **Delete Task**: Click the ❌ button next to task
- **Edit Task**: Click on task text to edit (if you have permission)

### Task Organization Tips
- Keep tasks specific and actionable
- Break large tasks into smaller steps
- Use separate lists for different projects or contexts
- Mark tasks complete as you finish them for motivation

## 📝 Working with Note Pages

### Adding Notes  
1. Select a note page from sidebar
2. Click "Add Note" button
3. Type your content in the text area
4. Content saves automatically

### Managing Notes
- **Edit Note**: Click in any note area to edit
- **Delete Note**: Click the ❌ button in note corner
- **Add Multiple Notes**: Each note is a separate block
- **Organize Content**: Use multiple note blocks for different topics

### Note-Taking Tips
- Use separate note blocks for different topics
- Keep important information at the top
- Use clear headings and structure
- Take advantage of the freeform format for creativity

## 🔍 Search Functionality

### Using Search
1. **Click search bar** or press `Ctrl/Cmd + K`
2. **Type your query** - results appear instantly
3. **Click any result** to jump to that list

### What Search Finds
- **List titles** and descriptions
- **Todo task** content  
- **Note text** content
- **Shared lists** you have access to

### Search Tips
- Search works across all your lists
- Results show which list contains the match
- Search includes shared lists you can access
- Use specific keywords for better results

## 🤝 Collaboration & Sharing

### Sharing Your Lists
1. **Find the list** you want to share
2. **Hover over list name** in sidebar
3. **Click share button** (🔗)
4. **Enter username** of person to share with
5. **Choose permission level**
6. **Click "Share List"**

### Permission Levels

**Read Only:**
- Can view list content
- Cannot add, edit, or delete items
- Perfect for: Status updates, reference materials

**Read & Write:**  
- Can view list content
- Can add new items
- Can edit existing items
- Can delete items
- Perfect for: Collaborative projects, team planning

### Working with Shared Lists
- **Shared lists** appear in your sidebar with 🤝 icon
- **Real-time updates** - changes appear immediately
- **Respect permissions** - don't edit read-only lists
- **Communicate** - coordinate with list owners

### Collaboration Best Practices
- **Discuss before sharing** - agree on purpose and permissions
- **Use clear naming** - make list purposes obvious
- **Respect others' content** - don't delete others' work without discussion
- **Communicate changes** - let others know about important updates

## ⌨️ Keyboard Shortcuts

Speed up your workflow with these shortcuts:

| Shortcut | Action |
|----------|--------|
| `Ctrl/Cmd + K` | Focus search bar |
| `Ctrl/Cmd + N` | Create new list |
| `Esc` | Close any open modal |
| `Enter` | Submit forms/add items |

### Using Shortcuts Effectively
- **Learn gradually** - start with Ctrl+K for search
- **Muscle memory** - use shortcuts consistently
- **Save time** - shortcuts are faster than clicking
- **Stay focused** - keep hands on keyboard

## 🎯 Use Case Examples

### Personal Organization
**Morning Routine Todo:**
```
📋 Morning Routine
☐ Make coffee
☐ Check emails  
☐ Review daily goals
☐ Exercise 30 minutes
```

**Meeting Notes:**
```
📝 Weekly Team Meeting - Jan 15
Project updates:
- Design review completed
- Development 60% done
- Testing starts next week

Action items:
- Sarah: Finalize API docs
- Mike: Setup staging environment
```

### Team Collaboration
**Shared Project List:**
```
📋 Website Redesign (Shared with: designer, developer)
☐ Create wireframes
☐ Design mockups
☐ Code frontend
☐ Setup backend
☐ User testing
☐ Launch
```

**Knowledge Sharing:**
```
📝 API Documentation (Read-only shared with team)
Authentication:
- Use Bearer tokens
- Tokens expire in 24 hours
- Refresh endpoint: /auth/refresh

Rate Limits:
- 1000 requests per hour
- 429 status for rate limit exceeded
```

## 🔧 Settings & Account

### Account Settings
- **Access**: Click "Settings" in sidebar
- **View**: Your username and account info
- **Delete Account**: Permanently remove account and all data

### Email Verification
- **Required**: Email must be verified to use TaskFlow
- **Check logs**: In development, verification emails are logged
- **Re-verify**: Contact admin if verification fails

### Security Tips
- **Use strong passwords** - Mix of letters, numbers, symbols
- **Don't share accounts** - Each person should have their own
- **Logout when done** - Especially on shared computers
- **Keep credentials private** - Don't share login info

## 🆘 Troubleshooting

### Common Issues

**Can't Login:**
- Check email is verified
- Verify username/password are correct
- Try resetting password (if available)

**Lists Not Loading:**
- Refresh the page
- Check internet connection
- Try logging out and back in

**Search Not Working:**
- Try different keywords
- Check if you have permission to view content
- Refresh the page

**Sharing Problems:**
- Verify username is spelled correctly
- Check the other person has an account
- Ensure you own the list (can't share shared lists)

### Getting Help
1. **Check this guide** first
2. **Try basic troubleshooting** (refresh, logout/login)
3. **Contact support** with specific error details
4. **Include**: What you were doing, what went wrong, error messages

## 🎉 Pro Tips

### Efficiency Tips
- **Use descriptive names** for lists and tasks
- **Regular cleanup** - delete completed tasks/old notes  
- **Organize by context** - separate work, personal, projects
- **Search everything** - faster than browsing when you have many lists

### Organization Strategies
- **Daily lists** for immediate tasks
- **Project lists** for ongoing work
- **Reference notes** for information you'll need again
- **Shared spaces** for team coordination

### Collaboration Success
- **Set expectations** clearly about list purposes
- **Update regularly** so others see current status
- **Use descriptions** to provide context
- **Respect permissions** and others' content

---

**Happy organizing!** 🎯 TaskFlow helps you stay productive whether working alone or with others.