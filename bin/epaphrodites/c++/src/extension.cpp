#include <phpcpp.h>
#include "functions.h"

// Point d'entrée de l'extension
extern "C" {
    PHPCPP_EXPORT void *get_module()
    {
        // Créer l'extension
        static Php::Extension extension("epaphrodites", "1.0");

        // Ajouter les fonctions
        extension.add("add", add, {
            Php::ByVal("a", Php::Type::Numeric),
            Php::ByVal("b", Php::Type::Numeric)
        });

        extension.add("multiply", multiply, {
            Php::ByVal("a", Php::Type::Numeric),
            Php::ByVal("b", Php::Type::Numeric)
        });
        
        extension.add("sayHello", sayHello);

        return extension.module();
    }
}
