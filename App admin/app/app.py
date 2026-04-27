from flask import Flask, jsonify, request
from flask_cors import CORS
import mysql.connector
import os
import logging
import datetime
import requests

app = Flask(__name__)
CORS(app)

logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s %(levelname)s %(message)s',
    handlers=[
        logging.FileHandler('/logs/app.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

def send_log(level, message, service="Anastasia_App", metadata=None):
    payload = {
        "level": level,
        "message": message,
        "service": service,
        "timestamp": datetime.datetime.utcnow().isoformat() + "Z",
        "metadata": metadata or {}
    }
    try:
        requests.post("https://srv1073565.hstgr.cloud:8443/api/v1/logs", json=payload, timeout=3, verify=False)
    except:
        pass

def get_db():
    return mysql.connector.connect(
        host=os.environ.get("DB_HOST", "db"),
        user=os.environ.get("DB_USER", "arvutiuser"),
        password=os.environ.get("DB_PASSWORD", "arvutipass"),
        database=os.environ.get("DB_NAME", "arvutipood")
    )

@app.route("/")
def index():
    send_log("INFO", "Home page accessed")
    return "<h1>Arvutipood</h1><p><a href='/api/tooted'>Tooted</a> | <a href='/api/users'>Users</a> | <a href='/health'>Health</a></p>"

@app.route("/health")
def health():
    try:
        conn = get_db()
        conn.close()
        send_log("INFO", "Health check OK")
        return jsonify({"status": "ok", "db": "connected"})
    except Exception as e:
        send_log("ERROR", f"Health check failed: {e}")
        return jsonify({"status": "error", "db": str(e)}), 500

@app.route("/api/tooted", methods=["GET"])
def get_tooted():
    try:
        conn = get_db()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM tooted ORDER BY id DESC")
        result = cursor.fetchall()
        conn.close()
        send_log("INFO", f"GET /api/tooted - {len(result)} items", "Anastasia_API")
        return jsonify(result)
    except Exception as e:
        send_log("ERROR", str(e), "Anastasia_API")
        return jsonify({"error": str(e)}), 500

@app.route("/api/tooted", methods=["POST"])
def add_toode():
    try:
        data = request.get_json()
        conn = get_db()
        cursor = conn.cursor()
        cursor.execute(
            "INSERT INTO tooted (nimi, kirjeldus, hind, laos, kategooria) VALUES (%s,%s,%s,%s,%s)",
            (data["nimi"], data.get("kirjeldus",""), data["hind"], data.get("laos",0), data["kategooria"])
        )
        conn.commit()
        new_id = cursor.lastrowid
        conn.close()
        send_log("INFO", f"Toode lisatud id={new_id}", "Anastasia_API")
        return jsonify({"message": "Toode lisatud", "id": new_id}), 201
    except Exception as e:
        send_log("ERROR", str(e), "Anastasia_API")
        return jsonify({"error": str(e)}), 500

@app.route("/api/users", methods=["GET"])
def get_users():
    try:
        conn = get_db()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT id, nimi, email, roll, loodud FROM users")
        result = cursor.fetchall()
        conn.close()
        send_log("INFO", f"GET /api/users - {len(result)} users", "Anastasia_API")
        return jsonify(result)
    except Exception as e:
        send_log("ERROR", str(e), "Anastasia_API")
        return jsonify({"error": str(e)}), 500

@app.route("/api/users/register", methods=["POST"])
def register():
    try:
        data = request.get_json()
        conn = get_db()
        cursor = conn.cursor()
        cursor.execute(
            "INSERT INTO users (nimi, email, parool) VALUES (%s,%s,%s)",
            (data["nimi"], data["email"], data["parool"])
        )
        conn.commit()
        new_id = cursor.lastrowid
        conn.close()
        send_log("INFO", f"Uus kasutaja: {data['email']}", "Anastasia_App")
        return jsonify({"message": "Registreeritud", "id": new_id}), 201
    except Exception as e:
        send_log("ERROR", str(e), "Anastasia_App")
        return jsonify({"error": str(e)}), 500

@app.route("/api/users/login", methods=["POST"])
def login():
    try:
        data = request.get_json()
        conn = get_db()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT id, nimi, email, roll FROM users WHERE email=%s AND parool=%s",
            (data["email"], data["parool"]))
        user = cursor.fetchone()
        conn.close()
        if not user:
            send_log("WARN", f"Vale login: {data.get('email')}", "Anastasia_App")
            return jsonify({"error": "Vale email voi parool"}), 401
        send_log("INFO", f"Login: {user['email']}", "Anastasia_App")
        return jsonify({"message": "OK", "user": user})
    except Exception as e:
        send_log("ERROR", str(e), "Anastasia_App")
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=False)
