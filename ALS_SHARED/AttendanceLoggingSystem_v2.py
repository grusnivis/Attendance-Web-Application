import matplotlib

matplotlib.use('Agg')
import datetime
import os
import shutil
import fcntl
import threading
import RPi.GPIO as GPIO
import pandas as pd
from sys import exit

from tkinter import *
from mfrc522 import SimpleMFRC522
from threading import Thread
from time import sleep
import time
from pathlib import Path

from database import DataBase
from database import Class_DataBase

signal = True
mode = 1
id_scanned = 0
scanning_start = 0

rfid_tag = ""
rfid = ""
rfid2 = ""
id = ""
id2 = ""
name = ""
name2 = ""
selected_class = ""

# file paths
path = ""
class_file = ""
config_file = ""
newfile = ""
logs = ""
log_path = ""

# time
currentTime = ""
year = ""
month = ""
day = ""
hour = ""
mins = ""
sec = ""

# for personal configurations (late, absent, etc)
class_start = ""
class_end = ""
mark_teacher = ""
teacher_status = ""
teacher_late = ""
teacher_absent = ""
base_student = ""
student_status = ""
student_late = ""
student_absent = ""


################################### MAIN FUNCTIONS FOR PAGES IN WINDOW ###################################
def start():
    global signal, rfid, name, s_masterFile, t_masterFile, t_copy, s_copy, t_master_copy, s_master_copy, event

    # in case the device powers down in the middle of attendance taking, this action will be performed
    # something like an autosave function
    os.chdir('/home/pi/')
    for file in os.listdir('/home/pi/'):
        if file.endswith(".csv"):
            shutil.move(file, '/home/pi/ALS_SHARED/Attendance Logs')
    os.chdir('/home/pi/ALS_SHARED/')

    # retrieves unique raspberry Pi serial ID
    serial = getSerial()

    # true files
    t_masterFile = 'Authorized User Masterlist/AuthorizedUsers.csv'
    s_masterFile = 'Student Masterlist/StudentMasterlist.csv'

    # duplicate AuthorizedUsers.csv and StudentMasterList.csv as files with unique serial ID of rasPi
    t_copy = 'Authorized User Masterlist/AuthorizedUsers' + serial + '.csv'
    s_copy = 'Student Masterlist/StudentMasterlist' + serial + '.csv'

    # creates a duplicate if duplicate file doesn't exist, updates (replaces) duplicate
    # file with updated data if it does.
    # This means the temporary duplicate data will always be present and updated in directory
    if not os.path.exists(t_copy):
        shutil.copy(t_masterFile, t_copy)
    if not os.path.exists(s_copy):
        shutil.copy(s_masterFile, s_copy)

    # duplicated files as DataBase objects for byReference use
    t_master_copy = DataBase(t_copy)
    s_master_copy = DataBase(s_copy)

    #file not being found errorchuchu
    s_masterFile = '/home/pi/ALS_SHARED/Student Masterlist/StudentMasterlist.csv'
    s_copy = '/home/pi/ALS_SHARED/' + s_copy

    rfid = name = ""
    signal = True
    event = 0
    clear_frame()

    attendance = Button(root, text='CLASS ATTENDANCE', font="Arial, 25", command=teacher_scan)
    attendance.pack(side="top", expand=True, fill="both")

    event = Button(root, text='SCHOOL EVENT', font="Arial, 25", command=school_event_buffer)
    event.pack(expand=True, fill="both")

    register = Button(root, text='TEACHER ENROLLMENT', font="Arial, 25", command=regi)
    register.pack(expand=True, fill="both")

    shut = Button(root, text='SHUT DOWN', font="Arial, 25", command=shutdown)
    shut.pack(side="bottom", expand=True, fill="both")

    # done = Button(root, text="EXIT", padx=20, pady=5, command=root.destroy)
    # done.place(x=45, y=415)

    # for files in os.walk(path):
    # scans for .csv files existing outside of created directories
    # if i.endswith(".csv"):
    # checks if the directory exists
    # if not os.path.exists("/home/pi/ALS_SHARED/Attendance Logs"):
    # os.mkdir("/home/pi/ALS_SHARED/Attendance Logs")
    # shutil.move(i, "/home/pi/ALS_SHARED/Attendance Logs")

    # else:
    # shutil.move(i, "/home/pi/ALS_SHARED/Attendance Logs")

    # os.chdir('./ALS_SHARED/')


################################### ADMIN PAGES ###################################


def regi():
    global reg, sub, prompt
    clear_frame()

    menu = Menubutton(root, text='OPTIONS')
    menu.config(font="Arial, 18")
    menu.pack(anchor="ne")
    menu.menu = Menu(menu, tearoff=0)
    menu["menu"] = menu.menu
    menu.menu.add_command(label='RETURN', font='Arial, 25', command=start)
    menu.menu.add_command(label='EXIT', font='Arial, 25', command=root.destroy)
    menu.menu.add_command(label='RESTART', font='Arial, 25', command=restart)
    menu.menu.add_command(label='SHUT DOWN', font='Arial, 25', command=shutdown)
    Button(root, text="BACK", font="Arial, 18", highlightthickness=0,
           borderwidth=0, command=start).place(anchor="nw")

    title = Label(root, text="ARE YOU ADMIN?", font="Arial, 38")
    title.pack(padx=35, pady=(60, 25))

    ver = LabelFrame(root, text="Please verify", font="Arial, 15", padx=15)
    ver.pack()

    prompt = Label(ver, text="SCAN YOUR ID\nTO PROCEED", font="Arial, 20")
    prompt.pack(padx=70, pady=20)

    reg = Button(root, text="ENROLL\nTEACHER", padx=20, pady=5, height=3, width=16, command=teacher_enrollment)
    reg.config(font="Arial, 15", state=DISABLED)
    reg.place(x=490, y=360)

    # sub = Button(root, text="SUBSTITUTE\nTEACHER", padx=20, pady=5, height=3, width=15, command=substitute_teacher)
    # sub.config(font="Arial, 15", state=DISABLED)
    # sub.place(x=70, y=360)

    root.after(300, reg_scanning)


