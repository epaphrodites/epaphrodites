import sys
import json
import pytesseract
from PIL import Image
sys.path.append('bin/epaphrodites/python/config/')
from initJsonLoader import InitJsonLoader

class TranslateImgToText:

    def __init__(self, img_path):

        self.img_path = img_path

    def getImgContent(self):
        try:
            image = Image.open(self.img_path)

            texte_extrait = pytesseract.image_to_string(image)

            return texte_extrait

        except Exception as e:

            return "Error when extracting text from the image : " + str(e)

if __name__ == '__main__':

    if len(sys.argv) != 2:

        print("Usage: python translateImgTotext.py")

        sys.exit(1)

    json_values = sys.argv[1]

    json_datas = InitJsonLoader.loadJsonValues(json_values)

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