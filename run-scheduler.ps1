# Laravel Scheduler Runner
Write-Host "Running Laravel Scheduler..." -ForegroundColor Green
php artisan schedule:run
Write-Host "Scheduler run completed." -ForegroundColor Green
Read-Host "Press Enter to exit"
