"""
API handlers and utilities for the HTTP server.
This module contains all the route handlers and business logic.
"""
import json
import urllib.parse
import time
import random
import logging
from datetime import datetime
from typing import Dict, Any, Callable, Tuple, Optional, List, Union

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger("api")

# Type alias for API handlers
HandlerFunc = Callable[[Dict[str, List[str]]], Tuple[int, Dict[str, Any]]]

# In-memory data store for demonstration
data_store = {
    "todos": [
        {"id": 1, "task": "Learn Python", "completed": True},
        {"id": 2, "task": "Build an API", "completed": False},
    ],
    "counters": {
        "visits": 0
    }
}

def get_links_handler(query_params: Dict[str, List[str]]) -> Tuple[int, Dict[str, Any]]:
    """Handler for /api/links - Returns available API endpoints."""
    routes = {
        "hello": "/api/hello?name=John",
        "sum": "/api/sum?a=2&b=3",
        "time": "/api/time",
        "random": "/api/random?min=1&max=100",
        "todos": "/api/todos",
        "stats": "/api/stats"
    }
    return 200, routes

def hello_handler(query_params: Dict[str, List[str]]) -> Tuple[int, Dict[str, Any]]:
    """Handler for /api/hello - Returns a greeting."""
    name = query_params.get("name", ["stranger"])[0]
    return 200, {"message": f"Hello {name} from Python!"}

def sum_handler(query_params: Dict[str, List[str]]) -> Tuple[int, Dict[str, Any]]:
    """Handler for /api/sum - Adds two numbers."""
    try:
        a = int(query_params.get("a", ["0"])[0])
        b = int(query_params.get("b", ["0"])[0])
        return 200, {"sum": a + b, "a": a, "b": b}
    except ValueError:
        return 400, {"error": "Parameters 'a' and 'b' must be integers"}

def time_handler(query_params: Dict[str, List[str]]) -> Tuple[int, Dict[str, Any]]:
    """Handler for /api/time - Returns the current server time."""
    now = datetime.now()
    return 200, {
        "timestamp": time.time(),
        "iso": now.isoformat(),
        "formatted": now.strftime("%Y-%m-%d %H:%M:%S")
    }

def random_handler(query_params: Dict[str, List[str]]) -> Tuple[int, Dict[str, Any]]:
    """Handler for /api/random - Returns a random number."""
    try:
        min_val = int(query_params.get("min", ["1"])[0])
        max_val = int(query_params.get("max", ["100"])[0])
        
        if min_val >= max_val:
            return 400, {"error": "min must be less than max"}
            
        return 200, {"random": random.randint(min_val, max_val), "min": min_val, "max": max_val}
    except ValueError:
        return 400, {"error": "Parameters 'min' and 'max' must be integers"}

def todos_handler(query_params: Dict[str, List[str]]) -> Tuple[int, Dict[str, Any]]:
    """Handler for /api/todos - Returns the list of todos."""
    return 200, {"todos": data_store["todos"]}

def stats_handler(query_params: Dict[str, List[str]]) -> Tuple[int, Dict[str, Any]]:
    """Handler for /api/stats - Returns server statistics."""
    # Increment visit counter
    data_store["counters"]["visits"] += 1
    
    return 200, {
        "uptime": time.time(),  # In a real app, this would be time since server start
        "visits": data_store["counters"]["visits"],
        "todos_count": len(data_store["todos"]),
        "completed_todos": sum(1 for todo in data_store["todos"] if todo["completed"])
    }

# Route mapping: URL path to handler function
ROUTES: Dict[str, HandlerFunc] = {
    "/api/links": get_links_handler,
    "/api/hello": hello_handler,
    "/api/sum": sum_handler,
    "/api/time": time_handler,
    "/api/random": random_handler,
    "/api/todos": todos_handler,
    "/api/stats": stats_handler,
}

def handle_request(path: str) -> Tuple[int, Dict[str, Any]]:
    """
    Process an API request and return the appropriate response.
    
    Args:
        path: The request path including query parameters
        
    Returns:
        A tuple of (status_code, response_data)
    """
    # Parse the URL to extract path and query parameters
    parsed_url = urllib.parse.urlparse(path)
    route_path = parsed_url.path
    query_params = urllib.parse.parse_qs(parsed_url.query)
    
    # Log the incoming request
    logger.info(f"Request: {route_path} with params {query_params}")
    
    # Find the appropriate handler or return 404
    handler = ROUTES.get(route_path)
    if handler:
        try:
            return handler(query_params)
        except Exception as e:
            logger.error(f"Error handling request {route_path}: {str(e)}")
            return 500, {"error": "Internal server error"}
    else:
        return 404, {"error": "Not found"}