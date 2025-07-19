# 🎓 Internship Management System (IMS)

The **Internship Management System (IMS)** is a web-based platform designed to simplify and manage the end-to-end process of internships between students, companies, and administrators. It provides registration, job application, and internship tracking functionalities in one unified portal.

## 🔧 Key Features

### 👨‍🎓 Student Panel:
- Register and manage profile
- View available internship opportunities
- Apply for internships (upload resume & cover letter)
- Track application status
- Register for workshops/events

### 🏢 Company Panel:
- Register and login
- Post internship/job vacancies
- View student applicants
- Schedule workshops or webinars
- Track responses to posted positions

### 🛠️ Admin Panel:
- Login directly (no registration)
- View and manage all students and companies
- Approve or reject company accounts
- Monitor posted vacancies and student applications
- Generate reports

## 🗃️ Database Design (MySQL)

**Tables:**
- `students` – id, name, email, password, resume, etc.
- `company` – id, name, email, password, description, etc.
- `vacancies` – id, company_id, title, description, skills_required, last_date
- `applications` – id, student_id, vacancies_id, company_id, resume, cover_letter, status (default: pending)
- `events` – id, company_id, title, description, date, time
- `event_registered` – id, student_id, event_id, name, email, phone

## 💻 Tech Stack

- **Frontend**: HTML, CSS, Bootstrap
- **Backend**: PHP
- **Database**: MySQL
- **Tools**: VS Code, XAMPP/LAMP

## 📋 Modules

1. **Login/Register System** – For students and companies
2. **Dashboard** – Role-based dashboards with job cards and event listings
3. **Application System** – For applying and reviewing internship applications
4. **Workshop/Event Management** – Company can host and manage events
5. **Admin Management** – Approvals, Reports, Overview

## 📸 Screenshots

> Add snapshots of dashboard, vacancy listings, application form, and admin panel here.

## 🧠 Future Improvements

- Email notifications on application updates
- Resume parsing with AI
- Admin analytics dashboard
- Internship certificate generation
- Chat system for student-company communication

## 🙌 Contribution

Have ideas for improvements or want to contribute? Fork the repo, push changes, and submit a pull request!

## 📄 License

This project is made for educational purposes as part of MCA project submission.

---

**Made with 💡 by Arpit Kukadiya**
