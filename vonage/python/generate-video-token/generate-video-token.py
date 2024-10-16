import os
import sys
from os.path import dirname, join
from dotenv import load_dotenv
from vonage import Client

dotenv_path = join(dirname(__file__), '../../.env')
load_dotenv(dotenv_path)

application_id = os.environ.get('VONAGE_APPLICATION_ID')
application_private_key = os.environ.get('VONAGE_APPLICATION_PRIVATE_KEY')

if len(sys.argv) != 2:
    print('Usage: python %s VONAGE_VIDEO_SESSION_ID'
        % (os.path.basename(__file__)))
    sys.exit(1)
session_id = sys.argv[1]

try:
    client = Client(
        application_id=application_id,
        private_key=application_private_key,
    )
    token = client.video.generate_client_token(session_id)
    print(token)
except Exception as e:
    print('Exception %s: %s' % (type(e), e))
    sys.exit(1)

sys.exit