def reg_scanning():
    global scanning_start
    scanning_start = 1

    while scanning_start:
        root.update()
        global id_scanned, rfid_tag

        if id_scanned == 1:
            id_scanned = 0
            verify_admin = rfid_tag
            admin = t_master_copy.validate2(verify_admin)

            # if not admin:
            if admin == "ADMIN,GROUP H" or admin == "AMBER BRINETTE U,LIM" or admin == "JUSTIN STUART R,HIPOLITO" \
                    or admin == "KATHRYN MARIE P,SIGAYA":
                reg.config(state=ACTIVE)
                scanning_start = 0
                # sub.config(state=ACTIVE)
                break

            else:
                if not verify_admin == "None":
                    prompt.config(text="ACCESS DENIED", foreground="red")
                    root.update()
                    scanning_start = 0
                    root.after(2000, start)


def teacher_enrollment():
    clear_frame()

    global lbl2
    menu = Menubutton(root, text='OPTIONS')
    menu.config(font="Arial, 18")
    menu.pack(anchor="ne")
    menu.menu = Menu(menu, tearoff=0)
    menu["menu"] = menu.menu
    menu.menu.add_command(label='RETURN', font='Arial, 25', command=start)
    menu.menu.add_command(label='EXIT', font='Arial, 25', command=root.destroy)
    menu.menu.add_command(label='RESTART', font='Arial, 25', command=restart)
    menu.menu.add_command(label='SHUT DOWN', font='Arial, 25', command=shutdown)
    Button(root, text="BACK", font="Arial, 18", highlightthickness=0,
           borderwidth=0, command=regi).place(anchor="nw")

    lbl = Label(root, text=f"Teacher RFID Enrollment", font="Arial, 28", anchor="n")
    lbl.pack(padx=38, pady=(30, 7))

    lbl2 = Label(root, text=f"Tap your ID only after your name is displayed", font="Arial, 15", anchor="n")
    lbl2.pack()

    # displays listbox
    listbox = Listbox(root, font="Arial, 30")
    listbox.pack(padx=(20, 90), pady=(10, 15), expand=True, fill="both")

    sel = Button(root, text="SELECT", padx=20, pady=5, height=2, width=10)
    sel.config(font="Arial, 15", state=DISABLED)
    sel.pack(side=LEFT, padx=(55, 0), pady=(0, 20))

    d = Button(root, text="DONE", padx=20, pady=5, height=2, width=9)
    d.config(font="Arial, 15", command=start)
    d.pack(side=RIGHT, padx=(0, 55), pady=(0, 20))

    up = Button(root, text="^", padx=20, pady=5, height=2, width=3)
    up.config(font="Arial 9 bold", overrelief=GROOVE)
    up.place(x=720, y=210)

    down = Button(root, text="v", padx=20, pady=5, height=2, width=3)
    down.config(font="Arial 9 bold", overrelief=GROOVE)
    down.place(x=720, y=280)

    # allows for scrolling
    scroll = Scrollbar(listbox, orient=HORIZONTAL)
    scrollbar = Scrollbar(listbox)
    scroll.pack(side="bottom", fill="x")
    scrollbar.pack(side="right", fill="both")

    listbox.config(xscrollcommand=scroll.set)
    listbox.config(yscrollcommand=scrollbar.set)
    scroll.config(command=listbox.xview)
    scrollbar.config(command=listbox.yview)

    with open(t_copy, 'r') as f:
        file = iter(f)
        next(file)

        # to display the names of the students in the listbox to be chosen from
        for n in file:
            listed = n.split(",")[2] + ", " + n.split(",")[3]
            listed = listed.replace("\n", "")
            listbox.insert(END, listed)
    f.close()

    def up_select():
        listbox.yview('scroll', -1, 'pages')

    def down_select():
        listbox.yview('scroll', 1, 'pages')

    def choice(_):
        screen = Label(root, text="Teacher RFID Enrollment", font="Arial, 18")
        screen.place(anchor="nw")

        global match
        selected = listbox.get(listbox.curselection())
        match = selected.split(", ")[1]

        with open(t_copy, 'r') as f1:
            for line in f1:
                if match in line:
                    tag = line.split(',')[0]
                    break
        f1.close()

        if tag == "" or tag == "\n":
            lbl.config(text=f"{match}:\nRFID NOT ENROLLED", foreground="red", font="Arial, 23")
            sel.configure(text="SCAN", state=ACTIVE, command=teacher_reg)
            root.update()

        else:
            lbl.config(text=f"{match}:\nRFID ENROLLED", foreground="green", font="Arial, 23")
            sel.configure(text="OVERWRITE", state=ACTIVE, command=teacher_overwrite)
            root.update()

    listbox.bind('<<ListboxSelect>>', choice)
    up.config(command=up_select)
    down.config(command=down_select)


def teacher_reg():
    global scanning_start
    scanning_start = 1

    while scanning_start:
        lbl2.config(text="Scanning...")
        root.update()

        global id_scanned, rfid_tag

        if id_scanned == 1:
            id_scanned = 0
            reg_rfid = rfid_tag

            if not reg_rfid == "None":
                scanning_start = 0
                with open(t_copy, 'r') as f:
                    lines = f.readlines()
                    for i, line in enumerate(lines):
                        if match in line:
                            index = i
                            copy = line
                            break
                f.close()

                lines[index] = reg_rfid + copy
                while True:
                    try:
                        with open(t_copy, 'w') as f:
                            fcntl.flock(f, fcntl.LOCK_EX | fcntl.LOCK_NB)
                            f.writelines(lines)
                            fcntl.flock(f, fcntl.LOCK_UN)
                            f.close()
                            break
                    except IOError:
                        time.sleep(0.05)

                clear_frame()
                root['background'] = 'green'
                lbl = Label(root, text=f"RFID\nREGISTERED", font="Arial 65  bold", background="green",
                            foreground="white")
                lbl.place(relx=0.5, rely=0.5, anchor="center")
                scanning_start = 0
                root.after(500, teacher_enrollment)
                root.after(500, lambda: root.config(background="#d9d9d9"))


