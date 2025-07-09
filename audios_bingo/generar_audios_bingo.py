from gtts import gTTS
import os

# Carpeta donde se guardarán los audios
carpeta = "audios_bingo"
os.makedirs(carpeta, exist_ok=True)

# Generar audios tipo "B 1", "I 16", ..., "O 75"
columnas = {
    "B": range(1, 16),
    "I": range(16, 31),
    "N": range(31, 46),
    "G": range(46, 61),
    "O": range(61, 76)
}

for letra, numeros in columnas.items():
    for numero in numeros:
        texto = f"{letra} {numero}"
        tts = gTTS(text=texto, lang='es', slow=False)
        nombre_archivo = f"{carpeta}/{letra}_{numero}.mp3"
        tts.save(nombre_archivo)
        print(f"✅ Guardado: {nombre_archivo}")

