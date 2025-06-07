using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
//NO MODIFICAR 
namespace BackendAPI.Models
{
[Table("concepto_de_pagos")]  
    public class ConceptoDePago
    
    {
        [Key]
        public int Identificador { get; set; }

        [Required]
        [MaxLength(255)]
        public required string Descripcion { get; set; }

        [Required]
        public required string Estado { get; set; } // 'Activo' o 'Inactivo'
    }
}
