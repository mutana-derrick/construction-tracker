# 🏗️ CONSTRUCTION TRACKER - ARCHITECTURE DIAGRAM

## System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                              │
│                  (Web Browser / Blade Views)                     │
└────────────────────┬────────────────────────────────────────────┘
                     │
        ┌────────────┼────────────┐
        │            │            │
    ┌───▼───┐  ┌────▼────┐  ┌───▼────┐
    │ Login │  │Dashboard│  │Projects│
    │ Page  │  │  View   │  │  View  │
    └───┬───┘  └────┬────┘  └───┬────┘
        │           │           │
        └───────────┼───────────┘
                    │
    ┌───────────────▼───────────────┐
    │   ROUTER (routes/web.php)     │
    │  ├─ POST /login              │
    │  ├─ GET  /dashboard          │
    │  ├─ GET  /projects           │
    │  ├─ POST /projects           │
    │  ├─ GET  /projects/{id}      │
    │  ├─ PUT  /projects/{id}      │
    │  └─ ... (more in Phase 2)    │
    └────────────┬──────────────────┘
                 │
    ┌────────────▼──────────────────┐
    │  CONTROLLER LAYER             │
    │  ├─ DashboardController       │
    │  ├─ ProjectController         │
    │  ├─ AuthController (Breeze)   │
    │  └─ (Log Controllers Phase 2) │
    └────────────┬──────────────────┘
                 │
    ┌────────────▼──────────────────┐
    │  AUTHORIZATION LAYER          │
    │  ├─ ProjectPolicy             │
    │  ├─ @can/@cannot directives   │
    │  ├─ Role checks               │
    │  └─ (More policies Phase 2)   │
    └────────────┬──────────────────┘
                 │
    ┌────────────▼──────────────────┐
    │  MODEL LAYER (Eloquent)       │
    │  ├─ User                      │
    │  ├─ Project                   │
    │  ├─ EquipmentLog              │
    │  ├─ EquipmentCost             │
    │  ├─ ProductivityLog           │
    │  ├─ CasualLabourLog           │
    │  ├─ MaterialUsage             │
    │  └─ MaterialCost              │
    └────────────┬──────────────────┘
                 │
    ┌────────────▼──────────────────┐
    │  DATABASE LAYER (MySQL)       │
    │  ├─ users                     │
    │  ├─ projects                  │
    │  ├─ equipment_logs            │
    │  ├─ equipment_costs           │
    │  ├─ productivity_logs         │
    │  ├─ casual_labour_logs        │
    │  ├─ material_usage            │
    │  └─ material_costs            │
    └───────────────────────────────┘
```

---

## Current Component Map

### Phase 1: Implemented ✅

```
USER AUTHENTICATION
├── Login/Register (Breeze)
├── User Model + DB
├── Email verification
└── Password reset

DASHBOARD
├── DashboardController
├── dashboard.blade.php
└── KPI calculations

PROJECTS MANAGEMENT
├── ProjectController (index, create, store, show, edit, update)
├── ProjectPolicy (authorization)
└── Views/
    ├── projects/index.blade.php
    ├── projects/create.blade.php
    ├── projects/edit.blade.php
    └── projects/show.blade.php
        └── Tabs for:
            ├─ Equipment logs
            ├─ Productivity logs
            ├─ Labour logs
            └─ Material logs
```

### Phase 2: To Be Built 🔧

```
EQUIPMENT LOGS
├── EquipmentLogController
├── EquipmentLogPolicy
├── Views (index, create, edit, show)
└── 5-minute edit enforcement

EQUIPMENT COSTS
├── EquipmentCostController
├── EquipmentCostPolicy
└── Views

PRODUCTIVITY LOGS
├── ProductivityLogController
├── ProductivityLogPolicy
└── Views

CASUAL LABOUR LOGS
├── CasualLabourLogController
├── CasualLabourLogPolicy
└── Views

MATERIAL USAGE
├── MaterialUsageController
├── MaterialUsagePolicy
└── Views

MATERIAL COSTS
├── MaterialCostController
├── MaterialCostPolicy
└── Views

REPORTS
├── ReportController
├── Daily Report (PDF + Excel)
├── Monthly Report (PDF + Excel)
└── Dynamic aggregation
```

---

## Database Relationships Diagram

```
                    ┌─────────┐
                    │  users  │
                    │ (roles) │
                    └────┬────┘
                         │
          ┌──────────────┼──────────────┐
          │              │              │
      ┌───▼──┐        ┌──▼────┐       │
      │created_by    │user_id│    │
      │              │       │       │
┌─────┴──────┐    ┌──▼──────┴─────┴───┐
│ projects   │    │ All Log Tables    │
│            │    │                   │
│ ├─ id      │    │ ├─ equipment_logs │
│ ├─ name    │    │ ├─ equipment_costs│
│ ├─ location│    │ ├─ productivity.. │
│ ├─ desc    │    │ ├─ casual_labour..│
│ ├─ created_by   │ ├─ material_usage │
│ └─ timestamps   │ └─ material_costs │
└────┬─────────┘    └────┬────────────┘
     │                   │
     │  One-to-Many      │
     └───────────────────┘
        (Relationship)
```

---

## Request-Response Flow Example

### Creating a Project

```
1. USER ACTION
   Click "New Project" button on dashboard
   ↓
2. ROUTING
   GET /projects/create
   ↓