def teacher_overwrite():
    global scanning_start
    scanning_start = 1

    while scanning_start:
        lbl2.config(text="Overwriting...")
        root.update()

        global id_scanned, rfid_tag

        if id_scanned == 1:
            id_scanned = 0
            rfid_tag2 = rfid_tag

            if not rfid_tag2 == "None":
                with open(t_copy, 'r') as f:
                    lines = f.readlines()
                    for i, line in enumerate(lines):
                        if match in line:
                            index = i
                            copy = line
                            overwritten = copy.replace(line.split(',')[0], rfid_tag2)
                            break
                f.close()

                lines[index] = overwritten
                while True:
                    try:
                        with open(t_copy, 'w') as f:
                            fcntl.flock(f, fcntl.LOCK_EX | fcntl.LOCK_NB)
                            f.writelines(lines)
                            fcntl.flock(f, fcntl.LOCK_UN)
                            f.close()
                            break
                    except IOError:
                        time.sleep(0.05)

                clear_frame()
                root['background'] = 'green'
                lbl = Label(root, text=f"RFID\nOVERWRITTEN", font="Arial 65  bold", background="green",
                            foreground="white")
                lbl.place(relx=0.5, rely=0.5, anchor="center")
                scanning_start = 0
                root.after(500, teacher_enrollment)
                root.after(500, lambda: root.config(background="#d9d9d9"))


################################### SCHOOL EVENT PAGES ###################################

def school_event_buffer():
    global event
    event = 1
    root.after(300, teacher_scan)


def school_event():
    os.chdir('/home/pi/')

    global newfile, logs, b, class_file
    newfile = (year + "-" + month + "-" + day + "_" + name + "_SCHOOL EVENT_" + hour + "-" + mins + "-" + sec +
               "_OUT OF CLASS.csv")
    logs = "./" + newfile
    class_file = ""

    fullname = t_master_copy.get_user(rfid)
    with open(newfile, 'w') as f:
        f.write("RFID,ID Number,Lastname,Firstname,Status,Time-in\n" + rfid + "," + id + "," + fullname
                + ",PRESENT," + hour + ":" + mins + ":" + sec + "\n")
    f.close()

    clear_frame()

    menu = Menubutton(root, text='OPTIONS')
    menu.config(font="Arial, 18")
    menu.pack(anchor="ne")
    menu.menu = Menu(menu, tearoff=0)
    menu["menu"] = menu.menu
    menu.menu.add_command(label='RETURN', font='Arial, 25', command=start)
    menu.menu.add_command(label='EXIT', font='Arial, 25', command=root.destroy)
    menu.menu.add_command(label='RESTART', font='Arial, 25', command=restart)
    menu.menu.add_command(label='SHUT DOWN', font='Arial, 25', command=shutdown)
    Button(root, text="BACK", font="Arial, 18", highlightthickness=0,
           borderwidth=0, command=teacher_scan).place(anchor="nw")

    title = Label(root, text="School Event", font="Arial, 38")
    title.pack(padx=35, pady=(55, 28))

    s_welcome = LabelFrame(root, text="Welcome!", font="Arial, 20", padx=15)
    s_welcome.pack()

    b = Label(s_welcome, text="PLEASE TAP YOUR ID\nTO MARK YOUR ATTENDANCE", font="Arial, 20")
    b.pack(padx=50, pady=(30, 40))

    stop = Button(root, text="END ATTENDANCE", padx=20, pady=5, height=3, width=12, command=end_attendance)
    stop.config(font="Arial, 15")
    stop.place(x=513, y=373)

    root.after(300, event_scanning)


def event_scanning():
    while signal:
        b.config(text="PLEASE TAP YOUR ID\nTO MARK YOUR ATTENDANCE", foreground="black")
        root.update()

        global rfid2, rfid_tag, id_scanned, event_rfid

        if id_scanned == 1:
            id_scanned = 0
            event_rfid = rfid_tag

            current_time = datetime.datetime.now()
            get_time(current_time)

            if s_master_copy.validate(event_rfid):
                # gets name of student from masterlist
                global name2, id2
                name2 = s_master_copy.get_user(event_rfid)
                id2 = s_master_copy.get_id(event_rfid)

                try:
                    s_master_copy.append(event_rfid, id2, name2, newfile, hour, mins, sec, "PRESENT")
                    b.config(text=f"{name2}\nMATCH FOUND", foreground="green")
                    root.update()
                    sleep(1)
                except FileNotFoundError:
                    root.after(300, start)

            elif not s_master_copy.validate(event_rfid):
                try:
                    if not event_rfid == "None":
                        s_master_copy.append(event_rfid, "", ",", newfile, hour, mins, sec, "PRESENT")
                        b.config(text=f"MATCH NOT FOUND", foreground="red")
                        root.update()
                        sleep(1)
                except FileNotFoundError:
                    root.after(300, start)

    else:
        root.after(300, start)


################################### ATTENDANCE TAKING PAGES ###################################

