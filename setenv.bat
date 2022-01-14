@ECHO OFF

set DRIVE=%cd:~0,2%
set HOME=%DRIVE%\MyFiles
set HOME_LIB=%HOME%\lib

set PATH=%HOME_LIB%\GitPortable\bin;%PATH%

set PATH=%DRIVE%\xampp\php;%PATH%
