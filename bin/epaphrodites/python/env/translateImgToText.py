import sys
import re
import pytesseract
from scipy import ndimage
import numpy as np
import unicodedata as uni
from PIL import Image, ImageFilter, ImageOps
sys.path.append('bin/epaphrodites/python/config/')
from initJsonLoader import InitJsonLoader

class TranslateImgToText:
    def __init__(self, img_path):
        self.img_path = img_path

    def preprocess_image(self, image):
        
        gray = image.convert('L')

        gray = gray.filter(ImageFilter.GaussianBlur(radius=0.4))

        thresh = ImageOps.autocontrast(gray)

        thresh = thresh.point(lambda x: 0 if x < thresh.getextrema()[1] / 2 else 255, '1')

        thresh = ndimage.binary_closing(thresh, structure=np.ones((2, 2)))

        return thresh

    def detect_language(self, image):
        detected_language = pytesseract.image_to_string(image, lang='osd')
        try:
            language = detected_language.split('\n')[0].split(':')[1].strip()
        except IndexError:
            language = 'eng'
        return language

    def extract_text(self, image, language):
        langs = ['fra', 'eng', 'spa', 'deu', 'ita', 'por', 'rus', 'ara', 'hin', 'jpn', 'chi_sim', 'chi_tra']
        extracted_text = pytesseract.image_to_string(image, lang=language)

        if len(extracted_text.strip()) < 10:
            for lang in langs:
                if lang != language:
                    extracted_text += ' ' + pytesseract.image_to_string(image, lang=lang)

        return extracted_text

    def getImgContent(self):
        try:
            image = Image.open(self.img_path)
            preprocessed_image = self.preprocess_image(image)
            language = self.detect_language(preprocessed_image)
            extracted_text = self.extract_text(preprocessed_image, language)

            cleaned_text = TranslateImgToText.postProcessing(extracted_text)

            return cleaned_text
        except Exception as e:
            return "Error when extracting the image text : " + str(e)

    @staticmethod
    def postProcessing(text, stopwords=None):
       
        text = re.sub(r'\n\s*\n', '\n', text)
      
        text = re.sub(r'[^\w\s\u00C0-\u017F\/\'\",;:!)(+@?.-]', '', text)
   
        text = re.sub(r'([\\/\'\",;:!?_&%@|><.#*}{-])\1+', r'\1', text)
      
        text = re.sub(r'\s+', ' ', text)
        
        text = '\n'.join([line.strip() for line in text.split('\n')])
        
        text = uni.normalize('NFC', text)
        
        return text

if __name__ == '__main__':
    if len(sys.argv) != 2:
        print("Usage: python translateImgTotext.py <json_values>")
        sys.exit(1)

    json_values = sys.argv[1]
    json_datas = InitJsonLoader.loadJsonValues(json_values, ',')

    if 'function' not in json_datas or 'img' not in json_datas:
        print("The JSON file must contain 'function' and 'img'.")
        sys.exit(1)

    json_function = json_datas['function']
    if json_function == "getImgContent":
        img_path = json_datas.get("img")
        image_processor = TranslateImgToText(img_path)
        text_extract = image_processor.getImgContent()
        print(text_extract)
    else:
        print(f"The function '{json_function}' is not recognized.")
        sys.exit(1)