#include <iostream>
#include <string>
#include "Encadrant.h"
#include "CleGenerator.h"
#include "DatabaseConnector.h"
using namespace std;
 

int main(int argc, char* argv[]) {
 
    
    if (argc != 4) {
        cout << "ERREUR: arguments manquants" << endl;
        return 1;
    }
 
   
    string nom      = argv[1];   
    string prenom   = argv[2];   
    int    id_admin = atoi(argv[3]); 
 
    CleGenerator cg(8);
    string cle = cg.genererCle();
    // ex: "AB3XY9KL"
 
    Encadrant e(nom, prenom, cle, id_admin);
 
    DatabaseConnector db;
    bool succes = db.insererEncadrant(
        e.getNom(),
        e.getPrenom(),
        e.getCleAccee(),
        e.getIdAdmin()
    );
 
    if (succes) {
        cout << cle << endl; 
    } else {
        cout << "ERREUR" << endl;
    }
 
    return 0;
}