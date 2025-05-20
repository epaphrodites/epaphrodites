import importlib
import sys
import os

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../..')))

class Routes:
    @staticmethod
    def routes(path):
        try:

            controllers_module = importlib.import_module("bin.controllers.controllers.apiControlleurs")
            importlib.reload(controllers_module)

            ApiControlleurs = controllers_module.ApiControlleurs

            ApiControlleurs.routes = {
                "/bot": ApiControlleurs.ragFaissModel,
            }

            return ApiControlleurs.route(path)

        except Exception as e:
            return (f'{{"error": "Routing failed", "details": "{str(e)}"}}', 500)
