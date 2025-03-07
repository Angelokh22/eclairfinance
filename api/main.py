from auth import login
from flask import Flask, request
from flask_cors import CORS


app = Flask(__name__)
CORS(app)


@app.route("/login")
def strat_login():
    if request.method != 'POST': return '', 405
    
    data = request.get_json()
    
    if 'email' not in data or 'password' not in data: return '', 403
    
    email = data['email']
    password = data['password']

    if email == '' or password == '': return '', 403

    login_result = login(email=email, password=password)