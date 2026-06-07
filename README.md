# 🔥 Fire Extinguisher Management System

A full-stack RESTful microservices application for managing fire extinguishers, inspections, and maintenance for TZW LTD.

## 👤 Developer
**Nkusi Malvyn**

## 🔗 Links
- **Frontend:** http://localhost:5173
- **Backend API:** http://127.0.0.1:8000
- **Swagger Docs:** http://127.0.0.1:8000/api/documentation
- **Figma Mockup:** [ADD YOUR FIGMA LINK HERE]

## 🛠️ Tech Stack
- **Frontend:** React.js + Vite
- **Backend:** Laravel (PHP)
- **Database:** MySQL
- **Authentication:** JWT (JSON Web Tokens)
- **API Docs:** Swagger/OpenAPI 3.0
- **Styling:** CSS-in-JS

## 📋 Features
- ✅ User Authentication (Register, Login, Logout)
- ✅ Role-based Access Control (Admin, Inspector, User)
- ✅ Fire Extinguisher CRUD Operations
- ✅ Inspection Scheduling with Personnel Notification
- ✅ Maintenance Log Tracking
- ✅ Real-time Reports (Daily, Monthly, Yearly)
- ✅ PDF & CSV Export
- ✅ Swagger API Documentation
- ✅ CORS Protection
- ✅ Input Validation & Exception Handling
- ✅ Pagination on all records

## 🚀 How to Run

### Backend
```bash
cd fire-extinguisher-api
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

### Frontend
```bash
cd fire-extinguisher-frontend
npm install
npm run dev
```

## 🗄️ Database
- Database name: `fire_extinguisher_db`
- Tables: users, extinguishers, inspections, maintenance_logs
- Export file: `fire_extinguisher_db.sql` (included in repo)

## 📡 API Endpoints

### Auth
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/register | Register new user |
| POST | /api/login | Login user |
| POST | /api/logout | Logout user |
| GET | /api/me | Get current user |
| PUT | /api/profile | Update profile |
| PUT | /api/change-password | Change password |

### Extinguishers
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/extinguishers | List all |
| POST | /api/extinguishers | Create new |
| GET | /api/extinguishers/{id} | Get by ID |
| PUT | /api/extinguishers/{id} | Update |
| DELETE | /api/extinguishers/{id} | Delete |

### Inspections
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/inspections | List all |
| POST | /api/inspections | Schedule new |
| GET | /api/inspections/{id} | Get by ID |
| PUT | /api/inspections/{id} | Update |
| DELETE | /api/inspections/{id} | Delete |

### Maintenance Logs
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/maintenance-logs | List all |
| POST | /api/maintenance-logs | Create log |
| GET | /api/maintenance-logs/{id} | Get by ID |
| PUT | /api/maintenance-logs/{id} | Update |
| DELETE | /api/maintenance-logs/{id} | Delete |

### Reports
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/reports/general | General stats |
| GET | /api/reports/maintenance-history | Maintenance history |
| GET | /api/reports/expired | Expired extinguishers |
| GET | /api/reports/export/csv | Export CSV |
| GET | /api/reports/export/pdf | Export PDF |