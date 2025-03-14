import dotenv

from os import environ
from os.path import dirname, join
from sys import exit
from twilio.rest import Client

dotenv_path = join(dirname(__file__), '../../.env')
dotenv.load_dotenv(dotenv_path)

ACCOUNT_SID = environ.get("TWILIO_ACCOUNT_SID")
AUTH_TOKEN = environ.get("TWILIO_AUTH_TOKEN")
SMS_NUMBER = environ.get("TWILIO_SMS_NUMBER")
TO_NUMBER = environ.get("TO_NUMBER")

ALPHANUMERIC_SENDER_ID = 'Twilio'
SMS_TEXT = 'Hello, world!!'

try:
    client = Client(ACCOUNT_SID, AUTH_TOKEN)
    message = client.messages.create(
        body=SMS_TEXT,
        from_=SMS_NUMBER,
        to=TO_NUMBER,
    )
    print(message.sid)
except Exception as e:
    exit('Exception %s: %s' % (type(e), e))

exit
