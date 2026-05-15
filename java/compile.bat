@echo off
REM ============================================================
REM compile.bat
REM Compiles all Java files in the java/ folder.
REM Run this once from inside the java/ folder.
REM
REM Requirements:
REM   - Java JDK installed (java and javac must be in PATH)
REM   - mysql-connector.jar must be in this folder
REM     Download from: https://dev.mysql.com/downloads/connector/j/
REM     Choose: Platform Independent -> .zip
REM     Then copy mysql-connector-j-X.X.X.jar here and rename to mysql-connector.jar
REM ============================================================

echo Compiling Java files...

REM Compile all .java files together (they depend on each other)
javac -cp ".;mysql-connector.jar" Tache.java DatabaseConnector.java MainTache.java MainValiderTache.java MainRefuserTache.java MainSoumission.java MainStats.java

if %ERRORLEVEL% == 0 (
    echo.
    echo [OK] Compilation successful!
    echo.
    echo You can now test with:
    echo   java -cp ".;mysql-connector.jar" MainStats
) else (
    echo.
    echo [ERROR] Compilation failed. Check the errors above.
)

pause
