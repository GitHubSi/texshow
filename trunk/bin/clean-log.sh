#! /bin/bash

LOG_DIR_PATH=$1
SAVE_DAYS=$2

if [ -z $1 ]
then
    echo "dir path need to assign"
    exit
fi

if [ -z $2 ]
then
    echo "save days need to assign"
    exit
fi

#today
NOW_TIMESTAMP=`date +%s`

FILE_LIST=`ls $LOG_DIR_PATH`
if [ -d $LOG_DIR_PATH ]
then
    for FILE in $FILE_LIST
    do
	    FILE_DATE=`echo $FILE | awk -F . '{print $3;}'`
        FILE_DATE_TIMESTAMP=`date +%s -d $FILE_DATE`
        SAVE_LOG_TIMESTAMP=$((86400 * $SAVE_DAYS + $FILE_DATE_TIMESTAMP))

        if [ $NOW_TIMESTAMP -gt $SAVE_LOG_TIMESTAMP ]
        then
            rm -rf $LOG_DIR_PATH$FILE
        fi
    done
fi