def teacher_scan():
    global prompt, sub, scanning_start
    scanning_start = 1

    os.chdir('/home/pi/ALS_SHARED/')
    clear_frame()

    menu = Menubutton(root, text='OPTIONS')
    menu.config(font="Arial, 18")
    menu.pack(anchor="ne")
    menu.menu = Menu(menu, tearoff=0)
    menu["menu"] = menu.menu
    menu.menu.add_command(label='RETURN', font='Arial, 25', command=start)
    menu.menu.add_command(label='EXIT', font='Arial, 25', command=root.destroy)
    menu.menu.add_command(label='RESTART', font='Arial, 25', command=restart)
    menu.menu.add_command(label='SHUT DOWN', font='Arial, 25', command=shutdown)
    Button(root, text="BACK", font="Arial, 18", highlightthickness=0,
           borderwidth=0, command=start).place(anchor="nw")

    title = Label(root, text="Attendance Logging System", font="Arial, 38")
    title.pack(padx=35, pady=(60, 45))

    t_welcome = LabelFrame(root, text="Welcome! (Authorized Access Only)", font="Arial, 15", padx=15)
    t_welcome.pack()

    prompt = Label(t_welcome, text="PLEASE SCAN YOUR ID\nTO START THE ATTENDANCE\nTAKING PROCESS", font="Arial, 20")
    prompt.pack(padx=50, pady=40)

    root.after(300, teacher_scanning)


def teacher_scanning():
    global scanning_start

    while scanning_start:
        global rfid, name, rfid_tag, id_scanned
        prompt.config(text="PLEASE SCAN YOUR ID\nTO START THE ATTENDANCE\nTAKING PROCESS", foreground="black")
        root.update()

        if id_scanned == 1:
            id_scanned = 0
            rfid = rfid_tag

            # if teacher found on list
            if t_master_copy.validate(rfid):
                scanning_start = 0
                # gets the name of ID holder based on list
                global name, id, currentTime, log_path, path
                name = t_master_copy.get_teacher(rfid)
                id = t_master_copy.get_id(rfid)

                # gets the time of Teacher's ID tap
                current_time = datetime.datetime.now()
                currentTime = current_time
                get_time(current_time)
                name = name.replace(",", " ")

                # if school event
                if event == 1:
                    root.after(300, school_event)

                else:
                    if os.path.isdir(f"./{name}"):
                        path = os.getcwd() + "/" + name
                        d = os.listdir(path)

                        # if directory is empty, it's a substitute; else proceed to class selection
                        if len(d) == 0:
                            root.after(300, substitute_teacher)
                        else:
                            root.after(300, select_class)

                    else:
                        root.after(300, substitute_teacher)


            # else if student or other invalid ID
            else:
                if rfid == "None":
                    pass
                else:
                    prompt.config(text="INVALID ID", foreground="red")
                    root.update()
                    sleep(1)


def substitute_teacher():
    global name
    clear_frame()

    menu = Menubutton(root, text='OPTIONS')
    menu.config(font="Arial, 18")
    menu.pack(anchor="ne")
    menu.menu = Menu(menu, tearoff=0)
    menu["menu"] = menu.menu
    menu.menu.add_command(label='RETURN', font='Arial, 25', command=start)
    menu.menu.add_command(label='EXIT', font='Arial, 25', command=root.destroy)
    menu.menu.add_command(label='RESTART', font='Arial, 25', command=restart)
    menu.menu.add_command(label='SHUT DOWN', font='Arial, 25', command=shutdown)
    Button(root, text="BACK", font="Arial, 18", highlightthickness=0,
           borderwidth=0, command=teacher_scan).place(anchor="nw")

    # label that welcomes the teacher
    welcome = Label(root, text=f"HELLO\n{name}\nPlease select who you are substituting:", font="Arial, 24")
    welcome.pack(padx=38, pady=(43, 9))

    # displays listbox
    listbox = Listbox(root, font="Arial, 30")
    listbox.pack(padx=(43, 90), pady=(10, 80), expand=True, fill="both")

    p = os.listdir()
    for i in p:
        if os.path.isdir(i):
            if not i.endswith('Attendance Logs') | i.endswith('Authorized User Masterlist') | \
                   i.endswith('.stfolder') | i.endswith('.stversions') | i.endswith('Student Masterlist') | \
                   i.endswith('.idea') | i.endswith('__pycache__') | i.endswith('Attendance Logs - Copy'):
                listbox.insert(END, i)
                # listbox.insert(END, i.encode('utf-8').decode('unicode_escape', 'ignore'))

    # allows for scrolling
    scroll = Scrollbar(listbox, orient=HORIZONTAL)
    scrollbar = Scrollbar(listbox)
    scroll.pack(side="bottom", fill="x")
    scrollbar.pack(side="right", fill="both")

    listbox.config(xscrollcommand=scroll.set)
    listbox.config(yscrollcommand=scrollbar.set)
    scroll.config(command=listbox.xview)
    scrollbar.config(command=listbox.yview)

    up = Button(root, text="^", padx=20, pady=5, height=2, width=3)
    up.config(font="Arial 9 bold", overrelief=GROOVE)
    up.place(x=720, y=260)

    down = Button(root, text="v", padx=20, pady=5, height=2, width=3)
    down.config(font="Arial 9 bold", overrelief=GROOVE)
    down.place(x=720, y=330)

    def up_select():
        listbox.yview('scroll', -1, 'pages')

    def down_select():
        listbox.yview('scroll', 1, 'pages')

    def choice(_):
        global name, path
        name = listbox.get(listbox.curselection())
        # folder = name.encode().decode('unicode_escape')
        path = os.getcwd() + "/" + name

        root.after(300, select_class)

    listbox.bind('<<ListboxSelect>>', choice)
    up.config(command=up_select)
    down.config(command=down_select)


