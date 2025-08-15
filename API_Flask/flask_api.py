import pythoncom
import comtypes.client
import os
import sys
import asyncio
import threading
from docx import Document
from flask import Flask, jsonify, request
from flask_cors import CORS
from datetime import datetime
from docx.enum.text import WD_ALIGN_PARAGRAPH
import requests
import mysql.connector
from dotenv import load_dotenv
import re
from docx.shared import Pt
import json

app = Flask(__name__)
CORS(app)
load_dotenv()
wdFormatPDF = 17
bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']

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
    pythoncom.CoInitialize()
    word = comtypes.client.CreateObject('Word.Application')
    now = datetime.now()
    document = Document(file_template_path)

    mycursor = mydb.cursor()
    sqlStr = f"SELECT * FROM surat_tugas_pengganti_driver WHERE id_surat_tugas = '{request.json['id_surat_tugas']}'"
    print(sqlStr)
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
        

        print('Saving Docx...')
        document.save(f'Surat Tugas_{myresult[1]}.docx')
        dirname = os.path.dirname(__file__)
        filename_docx = os.path.join(dirname, f'Surat Tugas_{myresult[1]}.docx')
        filename_pdf = os.path.join(dirname, f'Surat Tugas_{myresult[1]}.pdf')
        word.Visible = False
        doc = word.Documents.Open(filename_docx, ReadOnly=True)
        print('Saving PDF...')
        doc.SaveAs(filename_pdf, FileFormat=wdFormatPDF)
        doc.Close()
        word.Quit()
        print('Done!')

        print('Start threading in background...')
        # TODO
        t = threading.Thread(
            target=background_generate_file,
            args=(filename_docx, filename_pdf, laravel_url, request.json['id_surat_tugas'])
        )
        t.start()
        print('After threading...')
        print('Sending response!')

        return jsonify({
            'status': 'success'
        })
    else:
        return jsonify({
            'status': 'error'
        })

# Surat Tugas Promotor Indosat

def background_generate_file(filename_docx, filename_pdf, laravel_url, surat_id):
    asyncio.run(generateFile(filename_docx, filename_pdf, laravel_url, surat_id))

