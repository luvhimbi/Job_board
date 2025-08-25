# Job Board & CV Management App

A Laravel-based web application designed to help small businesses post jobs, manage applicants, and enable candidates to create, upload, and download CVs. This platform also includes automatic skill parsing from uploaded resumes to make hiring easier and more efficient.

## Features

### For Applicants
- Browse open jobs with search by title, company, or location
- Apply to jobs with optional cover letter and resume upload
- Build and manage personal CVs
- Download CV as PDF
- Skills parsed automatically from uploaded resumes
- Receive notifications when application is submitted

### For Employers/Companies
- Create company profiles and verify accounts
- Post and manage jobs quickly
- View applications and track applicant details
- Auto-highlight key skills for each applicant
- Access analytics: number of views and applicants per job
- Featured jobs and social sharing (optional)

### Common Features
- Authentication and role-based access (Applicant / Company / Admin)
- Responsive UI built with Bootstrap
- Job and applicant search with filtering
- Tips section for interview and job market guidance
- CV parsing (PDF/DOCX) to extract key skills automatically

## Tech Stack
- **Backend**: Laravel PHP
- **Frontend**: Blade templates, Bootstrap CSS
- **Database**: MySQL / PostgreSQL
- **File Storage**: Laravel filesystem (resumes, CVs)
- **PDF Generation**: Barryvdh/DomPDF
- **CV Parsing**: Smalot/PDFParser & PhpOffice/PHPWord

## Setup Instructions

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL or PostgreSQL
- Node.js and NPM (for frontend assets)
- Git

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/luvhimbi/job-board-cv-app.git
   cd job-board-cv-app
