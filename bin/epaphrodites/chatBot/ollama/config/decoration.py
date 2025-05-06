
import threading
from functools import wraps

class Decorator:
    
    # Timeout decorator with threading
    def timeout(seconds):
        def decorator(func):
            @wraps(func)
            def wrapper(*args, **kwargs):
                result = [None]
                exception = [None]
                
                def target():
                    try:
                        result[0] = func(*args, **kwargs)
                    except Exception as e:
                        exception[0] = e
                        
                thread = threading.Thread(target=target, daemon=True)
                thread.start()
                thread.join(seconds)
                
                if thread.is_alive():
                    raise TimeoutError(f"Timeout after {seconds} seconds")
                if exception[0]:
                    raise exception[0]
                return result[0]
            return wrapper
        return decorator