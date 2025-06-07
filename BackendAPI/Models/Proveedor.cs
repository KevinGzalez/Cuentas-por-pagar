using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BackendAPI.Models
{
    public class Proveedor
    {
        [Key]
        public int Identificador { get; set; }

        [Required]
        [MaxLength(255)]
        public required string Nombre { get; set; }

        [Required]
        [Column("tipo_de_persona")] 
        public required string TipoDePersona { get; set; }

        [Required]
        [Column("cedula_rnc")] 
        public required string CedulaRNC { get; set; }

        [Required]
        public decimal Balance { get; set; }

        [Required]
        public required string Estado { get; set; }

    
    }
}
