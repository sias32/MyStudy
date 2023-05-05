#!/bin/bash

echo "Скрипт запущен"

curlCheck() {
  responseCode=$(curl -sI --connect-timeout 4 $1 -w '%{response_code}' | head -n 1 | awk '{print $2}')

  if [[ $responseCode =~ ^[0-9]+$ && $responseCode -lt 400 ]]; then
    echo 'OK - '$1'@'$responseCode
  else
    echo 'ERROR - '$1'@'$responseCode
    exit
  fi
}

if [ -r $1 ]; then
  IFS=$'\n'
  cat $1 | while read line; do
    curlCheck $line
  done
elif [ -n $1 ]; then
  curlCheck $1
else
  echo "Неверно задан url или файл"
fi

echo "Скрипт завершен"