def select_class():
    os.chdir('/home/pi/ALS_SHARED/')
    clear_frame()

    menu = Menubutton(root, text='OPTIONS')
    menu.config(font="Arial, 18")
    menu.pack(anchor="ne")
    menu.menu = Menu(menu, tearoff=0)
    menu["menu"] = menu.menu
    menu.menu.add_command(label='RETURN', font='Arial, 25', command=start)
    menu.menu.add_command(label='EXIT', font='Arial, 25', command=root.destroy)
    menu.menu.add_command(label='RESTART', font='Arial, 25', command=restart)
    menu.menu.add_command(label='SHUT DOWN', font='Arial, 25', command=shutdown)
    Button(root, text="BACK", font="Arial, 18", highlightthickness=0,
           borderwidth=0, command=teacher_scan).place(anchor="nw")

    # label that welcomes the teacher
    welcome = Label(root, text=f"HELLO\n{name}\nPlease select your class:", font="Arial, 24")
    welcome.pack(padx=38, pady=(23, 9))

    # displays listbox
    listbox = Listbox(root, font="Arial, 30")
    listbox.pack(padx=(43, 90), pady=(0, 105), expand=True, fill="both")

    # to display the files in the specific teacher's directory, ignores the config files
    # classes.split just splits the filename and the file extension
    for classes in os.listdir(f'./{name}'):
        if not classes.endswith("_config.csv"):
            listbox.insert(END, classes.split(".")[0])

    # allows for scrolling
    scroll = Scrollbar(listbox, orient=HORIZONTAL)
    scrollbar = Scrollbar(listbox)
    scroll.pack(side="bottom", fill="x")
    scrollbar.pack(side="right", fill="both")

    listbox.config(xscrollcommand=scroll.set)
    listbox.config(yscrollcommand=scrollbar.set)
    scroll.config(command=listbox.xview)
    scrollbar.config(command=listbox.yview)

    up = Button(root, text="^", padx=20, pady=5, height=2, width=3)
    up.config(font="Arial 9 bold", overrelief=GROOVE)
    up.place(x=720, y=230)

    down = Button(root, text="v", padx=20, pady=5, height=2, width=3)
    down.config(font="Arial 9 bold", overrelief=GROOVE)
    down.place(x=720, y=300)

    substi = Button(root, text="SUBSTITUTE TEACHER", padx=20, pady=5, height=2, width=20, command=substitute_teacher)
    substi.config(font="Arial, 15")
    substi.place(x=430, y=395)

    def up_select():
        listbox.yview('scroll', -1, 'pages')

    def down_select():
        listbox.yview('scroll', 1, 'pages')

    def choice(_):
        # CPE 3202_g2_S 1-430PM
        global selected_class, config_file
        selected_class = listbox.get(listbox.curselection())
        config_file = path + "/" + selected_class + "_config." + classes.split(".")[1]
        # checks if end time of class already
        # val = end()
        # if val == 1:
        root.after(300, display_selection)
        # else:
        # root.after(300, select_class)

    listbox.bind('<<ListboxSelect>>', choice)
    up.config(command=up_select)
    down.config(command=down_select)


def display_selection():
    os.chdir('/home/pi/ALS_SHARED/')
    #s_master = DataBase('/home/pi/ALS_SHARED/Student Masterlist/StudentMasterlist.csv')
    clear_frame()
    global mode
    mode = 1

    menu = Menubutton(root, text='OPTIONS')
    menu.config(font="Arial, 18")
    menu.pack(anchor="ne")
    menu.menu = Menu(menu, tearoff=0)
    menu["menu"] = menu.menu
    menu.menu.add_command(label='RETURN', font='Arial, 25', command=start)
    menu.menu.add_command(label='BACK', font='Arial, 25', command=select_class)
    menu.menu.add_command(label='EXIT', font='Arial, 25', command=root.destroy)
    menu.menu.add_command(label='RESTART', font='Arial, 25', command=restart)
    menu.menu.add_command(label='SHUT DOWN', font='Arial, 25', command=shutdown)
    Button(root, text="BACK", font="Arial, 18", highlightthickness=0,
           borderwidth=0, command=select_class).place(anchor="nw")

    # dependent on file name convention
    course = selected_class.split("_")[1]
    group = selected_class.split("_")[0]
    schedule = selected_class.split("_")[2]

    # displays selected class list for verification
    display = Label(root, text=f"Please verify your selection:", font='Arial, 28')
    display.pack(padx=35, pady=(60, 40))
    display2 = Label(root, text=f"COURSE CODE:\t{course}\nGROUP:\t\t{group}\nSCHEDULE:\t{schedule}",
                     font='Arial, 23', justify='left')
    display2.pack()

    val = end()
    if val == 1:
        yes = Button(root, text="ATTENDANCE\nTAKING", padx=20, pady=5, height=3, width=15, command=create_file)
    else:
        yes = Button(root, text="ATTENDANCE\nTAKING", padx=20, pady=5, height=3, width=15, state=DISABLED)

    yes.config(font="Arial, 15")
    yes.place(x=490, y=360)

    reg = Button(root, text="STUDENT\nENROLLMENT", padx=20, pady=5, height=3, width=16, command=buffer)
    reg.config(font="Arial, 15")
    reg.place(x=70, y=360)

    root.update()


def student_scan():
    clear_frame()
    global b

    menu = Menubutton(root, text='OPTIONS')
    menu.config(font="Arial, 18")
    menu.pack(anchor="ne")
    menu.menu = Menu(menu, tearoff=0)
    menu["menu"] = menu.menu
    menu.menu.add_command(label='RETURN', font='Arial, 25', command=start)
    menu.menu.add_command(label='EXIT', font='Arial, 25', command=root.destroy)
    menu.menu.add_command(label='RESTART', font='Arial, 25', command=restart)
    menu.menu.add_command(label='SHUT DOWN', font='Arial, 25', command=shutdown)
    Button(root, text="BACK", font="Arial, 18", highlightthickness=0,
           borderwidth=0, command=display_selection).place(anchor="nw")

    title = Label(root, text="Class Attendance", font="Arial, 38")
    title.pack(padx=35, pady=(55, 28))

    s_welcome = LabelFrame(root, text="Welcome!", font="Arial, 20", padx=15)
    s_welcome.pack()

    b = Label(s_welcome, text="PLEASE TAP YOUR ID\nTO MARK YOUR ATTENDANCE", font="Arial, 20")
    b.pack(padx=50, pady=(30, 40))

    stop = Button(root, text="END ATTENDANCE", padx=20, pady=5, height=3, width=12, command=end_attendance)
    stop.config(font="Arial, 15")
    stop.place(x=513, y=373)

    root.update()

    # (./Teacher1/CPE 3202_g2_S 1-430PM.csv)
    global class_file, s_list
    class_file = path + "/" + selected_class + ".csv"
    s_list = Class_DataBase(class_file)

    if signal:
        root.after(300, student_scanning)
    else:
        start()


