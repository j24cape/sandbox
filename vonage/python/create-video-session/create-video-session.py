import os
import sys
from dotenv import load_dotenv
from vonage import Client

load_dotenv()

application_id = os.environ.get('VONAGE_APPLICATION_ID')
application_private_key = os.environ.get('VONAGE_APPLICATION_PRIVATE_KEY')

try:
    client = Client(
        application_id=application_id,
        private_key=application_private_key,
    )
    session = client.video.create_session()
    print(session['session_id'])
except Exception as e:
    print('Exception %s: %s' % (type(e), e))
    sys.exit(1)

sys.exit
