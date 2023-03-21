import csv
import os
import pandas as pd

path = os.getcwd()

# CHANGE FILENAME HERE
filename = 'Student Masterlist/StudentMasterlist'

mainfile = str(path + os.sep + filename + '.csv')
# Raspberry Pi 1
copy1 = str(path + os.sep + filename + '00000000944de39a.csv')
# Raspberry Pi 2
copy2 = str(path + os.sep + filename + '00000000781547eb.csv')

try:
    with open(mainfile, 'r') as f:
        file_read = csv.reader(f)
        masterArray = list(file_read)
    masterFlag = 1
except FileNotFoundError:
    mainFlag = 0
    print(mainfile + " does not exist.")

try:
    with open(copy1, 'r') as f:
        file_read = csv.reader(f)
        copy1Array = list(file_read)
        copy1mdate = os.path.getmtime(copy1)
    copy1Flag = 1
except FileNotFoundError:
    copy1Flag = 0
    print(copy1 + " does not exist.")

try:
    with open(copy2, 'r') as f:
        file_read = csv.reader(f)
        copy2Array = list(file_read)
        copy2mdate = os.path.getmtime(copy2)
    copy2Flag = 1
except FileNotFoundError:
    copy2Flag = 0
    print(copy2 + " does not exist.")

if masterFlag == 1 and copy1Flag == 1 and copy2Flag == 1:
    i = 1;
    l = len(masterArray)

    # - 1 for array syntax, another - 1 to ignore header
    l = l - 2

    if (copy1mdate > copy2mdate):
        print(copy1 + " is newer")
    else:
        print(copy2 + " is newer")

    while l >= 0:
        if masterArray[i][0]:
            if masterArray[i][0] == copy1Array[i][0] and masterArray[i][0] == copy2Array[i][0]:
                print("Retaining Value. Same data for all three files.")
            else:
                if not (copy1Array[i][0]) and not (copy2Array[i][0]):
                    pass
                elif (copy1Array[i][0]) and not (copy2Array[i][0]):
                    print('Swapping ' + masterArray[i][0] + ' for ' + copy1Array[i][0])
                    masterArray[i][0] = copy1Array[i][0]
                elif (copy2Array[i][0]) and not (copy1Array[i][0]):
                    print('Swapping ' + masterArray[i][0] + ' for ' + copy2Array[i][0])
                    masterArray[i][0] = copy2Array[i][0]
                else:
                    if copy1mdate > copy2mdate:
                        print('Swapping ' + masterArray[i][0] + ' for ' + copy1Array[i][0])
                        masterArray[i][0] = copy1Array[i][0]
                    else:
                        print('Swapping ' + masterArray[i][0] + ' for ' + copy2Array[i][0])
                        masterArray[i][0] = copy2Array[i][0]
            i = i + 1
            l = l - 1
        else:
            if not (copy1Array[i][0]) and not (copy2Array[i][0]):
                pass
            elif (copy1Array[i][0]) and not (copy2Array[i][0]):
                print("New RFID entry for " + masterArray[i][2] + " " + masterArray[i][3] + ": " + copy1Array[i][0])
                masterArray[i][0] = copy1Array[i][0]
            elif (copy2Array[i][0]) and not (copy1Array[i][0]):
                print("New RFID entry for " + masterArray[i][2] + " " + masterArray[i][3] + ": " + copy2Array[i][0])
                masterArray[i][0] = copy2Array[i][0]
            else:
                if copy1mdate > copy2mdate:
                    print("New RFID entry for " + masterArray[i][2] + " " + masterArray[i][3] + ": " + copy1Array[i][0])
                    masterArray[i][0] = copy1Array[i][0]
                else:
                    print("New RFID entry for " + masterArray[i][2] + " " + masterArray[i][3] + ": " + copy2Array[i][0])
                    masterArray[i][0] = copy2Array[i][0]
            i = i + 1
            l = l - 1

    print(filename + '.csv has been successfully updated.')
    pd.DataFrame(masterArray).to_csv(path + os.sep + filename + '.csv', index=False, header=None)
    os.remove(path + os.sep + filename + '00000000944de39a.csv')
    os.remove(path + os.sep + filename + '00000000781547eb.csv')
    print("Copy files have been removed.")
else:
    print("Program skipped. One/Some of the files does not exist.")
