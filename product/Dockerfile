# Usa una imagen oficial de Python 3.11
FROM python:3.11

# Actualiza pip a la última versión
RUN python -m pip install --upgrade pip

# Copia el contenido del proyecto al contenedor
COPY . /usr/src/app

# Establecer directorio de trabajo
WORKDIR /usr/src/app

# Instala las dependencias de Python desde requirements.txt
RUN pip install --no-cache-dir -r requirements.txt

# Ejecuta la aplicación Python
CMD ["python", "api.py"]
