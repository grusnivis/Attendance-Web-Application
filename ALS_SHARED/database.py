
class DataBase:
    def __init__(self, filename):
        self.filename = filename
        self.teachers = None
        self.users = None
        self.id = None
        self.file = None
        self.load()

    def load(self):
        self.file = open(self.filename, "r")
        self.teachers = {}
        self.users = {}
        self.id = {}
        import time

        for line in self.file:
            # indicate here how the values are separated
            #rfid, name, surname, email = line.strip().split(",")
            rfid, id, lastname, firstname = line.split(",")
            self.teachers[rfid] = f"{firstname},{lastname}"
            self.users[rfid] = f"{lastname},{firstname}"
            self.id[rfid] = id

            self.teachers[rfid] = self.teachers[rfid].replace("\n", "")
            self.users[rfid] = self.users[rfid].replace("\n", "")

        self.file.close()

    def get_user(self, rfid):
        if rfid in self.users:
            return self.users[rfid]
        else:
            return -1

    def get_teacher(self, rfid):
        if rfid in self.teachers:
            return self.teachers[rfid]
        else:
            return -1
        
    def get_id(self, rfid):
        if rfid in self.id:
            return self.id[rfid]
        else:
            return -1

    def validate(self, rfid):
        if self.get_user(rfid) != -1:
            return self.users[rfid][0]
        else:
            return False

    def validate2(self, rfid):
        if self.get_teacher(rfid) != -1:
            return self.teachers[rfid]
        else:
            return False


    @staticmethod
    def append(rfid, id, name, newfile, hour, mins, sec, status):
        with open(newfile) as f:
            scan = f.readlines()
        # to hold existing rfid tags
        rfid_check = []
        for x in scan:
            rfid_check.append(x.split(',')[0])
            
        # append to file if student has not tapped yet
        if rfid not in rfid_check:
            with open(newfile, 'a') as f:
                f.write(rfid + "," + id + "," + name + "," + status + "," + hour + ":" + mins + ":" + sec + "\n")
            f.close()
            '''return 1
        else:
            f.close()
            return -1'''

    @staticmethod
    def check(path, newfile):
        list = open(path, 'r')
        logs = open(newfile, "r+")

        f1 = iter(list)
        f2 = iter(logs)

        compare = []
        withis = []

        # holds all IDs and Names in compare list (from class list)
        for line in list:
            if "ID" in line:
                nline = next(list)
                compare.append(nline)

                for nline in f1:
                    compare.append(nline)

        # holds all tapped RFIDs and Names in withis list (from attendance logs)
        for line in logs:
            if "ID" in line:
                nline = next(logs)
                withis.append(nline.split(',')[1] + "," + nline.split(',')[2] +
                              "," + nline.split(',')[3] + "\n")

                for nline in f2:
                    withis.append(nline.split(',')[1] + "," + nline.split(',')[2] +
                                  "," + nline.split(',')[3] + "\n")

        # compares the 2 lists and copies the missing ones into the attendance logs (with ABSENT status)
        for ele in compare:
            if ele not in withis:
                withis.append(ele.split(',')[0] + "," + ele.split(',')[1] + "," + ele.split(',')[2])
                ele = "," + ele.strip() + ",ABSENT,\n"
                logs.write(ele)

        list.close()
        logs.close()


class Class_DataBase:
    def __init__(self, filename):
        self.filename = filename
        self.users = None
        self.id = None
        self.file = None
        self.load()

    def load(self):
        self.file = open(self.filename, "r")
        self.users = {}

        for line in self.file:
            # indicate here how the values are separated
            id, lastname, firstname = line.strip().split(",")
            self.users[id] = f"{lastname},{firstname}"

            self.users[id] = self.users[id].replace("\n", "")

        self.file.close()

    def get_user(self, id):
        if id in self.users:
            return 1
        else:
            return -1