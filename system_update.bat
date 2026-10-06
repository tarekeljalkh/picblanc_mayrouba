@echo off
setlocal
cd /d "%~dp0"

:: Do not update over local client changes
for /f "delims=" %%i in ('git status --porcelain') do (
    echo Local changes found. Save or commit them before updating.
    pause
    exit /b 1
)

echo Fetching updates from main...
git fetch origin main
if errorlevel 1 goto failed

:: Switch to local main, or create it from origin/main for older clients
git show-ref --verify --quiet refs/heads/main
if errorlevel 1 (
    git switch --track -c main origin/main
) else (
    git switch main
)
if errorlevel 1 goto failed

:: Update only when the local branch can safely fast-forward
git merge --ff-only origin/main
if errorlevel 1 goto failed

echo System updated successfully.
pause
exit /b 0

:failed
echo Update failed. No local changes were overwritten.
pause
exit /b 1
