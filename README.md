# Healthcare Appointments API (Laravel 12 + Passport + Docker)

A RESTful API built with **Laravel 12** that allows users to **book, view, and cancel healthcare appointments**.  
The app is fully containerized with **Docker** for easy setup and portability.  

---

## 🚀 Features
- User Registration & Login (with Laravel Passport authentication)
- List all Healthcare Professionals
- Book Appointments (with availability & double-booking checks)
- View Appointments (per user)
- Cancel Appointments (not allowed within 24 hours of start time)
- (Optional) Mark Appointment as Completed
- MySQL Database with Seeder
- Dockerized (PHP-FPM, MySQL, Nginx)

---

## 🛠️ Tech Stack
- PHP 8.2 (Laravel 12)
- MySQL 5.7
- Nginx
- Laravel Passport (OAuth2 authentication)
- Docker & Docker Compose

---

## 📂 Project Structure
app/ # Laravel app code
database/ # Migrations & seeders
docker-compose.yml # Docker services
Dockerfile # PHP-FPM container
nginx/conf.d/ # Nginx configs
.env.docker # Env file for Docker
README.md # Project docs



---

## ⚡ Quick Start (with Docker)

### 
1️⃣ Clone the repository
```bash
git clone https://github.com/ronakGitHubb/healthcare-appointments.git
cd healthcare-appointments

2️⃣ Setup environment
cp .env.docker .env

3️⃣ Build & start containers
docker-compose up -d --build

4️⃣ Install dependencies
docker exec -it healthcare-app composer install

5️⃣ Generate app key
docker exec -it healthcare-app php artisan key:generate

6️⃣ Run migrations + seeders
docker exec -it healthcare-app php artisan migrate --seed

7️⃣ Install Passport
docker exec -it healthcare-app php artisan passport:install


Your API is now running at:
👉 http://localhost:8000

🔑 Authentication (Laravel Passport)

This project uses Laravel Passport for token-based authentication.

After registration or login, you’ll receive an access_token.

Add it to Postman under Authorization → Bearer Token for secured requests.

📡 API Endpoints
User Registration
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "secret",
  "password_confirmation": "secret"
}

User Login
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "secret"
}

List Healthcare Professionals
GET /api/professionals
Authorization: Bearer <access_token>

Book Appointment
POST /api/appointments
Authorization: Bearer <access_token>
Content-Type: application/json

{
  "healthcare_professional_id": 1,
  "appointment_start_time": "2025-08-25 14:00:00",
  "appointment_end_time": "2025-08-25 15:00:00"
}

View User Appointments
GET /api/appointments
Authorization: Bearer <access_token>

Cancel Appointment
DELETE /api/appointments/{id}
Authorization: Bearer <access_token>

Mark Appointment as Completed (optional)
PUT /api/appointments/{id}/complete
Authorization: Bearer <access_token>

🧪 Running Tests (optional)
docker exec -it healthcare-app php artisan test
