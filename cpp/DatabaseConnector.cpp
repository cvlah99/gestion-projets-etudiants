#include "DatabaseConnector.h"
#include <iostream>
#include <mariadb/mysql.h>
#include <string>
using namespace std;


using namespace std;

bool DatabaseConnector::testConnection() {
    MYSQL* conn; // we acces to  connection  object  that is  stored  in  the  memory via the adress so  we use the  pointer 

    conn = mysql_init(nullptr); //mysql_init() creates the connection object somewhere in memory conn stores the address of that object

    if (conn == nullptr) { // we check if mysql has prepared the conncection  object 
        cout << "mysql_init failed" << endl; // here if conn the  object that we create  doesn't  point  to  the  pointer  so  automataclly has no  pointer so  null so  the connection  has failed 
        return false;
    }

    conn = mysql_real_connect( 
        conn,
        "localhost",              
        "root",                   
        "yassine123!@",                       
        "gestion_de_project",     
        3306,                     
        nullptr, // tell  my  sql to use the normal TCP connection with localhost and port 3306 (TCP is a conncection  protocol)
        0 // means no  special  connection  options
    );

    if (conn == nullptr) { // here we check if the connection  to mysql server succeed
        cout << "Connection failed: " << mysql_error(conn) << endl;
        return false;
    }
    string query = "SELECT * FROM groupe ";
    if(mysql_query(conn, query.c_str())){  // mysql_querry(means if mysql return and error)  needs two parameter the active db connection conn and the sql command to execute it does not accept the c++ string  style it  accept C-style string that's why we use c_str function 
        cout<<"Query failed: "<<mysql_error(conn) <<endl;
        mysql_close(conn);
        return false;
    }

    cout << "Database connected successfully" << endl;

    mysql_close(conn);

    return true;
}

int DatabaseConnector::insertCode(const string & code){
    MYSQL* conn;

    conn = mysql_init(nullptr);

    if (conn == nullptr){
        return -1;
    } 

    conn = mysql_real_connect(
        conn, 
        "localhost", 
        "root", 
        "yassine123!@", 
        "gestion_de_project", 
        3306, 
        nullptr, 
        0
    );

    if (conn == nullptr){
        return -1;
    } 

    string query = "INSERT INTO groupe (code_groupe, Date_creationGrp) VALUES ('" + code + "', CURDATE())";

    if (mysql_query(conn, query.c_str())) {   // mysql_querry(means if mysql return and error)  needs two parameter the active db connection conn and the sql command to execute it does not accept the c++ string  style it  accept C-style string that's why we use c_str function 
        mysql_close(conn);
        return -1;
    }

    // Get the Id_Groupe that was just created
    int id_groupe = (int)mysql_insert_id(conn); // reads the  number  id  of the groupe who  was just  created  and the  function  int  concert it  to  an  integer 

    mysql_close(conn);

    // Return the Id_Groupe so main.cpp can print it for PHP
    return id_groupe;
}

bool DatabaseConnector::insererEncadrant(string nom, string prenom, string cle, int id_admin) {
    MYSQL* conn;

    conn = mysql_init(nullptr);

    if (conn == nullptr) {
        return false;
    }

    conn = mysql_real_connect(
        conn,
        "localhost",
        "root",
        "yassine123!@",
        "gestion_de_project",
        3306,
        nullptr,
        0
    );

    if (conn == nullptr) {
        return false;
    }

    // Construire la requête INSERT
    string query = "INSERT INTO encadrant (nom_Encad, prenom_Encad, cle_accee, Id_admin) VALUES ('"
                   + nom + "', '"
                   + prenom + "', '"
                   + cle + "', "
                   + to_string(id_admin) + ")";

    if (mysql_query(conn, query.c_str())) {
        mysql_close(conn);
        return false;
    }

    mysql_close(conn);

    return true;
}