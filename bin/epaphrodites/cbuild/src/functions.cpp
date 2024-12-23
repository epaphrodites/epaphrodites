#include "functions.h"

// Addition de deux nombres
Php::Value add(Php::Parameters &params)
{
    return params[0].numericValue() + params[1].numericValue();
}

// Multiplication de deux nombres
Php::Value multiply(Php::Parameters &params)
{
    return params[0].numericValue() * params[1].numericValue();
}

// Affichage d'un message
void sayHello()
{
    Php::out << "Hello from epaphrodites extension!" << std::endl;
}
