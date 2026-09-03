$baseUrl = "http://localhost/devel/payroll_app"

$health = Invoke-RestMethod -Uri "$baseUrl/health" -Method Get
if ($health.status -ne "ok") { throw "Health check failed" }

try {
    Invoke-WebRequest -Uri "$baseUrl/api/v1/presensi" -Method Get -UseBasicParsing -ErrorAction Stop | Out-Null
    throw "Expected HTTP 401 for missing token"
} catch {
    if ($_.Exception.Response.StatusCode.value__ -ne 401) { throw }
}

Write-Output "Health check passed"
Write-Output "Missing-token check passed"
Write-Output "Upload, duplicate retry, list, and detail checks require database.sql to be imported first."
