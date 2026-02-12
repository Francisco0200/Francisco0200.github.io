import { consume } from "./consume.js"
import { enviaFormRecibeJson } from "./enviaFormRecibeJson.js"

/**
 * @param {string} url
 * @param {HTMLFormElement} formulario
 * @param {string} mensaje
 * @param {string} nuevaVista
 */
export async function accionElimina(url, formulario, mensaje, nuevaVista) {
 if (confirm(mensaje)) {
  await consume(enviaFormRecibeJson(url, formulario))
  location.href = nuevaVista
 }
}