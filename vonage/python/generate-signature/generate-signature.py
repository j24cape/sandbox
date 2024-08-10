import os
import sys
from dotenv import load_dotenv
from vonage_jwt.jwt import JwtClient

load_dotenv()

application_id = os.environ.get('VONAGE_APPLICATION_ID')
application_private_key = os.environ.get('VONAGE_APPLICATION_PRIVATE_KEY')

try:
    client = JwtClient(application_id, application_private_key);
    print(client.generate_application_jwt().decode())
except Exception as e:
    print('Exception %s: %s' % (type(e), e))
    sys.exit(1)

sys.exit
