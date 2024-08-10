import sys
import os
from dotenv import load_dotenv
from vonage_jwt.verify_jwt import verify_signature

load_dotenv()

signature_secret = os.environ.get('VONAGE_SIGNATURE_SECRET')

if len(sys.argv) != 2:
    print('Usage: python verify-signature.py VONAGE_SIGNATURE_TOKEN')
    sys.exit(1)
token = sys.argv[1]

try:
    if verify_signature(token, signature_secret):
        print('Verified')
    else:
        print('Not verified')
except Exception as err:
    print(f"Unexpected {err=}, {type(err)=}")
    sys.exit(1)

exit
