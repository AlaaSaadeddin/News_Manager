📰 News Management System

## 🔹 Live Demo  
You can check out the live version of the project here:  
👉 [News Management System - Live Demo]([http://your-infinityfree-link.infinityfreeapp.com](https://newsmanager.42web.io/deleted_news.php))

## 🔹 Challenge

Managing online news articles is often difficult without a structured system. News editors need to:

Create categories (e.g., Sports, Politics, Technology).

Add and update news articles with details like title, description, and images.

Manage users who post the news.

Soft delete and restore news when needed.


Without a proper system, all of this can be messy and unorganized.


---

## 🔹 Solution

This project is a News Management System built with PHP and MySQL that allows:

Users to register and log in.

Admins to add, edit, delete, and view categories.

Adding, editing, soft deleting, and restoring news articles.

Managing all data in a MySQL database through a simple dashboard.



---

## 🔹 User Stories

#### As a User:

I can sign up for a new account.

I can log in and log out securely.

I can add a new news article with title, details, image, and category.

I can edit or delete (soft delete) my news articles.

I can view all news articles including deleted ones.


#### As an Admin:

I can manage categories (add / view).

I can manage all news (view, edit, delete).

I can monitor all users and their activity.



---

## 🔹 The MVP

User authentication (sign up / login).

Category management.

Add, edit, delete news.

Dashboard to display all articles.



---

## 🔹 Setup Instructions

1. Clone the repository

git clone https://github.com/AlaaSaadeddin/News_Manager.git

2. Setup Database

Create a new MySQL database (e.g., news_db) and import the provided SQL file.

Tables:

users → id, name, email, password

categories → id, name

news → id, title, category_id, details, image, user_id, status


3. Configure the Project

Update the database connection in config.php:

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "news_db";

4. Run the Project

Move the project folder to htdocs (XAMPP) and run:

http://localhost/news-management


---

## 🔹 Technologies

Backend: PHP

Database: MySQL

Frontend: HTML, CSS 



---
