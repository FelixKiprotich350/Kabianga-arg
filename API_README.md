# Kabianga ARG Portal - API Documentation

REST API for the Kabianga Annual Research Grants Portal system.

## Base URL

```
http://your-domain.com/api/v1
```

## Authentication

The API uses Laravel's built-in session-based authentication. Most endpoints require authentication.

### Login
```http
POST /auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

### Check Authentication Status
```http
GET /auth/check
```

### Get Current User
```http
GET /auth/me
```

### Logout
```http
POST /auth/logout
```

## Core Endpoints

### Users Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/users` | Get all users |
| POST | `/users` | Create new user |
| GET | `/users/{id}` | Get specific user |
| PUT | `/users/{id}` | Update user details |
| PATCH | `/users/{id}/role` | Update user role |
| PATCH | `/users/{id}/permissions` | Update user permissions |
| PATCH | `/users/{id}/enable` | Enable user account |
| PATCH | `/users/{id}/disable` | Disable user account |

### Proposals Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/proposals` | Get all proposals |
| POST | `/proposals` | Create new proposal |
| GET | `/proposals/my` | Get current user's proposals |
| GET | `/proposals/{id}` | Get specific proposal |
| PUT | `/proposals/{id}/basic` | Update basic details |
| PUT | `/proposals/{id}/research` | Update research details |
| POST | `/proposals/{id}/submit` | Submit proposal for review |
| POST | `/proposals/{id}/approve` | Approve proposal |
| POST | `/proposals/{id}/reject` | Reject proposal |
| POST | `/proposals/{id}/request-changes` | Request changes |
| GET | `/proposals/{id}/pdf` | Generate PDF |

### Projects Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/projects` | Get all projects |
| GET | `/projects/my` | Get current user's projects |
| GET | `/projects/active` | Get active projects |
| GET | `/projects/{id}` | Get specific project |
| POST | `/projects/{id}/progress` | Submit progress report |
| GET | `/projects/{id}/progress` | Get project progress |
| POST | `/projects/{id}/funding` | Add funding |
| GET | `/projects/{id}/funding` | Get project funding |
| PATCH | `/projects/{id}/pause` | Pause project |
| PATCH | `/projects/{id}/resume` | Resume project |
| PATCH | `/projects/{id}/complete` | Mark as complete |

### Grants Management

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/grants` | Get all grants |
| POST | `/grants` | Create new grant |
| GET | `/grants/{id}` | Get specific grant |
| PUT | `/grants/{id}` | Update grant |

### Reports

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/reports/summary` | Get reports summary |
| GET | `/reports/proposals` | Get proposals report |
| GET | `/reports/proposals/by-school` | Proposals by school |
| GET | `/reports/proposals/by-theme` | Proposals by theme |
| GET | `/reports/financial` | Financial summary |
| GET | `/reports/projects` | Projects report |
| POST | `/reports/export` | Export report |

## Request/Response Format

### Standard Response Structure
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {
    // Response data
  },
  "meta": {
    "pagination": {
      "current_page": 1,
      "total_pages": 10,
      "per_page": 15,
      "total": 150
    }
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

## Common Parameters

### Pagination
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15, max: 100)

### Filtering
- `search` - Search term
- `status` - Filter by status
- `school_id` - Filter by school
- `theme_id` - Filter by research theme
- `grant_id` - Filter by grant

### Sorting
- `sort_by` - Field to sort by
- `sort_order` - `asc` or `desc` (default: `asc`)

## Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

## Example Usage

### Create a New Proposal
```javascript
const response = await fetch('/api/v1/proposals', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({
    researchtitle: 'AI in Agriculture',
    researchtheme: 1,
    grantfk: 1,
    schoolfk: 2,
    departmentfk: 5,
    researchobjectives: 'To develop AI solutions for farming',
    expectedoutcomes: 'Improved crop yields',
    totalbudget: 500000
  })
});

const data = await response.json();
```

### Get Proposals with Filtering
```javascript
const response = await fetch('/api/v1/proposals?search=AI&status=SUBMITTED&page=1&per_page=10');
const data = await response.json();
```

### Submit Progress Report
```javascript
const response = await fetch('/api/v1/projects/123/progress', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({
    progressdescription: 'Completed data collection phase',
    percentagecomplete: 45,
    challengesfaced: 'Weather delays',
    nextphase: 'Data analysis'
  })
});
```

## Rate Limiting

API requests are limited to 60 requests per minute per user.

## Testing

Test endpoints are available for development:
- `GET /test/connection` - Test API connectivity
- `GET /test/users` - Test user data (requires auth)
- `GET /test/proposals` - Test proposal data (requires auth)

## Error Handling

The API returns consistent error responses with appropriate HTTP status codes. Always check the `success` field in the response to determine if the request was successful.

## Security

- All authenticated endpoints require valid session
- CSRF protection is enabled for state-changing operations
- Input validation is performed on all endpoints
- SQL injection protection through Eloquent ORM