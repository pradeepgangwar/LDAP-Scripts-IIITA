import getpass
import ldap
import pprint

LDAP_SERVER = ""

try:
    ldap_conn = ldap.initialize(LDAP_SERVER)
    ldap_conn.simple_bind_s()
except:
    print("Cannot connect to LDAP server")
    exit(1)

username = input("Enter your LDAP username: ")
password = getpass.getpass("Enter your LDAP password: ")


searchScope = ldap.SCOPE_SUBTREE
conn_string = "dc=iiita,dc=ac,dc=in"
searchFilter = "uid="+username

# Let's Fetch Details (This can work without logging in)
try:    
    ldap_result_id = ldap_conn.search(conn_string, searchScope, searchFilter)
    result_type, result_data = ldap_conn.result(ldap_result_id, 0)
    if result_type == ldap.RES_SEARCH_ENTRY:
        pp = pprint.PrettyPrinter(indent=4)
except ldap.LDAPError as e:
    print(e)

cn = result_data[0][0]

# Let's log in
try:
    ldap_conn.protocol_version = ldap.VERSION3
    ldap_conn.simple_bind_s(cn, password) 
except ldap.INVALID_CREDENTIALS:
  print("Your username or password is incorrect.")
  exit(0)
except ldap.LDAPError as e:
  if type(e.message) == dict and e.message.has_key('desc'):
      print(e.message['desc'])
  else: 
      print(e)
  exit(0)
else:
    print("Logged in successfully")

# pp.pprint(result_data)
