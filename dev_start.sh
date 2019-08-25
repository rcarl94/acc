dev_appserver.py --env_var GOOGLE_CREDS="$(cat cal-creds.json)" --env_var SIGNIN_CLIENT_ID="$SIGNIN_CLIENT_ID" --env_var PROFILE="TEST" app.yaml
