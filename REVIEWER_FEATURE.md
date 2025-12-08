# Proposal Multi-Reviewer Feature

## Overview
This feature allows proposals to be assigned to multiple reviewers who can view and suggest changes to proposals.

## Database Schema

### Table: `proposal_reviewers`
- `id` - Primary key
- `proposal_id` - Foreign key to proposals table
- `reviewer_id` - Foreign key to users table (reviewer)
- `assigned_by` - Foreign key to users table (who assigned)
- `assigned_at` - Timestamp of assignment
- `created_at`, `updated_at` - Standard timestamps

## API Endpoints

### 1. Assign Reviewers to Proposal
**POST** `/api/v1/proposals/{id}/reviewers`

**Permission Required:** `canassignreviewers`

**Request Body:**
```json
{
  "reviewer_ids": ["uuid1", "uuid2", "uuid3"]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Reviewers assigned successfully",
  "data": [...]
}
```

### 2. Get Proposal Reviewers
**GET** `/api/v1/proposals/{id}/reviewers`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "proposal_id": 123,
      "reviewer_id": "uuid",
      "reviewer": {
        "userid": "uuid",
        "name": "John Doe",
        "email": "john@example.com"
      },
      "assigned_by": "uuid",
      "assigned_at": "2025-02-01T10:00:00Z"
    }
  ]
}
```

### 3. Remove Reviewer from Proposal
**DELETE** `/api/v1/proposals/{id}/reviewers/{reviewerId}`

**Permission Required:** `canassignreviewers`

**Response:**
```json
{
  "success": true,
  "message": "Reviewer removed successfully"
}
```

### 4. Get My Review Proposals
**GET** `/api/v1/my-reviews`

Returns all proposals assigned to the authenticated user for review.

**Response:**
```json
{
  "success": true,
  "data": [...]
}
```

### 5. Request Changes (Updated)
**POST** `/api/v1/proposals/{id}/request-changes`

**Authorization:** Assigned reviewers OR users with `canapproveproposal` permission

**Request Body:**
```json
{
  "triggerissue": "Issue description",
  "suggestedchange": "Suggested change description"
}
```

## Authorization Rules

### View Proposal
Users can view a proposal if:
- They have `canreadproposaldetails` permission, OR
- They are the proposal owner, OR
- They are an assigned reviewer

### Suggest Changes
Users can suggest changes if:
- They are an assigned reviewer, OR
- They have `canapproveproposal` permission

### Assign/Remove Reviewers
Users can assign/remove reviewers if:
- They have `canassignreviewers` permission

## Setup Instructions

1. **Run Migration:**
```bash
php artisan migrate
```

2. **Seed Permission:**
```bash
php artisan db:seed --class=ReviewerPermissionSeeder
```

3. **Assign Permission:**
Assign the `canassignreviewers` permission to appropriate users through the user management interface.

## Model Relationships

### Proposal Model
```php
// Get all reviewers
$proposal->reviewers

// Check if user is reviewer
$proposal->isReviewer($userId)

// Check if user can request changes
$proposal->canRequestChanges($userId)
```

### ProposalReviewer Model
```php
// Get proposal
$proposalReviewer->proposal

// Get reviewer user
$proposalReviewer->reviewer

// Get who assigned
$proposalReviewer->assignedBy
```

## Usage Example

```php
// Assign reviewers
$proposal = Proposal::find(1);
$reviewerIds = ['uuid1', 'uuid2'];

foreach ($reviewerIds as $reviewerId) {
    ProposalReviewer::create([
        'proposal_id' => $proposal->proposalid,
        'reviewer_id' => $reviewerId,
        'assigned_by' => auth()->user()->userid
    ]);
}

// Check if user is reviewer
if ($proposal->isReviewer(auth()->user()->userid)) {
    // User can suggest changes
}
```

## Notes

- Multiple reviewers can be assigned to a single proposal
- Each reviewer can view the proposal and suggest changes
- Users with `canreadproposaldetails` permission can view all proposals
- Only reviewers can suggest changes (not just view)
- The proposal owner cannot be a reviewer of their own proposal
