import os
import sys
import json
from http import HTTPStatus
from typing import Tuple, Union, Iterator

class ApiControlleurs:

    def not_found(self, request):
        return 404, "Route not found"
    
    def helloEpaphrodites(self, request):
        return 200, "Hello form python API"    
    
    def faissRagModel(self, request):
        return 200, "Hello form python API"
