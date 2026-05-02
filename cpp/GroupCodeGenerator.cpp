#include "GroupCodeGenerator.h"
#include <cstdlib> // radom  generation
#include <ctime> // give us the  current  time 

string CodeGenerator::generateCode(){
    string characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
    string code = "";
    
    for (int i = 0; i < 5; i++) {
        int randomIndex = rand() % characters.length(); // choose  a random position  inside
        code += characters[randomIndex];
    }

    return code;
}

//srand(value) chooses the sequence.
//rand() reads numbers from that sequence.