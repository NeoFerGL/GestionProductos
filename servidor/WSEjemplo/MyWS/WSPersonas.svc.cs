using System;
using System.Collections.Generic;
using FireSharp.Config;
using FireSharp.Interfaces;
using FireSharp.Response;
using Newtonsoft.Json;
using System.Linq;
using Newtonsoft.Json.Linq;

namespace MyWS
{
    public class WSPersonas : IWSPersonas
    {
        IFirebaseConfig config = new FirebaseConfig
        {
            AuthSecret = "",  // Secreto de autenticación de Firebase
            BasePath = ""  // URL de la base de datos en Firebase
        };

        IFirebaseClient client;

        public WSPersonas()
        {
            client = new FireSharp.FirebaseClient(config);
        }

        // Función para verificar si el producto existe
        private bool ExisteProducto(string isbn)
        {
            FirebaseResponse response = client.Get("detalles/" + isbn);
            return response.Body != "null";
        }

        // Función de validaciones y generación de errores comunes (validación de producto y JSON)
        private BaseRespuesta ValidarExistenciaProductoYJson(string isbn, string json, bool checkIfExists = false)
        {
            // Validar que el ISBN no esté vacío
            if (string.IsNullOrEmpty(isbn))
                return new BaseRespuesta { Code = "304", Message = "El ISBN no puede estar vacío", Status = "error" };

            // Validar que el JSON esté bien formado (solo si se proporciona un JSON)
            if (!string.IsNullOrEmpty(json))
            {
                var productoDetalles = ValidarJSON(json);
                if (productoDetalles == null)
                    return new BaseRespuesta { Code = "400", Message = "El formato del JSON no es válido", Status = "error" };
            }

            // Si se debe verificar si el producto ya existe (para setProd)
            if (checkIfExists)
            {
                if (ExisteProducto(isbn))
                    return new BaseRespuesta { Code = "302", Message = "El producto ya existe", Status = "error" };
            }
            else
            {
                // Verificar si el producto no existe (para updateProd y deleteProd)
                if (!ExisteProducto(isbn))
                    return new BaseRespuesta { Code = "404", Message = "El producto no existe", Status = "error" };
            }

            return null; // Si pasa todas las validaciones
        }

        // Función para validar la sintaxis del JSON
        private Dictionary<string, string> ValidarJSON(string json)
        {
            try
            {
                return JsonConvert.DeserializeObject<Dictionary<string, string>>(json);
            }
            catch (Exception)
            {
                return null; // JSON mal formado
            }
        }

        // Función para generar la fecha y hora en formato personalizado (sin "T")
        private string GenerarFechaHora()
        {
            return DateTime.Now.ToString("yyyy-MM-dd HH:mm:ss");
        }

        // Función para validar usuario y contraseña utilizando MD5
        private BaseRespuesta ValidarUsuarioYContrasena(string user, string pass)
        {
            // Verificar si el cliente de Firebase está disponible
            if (client == null)
                return new BaseRespuesta { Code = "500", Message = "Error en la conexión con Firebase", Status = "error" };

            try
            {
                // Obtener los datos del usuario desde Firebase
                FirebaseResponse response = client.Get("usuarios/" + user);
                if (response.Body == "null")
                    return new BaseRespuesta { Code = "404", Message = "Usuario no encontrado", Status = "error" };

                // Almacenar la contraseña como un string
                string passwordAlmacenada = JsonConvert.DeserializeObject<string>(response.Body);

                if (string.IsNullOrEmpty(passwordAlmacenada))
                    return new BaseRespuesta { Code = "404", Message = "Contraseña no encontrada", Status = "error" };

                // Generar el hash de la contraseña proporcionada por el usuario usando MD5
                string hashPasswordProporcionada = GenerarHash(pass);

                // Comparar el hash generado con el almacenado en Firebase
                if (passwordAlmacenada == hashPasswordProporcionada)
                {
                    return null; // Credenciales válidas
                }
                else
                {
                    return new BaseRespuesta { Code = "401", Message = "Contraseña incorrecta", Status = "error" };
                }
            }
            catch (Exception ex)
            {
                // Aquí capturamos el detalle del error para un mejor diagnóstico
                return new BaseRespuesta { Code = "500", Message = "Error en la conexión o procesamiento: " + ex.Message, Status = "error" };
            }
        }

        // Función para generar el hash de la contraseña usando MD5 (como se utilizó en PHP)
        private string GenerarHash(string input)
        {
            using (var md5 = System.Security.Cryptography.MD5.Create())
            {
                var bytes = System.Text.Encoding.UTF8.GetBytes(input);
                var hash = md5.ComputeHash(bytes);
                return BitConverter.ToString(hash).Replace("-", "").ToLower(); // Hash en formato hexadecimal en minúsculas
            }
        }

