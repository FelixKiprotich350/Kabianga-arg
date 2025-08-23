# Authentication & Permission Management Fixes

## Changes Made

### 1. Unified Authentication Middleware
- **Created**: `AuthMiddleware.php` - Single middleware handling auth, email verification, and permissions
- **Replaced**: Multiple separate middlewares with one streamlined solution
- **Features**:
  - Authentication check
  - Email verification check
  - Account status check
  - Permission validation
  - JSON/Web response handling

### 2. Simplified Permission System
- **Enhanced**: `User.php` model with cleaner permission methods
- **Added**: `HasPermissions` trait for reusable permission logic
- **Methods**:
  - `hasPermission($permission)` - Check single permission
  - `hasAnyPermission($permissions)` - Check if user has any of the permissions
  - `hasAllPermissions($permissions)` - Check if user has all permissions
  - `can($permission)` - Laravel-style permission check
  - `cannot($permission)` - Inverse permission check

### 3. Streamlined Middleware Registration
- **Updated**: `Kernel.php` with minimal middleware setup
- **Removed**: Redundant middleware entries
- **Simplified**: Route middleware to use single `auth` middleware

### 4. Frontend Auth Service
- **Created**: `auth-service.js` for client-side permission checking
- **Features**:
  - Auto-initialization on page load
  - Permission caching
  - Admin privilege handling
  - Authentication status checking

### 5. API Endpoints
- **Added**: `/api/v1/auth/check` - Get auth status and permissions
- **Added**: `/api/v1/auth/permissions` - Get user permissions
- **Created**: `AuthController.php` for auth-related API endpoints

## Usage Examples

### Backend Permission Checking
```php
// In controllers
if (!auth()->user()->hasPermission('canviewallusers')) {
    return response()->json(['message' => 'Unauthorized'], 403);
}

// Multiple permissions
if (auth()->user()->hasAnyPermission(['canviewallusers', 'canviewallapplications'])) {
    // User has at least one permission
}

// All permissions required
if (auth()->user()->hasAllPermissions(['canviewallusers', 'canedituserprofile'])) {
    // User has all permissions
}
```

### Route Protection
```php
// Simple auth
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

// With specific permission
Route::get('/users', [UsersController::class, 'index'])->middleware('auth:canviewallusers');

// Multiple permissions
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth:canviewadmindashboard,canmanageusers');
```

### Frontend Permission Checking
```javascript
// Check if authenticated
if (Auth.isAuthenticated()) {
    // User is logged in
}

// Check permission
if (Auth.hasPermission('canviewallusers')) {
    // Show users section
}

// Check multiple permissions
if (Auth.hasAnyPermission(['canviewallusers', 'canviewallapplications'])) {
    // Show admin menu
}

// Check if admin
if (Auth.isAdmin()) {
    // Show admin features
}
```

### Blade Templates
```blade
@auth
    @if(auth()->user()->hasPermission('canviewallusers'))
        <a href="/users">Manage Users</a>
    @endif
    
    @if(auth()->user()->isAdmin())
        <a href="/admin">Admin Panel</a>
    @endif
@endauth
```

## Benefits

1. **Single Source of Truth**: One middleware handles all auth concerns
2. **Fluent API**: Clean, readable permission checking methods
3. **Admin Override**: Admins automatically have all permissions
4. **Frontend Integration**: JavaScript can check permissions without server calls
5. **Backward Compatibility**: Old `haspermission()` method still works
6. **Performance**: Cached permissions on frontend, efficient database queries
7. **Consistency**: Same permission checking logic across backend and frontend

## Migration Notes

- All existing routes using `auth.custom` and `email.account.verification` now use single `auth` middleware
- Permission checking methods are backward compatible
- Frontend permission checking is now available via `Auth` global object
- API endpoints provide real-time auth status and permissions