#include "CleGenerator.h"
#include <cstdlib>
#include <ctime>

CleGenerator::CleGenerator() {
    longueur   = 8;
    caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
}
 

CleGenerator::CleGenerator(int longueur) {
    this->longueur = longueur;
    this->caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
}
 

CleGenerator::CleGenerator(const CleGenerator& c) {
    this->longueur   = c.longueur;
    this->caracteres = c.caracteres;
}
 

CleGenerator& CleGenerator::operator=(const CleGenerator& c) {
    if (this != &c) {
        this->longueur   = c.longueur;
        this->caracteres = c.caracteres;
    }
    return *this;
}
 

string CleGenerator::genererCle() {
    srand(time(0)); // initialiser le générateur aléatoire
 
    string cle = "";
 
    for (int i = 0; i < longueur; i++) {
        int index = rand() % caracteres.size();
        cle += caracteres[index];
    }
 
    return cle;
}

int CleGenerator::getLongueur()const{
     return longueur;
    }
string CleGenerator::getCaracteres()const{
    return caracteres; 
}
 

void CleGenerator::setLongueur(int longueur) {
    this->longueur = longueur;
}
 

CleGenerator::~CleGenerator() {

}