async def generateFile(filename_docx, filename_pdf, laravel_url, id_surat):
    print('Start Async Function....')
    print('Start Await Sleep....')
    await asyncio.sleep(2)
    print('Done Await Sleep....')
    f = open(filename_docx, 'rb')
    pdf = open(filename_pdf, 'rb')
    
    files = {
        'file_docx': (filename_docx, f, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        'file_pdf': (filename_pdf, pdf, 'application/pdf')
    }

    print('Sending file to Laravel...')
    res = requests.post(laravel_url, files=files, stream=True)
    print('Done!')
    print('Update PATH in Database...')
    dataJsonFromRes = res.json()
    print(dataJsonFromRes)
    mycursor = mydb.cursor()
    mycursor.execute(f"UPDATE surat_tugas_pengganti_driver SET file_path_docx = '{dataJsonFromRes['files']['docx']}', file_path_pdf = '{dataJsonFromRes['files']['pdf']}' WHERE id_surat_tugas = '{id_surat}'")
    mydb.commit()
    print(mycursor.rowcount, "record(s) affected")
    print('Done!')
    f.close()
    pdf.close()
    print('Deleting the temporary file...')
    if os.path.exists(filename_docx):
        print(filename_docx)
        os.remove(filename_docx)
        print('Success deleting the docx file!')
    else:
        print('File docx not found')

    if os.path.exists(filename_pdf):
        print(filename_pdf)
        os.remove(filename_pdf)
        print('Success deleting the pdf file!')
    else:
        print('File pdf not found')

    print('Done!')


def background_generate_promotor_file(docx_path, pdf_path, laravel_url, surat_id):
    """Background task to upload generated files to Laravel"""
    asyncio.run(upload_promotor_files(docx_path, pdf_path, laravel_url, surat_id))

async def upload_promotor_files(docx_path, pdf_path, laravel_url, surat_id):
    """Async function to handle file uploads"""
    try:
        await asyncio.sleep(2)  # Small delay
        
        with open(docx_path, 'rb') as docx_file, open(pdf_path, 'rb') as pdf_file:
            files = {
                'file_docx': (os.path.basename(docx_path), docx_file, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
                'file_pdf': (os.path.basename(pdf_path), pdf_file, 'application/pdf')
            }
            
            data = {
                'id_surat_tugas_promotor': surat_id
            }
            
            response = requests.post(laravel_url, files=files, data=data)
            response.raise_for_status()
            response_data = response.json()

            if response.status_code == 200:
                mycursor = mydb.cursor()
                update_sql = f"""
                    UPDATE surat_tugas_promotor 
                    SET file_path_docx = '{response_data['files']['docx']}', 
                        file_path_pdf = '{response_data['files']['pdf']}' 
                    WHERE id_surat_tugas_promotor = '{surat_id}'
                """
                mycursor.execute(update_sql)
                mydb.commit()

    except Exception as e:
        print(f"Error in background process: {str(e)}")
    finally:
        # Clean up files
        for path in [docx_path, pdf_path]:
            try:
                if os.path.exists(path):
                    os.remove(path)
                    print(f"Successfully deleted {path}")
            except Exception as e:
                print(f"Error deleting file {path}: {str(e)}")

@app.route('/generate/surat/promotor', methods=['POST'])
def generate_surat_promotor():
    laravel_url = 'http://localhost:8000/api/send/surat/promotor'
    file_template_path = 'Contoh Template/275-Surat Tugas Promotor Indosat-ahmad.docx'
    
    pythoncom.CoInitialize()
    word = comtypes.client.CreateObject('Word.Application')
    
    try:
        # Get data from database
        mycursor = mydb.cursor(dictionary=True)
        sqlStr = f"SELECT * FROM surat_tugas_promotor WHERE id_surat_tugas_promotor = '{request.json['id_surat_tugas_promotor']}'"
        mycursor.execute(sqlStr)
        result = mycursor.fetchone()

        if not result:
            return jsonify({
                'status': 'error',
                'message': 'Data not found'
            })

        # Load template document
        document = Document(file_template_path)

        # Format date function
        def format_date(date_str):
            if not date_str:
                return ""
            try:
                date_obj = datetime.strptime(str(date_str), '%Y-%m-%d')
                return f"{date_obj.day} {bulan[date_obj.month-1]} {date_obj.year}"
            except:
                return ""

        # Process penempatan data - FIXED VERSION
        penempatan = result.get('penempatan', '[]')
        try:
            # Handle case where penempatan is a comma-separated string
            if isinstance(penempatan, str):
                if penempatan.startswith('[') and penempatan.endswith(']'):
                    # If it's a JSON array string
                    penempatan = json.loads(penempatan)
                else:
                    # If it's a comma-separated string
                    penempatan = [item.strip() for item in penempatan.split(',') if item.strip()]
            
            # Ensure we have a list
            if not isinstance(penempatan, list):
                penempatan = [str(penempatan)]
        except Exception as e:
            print(f"Error processing penempatan: {str(e)}")
            penempatan = ["-"]  # Default value if error occurs

        # Format penempatan for document - IMPROVED FORMATTING
        penempatan_text = ""
        if len(penempatan) == 1:
            penempatan_text = penempatan[0]
        else:
            # Create bullet points with proper Word formatting
            penempatan_text = "\n• ".join(penempatan)
            penempatan_text = "• " + penempatan_text  # Add first bullet

        # Replace placeholders in paragraphs
        for paragraph in document.paragraphs:
            if '__PENEMPATAN__' in paragraph.text:
                # Special handling for penempatan to preserve formatting
                for run in paragraph.runs:
                    if '__PENEMPATAN__' in run.text:
                        run.text = run.text.replace('__PENEMPATAN__', penempatan_text)
            
            paragraph.text = paragraph.text.replace('__DATATGLSURATPEMBUATAN__', format_date(result.get('tgl_surat_pembuatan')))
            paragraph.text = paragraph.text.replace('__NAMAKANDIDAT__', result.get('nama_kandidat', '-'))
            paragraph.text = paragraph.text.replace('__DATATGLPENUGASAN__', format_date(result.get('tgl_penugasan')))

        # Handle tables if needed
        for table in document.tables:
            for row in table.rows:
                for cell in row.cells:
                    if '__PENEMPATAN__' in cell.text:
                        # Clear cell and add formatted content
                        cell.text = ''
                        paragraph = cell.paragraphs[0]
                        runner = paragraph.add_run(penempatan_text)
                        runner.font.name = 'Arial'  # Match template font
                        runner.font.size = Pt(11)   # Match template font size
                    else:
                        cell.text = cell.text.replace('__DATATGLSURATPEMBUATAN__', format_date(result.get('tgl_surat_pembuatan')))
                        cell.text = cell.text.replace('__NAMAKANDIDAT__', result.get('nama_kandidat', '-'))
                        cell.text = cell.text.replace('__DATATGLPENUGASAN__', format_date(result.get('tgl_penugasan')))

        # Save documents
        nama_kandidat = result.get('nama_kandidat', 'Surat_Tugas').replace(' ', '_')
        docx_filename = f"Surat_Tugas_Promotor_{nama_kandidat}.docx"
        pdf_filename = f"Surat_Tugas_Promotor_{nama_kandidat}.pdf"
        
        document.save(docx_filename)
        abs_docx_path = os.path.abspath(docx_filename)
        abs_pdf_path = os.path.abspath(pdf_filename)

        # Convert to PDF
        word.Visible = False
        doc = word.Documents.Open(abs_docx_path)
        doc.SaveAs(abs_pdf_path, FileFormat=wdFormatPDF)
        doc.Close()
        word.Quit()

        # Start background upload
        t = threading.Thread(
            target=background_generate_promotor_file,
            args=(abs_docx_path, abs_pdf_path, laravel_url, request.json['id_surat_tugas_promotor'])
        )
        t.start()

        return jsonify({
            'status': 'success',
            'message': 'Document generation started',
            'penempatan_format': penempatan_text  # For debugging
        })

    except Exception as e:
        return jsonify({
            'status': 'error',
            'message': str(e)
        }), 500