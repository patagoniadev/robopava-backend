# Documentación API

## Mostrar Temperatura
Muestra la temperatura actual de la pava en grados centígrados
* **URL**
`/pava/temperatura`
* **Method**
`GET`
* **Success Response**
	* **Code**: `200`
	* **Content**: `50`

## Calentar
Enciende la pava y la calienta hasta una temperatura determinada
* **URL**
`/pava/calentar`
* **Method**
`POST`
* **Data Params**
	* **Required**
		* temperatura - la temperatura deseada
* **Success Response**
	* **Code**: 200
