#ifndef GROUPCODEGENERATOR_H
#define GROUPCODEGENERATOR_H
#include <string>
using  namespace std;
class CodeGenerator{
    private:
        string prefix;
        int codelenght;
    public:
        string generateCode();
};

#endif 