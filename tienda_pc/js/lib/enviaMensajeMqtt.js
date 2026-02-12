// @ts-nocheck
/**
 * Envá un valor al servidor de MQTT y es reenviado a todos los dispositivos
 * suscritos al tópico indicado
 * @param {Paho.MQTT.Client} cliente
 * @param {string} mensaje
 * @param {string} topico
 */
export function enviaMensajeMqtt(cliente, mensaje, topico) {
 const mensajeMqtt = new Paho.MQTT.Message(mensaje)
 mensajeMqtt.destinationName = topico
 cliente.send(mensajeMqtt)
}