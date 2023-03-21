echo %~dp0
pushd %~dp0

REM /tr -insert Scheduler.bat path here-
schtasks /create /sc onlogon /tn "ONSTARTUP MergeCopies" /tr "C:\Users\Amber\PycharmProjects\ALS_SHARED\Scheduler.bat" /f

schtasks /run /tn "ONSTARTUP MergeCopies"


pause