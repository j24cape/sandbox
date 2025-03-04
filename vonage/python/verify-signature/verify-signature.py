import os
import sys
from os.path import dirname, join
from dotenv import load_dotenv
from vonage_jwt.verify_jwt import verify_signature

dotenv_path = join(dirname(__file__), '../../.env')
load_dotenv(dotenv_path)

signature_secret = os.environ.get('VONAGE_SIGNATURE_SECRET')

if len(sys.argv) != 2:
    print('Usage: python %s VONAGE_SIGNATURE_TOKEN'
        % (os.path.basename(__file__)))
    sys.exit(1)
token = sys.argv[1]

try:
    result = verify_signature(token, signature_secret)
    print('Verified' if result else 'Not verified')
except Exception as e:
    print('Exception %s: %s' % (type(e), e))
    sys.exit(1)

sys.exit
