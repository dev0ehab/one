$root = 'd:\Herd\codebase\resources\views\admin'
Get-ChildItem $root -Recurse -Include 'table.blade.php','provider_table.blade.php' | ForEach-Object {
  $t = [IO.File]::ReadAllText($_.FullName)
  $o = $t
  $t = $t.Replace(' style="color: #17a2b8 !important;"', '')
  $t = $t.Replace(' style="color: #007bff !important;"', '')
  $t = $t.Replace(' style="color: #28a745 !important;"', '')
  $t = $t.Replace(' style="color: #ffc107 !important;"', '')
  $t = $t.Replace(' style="color: #dc3545 !important;"', '')
  if ($t -ne $o) {
    [IO.File]::WriteAllText($_.FullName, $t)
    Write-Host $_.FullName
  }
}
