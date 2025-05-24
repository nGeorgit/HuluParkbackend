# HuluPark Backend Setup Guide

## Prerequisites

- [XAMPP](https://www.apachefriends.org/index.html) installed on your machine.

## Setup Instructions

1. **Start XAMPP**  
   Open the XAMPP control panel and start the following modules:
   - Apache
   - MySQL

2. **Access phpMyAdmin**  
   Open your browser and go to:  
   `http://localhost/phpmyadmin`

3. **Create a New Database**
   - Click on **"New"** in the left sidebar.
   - Enter the name of the database (e.g., `hulupark`) and click **"Create"**.

4. **Import the Database**
   - With the new database selected, go to the **"Import"** tab.
   - Click **"Choose File"** and select the `hulupark.sql` file from the project directory.
   - Click **"Go"** to import the database.

5. **You're Done!**  
   The database is now set up and ready to use with the HuluPark backend.

---

If you run into any issues, make sure:
- MySQL is running in XAMPP.
- The `.sql` file is valid and correctly formatted.
- You are importing into the correct database.

