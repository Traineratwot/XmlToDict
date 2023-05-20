# -*- coding: utf-8 -*-
import os

import xmltodict
import json
import sys
from io import open

def file_get_contents(pathAndFileName):
    with open(pathAndFileName, 'r', encoding="utf-8") as theFile:
        data = theFile.read()
        return data
    return False


def file_put_contents(pathAndFileName, content):
    with open(pathAndFileName, 'w', encoding="utf-8") as theFile:
        data = theFile.write(content)
        return data
    return False


if len(sys.argv) >= 2:
    path = sys.argv[1]
    xml = file_get_contents(path)
    if xml != False:
        o = xmltodict.parse(xml)
        out = json.dumps(o, ensure_ascii=False)
        dirName, fName = os.path.split(path)
        outPath = dirName + '/' + fName + '.json'
        print(outPath)
        xml = file_put_contents(outPath, out)
else:
    print('empty path')
