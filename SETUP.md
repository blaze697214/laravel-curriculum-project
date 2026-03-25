# Laravel Project Setup Guide 🚀

This document explains how to:

* Create the Laravel project
* Push it to GitHub
* Clone and set it up on another device

---

# 1️⃣ Creating a Laravel Project

## Install Composer (if not installed)

Check installation:

```
composer --version
```

If not installed, download from:

```
https://getcomposer.org
```

---

## Install Laravel Installer

```
composer global require laravel/installer
```

---

## Create New Laravel Project

Using Laravel installer:

```
laravel new project-name
```

OR using Composer:

```
composer create-project laravel/laravel project-name
```

---

## Move into Project Folder

```
cd project-name
```

---

## Run Laravel Development Server

```
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

# 2️⃣ Upload Project to GitHub

## Initialize Git Repository

```
git init
```

---

## Ensure `.gitignore` Includes

```
/vendor
/node_modules
.env
```

`.env` should **never be pushed to GitHub**.

---

## Add Project Files

```
git add .
```

---

## Commit Files

```
git commit -m "Initial Laravel Project"
```

---

## Connect GitHub Repository

Create a new repository on GitHub and copy the repository URL.

Example:

```
https://github.com/username/project-name.git
```

Add remote:

```
git remote add origin https://github.com/username/project-name.git
```

---

## Push Code to GitHub

```
git branch -M main
git push -u origin main
```

Project is now uploaded to GitHub ✅

---

# 3️⃣ Setup Project on Another Device

## Install Required Software

Ensure these are installed:

* PHP
* Composer
* MySQL
* Node.js
* npm
* Git

Check installation:

```
php -v
composer -V
node -v
npm -v
git --version
```

---

## Clone the Repository

```
git clone https://github.com/username/project-name.git
```

Move into project directory:

```
cd project-name
```

---

## Install PHP Dependencies

```
composer install
```

---

## Install Frontend Dependencies

```
npm install
```

---

## Create Environment File

Linux / Mac:

```
cp .env.example .env
```

Windows:

```
copy .env.example .env
```

---

## Generate Application Key

```
php artisan key:generate
```

---

## Configure Database

Edit `.env` file:

```
DB_DATABASE=database_name
DB_USERNAME=root
DB_PASSWORD=
```

---

## Run Database Migrations

```
php artisan migrate
```

---

## Build Frontend Assets

Development build:

```
npm run dev
```

Production build:

```
npm run build
```

---

## Run Laravel Server

```
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

Project is now running successfully 🎉

---

# 4️⃣ Pull Latest Updates

When changes are pushed to GitHub, update project using:

```
git pull
```

If dependencies changed:

```
composer install
npm install
```

---

# 5️⃣ Common Useful Commands

Clear cache:

```
php artisan optimize:clear
```

Run migrations:

```
php artisan migrate
```

Rollback migrations:

```
php artisan migrate:rollback
```

Seed database:

```
php artisan db:seed
```

---

# 📌 Notes

* `.env` file is not included in GitHub for security reasons.
* Always run `composer install` after cloning the project.
* Run `php artisan key:generate` if the application key is missing.

---

# 👨‍💻 Happy Coding
