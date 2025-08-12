import pythoncom
import comtypes.client
import os
import sys
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

mydb = mysql.connector.connect(
  host=os.getenv('DB_HOST'),
  user=os.getenv('DB_USERNAME'),
  password=os.getenv('DB_PASSWORD'),
  database=os.getenv('DB_DATABASE')
)

@app.route('/nyoba/pdf', methods=['GET'])
def nyoba_pdf():
    pythoncom.CoInitialize()
    word = comtypes.client.CreateObject('Word.Application')
    now = datetime.now()
    document = Document('template.docx')
    for i in range(len(document.paragraphs)):
        document.paragraphs[i].text = document.paragraphs[i].text.replace('__DATATGLSURATPEMBUATAN__', now.strftime('%d %B %Y'))
        document.paragraphs[i].text = document.paragraphs[i].text.replace('__DATATEMPAT__', 'PT. Bank Mandiri (Persero) Tbk')
        document.paragraphs[i].text = document.paragraphs[i].text.replace('__DATAPENGGANTI__', 'Buffer Driver')
        document.paragraphs[i].text = document.paragraphs[i].text.replace('__DATAAREA__', 'Genteng Kali')
        document.paragraphs[i].text = document.paragraphs[i].text.replace('__DATATGLPENUGASAN__', now.strftime('%d %B %Y'))

    for table in document.tables:
        for row in table.rows:
            for cell in row.cells:
                cell.text = cell.text.replace('__NAMAKANDIDAT__', 'Yusuf Hilya')
                cell.text = cell.text.replace('__NIKKANDIDAT__', '00000000000')
                cell.text = cell.text.replace('__JABATAN__', 'Buffer Driver')
                cell.text = cell.text.replace('__DATANAMATTD__', 'Abi Bayu')
                cell.text = cell.text.replace('__TTDJABATAN__', 'PIC')

    table = document.tables[1]
    cell1 = table.rows[3].cells[0]
    cell2 = table.rows[4].cells[0]

    for para in cell1.paragraphs:
        for run in para.runs:
            run.bold = True
            run.underline = True
            print(para.text)

    for para in cell2.paragraphs:
        para.alignment = WD_ALIGN_PARAGRAPH.CENTER
        for run in para.runs:
            run.italic = True
            run = True
            print(para.text)    
    
    
    document.save('Surat Tugas_Yusuf Hilya.docx')
    dirname = os.path.dirname(__file__)
    filename = os.path.join(dirname, 'Surat Tugas_Yusuf Hilya.docx')

    word.Visible = False
    print('In file...')
    doc = word.Documents.Open(filename, ReadOnly=True)
    print('Out file...')
    doc.SaveAs(os.path.join(dirname, 'Surat Tugas_Mahayoga.pdf'), FileFormat=wdFormatPDF)
    doc.Close()
    word.Quit()
    print('Done!')
    return {
        'status': 'success',        
    }

@app.route('/generate/surat/penggati/driver', methods=['POST'])
def nyoba_file():
    laravel_url = 'http://localhost:8000/api/send/surat/pengganti/driver'
    file_template_path = 'Contoh Template/template_surat_pengganti_driver.docx'

    mycursor = mydb.cursor()
    mycursor.execute(f"SELECT * FROM surat_tugas WHERE id_surat_tugas = '{request.json['id_surat_tugas']}'")
    myresult = mycursor.fetchone()

    print(myresult)

    # with open(file_path, 'rb') as f:
    #     file = {
    #         'file_docx': (file_path, f, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
    #         'file_pdf': '' # TODO
    #     }
    #     res = requests.post(laravel_url, files=file)
    #     print(res)
    
    return jsonify({
        'status': 'success'
    })