3. CONTROLLER
   ProjectController@create
   - Authorization check (@can directive)
   - Load create view
   ↓
4. AUTHORIZATION CHECK
   ProjectPolicy@create()
   - Check: $user->role === 'recorder'
   - If not, throw AuthorizationException
   ↓
5. VIEW RENDERING
   projects/create.blade.php
   - Form with name, location, description
   - CSRF token included
   ↓
6. USER SUBMITS FORM
   POST /projects
   ↓
7. CONTROLLER AGAIN
   ProjectController@store
   - Validate input
   - Check authorization
   ↓
8. DATABASE
   INSERT into projects table
   ↓
9. RESPONSE
   Redirect to /projects with success message
```

---

## 5-Minute Edit Rule (Next Phase)

```
WHEN USER EDITS A LOG:

1. Check if created_at + 5 minutes > now()
   
   if (now()->diffInMinutes($log->created_at) > 5) {
       // Redirect with error: "Can't edit logs after 5 minutes"
   }

2. If within 5 minutes:
   - Allow edit form to load
   - Update the log
   - Update updated_at timestamp

3. Show remaining time to user:
   "You can edit this log for 4 more minutes"
```

---

## Authorization Rules Matrix

```
┌──────────────────┬──────────┬────────┐
│ Action           │ Recorder │ Viewer │
├──────────────────┼──────────┼────────┤
│ View Project     │    ✅    │   ✅   │
│ Create Project   │    ✅    │   ❌   │
│ Edit Project     │    ✅*   │   ❌   │
│ Delete Project   │    ❌    │   ❌   │
│                  │          │        │
│ View Logs        │    ✅    │   ✅   │
│ Create Logs      │    ✅    │   ❌   │
│ Edit Logs (5min) │    ✅    │   ❌   │
│ Delete Logs      │    ❌    │   ❌   │
│                  │          │        │
│ View Reports     │    ✅    │   ✅   │
│ Export PDF/Excel │    ✅    │   ✅   │
│                  │          │        │
│ Edit User        │    ❌    │   ❌   │
│ Delete User      │    ❌    │   ❌   │
└──────────────────┴──────────┴────────┘

* Recorder can only edit own project
** Within 5 minutes of creation only
```

---

## Frontend Stack

```
┌─────────────────────────────────┐
│     Blade Templates (PHP)        │
│  - Server-side rendering        │
│  - Dynamic content              │
│  - Built-in CSRF protection     │
└────────────┬────────────────────┘
             │
    ┌────────▼─────────┐
    │  TailwindCSS v3   │
    │  - Utility-first  │
    │  - Custom colors  │
    │  - Responsive     │
    └────────┬──────────┘
             │
    ┌────────▼──────────┐
    │   Alpine.js v3    │
    │  - Lightweight    │
    │  - Tabs, modals   │
    │  - No build step  │
    └───────────────────┘
```

---

## Deployment & Performance Considerations

### Caching Strategy (To Implement)
```
- Route caching: php artisan route:cache
- Config caching: php artisan config:cache
- View caching: php artisan view:cache
- Database query optimization: eager loading
```

### Frontend Optimization (To Implement)
```
- Asset minification: npm run build
- Lazy loading for images
- Database pagination
- Query indexing on frequently searched fields
```

### Security (Implemented)
```
- ✅ CSRF protection on all forms
- ✅ SQL injection prevention (Eloquent)
- ✅ XSS prevention (Blade escaping)
- ✅ Authentication middleware
- ✅ Authorization policies
- ✅ Password hashing (Bcrypt)
```

---

## Folder Structure

```
construction-tracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php     ✅
│   │   │   ├── ProjectController.php       ✅
│   │   │   ├── EquipmentLogController.php  🔧
│   │   │   └── ... (more Phase 2)
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php                        ✅
│   │   ├── Project.php                     ✅
│   │   └── ... (7 models total)            ✅
│   ├── Policies/
│   │   ├── ProjectPolicy.php               ✅
│   │   └── ... (more Phase 2)
│   └── Providers/
│       ├── AuthServiceProvider.php         ✅
│       └── AppServiceProvider.php
│
├── database/
│   └── migrations/
│       ├── create_users_table
│       ├── create_projects_table           ✅
│       ├── create_equipment_logs_table     ✅
│       └── ... (7 tables total)            ✅
│
├── resources/
│   ├── css/
│   │   └── app.css (TailwindCSS)           ✅
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php               ✅
│   │   ├── dashboard.blade.php             ✅
│   │   └── projects/
│   │       ├── index.blade.php             ✅
│   │       ├── create.blade.php            ✅
│   │       ├── edit.blade.php              ✅
│   │       └── show.blade.php              ✅
│   └── js/
│       └── app.js
│
├── routes/
│   ├── web.php                             ✅
│   ├── api.php
│   └── auth.php (Breeze)                   ✅
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── ... (Laravel configs)
│
├── tailwind.config.js                      ✅
├── postcss.config.js                       ✅
├── composer.json                           ✅
└── package.json                            ✅
```

Legend: ✅ = Phase 1 Complete | 🔧 = Phase 2 To Do

---

## Summary

This architecture provides:
- ✅ Clean separation of concerns (MVC)
- ✅ Authorization & authentication
- ✅ Modern, responsive UI
- ✅ Scalable structure
- ✅ Professional design
- ✅ Ready for Phase 2 expansion

**Status:** Phase 1 Complete - Ready for Phase 2 🚀
