#################################################################################################
# usage of the script
# usage: python retrieve-cui-or-code.py -k APIKEY -v VERSION -i IDENTIFIER -s SOURCE
# If you do not provide the -s parameter, the script assumes you are retrieving information for a
# known UMLS CUI
#################################################################################################

from Authentication import *
import requests
import json
import sys

#query = sys.argv[1]
query = "C0301559"
# username = args.username
# password = args.password
apikey = ""  # enter api key from your UTS Profile
version = "current"
identifier = query  # enter version example-2015AA
# source = source              #enter source name if known
AuthClient = Authentication(apikey)

###################################
# get TGT for our session
###################################

tgt = AuthClient.gettgt()
uri = "https://uts-ws.nlm.nih.gov"

try:
    source
except NameError:
    source = None

#print(source)
##if we don't specify a source vocabulary, assume we're retrieving UMLS CUIs
if source is None:
    content_endpoint = "/rest/content/" + str(version) + "/CUI/" + str(identifier)+"/definitions"

else:
    content_endpoint = "/rest/content/" + str(version) + "/source/" + str(source) + "/" + str(identifier)
#print(content_endpoint)
##ticket is the only parameter needed for this call - paging does not come into play because we're only asking for one Json object
query = {'ticket': AuthClient.getst(tgt)}
#print(query)
r = requests.get(uri + content_endpoint, params=query)
#print(r)
r.encoding = 'utf-8'
#print(r.text)
items = json.loads(r.text)
jsonData = items["result"]
#print(items)

#print(jsonData)
##uncomment the print statment if you want the raw json output, or you can just look at the documentation :=)
# https://documentation.uts.nlm.nih.gov/rest/concept/index.html#sample-output
# https://documentation.uts.nlm.nih.gov/rest/source-asserted-identifiers/index.html#sample-output
#print(json.dumps(items, indent=4))

for elem in jsonData:
    try:
        print(elem["value"]+"|")
        #resultlist = resultlist + result["ui"] + "|"
    except:
        NameError



print("*")


    ##################################################################

if source is None:
    content_endpoint = "/rest/content/" + str(version) + "/CUI/" + str(identifier) + "/relations"

else:
    content_endpoint = "/rest/content/" + str(version) + "/source/" + str(source) + "/" + str(identifier)
# print(content_endpoint)
##ticket is the only parameter needed for this call - paging does not come into play because we're only asking for one Json object
query = {'ticket': AuthClient.getst(tgt)}
# print(query)
r = requests.get(uri + content_endpoint, params=query)
# print(r)
r.encoding = 'utf-8'
# print(r.text)
items = json.loads(r.text)
jsonData = items["result"]
#print(items)

#print(jsonData)
##uncomment the print statment if you want the raw json output, or you can just look at the documentation :=)
# https://documentation.uts.nlm.nih.gov/rest/concept/index.html#sample-output
# https://documentation.uts.nlm.nih.gov/rest/source-asserted-identifiers/index.html#sample-output
# print(json.dumps(items, indent=4))

for elem in jsonData:
    try:
        print(elem["relatedIdName"]+"|")
        # resultlist = resultlist + result["ui"] + "|"
    except:
        NameError








