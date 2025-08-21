import pythoncom
import comtypes.client
import os
import sys
import asyncio
import threading
import time
from docx import Document
from flask import Flask, jsonify, request
from flask_cors import CORS
from datetime import datetime
from docx.enum.text import WD_ALIGN_PARAGRAPH
import requests
import mysql.connector
from dotenv import load_dotenv

app = Flask(__name__)
CORS(app)
load_dotenv()
wdFormatPDF = 17
bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
processesId = []

mydb = mysql.connector.connect(
  host=os.getenv('DB_HOST'),
  user=os.getenv('DB_USERNAME'),
  password=os.getenv('DB_PASSWORD'),
  database=os.getenv('DB_DATABASE')
)

def background_generate_file(filename_docx, filename_pdf, laravel_url, surat_id, table_name, id_column_name):
    asyncio.run(generateFile(filename_docx, filename_pdf, laravel_url, surat_id, table_name, id_column_name))

async def generateFile(filename_docx, filename_pdf, laravel_url, id_surat, table_name, id_column_name):
    f = open(filename_docx, 'rb')
    pdf = open(filename_pdf, 'rb')
    
    files = {
        'file_docx': (filename_docx, f, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        'file_pdf': (filename_pdf, pdf, 'application/pdf')
    }

    res = requests.post(laravel_url, files=files, stream=True)
    dataJsonFromRes = res.json()

    mycursor = mydb.cursor()
    mycursor.execute(
    f"UPDATE {table_name} "f"SET file_path_docx = '{dataJsonFromRes['files']['docx']}', "f"file_path_pdf = '{dataJsonFromRes['files']['pdf']}' "f"WHERE {id_column_name} = '{id_surat}'")
    mydb.commit()
    f.close()
    pdf.close()

    try:
        processesId.remove(id_surat)
    except:
        pass

    if os.path.exists(filename_docx):
        print(filename_docx)
        os.remove(filename_docx)

    if os.path.exists(filename_pdf):
        print(filename_pdf)
        os.remove(filename_pdf)


@app.route('/check/generate/run', methods=['POST'])
def checkGenerateFiles():
    for id in processesId:
        if id == str(request.json['id']):
            return {
                'status': True
            }
        
    return {
        'status': False
    }

@app.route('/generate/surat/penggati/driver', methods=['POST'])
def nyoba_file():
    laravel_url = f'{os.getenv("LARAVEL_ENDPOINT")}/api/send/surat/pengganti/driver'
    file_template_path = 'Contoh Template/template_surat_pengganti_driver.docx'
    pythoncom.CoInitialize()
    word = comtypes.client.CreateObject('Word.Application')
    now = datetime.now()
    document = Document(file_template_path)

    processesId.append(request.json['id_surat_tugas'])

    mycursor = mydb.cursor()
    sqlStr = f"SELECT * FROM surat_tugas_pengganti_driver WHERE id_surat_tugas = '{request.json['id_surat_tugas']}'"
    mycursor.execute(sqlStr)
    myresult = mycursor.fetchone()

    print(myresult)
    if myresult != None:

        """
        __TANGGALPEMBUATAN__
        __NAMAKANDIDAT__
        __NIKKANDIDAT__
        __JABATANKANDIDAT__
        __PENGGANTIKANDIDAT__
        __TANGGALMULAI__
        __TANGGALSELESAI__
        """

        for i in range(len(document.paragraphs)):
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__TANGGALPEMBUATAN__', f'{str(myresult[7]).split("-")[2]} {bulan[int(str(myresult[7]).split("-")[1]) - 1]} {str(myresult[7]).split("-")[0]}')
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__NAMAKANDIDAT__', myresult[1])
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__NIKKANDIDAT__', myresult[2])
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__JABATANKANDIDAT__', myresult[3])
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__PENGGANTIKANDIDAT__', myresult[4])
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__TANGGALMULAI__', f'{str(myresult[5]).split("-")[2]} {bulan[int(str(myresult[5]).split("-")[1]) - 1]} {str(myresult[5]).split("-")[0]}')
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__TANGGALSELESAI__', f'{str(myresult[6]).split("-")[2]} {bulan[int(str(myresult[6]).split("-")[1]) - 1]} {str(myresult[6]).split("-")[0]}')

        table = document.tables[0]
        cell1 = table.rows[1].cells[0]
        cell2 = table.rows[3].cells[0]
        cell3 = table.rows[4].cells[0]

        for para in cell1.paragraphs:
            for run in para.runs:
                run.bold = True

        for para in cell2.paragraphs:
            for run in para.runs:
                run.bold = True
                run.underline = True

        for para in cell3.paragraphs:
            para.alignment = WD_ALIGN_PARAGRAPH.CENTER
            for run in para.runs:
                run.italic = True
        

        document.save(f'Surat Tugas_{myresult[1]}.docx')
        dirname = os.path.dirname(__file__)
        filename_docx = os.path.join(dirname, f'Surat Tugas_{myresult[1]}.docx')
        filename_pdf = os.path.join(dirname, f'Surat Tugas_{myresult[1]}.pdf')
        word.Visible = False
        doc = word.Documents.Open(filename_docx, ReadOnly=True)
        doc.SaveAs(filename_pdf, FileFormat=wdFormatPDF)
        doc.Close()
        word.Quit()

        # TODO
        t = threading.Thread(
            target=background_generate_file,
            args=(filename_docx, filename_pdf, laravel_url, request.json['id_surat_tugas'], request.json['table'], 'id_surat_tugas')
        )
        t.start()

        return jsonify({
            'status': 'success'
        })
    else:
        return jsonify({
            'status': 'error'
        })

@app.route('/generate/surat/tugas/mandiri', methods=['POST'])
def driver_mandiri():
    laravel_url = f'{os.getenv("LARAVEL_ENDPOINT")}/api/send/surat/pengganti/driver'
    file_template_path = 'Contoh Template/template_surat_penempatan_driver_mandiri.docx'
    pythoncom.CoInitialize()
    word = comtypes.client.CreateObject('Word.Application')
    now = datetime.now()
    document = Document(file_template_path)

    mycursor = mydb.cursor()
    sqlStr = f"SELECT * FROM surat_tugas_mandiri WHERE id_surat_penempatan = '{request.json['id_surat_penempatan']}'"
    mycursor.execute(sqlStr)
    myresult = mycursor.fetchone()

    print(myresult)
    if myresult is not None:

        """
        __NOMORSURAT__
        __TANGGALPEMBUATAN__
        __NAMAKANDIDAT__
        __JABATANKANDIDAT__
        __TANGGALPENEMPATAN__
        """

        for i in range(len(document.paragraphs)):
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__NOMORSURAT__', myresult[1])
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__TANGGALPEMBUATAN__',f'{str(myresult[2]).split("-")[2]} {bulan[int(str(myresult[2]).split("-")[1]) - 1]} {str(myresult[2]).split("-")[0]}')
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__NAMAKANDIDAT__', myresult[3])
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__JABATANKANDIDAT__', myresult[4])
            document.paragraphs[i].text = document.paragraphs[i].text.replace('__TANGGALPENEMPATAN__',f'{str(myresult[5]).split("-")[2]} {bulan[int(str(myresult[5]).split("-")[1]) - 1]} {str(myresult[5]).split("-")[0]}')

        table = document.tables[0]
        cell1 = table.rows[1].cells[0]
        cell2 = table.rows[3].cells[0]
        cell3 = table.rows[4].cells[0]

        for para in cell1.paragraphs:
            for run in para.runs:
                run.bold = True

        for para in cell2.paragraphs:
            for run in para.runs:
                run.bold = True
                run.underline = True

        for para in cell3.paragraphs:
            for run in para.runs:
                run.italic = True

        document.save(f'Surat Penempatan_{myresult[3]}.docx')
        dirname = os.path.dirname(__file__)
        filename_docx = os.path.join(dirname, f'Surat Penempatan_{myresult[3]}.docx')
        filename_pdf = os.path.join(dirname, f'Surat Penempatan_{myresult[3]}.pdf')
        word.Visible = False
        doc = word.Documents.Open(filename_docx, ReadOnly=True)
        doc.SaveAs(filename_pdf, FileFormat=wdFormatPDF)
        doc.Close()
        word.Quit()

        t = threading.Thread(
        target=background_generate_file,
        args=(
        filename_docx, filename_pdf, laravel_url, request.json['id_surat_penempatan'], request.json['table'], "id_surat_penempatan")
        )
        t.start()

        return jsonify({'status': 'success'})
    else:
        return jsonify({'status': 'error'})