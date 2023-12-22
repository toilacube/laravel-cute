#!/bin/bash

# API endpoint
API_ENDPOINT="http://localhost:8000/api/order/create"

# Request body in JSON format
REQUEST_BODY='{
  "shippingAddress": "toi nha cua em toi nay duoc khong",
  "paymentMethod": 0,
  "shippingMethod": 1
}'

# Array of bearer tokens
BEARER_TOKENS=(
"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDMwODQyNDEsImV4cCI6MTczNDYyMDI0MSwibmJmIjoxNzAzMDg0MjQxLCJqdGkiOiJMOWNhNWN5NzJNNkJ1c2VFIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlIjoidXNlciJ9.wZyEIPvumJw5uoyxpEFLEVoszlsCIOM-VFLtQD3PU_ZxrfIF9sEc9p-874Gl_b6P31vMrTDqHztVjOC33V3JYg" 
"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDMwODQyNjksImV4cCI6MTczNDYyMDI2OSwibmJmIjoxNzAzMDg0MjY5LCJqdGkiOiJxQmRpSDA2TlQ5eG9iSDdwIiwic3ViIjoiMiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlIjoidXNlciJ9.7jRcpsuaxrcqDOM43pa5MsxQabhr9ajf5os6meYYHT4Sq-SEpyFuOApp-QEysTpgPpnkaTvdx6qqQcXOKeDYDw"
"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDMwODQxOTcsImV4cCI6MTczNDYyMDE5NywibmJmIjoxNzAzMDg0MTk3LCJqdGkiOiJjamVRMUZCcnBTQ0ppYWZqIiwic3ViIjoiMyIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlIjoiYWRtaW4ifQ.eL5_Wr1yNvEXyqIoZj-yNqh79x0RSoxeNhbFT1dVLwDGHHyPTm6ZG-V5MTtpApE6NM-7CQPLMdvw_6VfHi0FRg"
"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDMwODQyODksImV4cCI6MTczNDYyMDI4OSwibmJmIjoxNzAzMDg0Mjg5LCJqdGkiOiJoN1dIMVZZZ1JSMDFTaXZKIiwic3ViIjoiNCIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlIjoidXNlciJ9.WSAuvAKQkXGAqYc5pGp3okA94KasxGvCxdu8GbAHuF6jJf0kHlX0AiMc0w-KunwjJ-An9GC5pFhN6euHDyaG0w"
"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDMwODQzMDQsImV4cCI6MTczNDYyMDMwNCwibmJmIjoxNzAzMDg0MzA0LCJqdGkiOiJtb043MTRZQXprdnhEdzNsIiwic3ViIjoiNSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlIjoidXNlciJ9.sHZFDvs6pCCW8YyyeLyh02MCm5Xs5OYV1UHHEPduxQw_M6MUQr_9CrXR4LdNWVlXbFMYly8yEykj40iGCWs7Ew"
"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDMwOTE5NzQsImV4cCI6MTczNDYyNzk3NCwibmJmIjoxNzAzMDkxOTc0LCJqdGkiOiJqbXp2VnZGbnN2NERnV1U4Iiwic3ViIjoiNiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlIjoidXNlciJ9.bPNh5T6uA8OLkpNnryoNAC9YoxAEuhrSg_uemHw37yE9f5fg2KpIJ1jdBGDEn818uRWsDMGDN-v1YcEIQUKy2A"
"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDMwOTIwMTIsImV4cCI6MTczNDYyODAxMiwibmJmIjoxNzAzMDkyMDEyLCJqdGkiOiJpTEdyTTFRZU02SVczVWxPIiwic3ViIjoiNyIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlIjoiYWRtaW4ifQ.0QTmlYK7KZD5MozlbnZgFRG9W14FkR6pMW0EsNeTm1Ql1lSkhUKCNwAWRyuzN7nM41r9LdUoicp0mQGZXy1uvA"
"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDMwOTIwMzYsImV4cCI6MTczNDYyODAzNiwibmJmIjoxNzAzMDkyMDM2LCJqdGkiOiJkUG5MWHB1VDZWYWN0UmZuIiwic3ViIjoiOCIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlIjoidXNlciJ9.CXrUSMORgwtHlegGEbegGpe6RD65LGnyw2V1R1--O9sREK17ugMMfRE55xuqPq3paNv_100Dr2mqF7zWQp7bjw"
"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MDMwOTIwNTYsImV4cCI6MTczNDYyODA1NiwibmJmIjoxNzAzMDkyMDU2LCJqdGkiOiJXM3FXVlBodWhvV2NKaDBDIiwic3ViIjoiOSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJyb2xlIjoiYWRtaW4ifQ.O2iknG2i0WUkxrwLcxx4JjLWAhqcn-RYO2-Bx77Z5zWCd4wVj6hea1lI0ncMQvwts1rWGc6SbflYLpDqReTosw"
)

# Function to send a POST request using curl with a specified bearer token
send_request() {
  local TOKEN=$1
  RESPONSE=$(curl -s -X POST "$API_ENDPOINT" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d "$REQUEST_BODY")
  
  #local RESPONSE_STATUS=$(echo "$RESPONSE" | head -n 1 | awk '{print $2}')
  local RESPONSE_MESSAGE=$(echo "$RESPONSE" | tail -n 1)
  
  #echo "HTTP status $RESPONSE" >> response.txt
  echo "Response: $RESPONSE_MESSAGE" >> response.txt

}

# Loop through the array of bearer tokens and send concurrent requests
for token in "${BEARER_TOKENS[@]}"
do
  send_request "$token"  &                                                                                              
done

# Wait for all background jobs to complete
wait
