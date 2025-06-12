using System;
using System.Collections.Generic;
using System.Runtime.Serialization;
using System.ServiceModel;

namespace MyWS
{
    [ServiceContract]
    public interface IWSPersonas
    {
        // Metodo para agregar un producto, recibiendo producto como una cadena (string)
        [OperationContract]
        RespuestaSetProd setProd(string user, string pass, string categoria, string producto);

        [OperationContract]
        RespuestaUpdateProd updateProd(string user, string pass, string isbn, string detalles);

        [OperationContract]
        RespuestaDeleteProd deleteProd(string user, string pass, string isbn);
    }
    
    // Set productos
    [DataContract]
    public class RespuestaSetProd : BaseRespuesta
    {
        [DataMember]
        public string Data { get; set; }
    }
    //Update productos
    [DataContract]
    public class RespuestaUpdateProd : BaseRespuesta
    {
        [DataMember]
        public string Data { get; set; }
    }
    //Delete productos
    [DataContract]
    public class RespuestaDeleteProd : BaseRespuesta
    {
        [DataMember]
        public string Data { get; set; }
    }

    //Otra clase para La respuesta de los parametros
    [DataContract]
    public class BaseRespuesta
    {
        [DataMember]
        public string Code { get; set; }

        [DataMember]
        public string Message { get; set; }

        [DataMember]
        public string Status { get; set; }

        [DataMember]
        public string Error { get; set; }
    }
}
