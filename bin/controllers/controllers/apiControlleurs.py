import os
import sys
import json
from http import HTTPStatus
from typing import Tuple, Union, Iterator

sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '../../../..')))
from bin.epaphrodites.python.others.lunchRagFaissModel import LunchRagFaissModel

class ApiControlleurs:
    routes = {}

    @classmethod
    def route(cls, path):
        handler = cls.routes.get(path, cls.not_found)
        return handler()

    @staticmethod
    def not_found() -> Tuple[dict, int]:
        return {"error": "Not Found"}, HTTPStatus.NOT_FOUND

    @staticmethod
    def ragFaissModel(data: dict = None) -> Tuple[Union[dict, Iterator], int]:
        try:
            # Extract parameters from POST data
            data = data or {}
            prompt = data.get('prompt', '')
            user_id = data.get('user_id', 'default')
            stream = data.get('stream', False)

            # Validate parameters
            if not prompt:
                return {"error": "Missing required parameter: prompt"}, HTTPStatus.BAD_REQUEST

            # Call LunchRagFaissModel
            result = LunchRagFaissModel.askQuestions(prompt=prompt, user_id=user_id, stream=stream)

            if stream:
                # Return a generator for streaming
                def stream_response():
                    try:
                        for chunk in result:
                            if isinstance(chunk, dict):
                                yield f"data: {json.dumps(chunk)}\n\n"
                            else:
                                yield f"data: {json.dumps({'response': str(chunk)})}\n\n"
                        yield "data: {\"done\": true}\n\n"
                    except Exception as e:
                        yield f"data: {json.dumps({'error': 'Streaming failed', 'details': str(e)})}\n\n"
                return stream_response(), HTTPStatus.OK
            else:
                # Return a static response
                return result, HTTPStatus.OK

        except Exception as e:
            return {"error": "Failed to process request", "details": str(e)}, HTTPStatus.INTERNAL_SERVER_ERROR