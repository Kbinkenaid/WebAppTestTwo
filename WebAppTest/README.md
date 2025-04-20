# WebAppTest
This web application is designed to demonstrate various web security testing scenarios and serves as a practical testing ground for security tools like Burp Suite, OWASP ZAP, and Fiddler.

## Application Overview

The application consists of several components that can be used to test different security aspects:

1. Main Information Hub (`cybersecurity.html`)
   - Static content about cybersecurity
   - Navigation to various testing endpoints

2. GET Request Testing (`get_form.html`)
   - Security assessment form using GET method
   - Useful for testing:
     - Parameter tampering
     - URL manipulation
     - Information disclosure in server logs
     - Cross-Site Scripting (XSS)
     - Parameter pollution

3. POST Request Testing (`post_form.html`)
   - Incident report form using POST method
   - Suitable for testing:
     - Form injection
     - CSRF (Cross-Site Request Forgery)
     - Input validation
     - File upload vulnerabilities
     - Content-Type manipulation

4. Authentication Testing (`credentials.php`)
   - Login functionality
   - Test scenarios:
     - SQL injection
     - Brute force attacks
     - Session management
     - Authentication bypass

5. Blog System (`blog.php`)
   - Dynamic content system
   - Test for:
     - XSS persistence
     - SQL injection
     - Access control
     - Data exposure

## Security Testing Guide

### Setting Up Proxy Tools

1. **Burp Suite**
   - Configure proxy to listen on localhost:8080
   - Install Burp certificate in your browser
   - Set browser proxy settings to route through Burp
   - Use the built-in browser for testing

2. **OWASP ZAP**
   - Configure local proxy (default: localhost:8080)
   - Import ZAP certificate
   - Use either manual explore or automated scan

3. **Fiddler**
   - Runs on localhost:8888 by default
   - Enable HTTPS decryption
   - Configure browser to use Fiddler proxy

### Testing Scenarios

1. **GET Form Testing**
   - Intercept and modify parameters
   - Test for parameter pollution by duplicating parameters
   - Try XSS payloads in form fields
   - Check for information disclosure in URLs

2. **POST Form Testing**
   - Modify content-type headers
   - Test for CSRF by removing/modifying tokens
   - Inject malicious payloads in form fields
   - Test file upload functionality if present

3. **Authentication Testing**
   - Attempt SQL injection in login fields
   - Test session token strength
   - Check for authentication bypass
   - Analyze cookie security

4. **General Security Testing**
   - Spider the application
   - Perform automated scans
   - Check for sensitive data exposure
   - Test for HTTP security headers
   - Verify SSL/TLS configuration

## Common Test Cases

1. **SQL Injection**
   ```sql
   ' OR '1'='1
   '; DROP TABLE users--
   ' UNION SELECT username,password FROM users--
