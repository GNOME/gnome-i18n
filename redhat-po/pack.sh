#! /bin/bash

PACKAGE=$1
TARBALL=$PACKAGE-po.tar.gz
echo "Packing $PACKAGE to $TARBALL"

/bin/rm -rf po
mkdir po
cp $PACKAGE/*.po po
tar cvzf $PACKAGE-po.tar.gz po/*

echo "Wrote $TARBALL"