def student_scanning():
    until_end = datetime.datetime.strptime(year + "-" + month + "-" + day + "_" + class_end, "%Y-%m-%d_%H:%M\n")

    # time_diff = until_end - now
    # stop_time = time_diff.seconds

    # def stop():
    # try:
    # end_attendance()
    # except FileNotFoundError:
    # pass
    # root.after(2000, shutdown)
    # root.after(300, start)

    # start_time = threading.Timer(stop_time, stop)
    # start_time.daemon = True
    # start_time.start()

    while signal:
        b.config(text="PLEASE TAP YOUR ID\nTO MARK YOUR ATTENDANCE", foreground="black")
        root.update()

        global rfid2, rfid_tag, id_scanned

        if id_scanned == 1:
            id_scanned = 0
            rfid2 = rfid_tag
            get_sstatus()

            if s_master_copy.validate(rfid2):
                # gets name of student
                global name2, id2
                name2 = s_master_copy.get_user(rfid2)
                id2 = s_master_copy.get_id(rfid2)

                try:
                    # global frame
                    valid = s_list.get_user(id2)
                    if valid == 1:
                        s_master_copy.append(rfid2, id2, name2, newfile, hour, mins, sec, student_status)
                        b.config(text=f"{name2}\nMATCH FOUND", foreground="green")
                        root.update()
                        sleep(1)

                    else:
                        s_master_copy.append(rfid2, id2, name2, newfile, hour, mins, sec, student_status)
                        b.config(text=f"{name2}\nATTENDANCE TAKEN\nNOT FOUND IN CLASS ROSTER", foreground="red")
                        root.update()
                        sleep(1)

                except FileNotFoundError:
                    root.after(300, start)

            elif not s_master_copy.validate(rfid2):
                try:
                    if not rfid2 == "None":
                        s_master_copy.append(rfid2, "", ",", newfile, hour, mins, sec, student_status)
                        b.config(text=f"MATCH NOT FOUND", foreground="red")
                        root.update()
                        sleep(1)
                except FileNotFoundError:
                    root.after(300, start)

            if datetime.datetime.now() >= until_end:
                end_attendance()
                sleep(2)
                root.after(300, shutdown)
                break
    else:
        root.after(300, start)


################################### REGISTER STUDENT PAGES ###################################

def buffer():
    global mode, class_file, s_list
    mode = 0
    create_file()

    # (./Teacher1/CPE 3202_g2_S 1-430PM.csv)
    class_file = path + "/" + selected_class + ".csv"
    s_list = Class_DataBase(class_file)

    root.after(300, student_enrollment)


def buffer2():
    if os.path.exists(newfile):
        os.remove(newfile)
        display_selection()
        os.chdir('/home/pi/ALS_SHARED/')
    else:
        display_selection()
        os.chdir('/home/pi/ALS_SHARED/')


def student_enrollment():
    clear_frame()

    try:
        global inst2
        menu = Menubutton(root, text='OPTIONS')
        menu.config(font="Arial, 18")
        menu.pack(anchor="ne")
        menu.menu = Menu(menu, tearoff=0)
        menu["menu"] = menu.menu
        menu.menu.add_command(label='RETURN', font='Arial, 25', command=start)
        menu.menu.add_command(label='BACK', font='Arial, 25', command=display_selection)
        menu.menu.add_command(label='EXIT', font='Arial, 25', command=root.destroy)
        menu.menu.add_command(label='RESTART', font='Arial, 25', command=restart)
        menu.menu.add_command(label='SHUT DOWN', font='Arial, 25', command=shutdown)

        inst = Label(root, text=f"Student RFID Enrollment", font="Arial, 28", anchor="n")
        inst.pack(padx=38, pady=(30, 7))

        inst2 = Label(root, text=f"Tap your ID only after your name is displayed", font="Arial, 15", anchor="n")
        inst2.pack()

        # displays listbox
        listbox = Listbox(root, font="Arial, 30")
        listbox.pack(padx=(20, 90), pady=(10, 15), expand=True, fill="both")

        ow = Button(root, text="SELECT", padx=20, pady=5, height=2, width=10)
        ow.config(font="Arial, 15", state=DISABLED)
        ow.pack(side=LEFT, padx=(30, 0), pady=(0, 20))

        cal = Button(root, text="CREATE ATTENDANCE LOG", padx=20, pady=5, height=2, width=21)
        cal.config(font="Arial, 15", command=end_attendance)
        cal.pack(side=LEFT, padx=(38, 0), pady=(0, 20))

        d = Button(root, text="DONE", padx=20, pady=5, height=2, width=9)
        d.config(font="Arial, 15", command=buffer2)
        d.pack(side=RIGHT, padx=(0, 30), pady=(0, 20))

        # allows for scrolling
        scroll = Scrollbar(listbox, orient=HORIZONTAL)
        scrollbar = Scrollbar(listbox)
        scroll.pack(side="bottom", fill="x")
        scrollbar.pack(side="right", fill="both")

        listbox.config(xscrollcommand=scroll.set)
        listbox.config(yscrollcommand=scrollbar.set)
        scroll.config(command=listbox.xview)
        scrollbar.config(command=listbox.yview)

        up = Button(root, text="^", padx=20, pady=5, height=2, width=3)
        up.config(font="Arial 9 bold", overrelief=GROOVE)
        up.place(x=720, y=210)

        down = Button(root, text="v", padx=20, pady=5, height=2, width=3)
        down.config(font="Arial 9 bold", overrelief=GROOVE)
        down.place(x=720, y=290)

        with open(class_file, 'r') as f:
            file = iter(f)
            next(file)

            # to display the names of the students in the listbox to be chosen from
            for n in file:
                listed = n.split(",")[1] + ", " + n.split(",")[2]
                listed = listed.replace("\n", "")
                listbox.insert(END, listed)
        f.close()

        def up_select():
            listbox.yview('scroll', -1, "pages")

        def down_select():
            listbox.yview('scroll', 1, "pages")

        def choice(_):
            screen = Label(root, text="Student RFID Enrollment", font="Arial, 18")
            screen.place(anchor="nw")

            global match
            selected = listbox.get(listbox.curselection())
            match = selected.split(", ")[1]

            with open(s_copy, 'r') as f1:
                for i, line in enumerate(f1):
                    if match in line:
                        global id_key
                        tag = line.split(',')[0]
                        id_key = line.split(',')[1] + "," + line.split(',')[2] + "," + line.split(',')[3]
                        break
            f1.close()

            if tag == "" or tag == "\n":
                inst.config(text=f"{match}:\nRFID NOT ENROLLED", foreground="red", font="Arial, 23")
                ow.configure(text="SCAN", state=ACTIVE, command=enrollment_scanning)
                root.update()

            else:
                inst.config(text=f"{match}:\nRFID ENROLLED", foreground="green", font="Arial, 23")
                ow.configure(text="OVERWRITE", state=ACTIVE, command=enrollment_overwrite)
                root.update()

        listbox.bind('<<ListboxSelect>>', choice)
        up.config(command=up_select)
        down.config(command=down_select)

    except:
        root.after(300, display_selection)