        // Método setProd
        public RespuestaSetProd setProd(string user, string pass, string categoria, string producto)
        {
            // Validar el usuario y contraseña usando MD5
            var credencialesValidas = ValidarUsuarioYContrasena(user, pass);
            if (credencialesValidas != null)
                return new RespuestaSetProd { Code = credencialesValidas.Code, Message = credencialesValidas.Message, Status = credencialesValidas.Status };

            // Validar el formato del JSON y si el producto ya existe
            var productoData = ValidarJSON(producto);
            var isbn = productoData.Keys.First();

            var error = ValidarExistenciaProductoYJson(isbn, producto, true); // Verifica si el producto ya existe
            if (error != null)
                return new RespuestaSetProd { Code = error.Code, Message = error.Message, Status = error.Status };

            // Guardar el producto en la base de datos
            var nombre = productoData[isbn];
            SetResponse response = client.Set("productos/" + categoria + "/" + isbn, nombre);

            // Crear campos vacíos en detalles
            var detallesVacios = new Dictionary<string, object>
            {
                { "Autor", "" },
                { "Nombre", nombre },
                { "Editorial", "" },
                { "Fecha", 0 },
                { "Precio", 0.0 },
                { "Descuento", 0.0 }
            };
            client.Set("detalles/" + isbn, detallesVacios);

            // Generar fecha y hora actuales en formato personalizado
            string fechaHora = GenerarFechaHora();

            return new RespuestaSetProd
            {
                Code = "202",
                Message = "Producto registrado correctamente",
                Data = fechaHora,
                Status = "success"
            };
        }

        // Método updateProd
        public RespuestaUpdateProd updateProd(string user, string pass, string isbn, string detalles)
        {
            // Validar el usuario y contraseña usando MD5
            var credencialesValidas = ValidarUsuarioYContrasena(user, pass);
            if (credencialesValidas != null)
                return new RespuestaUpdateProd { Code = credencialesValidas.Code, Message = credencialesValidas.Message, Status = credencialesValidas.Status };

            // Convertir el identificador del producto a mayúsculas o minúsculas para uniformidad
            isbn = isbn.ToUpper();  // O ToLower() si prefieres minúsculas

            // Validar la existencia del producto y el formato del JSON
            var error = ValidarExistenciaProductoYJson(isbn, detalles); // Validamos tanto el ISBN como el JSON
            if (error != null)
                return new RespuestaUpdateProd { Code = error.Code, Message = error.Message, Status = error.Status };

            // Actualizar los detalles del producto
            var productoDetalles = ValidarJSON(detalles);
            FirebaseResponse updateResponse = client.Update("detalles/" + isbn, productoDetalles);

            // Generar fecha y hora actuales en formato personalizado
            string fechaHora = GenerarFechaHora();

            return new RespuestaUpdateProd
            {
                Code = "203",
                Message = "Producto actualizado correctamente",
                Data = fechaHora,
                Status = "success"
            };
        }

        // Método deleteProd
        public RespuestaDeleteProd deleteProd(string user, string pass, string isbn)
        {
            // Validar el usuario y contraseña usando MD5
            var credencialesValidas = ValidarUsuarioYContrasena(user, pass);
            if (credencialesValidas != null)
                return new RespuestaDeleteProd { Code = credencialesValidas.Code, Message = credencialesValidas.Message, Status = credencialesValidas.Status };

            // Convertir el identificador del producto a mayúsculas para uniformidad
            isbn = isbn.ToUpper();

            // Validar la existencia del producto (no es necesario validar JSON)
            var error = ValidarExistenciaProductoYJson(isbn, null);
            if (error != null)
                return new RespuestaDeleteProd { Code = error.Code, Message = error.Message, Status = error.Status };

            // Obtener las categorías y productos de Firebase
            FirebaseResponse getCategoriasResponse = client.Get("productos");

            // Deserializamos a un diccionario genérico para manejar ambos casos
            var categoriasRaw = JsonConvert.DeserializeObject<Dictionary<string, object>>(getCategoriasResponse.Body);

            bool eliminado = false;

            foreach (var categoria in categoriasRaw)
            {
                // Intentamos convertir el valor en JObject para identificar diccionario
                if (categoria.Value is JObject jObject)
                {
                    var productos = jObject.ToObject<Dictionary<string, string>>();
                    if (productos != null && productos.ContainsKey(isbn))
                    {
                        client.Delete($"productos/{categoria.Key}/{isbn}");
                        eliminado = true;
                        break;
                    }
                }
                else if (categoria.Value is string)
                {
                    // Caso cuando el producto está directamente como clave string en 'productos'
                    if (categoria.Key == isbn)
                    {
                        client.Delete($"productos/{isbn}");
                        eliminado = true;
                        break;
                    }
                }
            }

            // También eliminamos el detalle del producto si existía
            client.Delete($"detalles/{isbn}");

            string fechaHora = GenerarFechaHora();

            if (eliminado)
            {
                return new RespuestaDeleteProd
                {
                    Code = "204",
                    Message = "Producto y detalles borrados correctamente",
                    Data = fechaHora,
                    Status = "success"
                };
            }
            else
            {
                return new RespuestaDeleteProd
                {
                    Code = "301",
                    Message = "ISBN no encontrado en productos",
                    Data = fechaHora,
                    Status = "error"
                };
            }
        }


    }
}