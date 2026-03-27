/** Ubicación: js/lib/recibeJson.js */
export async function recibeJson(url, metodoHttp = "GET") {
  // Token Maestro: Simula que siempre estás logueado
  const token = "TOKEN_ACCESO_PC_MASTER_2026"; 
  
  return fetch(url, {
    method: metodoHttp,
    headers: { 
      "Accept": "application/json, application/problem+json",
      "Authorization": `Bearer ${token}` 
    }
  });
}