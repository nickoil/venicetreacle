# deploy.ps1
# Nick 2025-08-08
# This PowerShell script automates the deployment of a Laravel application
# using Git, Composer, NPM, and Docker. It assumes you have the necessary tools installed
# and configured on your local machine and the server.

# === Variables ===
$repoUrl = "git@github.com:nickoil/venicetreacle.git"
$buildFolder = "D:\Websites\venicetreacle\builds\venicetreacle-$(Get-Date -Format yyyyMMddHHmmss)"
#$buildFolder = "D:\Websites\venicetreacle\builds\venicetreacle-20250808145810"
$tarFile = "$env:TEMP\venicetreacle_build.tar.gz"
$remoteHost = "venicetreacle"
$deployPath = "/var/www/venicetreacle"

$currentFolder = Get-Location
Write-Host "Current folder: $currentFolder"

# === Step 0: Clean existing build folder if it exists ===
$parentFolder = Split-Path $buildFolder -Parent
Write-Host "Parent build folder: $parentFolder"
if (Test-Path $parentFolder) {
    Write-Host "Purging previous builds in: $parentFolder"
    Get-ChildItem $parentFolder -Directory | ForEach-Object {
        Write-Host "Removing build folder: $($_.FullName)"
        Remove-Item $_.FullName -Recurse -Force
    }
}

# === Step 1: Clone the master branch fresh ===
Write-Host "Cloning repository to $buildFolder"
git clone --branch master $repoUrl $buildFolder

# === Step 2: Run composer and npm build ===
Set-Location $buildFolder\venicetreacle_app 

#Write-Host "Running composer install..."
composer install --no-dev --optimize-autoloader

#Write-Host "Running npm ci and build..."
npm ci
npm run build

# === Step 3: Create tar.gz archive of build folder ===
Write-Host "Creating tarball..."
tar -czf $tarFile -C $buildFolder .

# === Step 4: Upload tarball to server ===
Write-Host "Uploading tarball..."
scp "$tarFile" "${remoteHost}:/tmp/venicetreacle_build.tar.gz"

# === Step 5: SSH to server, extract, and swap ===
Write-Host "Extracting and swapping on server..."
ssh $remoteHost @"
set -e
sudo rm -rf ${deployPath}.old # do this at the beginning and leave in place afterwards in case of failure
sudo mkdir -p ${deployPath}.new
sudo tar -xzf /tmp/venicetreacle_build.tar.gz -C ${deployPath}.new
sudo rm /tmp/venicetreacle_build.tar.gz
# Move existing deploy path to .old, preserving config files and storage
sudo cp -a ${deployPath}/venicetreacle_app/.env ${deployPath}.new/venicetreacle_app 2>/dev/null || true
sudo cp -a ${deployPath}/venicetreacle_app/storage ${deployPath}.new/venicetreacle_app 2>/dev/null || true
sudo mv ${deployPath} ${deployPath}.old 2>/dev/null || true
sudo mv ${deployPath}.new ${deployPath}
cd ${deployPath}/venicetreacle_app
php artisan migrate --force
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan storage:link
"@

# === Step 6: Return to original folder ===
Set-Location $currentFolder

Write-Host "Deployment complete!"
