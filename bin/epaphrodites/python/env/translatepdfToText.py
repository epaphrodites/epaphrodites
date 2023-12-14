import sys
import json
import PyPDF2


class TranslateDocumentToText:
    def __init__(self, document_path, password=None):
        self.document_path = document_path
        self.password = password

    def extract_text(self):
        if self.is_pdf():
            return self.extract_text_from_pdf()
        else:
            return "Unsupported document type."

    def extract_text_from_pdf(self):
        with open(self.document_path, 'rb') as pdf_file:
            pdf_reader = PyPDF2.PdfFileReader(pdf_file)
            if pdf_reader.isEncrypted:
                pdf_reader.decrypt(self.password)
            text = ""
            for page_num in range(pdf_reader.numPages):
                page = pdf_reader.getPage(page_num)
                text += page.extractText()
            return text

    def is_pdf(self):
        return self.document_path.lower().endswith('.pdf')

    @staticmethod
    def load_json_values(json_values):
        values = json.loads(json_values)
        return values


if __name__ == "__main__":
    
    if len(sys.argv) != 2:
        print("Usage: python translateDocumentToText.py <document_json_path>")
        sys.exit(1)

    json_values = sys.argv[1]
    
    document_data = TranslateDocumentToText.load_json_values(json_values)

    if 'function' not in document_data or 'pdf' not in document_data:
        print("The JSON file must contain 'function' and 'pdf'.")
        sys.exit(1)

    json_function = document_data['function']

    document_path = document_data['pdf']

    password = None

    if json_function == "pdf_converter":
        document_converter = TranslateDocumentToText(document_path, password)
        extracted_text = document_converter.extract_text()
        print(extracted_text)
    
    else:
        print(f"The function '{json_function}' is not recognized.")
        sys.exit(1)

