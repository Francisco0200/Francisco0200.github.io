/**
 * Añade caracteres al azar a una raíz, para obtener un clientId único.
 * @param {string} raiz 
 */
export function creaIdCliente(raiz) {
 const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"
 for (var i = 0; i < 15; i++) {
  raiz += chars.charAt(Math.floor(Math.random() * chars.length))
 }
 return raiz
}