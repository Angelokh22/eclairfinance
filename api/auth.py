from connection import create_connection


def register(email, password, firstname, lastname):
    pass

def login(email, password):
    connection = create_connection()
    query = "SELECT userID, userFirstName FROM users WHERE userEmail = %s AND userPassword = %s"
    cursor = connection.cursor()
    cursor.execute(query, (email, password, ))
    user = cursor.fetchone()
    if user:
        return [user[0], user[1]]
    else:
        return None