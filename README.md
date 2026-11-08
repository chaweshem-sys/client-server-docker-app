# Containerizing a Client–Server Application

**Course:** Distributed Systems — Kabarak University  
**Practical:** Containerizing a PHP–MySQL Client–Server Application

## 1. Project contents
- `Dockerfile` — builds the PHP 8.2/Apache web container and installs PDO MySQL support.
- `docker-compose.yml` — defines the web, MySQL, and Mailpit services.
- `init.sql` — creates the `contact_db` database, `users` and `contacts` tables.
- PHP files — implement account registration/login, contact entry/search, password recovery, and logout.
- `msmtprc` — routes PHP email through Mailpit's SMTP service.

## 2. Requirements
Install Docker Desktop and Visual Studio Code. Start Docker Desktop before running the commands.

## 3. Run the application
Open this project folder in VS Code, open a PowerShell terminal, and run:

```powershell
docker compose up --build -d
docker compose ps
```

Open:
- Application: http://localhost:8080/index.php
- Mailpit inbox: http://localhost:8025

To view service logs:
```powershell
docker compose logs web
docker compose logs db
docker compose logs mail
```

## 4. Demonstration / testing checklist
1. **Task B — Registration and login:** Create an account from the login page, then sign in and confirm that the dashboard opens.
2. **Task C — Database write:** Enter a unique registration number, mobile number, email, and physical address. Confirm the success message.
3. **Task D — Database read:** Search using the same registration number and confirm the saved contact is displayed.
4. **Task A — Password recovery:** Log out, request a password reset, open Mailpit, open the captured message, follow its reset link, set a new password, and sign in using the new password.
5. Confirm that the web, database, and Mailpit containers are running using `docker compose ps`.

**Important:** Mailpit captures test email locally. It does not send the reset message to a real external mailbox.

## 5. Stop the application
```powershell
docker compose down
```
Do not add `-v` unless you intentionally want to delete the database volume and its stored data.

## 6. Evidence to capture for Moodle
Take your own screenshots after running the application:
- `docker compose ps` showing the services running.
- Successful account registration/login and the dashboard.
- Successful contact registration message.
- Search results showing the saved contact.
- Mailpit showing the password-reset email.
- Successful password update/login.

Place the screenshots in an `evidence` folder before submission if your Moodle instructions request evidence. Do not submit screenshots of tests you have not actually performed.

## 7. Notes
The database credentials in this classroom demonstration are for local practical use only. Change them before any real deployment. The supplied SQL includes a demonstration account hash; create your own account through the registration page for testing.
