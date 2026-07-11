# Entity Update Request Functionality

This document describes the implementation of entity update requests for workers, allowing stations to approve or reject worker profile updates.

## Overview

When a worker updates their profile, instead of directly updating the database, an `EntityUpdate` record is created that requires approval from the worker's station before the changes are applied.

## Components Added

### 1. Models
- **EntityUpdate**: Stores update requests with polymorphic relationship to any entity
- **Station**: Added relationships to access worker update requests

### 2. Controllers
- **EntityUpdateController**: Handles station operations for managing update requests

### 3. Services
- **EntityUpdateService**: Business logic for managing update requests

### 4. API Resources
- **EntityUpdateResource**: Basic update request information
- **EntityUpdateDetailsResource**: Detailed update request with comparison data
- **EntityUpdatesCollection**: Paginated collection of update requests

### 5. Validation
- **RejectUpdateRequest**: Validates rejection reason input

## API Endpoints

### Station Endpoints (Authenticated with `auth:sanctum` and `check-auth-type:station`)

```
GET    /api/station/update-requests              # List all pending update requests
GET    /api/station/update-requests/statistics   # Get statistics
GET    /api/station/update-requests/{id}         # Get specific update request details
POST   /api/station/update-requests/{id}/approve # Approve an update request
POST   /api/station/update-requests/{id}/reject  # Reject an update request
```

### Query Parameters for List Endpoint
- `worker_id`: Filter by specific worker
- `type`: Filter by update type (update/delete)
- `date_from`: Filter by creation date from
- `date_to`: Filter by creation date to
- `per_page`: Number of items per page (default: 15)

### Reject Request Body
```json
{
    "rejection_reason": "Required string, 10-500 characters"
}
```

## Usage Examples

### 1. List Pending Update Requests
```bash
GET /api/station/update-requests
Authorization: Bearer {station_token}
```

### 2. Get Update Request Details
```bash
GET /api/station/update-requests/1
Authorization: Bearer {station_token}
```

### 3. Approve Update Request
```bash
POST /api/station/update-requests/1/approve
Authorization: Bearer {station_token}
```

### 4. Reject Update Request
```bash
POST /api/station/update-requests/1/reject
Authorization: Bearer {station_token}
Content-Type: application/json

{
    "rejection_reason": "The provided information is incomplete"
}
```

### 5. Get Statistics
```bash
GET /api/station/update-requests/statistics
Authorization: Bearer {station_token}
```

## Response Format

### Success Response
```json
{
    "key": "success",
    "msg": "Success message",
    "data": { ... }
}
```

### Error Response
```json
{
    "key": "fail",
    "msg": "Error message"
}
```

## Security Features

1. **Authorization**: Only the station that owns the worker can manage their update requests
2. **Validation**: Proper validation for rejection reasons
3. **Transaction Safety**: Database transactions ensure data consistency
4. **Status Checking**: Prevents processing already processed requests

## Translations

The functionality includes full Arabic and English translations for:
- Validation messages
- Success/error messages
- Field attributes

## Database Schema

The `entity_updates` table includes:
- `id`: Primary key
- `updateable_type`: Polymorphic type (e.g., 'App\Models\Worker')
- `updateable_id`: Polymorphic ID
- `type`: Update type (update/delete)
- `data`: JSON data containing the changes
- `is_approved`: Boolean approval status
- `rejection_reason`: Optional rejection reason
- `rejected_at`: Timestamp of rejection
- `created_at`, `updated_at`: Timestamps

## Future Enhancements

1. **Notifications**: Send notifications to workers when requests are approved/rejected
2. **Email Notifications**: Email notifications for status changes
3. **Audit Trail**: Track who approved/rejected requests
4. **Bulk Operations**: Approve/reject multiple requests at once
5. **Comments**: Allow stations to add comments when approving/rejecting
