import { consume } from "./consume.js"
import { enviaFormRecibeJson } from "./enviaFormRecibeJson.js"

/**
 * @param {Event} event
 * @param {string} url
 * @param {HTMLFormElement} formulario
 * @param {string} nuevaVista
 */
export async function submitAccion(event, url, formulario, nuevaVista) {
 event.preventDefault()
 await consume(enviaFormRecibeJson(url, formulario))
 location.href = nuevaVista
}