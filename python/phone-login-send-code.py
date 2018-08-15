#THE SCRIPT BELOW HAS BEEN FORKED BY THIS ORIGINAL ONE: https://github.com/fbessez/Tinder
import requests
import json
import sys

CODE_REQUEST_URL = "https://graph.accountkit.com/v1.2/start_login?access_token=AA%7C464891386855067%7Cd1891abb4b0bcdfa0580d9b839f4a522&credentials_type=phone_number&fb_app_events_enabled=1&fields=privacy_policy%2Cterms_of_service&locale=fr_FR&phone_number=#placeholder&response_type=token&sdk=ios"

HEADERS = {'user-agent': 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_2_5 like Mac OS X) AppleWebKit/604.5.6 (KHTML, like Gecko) Mobile/15D60 AKiOSSDK/4.29.0'}

def sendCode(number):
    URL = CODE_REQUEST_URL.replace("#placeholder", number)
    r = requests.post(URL, headers=HEADERS, verify=False)
    #print(r.url)
    response = r.json()
    if(response.get("login_request_code") == None):
        return False
    else:
        return response["login_request_code"]

phone_number = sys.argv[1]
log_code = sendCode(phone_number)
print(log_code)