def enrollment_scanning():
    global scanning_start
    scanning_start = 1

    while scanning_start:
        inst2.config(text="Scanning...")
        root.update()

        global id_scanned, rfid_tag

        if id_scanned == 1:
            id_scanned = 0
            reg_rfid = rfid_tag
            get_sstatus()

            if not reg_rfid == "None":
                scanning_start = 0
                with open(s_copy, 'r') as f1:
                    lines = f1.readlines()
                    for i, line in enumerate(lines):
                        global index, enrolled, copy
                        if id_key in line:
                            index = i
                            copy = line
                            enrolled = reg_rfid + copy
                            break

                    lines[index] = enrolled

                    while True:
                        try:
                            with open(s_copy, 'w') as f2:
                                fcntl.flock(f2, fcntl.LOCK_EX | fcntl.LOCK_NB)
                                f2.writelines(lines)
                                fcntl.flock(f2, fcntl.LOCK_UN)
                                f2.close()
                                break
                        except IOError:
                            time.sleep(0.05)

                f1.close()

                logging = copy.strip().split(',')[2] + "," + copy.strip().split(',')[3]
                val = s_master_copy.append(reg_rfid, id_key.split(',')[0], logging, newfile, hour, mins, sec, student_status)

                clear_frame()
                root['background'] = 'green'
                lbl = Label(root, text=f"RFID\nREGISTERED", font="Arial 65  bold", background="green",
                            foreground="white")
                lbl.place(relx=0.5, rely=0.5, anchor="center")
                # sleep(1)
                root.after(500, student_enrollment)
                root.after(500, lambda: root.config(background="#d9d9d9"))


def enrollment_overwrite():
    inst2.config(text="Overwriting...")
    root.update()

    global scanning_start
    scanning_start = 1

    while scanning_start:
        inst2.config(text="Scanning...")
        root.update()

        global id_scanned, rfid_tag

        if id_scanned == 1:
            id_scanned = 0
            rfid_tag2 = rfid_tag
            get_sstatus()

            if not rfid_tag2 == "None":
                scanning_start = 0
                with open(s_copy, 'r') as f:
                    lines = f.readlines()
                    for i, line in enumerate(lines):
                        if match in line:
                            index = i
                            copy = line
                            overwritten = copy.replace(line.split(',')[0], rfid_tag2)
                            break
                f.close()

                lines[index] = overwritten

                while True:
                    try:
                        with open(s_copy, 'w') as f:
                            fcntl.flock(f, fcntl.LOCK_EX | fcntl.LOCK_NB)
                            f.writelines(lines)
                            fcntl.flock(f, fcntl.LOCK_UN)
                            f.close()
                            break
                    except IOError:
                        time.sleep(0.05)

                trigger = 1
                # insert append to attendance log file here
                with open(newfile, 'r') as f1:
                    lines = f1.readlines()
                    for i, line in enumerate(lines):
                        if match in line:
                            trigger = 0
                            index = i
                            copy = line
                            newfile_overwritten = copy.replace(line.split(',')[0], rfid_tag2)
                            break
                f1.close()

                if trigger == 0:
                    lines[index] = newfile_overwritten
                    with open(newfile, 'w') as f1:
                        f1.writelines(lines)
                    f1.close()

                else:
                    id = copy.strip().split(',')[1]
                    logging = copy.strip().split(',')[2] + "," + copy.strip().split(',')[3]
                    val = s_master_copy.append(rfid_tag2, id, logging, newfile, hour, mins, sec, student_status)

                clear_frame()
                root['background'] = 'green'
                lbl = Label(root, text=f"RFID\nOVERWRITTEN", font="Arial 65  bold", background="green",
                            foreground="white")
                lbl.place(relx=0.5, rely=0.5, anchor="center")
                root.after(500, student_enrollment)
                root.after(500, lambda: root.config(background="#d9d9d9"))


################################### SCANNING FUNCTION ###################################

def scanning():
    global id_scanned, rfid_tag
    reader = SimpleMFRC522()

    while True:
        try:
            rfid_tag, text = reader.read()
            rfid_tag = str(rfid_tag)
            if scanning_start or signal:
                id_scanned = 1

        finally:
            GPIO.cleanup()


################################### OTHER FUNCTIONS ###################################

