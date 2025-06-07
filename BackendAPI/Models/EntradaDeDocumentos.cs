using System;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace BackendAPI.Models
{
    [Table("entrada_de_documentos")]
    public class EntradaDeDocumentos
    {
        [Key]
        [Column("numero_documento")] // Asegura que coincida con la base de datos
        public int NumeroDocumento { get; set; }

        [Required]
        [Column("numero_factura_a_pagar")] // Asegura que el nombre coincida
        public int NumeroFacturaAPagar { get; set; }

        [Required]
        [Column("fecha_document")]
        public DateTime FechaDocumento { get; set; }

        [Required]
        [Column(TypeName = "decimal(18,2)")]
        public decimal Monto { get; set; }

        [Required]
        [Column("fecha_registro")]
        public DateTime FechaRegistro { get; set; }

        [Required]
        [Column("proveedor")] // Si la base de datos usa este nombre para la FK
        public int Proveedor { get; set; }

        [Required]
        [MaxLength(50)]
        [Column("estado")] // Asegura que coincida con la BD
        public required string Estado { get; set; } // 'Pendiente', 'Pagado', 'Anulado', etc.
    }
}
