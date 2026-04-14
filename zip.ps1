Add-Type -AssemblyName System.IO.Compression.FileSystem
Remove-Item C:\xampp\htdocs\VietGo\patch-update.zip -Force -ErrorAction SilentlyContinue
Remove-Item C:\xampp\htdocs\VietGo\patch -Recurse -Force -ErrorAction SilentlyContinue
New-Item -ItemType Directory -Force -Path C:\xampp\htdocs\VietGo\patch\public\images | Out-Null
New-Item -ItemType Directory -Force -Path C:\xampp\htdocs\VietGo\patch\resources\views\layouts | Out-Null
Copy-Item C:\xampp\htdocs\VietGo\public\build -Destination C:\xampp\htdocs\VietGo\patch\public -Recurse -Force
Copy-Item C:\xampp\htdocs\VietGo\public\images\*_3d.jpg -Destination C:\xampp\htdocs\VietGo\patch\public\images\ -Force
Copy-Item C:\xampp\htdocs\VietGo\resources\views\layouts\3d-app.blade.php -Destination C:\xampp\htdocs\VietGo\patch\resources\views\layouts\ -Force
[System.IO.Compression.ZipFile]::CreateFromDirectory("C:\xampp\htdocs\VietGo\patch", "C:\xampp\htdocs\VietGo\patch-update.zip")
Remove-Item C:\xampp\htdocs\VietGo\patch -Recurse -Force -ErrorAction SilentlyContinue
Write-Output "Zip done"
