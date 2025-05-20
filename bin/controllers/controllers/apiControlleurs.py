import os
import sys
import json
from http import HTTPStatus
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../..')))
from bin.epaphrodites.python.others.lunchRagFaissModel import LunchRagFaissModel

class ApiControlleurs:
    routes = {}

    @classmethod
    def route(cls, path):
        handler = cls.routes.get(path, cls.not_found)
        return handler()

    @staticmethod
    def not_found():
        return json.dumps({"error": "Not Found"}), HTTPStatus.NOT_FOUND

    @staticmethod
    def ragFaissModel():
        
        result = LunchRagFaissModel.askQuestions("comment creer une base de donnees", user_id='default', stream=True)
        
        return json.dumps('bonjour'), HTTPStatus.OK
