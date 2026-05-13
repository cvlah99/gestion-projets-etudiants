#!/bin/bash
# ============================================================
# compile.sh
# Compiles all Java files (Linux/Mac version)
# Run: bash compile.sh  (from inside the java/ folder)
# ============================================================

echo "Compiling Java files..."

javac -cp ".:mysql-connector.jar" \
    Tache.java \
    DatabaseConnector.java \
    MainTache.java \
    MainValiderTache.java \
    MainRefuserTache.java \
    MainSoumission.java \
    MainStats.java

if [ $? -eq 0 ]; then
    echo ""
    echo "[OK] Compilation successful!"
    echo ""
    echo "Test with:"
    echo "  java -cp .:mysql-connector.jar MainStats"
else
    echo ""
    echo "[ERROR] Compilation failed."
fi
