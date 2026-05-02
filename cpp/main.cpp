#include <iostream>
#include <cstdlib> // random generation
#include <ctime>   // gives us the current time

#include "GroupCodeGenerator.h"
#include "DatabaseConnector.h"

using namespace std;

int main() {
    srand(time(0)); // start the random generator using the current time

    CodeGenerator G1;
    string code = G1.generateCode();

    cout << "Generated code: " << code << endl;

    DatabaseConnector db;

    if (db.testConnection()) {
        cout << "Connection reussie" << endl;
    } else {
        cout << "Connection failed" << endl;
    }
    if(db.insertCode(code)){
        cout<<"Code c'est  stocker dans le db";
    }else{
        cout<<"failed";
    }

    return 0;
}