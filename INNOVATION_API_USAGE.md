# Innovation Proposal API Usage

## Overview
The API now supports innovation proposals with additional fields and team management. Innovation proposals require specific fields that are validated based on the proposal type.

## Creating an Innovation Proposal

### POST `/api/v1/proposals`

```json
{
  "proposaltype": "innovation",
  "grantnofk": 1,
  "departmentfk": "dept-uuid",
  "themefk": 1,
  "researchtitle": "AI-Powered Agricultural Monitoring System",
  "gap": "Current agricultural monitoring systems lack real-time data analysis and predictive capabilities, leading to crop losses and inefficient resource utilization.",
  "solution": "An AI-powered IoT system that provides real-time crop monitoring, predictive analytics, and automated irrigation control to optimize agricultural productivity.",
  "targetcustomers": "Small to medium-scale farmers, agricultural cooperatives, and agribusiness companies in Kenya and East Africa.",
  "valueproposition": "Increase crop yields by 30%, reduce water usage by 25%, and provide early warning systems for pest and disease management.",
  "competitors": "FarmLogs, Climate Corporation, Aerobotics - but our solution is specifically designed for African farming conditions with offline capabilities.",
  "attraction": "Low-cost hardware, mobile-first design, works offline, provides actionable insights in local languages, and integrates with mobile money systems.",
  "innovation_teams": [
    {
      "name": "Dr. John Kamau",
      "contacts": "john.kamau@uok.ac.ke, +254712345678",
      "role": "Project Lead & AI Specialist"
    },
    {
      "name": "Mary Wanjiku",
      "contacts": "mary.wanjiku@uok.ac.ke, +254723456789",
      "role": "IoT Hardware Developer"
    },
    {
      "name": "Peter Ochieng",
      "contacts": "peter.ochieng@uok.ac.ke, +254734567890",
      "role": "Mobile App Developer"
    }
  ]
}
```

## Updating Innovation Proposal Details

### PUT `/api/v1/proposals/{id}/research`

```json
{
  "researchtitle": "AI-Powered Agricultural Monitoring System",
  "objectives": "To develop and deploy an AI-powered agricultural monitoring system...",
  "hypothesis": "Implementation of AI-powered monitoring will significantly improve crop yields...",
  "significance": "This innovation addresses food security challenges in Kenya...",
  "ethicals": "The project will ensure data privacy and farmer consent...",
  "outputs": "Working prototype, mobile application, research publications...",
  "economicimpact": "Expected to benefit 10,000+ farmers and create 50+ jobs...",
  "res_findings": "Results will be shared through farmer training programs...",
  "commencingdate": "2025-03-01",
  "terminationdate": "2026-02-28",
  "gap": "Updated gap analysis...",
  "solution": "Updated solution description...",
  "targetcustomers": "Updated target customer analysis...",
  "valueproposition": "Updated value proposition...",
  "competitors": "Updated competitive analysis...",
  "attraction": "Updated market attraction strategy...",
  "innovation_teams": [
    {
      "name": "Dr. John Kamau",
      "contacts": "john.kamau@uok.ac.ke, +254712345678",
      "role": "Project Lead & AI Specialist"
    },
    {
      "name": "Mary Wanjiku",
      "contacts": "mary.wanjiku@uok.ac.ke, +254723456789", 
      "role": "IoT Hardware Developer"
    }
  ]
}
```

## Fetching Innovation Teams

### GET `/api/v1/proposals/{id}/innovation-teams`

Response:
```json
{
  "success": true,
  "message": "Innovation teams retrieved successfully",
  "data": [
    {
      "id": 1,
      "proposal_id": 123,
      "name": "Dr. John Kamau",
      "contacts": "john.kamau@uok.ac.ke, +254712345678",
      "role": "Project Lead & AI Specialist",
      "created_at": "2025-02-01T10:00:00.000000Z",
      "updated_at": "2025-02-01T10:00:00.000000Z"
    },
    {
      "id": 2,
      "proposal_id": 123,
      "name": "Mary Wanjiku",
      "contacts": "mary.wanjiku@uok.ac.ke, +254723456789",
      "role": "IoT Hardware Developer",
      "created_at": "2025-02-01T10:00:00.000000Z",
      "updated_at": "2025-02-01T10:00:00.000000Z"
    }
  ]
}
```

## Validation Rules

### For Innovation Proposals (proposaltype = "innovation"):
- `gap`: Required string
- `solution`: Required string  
- `targetcustomers`: Required string
- `valueproposition`: Required string
- `competitors`: Required string
- `attraction`: Required string
- `innovation_teams`: Required array with minimum 1 team member
- `innovation_teams.*.name`: Required string
- `innovation_teams.*.contacts`: Required string
- `innovation_teams.*.role`: Required string

### For Research Proposals (proposaltype = "research"):
- All innovation-specific fields are optional/nullable
- Standard research proposal validation applies

## Error Responses

### Validation Error Example:
```json
{
  "error": {
    "gap": ["Gap field is required for innovation proposals."],
    "innovation_teams": ["Innovation team is required for innovation proposals."],
    "innovation_teams.0.name": ["The innovation teams.0.name field is required."]
  }
}
```

## Database Schema

### Proposals Table (New Fields):
- `gap`: TEXT NULL
- `solution`: TEXT NULL  
- `targetcustomers`: TEXT NULL
- `valueproposition`: TEXT NULL
- `competitors`: TEXT NULL
- `attraction`: TEXT NULL

### Innovation Teams Table:
- `id`: Primary Key
- `proposal_id`: Foreign Key to proposals.proposalid
- `name`: VARCHAR(255)
- `contacts`: VARCHAR(255)
- `role`: VARCHAR(255)
- `created_at`: TIMESTAMP
- `updated_at`: TIMESTAMP