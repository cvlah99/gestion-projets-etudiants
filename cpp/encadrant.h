#ifndef ENCADRANT_H
#define ENCADRANT_H
 
#include <string>
using namespace std;
 

class Encadrant {
 
private:
    string nom;
    string prenom;
    string cle_accee;
    int    id_admin;
 
public:
    Encadrant();
 
    Encadrant(string nom, string prenom, string cle_accee, int id_admin);
 
    Encadrant(const Encadrant& e);
 
    Encadrant& operator=(const Encadrant& e);
 
    string getNom()      const;
    string getPrenom()   const;
    string getCleAccee() const;
    int    getIdAdmin()  const;
 
    void setNom(string nom);
    void setPrenom(string prenom);
    void setCleAccee(string cle_accee);
    void setIdAdmin(int id_admin);
 
    ~Encadrant();
};
 
#endif