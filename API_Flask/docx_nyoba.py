from docx import Document

document = Document('template1.docx')

for i in range(len(document.paragraphs)):
    # print('__DATANAMA__' in document.paragraphs[i].text)
    document.paragraphs[i].text = document.paragraphs[i].text.replace('__DATANAMA__', 'Mahayoga')
    # document.paragraphs[i].text = document.paragraphs[i].text.replace('__DATAPRODI__', 'MIF')
    # document.paragraphs[i].text = document.paragraphs[i].text.replace('__DATAJURUSAN__', 'Teknologi Informasi')

document.save('template1.docx')
