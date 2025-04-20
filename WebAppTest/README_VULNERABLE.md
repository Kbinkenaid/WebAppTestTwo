# Vulnerable Web Application - Fresh Groceries

This is a deliberately vulnerable web application created for educational purposes to demonstrate common web security vulnerabilities. It simulates a grocery delivery website with various security flaws that can be exploited.

**WARNING: This application contains intentional security vulnerabilities. DO NOT deploy it on a public server or in a production environment. It should only be used in a controlled, isolated environment for educational purposes.**

## Overview

Fresh Groceries is a fictional grocery delivery website that allows users to:
- Register and login
- Browse grocery items
- View and edit their profile
- Read and post on a community blog
- View various files and documents

The application has been designed with several security vulnerabilities to demonstrate common web attacks.

## Implemented Vulnerabilities

1. **Persistent XSS (Cross-Site Scripting)**
   - Location: Blog page (`blog_new.php`)
   - Vulnerability: User input in blog posts and comments is not sanitized before being displayed
   - Impact: Attackers can inject malicious JavaScript that executes in victims' browsers

2. **SQL Injection**
   - Location: Login page (`index.php`) and registration page (`register.php`)
   - Vulnerability: User input is directly concatenated into SQL queries without prepared statements
   - Impact: Attackers can bypass authentication, extract data, or manipulate the database

3. **Local File Inclusion (LFI)**
   - Location: File viewer (`view_file.php`)
   - Vulnerability: The file parameter is not properly validated
   - Impact: Attackers can read arbitrary files on the server using path traversal

4. **Brute Force Attack**
   - Location: Login page (`index.php`)
   - Vulnerability: No account lockout or rate limiting mechanisms
   - Impact: Attackers can repeatedly attempt to guess passwords

5. **Insecure Direct Object References (IDOR)**
   - Location: Profile page (`profile.php`)
   - Vulnerability: User IDs are exposed in URLs and not properly authorized
   - Impact: Attackers can access or modify other users' data by manipulating the ID parameter

## Setup Instructions

1. Make sure you have a web server with PHP and MySQL installed (e.g., XAMPP, WAMP, or LAMP)
2. Place the application files in your web server's document root
3. Run the setup script to create the database and necessary files:
   ```
   ./run_setup.sh
   ```
4. Access the application at: `http://localhost/WebAppTest/`
5. Access the attacker website at: `http://localhost/WebAppTest/attackersite/`

## Default Credentials

- Admin user:
  - Username: `admin`
  - Password: `admin123`

- Regular user:
  - Username: `user`
  - Password: `password123`

## Attacking the Application

The included attacker website (`attackersite/index.html`) provides tools and instructions for exploiting each vulnerability:

1. **Persistent XSS Attack**
   - Craft malicious JavaScript payloads
   - Post them to the blog
   - Steal cookies or log keystrokes

2. **SQL Injection Attack**
   - Bypass authentication
   - Extract sensitive data from the database

3. **Local File Inclusion Attack**
   - Read sensitive system files
   - Access configuration files

4. **Brute Force Attack**
   - Attempt to guess passwords
   - Gain unauthorized access

5. **IDOR Attack**
   - Access other users' profiles
   - View or modify unauthorized data

## Educational Purpose

This application is designed for:
- Security professionals learning to identify vulnerabilities
- Developers learning about secure coding practices
- Students studying web security concepts
- CTF (Capture The Flag) practice

## Legal Disclaimer

This application is provided for educational purposes only. The creators are not responsible for any misuse or damage caused by this application. Only use it in a controlled environment with proper authorization.
