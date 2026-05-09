#include "Encadrant.h"
 

Encadrant::Encadrant() {
    nom       = "";
    prenom    = "";
    cle_accee = "";
    id_admin  = 0;
}
 

Encadrant::Encadrant(string nom, string prenom, string cle_accee, int id_admin) {
    this->nom       = nom;
    this->prenom    = prenom;
    this->cle_accee = cle_accee;
    this->id_admin  = id_admin;
}
 

Encadrant::Encadrant(const Encadrant& e) {
    this->nom       = e.nom;
    this->prenom    = e.prenom;
    this->cle_accee = e.cle_accee;
    this->id_admin  = e.id_admin;
}
 

Encadrant& Encadrant::operator=(const Encadrant& e) {
    // Vérifier que ce n'est pas la même instance
    if (this != &e) {
        this->nom       = e.nom;
        this->prenom    = e.prenom;
        this->cle_accee = e.cle_accee;
        this->id_admin  = e.id_admin;
    }
    return *this;
}
 

string Encadrant::getNom()      const { 
    return nom;       
}
string Encadrant::getPrenom()const{ 
    return prenom;    
}
string Encadrant::getCleAccee() const { 
    return cle_accee; 
}
int    Encadrant::getIdAdmin()const{ 
    return id_admin;  
}
 

void Encadrant::setNom(string nom){
     this->nom = nom;
}
void Encadrant::setPrenom(string prenom){ 
    this->prenom    = prenom;
}
void Encadrant::setCleAccee(string cle){ 
    this->cle_accee = cle;
}
void Encadrant::setIdAdmin(int id){
     this->id_admin  = id;
}
 

Encadrant::~Encadrant() {
}
 