#ifndef DATABASECONNECTOR_H
#define DATABASECONNECTOR_H
#include <string>
using namespace std;
class DatabaseConnector {
    public: 
        bool testConnection();
        int insertCode(const string & );
};

#endif