def end_attendance():
    # moves the attendance log file to the Attendance Logs folder
    try:
        global signal
        signal = False

        s_master_copy.check(class_file, newfile)
        shutil.move(logs, "/home/pi/ALS_SHARED/Attendance Logs")
    except FileNotFoundError:
        pass
    finally:
        clear_frame()

        lbl = Label(root, text=f"ATTENDANCE LOG\nCREATED", font="Arial, 55")
        lbl.place(relx=0.5, rely=0.5, anchor="center")
        os.chdir("/home/pi/ALS_SHARED/Attendance Logs")
        root.after(500, start)


def end():
    config()
    stop = datetime.datetime.strptime(year + "-" + month + "-" + day + "_" + class_end, "%Y-%m-%d_%H:%M\n")
    time_now = datetime.datetime.now()

    if time_now == stop or time_now > stop:
        return 0
    else:
        return 1


def get_time(current_time):
    global year, month, day, hour, mins, sec
    year = str(current_time.year)
    month = str(current_time.month)
    day = str(current_time.day)
    hour = str(current_time.hour)
    mins = str(current_time.minute)
    sec = str(current_time.second)


def get_sstatus():
    global student_status
    current_time = datetime.datetime.now()
    get_time(current_time)

    # for attendance logs
    new_time = datetime.datetime.now()

    # this portion calculates how much time passed
    late = datetime.timedelta(minutes=float(student_late))
    absent = datetime.timedelta(minutes=float(student_absent))

    if base_student == "NO\n":
        # student attendance status based on schedule
        sched = datetime.datetime.strptime(year + "-" + month + "-" + day + "_" + class_start,
                                           "%Y-%m-%d_%H:%M\n")
        elapsed = new_time - sched
        if elapsed < late:
            student_status = "PRESENT"
        elif elapsed < absent:
            student_status = "LATE"
        else:
            student_status = "ABSENT"

    elif base_student == "YES\n":
        # student attendance status based on teacher ID tap
        # teacher_time = datetime.datetime.strptime(currentTime, "%Y-%m-%d %H:%M:%S.%f")
        elapsed = new_time - currentTime
        if elapsed < late:
            student_status = "PRESENT"
        elif elapsed < absent:
            student_status = "LATE"
        else:
            student_status = "ABSENT"


# created file is in csv format
def create_file():
    # checks if folder exists, if false, creates; if true, proceeds
    if not os.path.exists("Attendance Logs"):
        os.mkdir("Attendance Logs")

    # created attendance logs will be place in this directory
    # os.chdir("Attendance Logs")
    os.chdir('../')
    print(os.getcwd())

    # creates new file for attendance logs
    config()
    global newfile, teacher_status
    newfile = (year + "-" + month + "-" + day + "_" + name + "_" + selected_class + "_" + hour + "-" +
               mins + "-" + sec + ".csv")
    global logs
    logs = "./" + newfile

    sched = datetime.datetime.strptime(year + "-" + month + "-" + day + "_" + class_start, "%Y-%m-%d_%H:%M\n")
    elapsed = currentTime - sched

    if mark_teacher == "YES\n":
        late = datetime.timedelta(minutes=float(teacher_late))
        absent = datetime.timedelta(minutes=float(teacher_absent))

        if elapsed < late:
            teacher_status = "PRESENT"
        elif elapsed < absent:
            teacher_status = "LATE"
        else:
            teacher_status = "ABSENT"

    # writes on created file
    fullname = t_master_copy.get_user(rfid)
    with open(newfile, 'w') as f:
        f.write("RFID,ID Number,Lastname,Firstname,Status,Time-in\n" + rfid + "," + id + "," + fullname
                + "," + teacher_status + "," + hour + ":" + mins + ":" + sec + "\n")
    f.close()

    # mode 1 is enrollment; mode 0 is attendance
    if mode == 1:
        root.after(300, student_scan)
    elif mode == 0:
        root.after(300, student_enrollment)


def config():
    with open(config_file, 'r') as file:
        # special_line_indexes = []
        # for i, line in enumerate(file.readlines()):
        for i, line in enumerate(file):
            if 'CLASS START' in line:
                # extract line index for lines that contain Class Start Schedule
                # special_line_indexes.append(i + 1)
                global class_start
                class_start = line.split(',')[1]

            if 'CLASS END' in line:
                # extract line index for lines that contain Class End Schedule
                global class_end
                class_end = line.split(',')[1]

            if 'MARK TEACHER ATTENDANCE' in line:
                global mark_teacher
                mark_teacher = line.split(',')[1]
                if mark_teacher == "NO\n":
                    global teacher_status
                    teacher_status = "PRESENT"

            if 'TEACHER LATE' in line:
                global teacher_late
                teacher_late = line.split(',')[1]
            if 'TEACHER ABSENT' in line:
                global teacher_absent
                teacher_absent = line.split(',')[1]

            if 'BASE STUDENT ATTENDANCE ON TEACHER TAP' in line:
                global base_student
                base_student = line.split(',')[1]

            if 'STUDENT LATE' in line:
                global student_late
                student_late = line.split(',')[1]
            if 'STUDENT ABSENT' in line:
                global student_absent
                student_absent = line.split(',')[1]

    file.close()


def getSerial():
    cpuserial = "0000000000000000"
    try:
        f = open('/proc/cpuinfo', 'r')
        for line in f:
            if line[0:6] == 'Serial':
                cpuserial = line[10:26]
        f.close()
    except:
        cpuserial = "ERROR00000000000"

    return cpuserial


def clear_frame():
    for widgets in root.winfo_children():
        widgets.destroy()


def restart():
    os.system('sudo reboot')


def shutdown():
    os.system('sudo shutdown -h now')


root = Tk()
root.attributes('-zoomed', True)
#root.attributes('-fullscreen', True)
root.title("Group H")

# create a thread
thread = Thread(target=start)
thread2 = Thread(target=scanning)

# run the thread
thread.start()
thread2.start()

root.mainloop()
