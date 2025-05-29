import json
import logging

logging.basicConfig(level=logging.DEBUG)
logger = logging.getLogger(__name__)

class ApiControllers:
    def not_found(self, request_handler, stream_handler, body=None, *args):
        logger.debug("Handling not_found")
        return 404, {"error": "Route not found"}
    
    def helloEpaphrodites(self, request_handler, stream_handler, body=None, *args):
        logger.debug("Handling helloEpaphrodites")
        return 200, {"message": "Hello from python API"}
    
    def sendAndGetData(self, request_handler, stream_handler, body=None, *args):
        logger.debug(f"Handling sendAndGetData with body: {body}")
        try:
            if body is None:
                logger.error("No request body provided")
                return 400, {"error": "No request body provided"}
            
            try:
                data = json.loads(body) if isinstance(body, str) else body
            except json.JSONDecodeError:
                logger.error("Invalid JSON format in request body")
                return 400, {"error": "Invalid JSON format in request body"}

            variable2 = data.get('variable2', 'default_value2')

            response = {
                "status": "success",
                "received_variables": {
                    "variable2": variable2
                },
                "message": "Variables processed by sendAndGetData"
            }
            logger.debug(f"sendAndGetData response: {response}")
            return 200, response
        
        except Exception as e:
            logger.error(f"Error in sendAndGetData: {str(e)}")
            return 500, {"error": f"Error processing request: {str(e)}"}