#!/bin/sh -x

# This will download all files necessary to import the full geonames dump
downloader='wget -q -O - '
if hash curl 2>/dev/null; then
    downloader='curl'
fi

tmpDownloadDir='./var/geodata_tmp'
mkdir ${tmpDownloadDir} 2> /dev/null

${downloader} "https://download.geonames.org/export/dump/allCountries.zip"   > ${tmpDownloadDir}/allCountries.zip
${downloader} "https://download.geonames.org/export/dump/alternateNames.zip" > ${tmpDownloadDir}/alternateNames.zip
${downloader} "https://download.geonames.org/export/dump/countryInfo.txt"    > ${tmpDownloadDir}/countryInfo.txt

unzip ${tmpDownloadDir}/allCountries.zip -d ${tmpDownloadDir}
unzip ${tmpDownloadDir}/alternateNames.zip -d ${tmpDownloadDir}

mysql -u${DB_USER} -p${DB_PASS} ${DB_NAME} -h${DB_HOST} --verbose --progress-reports < import.sql \
    && rm -rfv ${tmpDownloadDir} \
    || echo "Error during GeoData import. Downloaded file have been kept for an eventual retry."
