import dotenv
import vonage
import vonage_messages

from os import environ
from os.path import dirname, join
from sys import exit

dotenv_path = join(dirname(__file__), '../../.env')
dotenv.load_dotenv(dotenv_path)

API_KEY = environ.get('VONAGE_API_KEY')
API_SECRET = environ.get('VONAGE_API_SECRET')
TO_NUMBER = environ.get('TO_NUMBER')

ALPHANUMERIC_SENDER_ID = 'Vonage'
SMS_TEXT = 'Hello, world!!'

try:
    auth = vonage.Auth(
        api_key=API_KEY,
        api_secret=API_SECRET,
    )
    vonage_client = vonage.Vonage(
        auth=auth,
    )
    message = vonage_messages.Sms(
        from_=ALPHANUMERIC_SENDER_ID,
        to=TO_NUMBER,
        text=SMS_TEXT,
    )
    response = vonage_client.messages.send(message)
    print(response)
except Exception as e:
    exit('Exception %s: %s' % (type(e), e))

exit
