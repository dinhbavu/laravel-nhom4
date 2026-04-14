Add-Type -AssemblyName System.Drawing
function Compress-Image {
    param([string]$inPath, [string]$outPath, [int]$quality)
    $img = [System.Drawing.Image]::FromFile($inPath)
    $newW = [int]($img.Width / 1.5)
    $newH = [int]($img.Height / 1.5)
    $bmp = New-Object System.Drawing.Bitmap($newW, $newH)
    $g = [System.Drawing.Graphics]::FromImage($bmp)
    $g.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
    $g.DrawImage($img, 0, 0, $newW, $newH)
    $codec = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object { $_.MimeType -eq 'image/jpeg' }
    $ep = New-Object System.Drawing.Imaging.EncoderParameters(1)
    $ep.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter([System.Drawing.Imaging.Encoder]::Quality, [long]$quality)
    $bmp.Save($outPath, $codec, $ep)
    $g.Dispose()
    $bmp.Dispose()
    $img.Dispose()
}

Compress-Image "C:\Users\dinhb\.gemini\antigravity\brain\ce692e38-402b-4797-b580-cd50dea42a44\sapa_optimized_1775918559080.png" "C:\xampp\htdocs\VietGo\public\images\sapa_3d.jpg" 70
Compress-Image "C:\Users\dinhb\.gemini\antigravity\brain\ce692e38-402b-4797-b580-cd50dea42a44\halong_optimized_1775918643392.png" "C:\xampp\htdocs\VietGo\public\images\halong_3d.jpg" 70
Compress-Image "C:\Users\dinhb\.gemini\antigravity\brain\ce692e38-402b-4797-b580-cd50dea42a44\hoian_optimized_1775918665235.png" "C:\xampp\htdocs\VietGo\public\images\hoian_3d.jpg" 70

Write-Output "Done"
