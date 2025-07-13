# Email Preferences Management System

## Overview

A comprehensive email notification preferences management system for the Vue.js 3 + Laravel task management application. This system allows users to control which email notifications they receive and provides administrators with tools to manage preferences across all users.

## Features Implemented

### ✅ **For Regular Users:**
- **Profile Integration**: Email preferences section added to user profile page
- **Toggle Controls**: Easy-to-use switches for each notification type
- **Real-time Updates**: Immediate saving of preference changes
- **Clear Descriptions**: Explanatory text for each notification type
- **Change Detection**: Only saves modified preferences

### ✅ **For Admin Users:**
- **Overview Dashboard**: Statistics and overview of all user preferences
- **User Management**: View and edit any user's email preferences
- **Bulk Statistics**: Percentage breakdowns for each preference type
- **Individual User Dialogs**: Detailed preference editing for specific users
- **Search Functionality**: Find users quickly in the preferences table

### ✅ **Technical Implementation:**
- **RESTful API**: Complete backend API with proper authentication
- **Vuetify Components**: Consistent UI design with existing application
- **Form Validation**: Proper error handling and validation
- **Toast Notifications**: Success/error feedback using vue-toastification
- **TypeScript Support**: Full type safety for all components

## File Structure

### Backend Files
```
backend/
├── app/Http/Controllers/EmailPreferencesController.php  # API endpoints
├── routes/api.php                                       # Updated with new routes
└── test-email-preferences.php                          # Test script
```

### Frontend Files
```
frontend/src/
├── components/
│   ├── user/EmailPreferences.vue                       # User preferences component
│   └── admin/
│       ├── EmailPreferencesOverview.vue                # Admin overview component
│       └── UserEmailPreferencesForm.vue                # Admin user edit form
├── views/
│   ├── Profile.vue                                     # Updated with preferences
│   └── Admin/EmailPreferences.vue                      # Admin preferences page
├── services/api.ts                                     # Updated API service
├── router/index.ts                                     # New route added
└── utils/constants.ts                                  # Updated constants
```

## API Endpoints

### User Endpoints
- `GET /api/email-preferences` - Get current user's preferences
- `PUT /api/email-preferences` - Update current user's preferences

### Admin Endpoints
- `GET /api/users/{id}/email-preferences` - Get specific user's preferences
- `PUT /api/users/{id}/email-preferences` - Update specific user's preferences
- `GET /api/admin/email-preferences/overview` - Get overview with statistics

## Email Preference Types

1. **Task Assigned** (`task_assigned`)
   - Triggered when a new task is assigned to the user
   - Default: `true`

2. **Task Updated** (`task_updated`)
   - Triggered when user's tasks are modified
   - Default: `true`

3. **Task Reminders** (`task_reminders`)
   - Triggered for upcoming and overdue task reminders
   - Default: `true`

4. **Welcome Email** (`welcome_email`)
   - Triggered when user account is created
   - Default: `true`

5. **Password Reset** (`password_reset`)
   - Triggered for password reset and security emails
   - Default: `true`

## Usage Instructions

### For Users
1. Navigate to **Profile** page
2. Scroll to **Email Notification Preferences** section
3. Toggle switches for desired notifications
4. Click **Save Preferences** to apply changes

### For Admins
1. Navigate to **Admin Panel** → **Email Preferences**
2. View statistics cards showing preference adoption rates
3. Use search to find specific users
4. Toggle preferences directly in the table OR
5. Click the settings icon to open detailed preference dialog

## Testing

### Backend Testing
Run the test script to verify backend functionality:
```bash
cd backend
php test-email-preferences.php
```

### Frontend Testing
1. Start the development servers:
```bash
# Backend
cd backend && php artisan serve

# Frontend
cd frontend && npm run dev
```

2. Test user preferences:
   - Login as a regular user
   - Go to Profile page
   - Test preference toggles

3. Test admin functionality:
   - Login as an admin user
   - Go to Admin → Email Preferences
   - Test overview and user management

## Integration with Existing Notifications

The system integrates seamlessly with existing notification classes:
- `TaskAssignedNotification`
- `TaskUpdatedNotification`
- `TaskReminderNotification`
- `WelcomeNotification`

Each notification checks user preferences using the `canReceiveEmail()` method before sending.

## Security & Authorization

- **User Access**: Users can only view/edit their own preferences
- **Admin Access**: Admins can view/edit any user's preferences
- **API Protection**: All endpoints require authentication via Sanctum
- **Validation**: Proper input validation on all preference updates

## Next Steps

1. **Deploy Frontend**: Ready for Vercel deployment
2. **Email Testing**: Test with real email service (Mailgun configured)
3. **User Training**: Inform users about new preference controls
4. **Monitoring**: Monitor preference adoption rates via admin dashboard

## Troubleshooting

### Common Issues
1. **Preferences not saving**: Check browser console for API errors
2. **Admin can't see overview**: Verify admin role in database
3. **Email still sending**: Clear Laravel config cache: `php artisan config:clear`

### Debug Commands
```bash
# Check user preferences in database
php artisan tinker --execute="User::find(ID)->email_preferences"

# Test API endpoints
curl -H "Authorization: Bearer TOKEN" http://localhost:8000/api/email-preferences
```

## Performance Considerations

- **Caching**: Consider caching preference statistics for large user bases
- **Database Indexing**: Email preferences are stored as JSON, consider indexing if needed
- **Batch Updates**: Admin bulk preference updates could be added for efficiency

---

**Status**: ✅ Complete and Ready for Production
**Last Updated**: 2025-07-13
**Version**: 1.0.0
