#ifndef CLEGENERATOR_H
#define CLEGENERATOR_H
 
#include <string>
using namespace std;
 

class CleGenerator {
 
private:
    int    longueur;   // longueur de la clé
    string caracteres; // caractères utilisés pour la génération
 
public:
   
    CleGenerator();
 
    CleGenerator(int longueur);
 
    CleGenerator(const CleGenerator& c);
 
    CleGenerator& operator=(const CleGenerator& c);
 
    string genererCle();
 
    int    getLongueur()   const;
    string getCaracteres() const;
 
    void setLongueur(int longueur);
 
    ~CleGenerator();
};
 
#endif
