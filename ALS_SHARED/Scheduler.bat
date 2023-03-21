@echo off

REM /tr -insert MergeCopiesStudent.bat path here-
schtasks /create /sc minute /tn "StudentMasterlist Task" /tr "\"C:\Users\Amber\PycharmProjects\ALS_SHARED\Student Masterlist\MergeCopiesStudent.bat"" /mo 10 /f

REM /tr -insert MergeCopiesTeacher.bat path here-
schtasks /create /sc minute /tn "AuthorizedUsers Task" /tr "\"C:\Users\Amber\PycharmProjects\ALS_SHARED\Authorized User Masterlist\MergeCopiesTeacher.bat\"" /mo 10 /f

schtasks /run /tn "StudentMasterlist Task"
schtasks /run /tn "AuthorizedUsers Task"

pause