#include <iostream>
#include <cstdlib> // random generation
#include <ctime>   // gives us the current time

#include "GroupCodeGenerator.h"
#include "DatabaseConnector.h"

using namespace std;

int main() {
    srand(time(0)); // start the random generator using the current time

    CodeGenerator G1;
    string code = G1.generateCode(); // here we generate the  code  and  store in  in  string called  code

    
    DatabaseConnector db;
    int id_groupe = db.insertCode(code);  // this fucntion  declared in database connector  called  insertcode accept one string with  is the code  

    
    cout << id_groupe;

    return 0;
}