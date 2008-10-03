## noisy script for updating alpha and production on svn commits.

[ "$USER" == "bwrox" ] || { echo "this script should only be run as bwrox user"; exit 255; }

#SITES="alpha.bewelcome.org"
SITES="alpha.bewelcome.org www.bewelcome.org"
TMPFILE=/home/bwrox/tmp/updates.$$

for SITE in ${SITES}
do

cd /home/bwrox/${SITE}
if ! svn stat --show-updates > ${TMPFILE} 2>&1
then
  date >> /home/bwrox/${SITE}/htdocs/update_status.txt
  echo "Update not successful.  Manual sysadm intervension needed.  Output of svn stat --show-updates follows:" >> /home/bwrox/${SITE}/htdocs/update_status.txt
  cat ${TMPFILE} >> /home/bwrox/${SITE}/htdocs/update_status.txt
fi

## any modifications in svn?
grep -q '  \*  ' ${TMPFILE} || continue

## then check for conflicts
if grep -q '^[\?MA] *\*  ' ${TMPFILE}
then
  cat ${TMPFILE} | mail -s "${SITE} NOT updated after commit - conflicts" bw-admin-discussion@bewelcome.org bw-dev-svn@bewelcome.org
  echo -e "\n\n" >> /home/bwrox/${SITE}/htdocs/update_status.txt
  date >> /home/bwrox/${SITE}/htdocs/update_status.txt
  echo "Update not successful.  Manual sysadm intervension needed.  Output of svn stat --show-updates follows:" >> /home/bwrox/${SITE}/htdocs/update_status.txt
  cat ${TMPFILE} >> /home/bwrox/${SITE}/htdocs/update_status.txt
  continue
fi

state="Successful"
echo -n "Update starting on " > ${TMPFILE}
date >> ${TMPFILE}
echo -e "\n\nOutput of svn up on ${SITE}" >> ${TMPFILE}
svn up >> ${TMPFILE} 2>&1 || state="UNSUCCESSFUL"
echo -e "\n\nOutput of svn stat on ${SITE}" >> ${TMPFILE}
svn stat >> ${TMPFILE}
echo -e "\n\nUpdate finished " >> ${TMPFILE}
date >> ${TMPFILE}
echo -e "\n\nOutput of svn info on ${SITE}" >> ${TMPFILE}
svn info >> ${TMPFILE}
echo -e "\n\nState of update: $state" >> ${TMPFILE}

mail -s "${state} update of ${SITE}" tobixen@gmail.com bw-dev-svn@bewelcome.org < ${TMPFILE}

cp ${TMPFILE} /home/bwrox/${SITE}/htdocs/update_status.txt
svn info | grep "Last Changed Rev"|awk '{ print $4 }' > /home/bwrox/${SITE}/htdocs/revision.txt

done