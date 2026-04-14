Add-Type -AssemblyName System.IO.Compression.FileSystem
$source = "C:\xampp\htdocs\VietGo"
$destZip = "C:\xampp\htdocs\VietGo\Upload-To-InfinityFree.zip"
Remove-Item $destZip -Force -ErrorAction SilentlyContinue
$tempDir = "C:\xampp\htdocs\VietGo_TempZip"
Remove-Item $tempDir -Recurse -Force -ErrorAction SilentlyContinue
New-Item -ItemType Directory -Force -Path $tempDir | Out-Null
$folders = @("app", "bootstrap", "config", "database", "public", "resources", "routes", "storage")
foreach ($folder in $folders) {
    Copy-Item "$source\$folder" -Destination "$tempDir\$folder" -Recurse -Force -ErrorAction SilentlyContinue
}
$files = @("index.php", ".env", ".htaccess", "composer.json", "artisan")
foreach ($file in $files) {
    Copy-Item "$source\$file" -Destination "$tempDir\$file" -Force -ErrorAction SilentlyContinue
}
Remove-Item "$tempDir\storage\framework\views\*.php" -Force -ErrorAction SilentlyContinue
Remove-Item "$tempDir\storage\framework\cache\data\*" -Recurse -Force -ErrorAction SilentlyContinue
[System.IO.Compression.ZipFile]::CreateFromDirectory($tempDir, $destZip)
Remove-Item $tempDir -Recurse -Force
Write-Output "Zip re-generated"
