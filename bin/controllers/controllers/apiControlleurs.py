import os
import sys
import json
from http import HTTPStatus
from typing import Tuple, Union, Iterator

class ApiControlleurs:
    
    def hello(self, request):
        return 200, "Hello depuis GET"

    def create(self, request):
        length = int(request.headers.get('Content-Length', 0))
        body = request.rfile.read(length).decode('utf-8')
        return 201, f"Utilisateur créé avec : {body}"

    def update(self, request, user_id):
        length = int(request.headers.get('Content-Length', 0))
        body = request.rfile.read(length).decode('utf-8')
        return 200, f"Utilisateur {user_id} mis à jour avec : {body}"

    def delete(self, request, user_id):
        return 200, f"Utilisateur {user_id} supprimé"

    def not_found(self, request):
        return 404, "Route not found"
