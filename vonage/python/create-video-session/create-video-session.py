import os
import sys
from os.path import dirname, join
from dotenv import load_dotenv
from vonage import Client

dotenv_path = join(dirname(__file__), '../../.env')
load_dotenv(dotenv_path)

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
