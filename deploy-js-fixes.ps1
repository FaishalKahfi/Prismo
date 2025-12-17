# Script untuk deploy JavaScript fixes ke Hostinger
# Jalankan script ini setelah push ke Git

Write-Host "==================================" -ForegroundColor Cyan
Write-Host "Deploy JavaScript Fixes" -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

# 1. Git status
Write-Host "1. Checking Git status..." -ForegroundColor Yellow
git status

# 2. Add changes
Write-Host ""
Write-Host "2. Adding changes..." -ForegroundColor Yellow
git add public/js/register.js
git add public/js/prevent-back.js

# 3. Commit
Write-Host ""
Write-Host "3. Committing changes..." -ForegroundColor Yellow
$commitMsg = "fix: Register & prevent-back JS errors - add null checks"
git commit -m $commitMsg

# 4. Push
Write-Host ""
Write-Host "4. Pushing to repository..." -ForegroundColor Yellow
git push origin main

Write-Host ""
Write-Host "==================================" -ForegroundColor Green
Write-Host "DONE! Now do this on Hostinger:" -ForegroundColor Green
Write-Host "==================================" -ForegroundColor Green
Write-Host ""
Write-Host "1. SSH ke Hostinger atau gunakan File Manager" -ForegroundColor White
Write-Host "2. Pull latest changes:" -ForegroundColor White
Write-Host "   cd /home/u326414212/domains/prismo.site/public_html" -ForegroundColor Gray
Write-Host "   git pull origin main" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Clear cache Laravel:" -ForegroundColor White
Write-Host "   php artisan optimize:clear" -ForegroundColor Gray
Write-Host "   php artisan view:clear" -ForegroundColor Gray
Write-Host ""
Write-Host "4. Clear browser cache atau hard refresh (Ctrl+Shift+R)" -ForegroundColor White
Write-Host ""
