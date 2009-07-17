#!/bin/sh
if [ -z `which svn` ]
then
    echo "Missing svn"
    exit 255
fi

if [ -z `which wget` ]
then
    echo "Missing wget"
    exit 255
fi

if [ -z `which tar` ]
then
    echo "Missing tar"
    exit 255
fi

if [ $# != 1 ]
then
  echo "USAGE: $0 NAME"
  exit 255
fi

echo $1 | grep -Eq '^[a-z0-9_.-]*$'
if [ $? != 0 ]
then
  echo "Illegal name $1"
  exit 255
fi

if [ -e $1 ]
then
  echo "$1 already exists"
  exit 255
fi

set -v
# Pull Konstrukt starterpack
svn export http://konstrukt.googlecode.com/svn/trunk/examples/starterpack_default $1
cd $1
# Pull krudt addon
cp -rf /home/tkn/public/krudt/* .
# Pull git://github.com/troelskn/bucket.git
mkdir -p thirdparty/bucket
wget -q -O - http://github.com/troelskn/bucket/tarball/master | tar -xz -C thirdparty/bucket
# Pull https://pdoext.googlecode.com/svn/trunk
svn export https://pdoext.googlecode.com/svn/trunk thirdparty/pdoext
# Set file permissions
chmod 777 log
chmod 777 var
cd ..
set +v
echo "Done krudtifying. You should delete $1/install.sh"