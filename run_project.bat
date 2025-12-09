@echo off
TITLE Bangladesh Railway - One Click Start
ECHO ========================================================
ECHO      BANGLADESH RAILWAY MANAGEMENT SYSTEM (Node.js)
ECHO ========================================================
ECHO.

ECHO 1. STOPPING XAMPP/APACHE (To prevent conflicts)...
taskkill /IM httpd.exe /F >nul 2>&1
ECHO Apache stopped.
ECHO.

ECHO 2. INSTALLING DEPENDENCIES...
call "C:\Program Files\nodejs\npm.cmd" install
ECHO Dependencies installed.
ECHO.

ECHO 3. INITIALIZING DATABASE...
call "C:\Program Files\nodejs\npm.cmd" run seed
ECHO Database seeded.
ECHO.

ECHO 4. LAUNCHING BROWSER...
timeout /t 2 >nul
start http://localhost:3000
ECHO Browser opened.
ECHO.

ECHO 5. STARTING SERVER...
ECHO Server is starting... DO NOT CLOSE THIS WINDOW.
ECHO.
call "C:\Program Files\nodejs\npm.cmd" start

PAUSE
