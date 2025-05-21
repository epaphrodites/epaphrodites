import sys
import os
import importlib

# Ajouter le chemin racine pour les imports
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../..')))

class Routes:
    # Charger le module une seule fois au démarrage
    controllers_module = importlib.import_module("bin.controllers.controllers.apiControlleurs")
    ApiControlleurs = controllers_module.ApiControlleurs

    @staticmethod
    def routes(path, data=None):
        try:
            # Définir les routes dynamiquement
            Routes.ApiControlleurs.routes = {
                "/bot": lambda: Routes.ApiControlleurs.ragFaissModel(data),
                "/health": lambda: ({"status": "ok"}, 200)
            }

            # Appeler la route correspondante
            handler = Routes.ApiControlleurs.routes.get(path, Routes.ApiControlleurs.not_found)
            return handler()

        except Exception as e:
            return ({"error": "Routing failed", "details": str(e)}, 500)
