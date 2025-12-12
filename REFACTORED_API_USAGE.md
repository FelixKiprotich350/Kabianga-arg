# Refactored Proposal API with Meta Tables

## Overview
Proposals now use separate meta tables for type-specific data:
- `proposal_research_meta` - Research proposal specific fields
- `proposal_innovation_meta` - Innovation proposal specific fields
- `innovation_teams` - Linked directly to proposals
- `collaborators` - Linked directly to proposals

## Database Structure

### Proposals Table (Core)
- Basic proposal info (title, dates, type, status, etc.)

### Meta Tables
- `proposal_research_meta`: objectives, hypothesis, significance, ethicals, expoutput, socio_impact, res_findings
- `proposal_innovation_meta`: gap, solution, targetcustomers, valueproposition, competitors, attraction

### Direct Relations
- `innovation_teams`: proposal_id, name, contacts, role
- `collaborators`: proposalidfk, collaboratorname, position, institution

## API Usage

### Create Innovation Proposal
```json
POST /api/v1/proposals
{
  "proposaltype": "innovation",
  "grantnofk": 1,
  "departmentfk": "dept-uuid",
  "themefk": 1,
  "researchtitle": "AI Agricultural System",
  "gap": "Lack of real-time monitoring...",
  "solution": "AI-powered IoT system...",
  "targetcustomers": "Small-scale farmers...",
  "valueproposition": "30% yield increase...",
  "competitors": "FarmLogs, Climate Corp...",
  "attraction": "Low-cost, offline capable...",
  "innovation_teams": [
    {
      "name": "Dr. John Kamau",
      "contacts": "john@uok.ac.ke",
      "role": "Project Lead"
    }
  ]
}
```

### Update Research Details
```json
PUT /api/v1/proposals/{id}/research
{
  "researchtitle": "Updated Title",
  "commencingdate": "2025-03-01",
  "terminationdate": "2026-02-28",
  
  // For research proposals
  "objectives": "Research objectives...",
  "hypothesis": "Research hypothesis...",
  "significance": "Study significance...",
  "ethicals": "Ethical considerations...",
  "outputs": "Expected outputs...",
  "economicimpact": "Economic impact...",
  "res_findings": "Findings utilization...",
  
  // For innovation proposals
  "gap": "Market gap...",
  "solution": "Proposed solution...",
  "targetcustomers": "Target market...",
  "valueproposition": "Value proposition...",
  "competitors": "Competitive analysis...",
  "attraction": "Market attraction...",
  "innovation_teams": [...]
}
```

### Fetch Proposal with Meta Data
```json
GET /api/v1/proposals/{id}
{
  "success": true,
  "data": {
    "proposalid": 123,
    "researchtitle": "AI Agricultural System",
    "proposaltype": "innovation",
    "innovation_meta": {
      "gap": "Lack of real-time monitoring...",
      "solution": "AI-powered IoT system...",
      "targetcustomers": "Small-scale farmers...",
      "valueproposition": "30% yield increase...",
      "competitors": "FarmLogs, Climate Corp...",
      "attraction": "Low-cost, offline capable..."
    },
    "innovation_teams": [
      {
        "name": "Dr. John Kamau",
        "contacts": "john@uok.ac.ke",
        "role": "Project Lead"
      }
    ],
    "collaborators": [...],
    "expenditures": [...]
  }
}
```

## Benefits
- **Clean separation**: Type-specific data in dedicated tables
- **Scalable**: Easy to add new proposal types
- **Maintainable**: Clear data relationships
- **Flexible**: Direct relations for shared entities (teams, collaborators)